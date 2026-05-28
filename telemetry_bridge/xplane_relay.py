import socket
import requests
import time
import threading
import select
import sys
import signal
import os
import json

# --- CONFIGURACIÓN ---
UDP_IP = "0.0.0.0"
UDP_PORT = 49005
LARAVEL_URL = "http://127.0.0.1/api/telemetry"

# --- ARCHIVOS Y SESIÓN ---
SESSION_ID = sys.argv[1] if len(sys.argv) > 1 else "vuelo_libre"
dir_script = os.path.dirname(os.path.abspath(__file__))
dir_vuelos = os.path.join(dir_script, "..", "public", "vuelos")
dir_flags = os.path.join(dir_script, "..", "storage", "app", "flags")
archivo_stop = os.path.join(dir_flags, f"stop_{SESSION_ID}.txt")

os.makedirs(dir_vuelos, exist_ok=True)
os.makedirs(dir_flags, exist_ok=True)
ruta_vuelo = []

running = True

# Si el sistema operativo lo mata de golpe, simplemente cambia la bandera
def detener_script(sig, frame):
    global running
    running = False

signal.signal(signal.SIGINT, detener_script)
signal.signal(signal.SIGTERM, detener_script)

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
sock.bind((UDP_IP, UDP_PORT))
sock.setblocking(0) 

state = {
    "spd": 0.0, "pitch": 0.0, "roll": 0.0, "hdg": 0.0, "alt": 0.0,
    "lat": 0.0, "lon": 0.0, 
    "nav1_obs": 0.0, "nav1_cdi": 0.0, "nav1_bearing": 0.0, "nav1_freq":0.0, "com1_freq":0.0
}

# --- HILO DE ENVÍO A LARAVEL Y REGISTRO ---
def laravel_sender():
    ultimo_guardado = 0
    while running:
        time.sleep(0.04) 
        current_state = state.copy()
        
        # 1. Enviar a Laravel (Para que el IDU siga en vivo)
        try:
            requests.post(LARAVEL_URL, json=current_state, timeout=0.04)
        except Exception:
            pass
            
        # 2. Guardar en memoria para el JSON
        curr_time = time.time()
        if curr_time - ultimo_guardado >= 0.2:
            lat_actual = current_state["lat"]
            lon_actual = current_state["lon"]
            
            # --- FILTRO ANTI-BASURA ---
            # Evita coordenadas 0.0 (Null Island / Pantalla de carga)
            if lat_actual != 0.0 and lon_actual != 0.0:
                # Evita coordenadas imposibles (ej. -999 cuando X-Plane crashea)
                if -90 <= lat_actual <= 90 and -180 <= lon_actual <= 180:
                    
                    punto = current_state.copy()
                    punto["ts"] = int(curr_time * 1000)
                    ruta_vuelo.append(punto)
                    ultimo_guardado = curr_time

threading.Thread(target=laravel_sender, daemon=True).start()

# --- BUCLE PRINCIPAL ---
try:
    while running:
        
        # --- Revisar el archivo STOP de Laravel ---
        if os.path.exists(archivo_stop):
            running = False
            try:
                os.remove(archivo_stop) # Borramos la bandera
            except Exception:
                pass
            break # Salimos del bucle para ir a guardar

        ready = select.select([sock], [], [], 0.1)
        
        if ready[0]:
            try:
                data, addr = sock.recvfrom(2048)
                msg = data.decode('utf-8').strip()
                
                # --- LÓGICA DE PARSEO EXCLUSIVA PARA FLYWITHLUA ---
                if msg.startswith("TLM:"):
                    # El mensaje viene así: TLM:spd|pitch|roll|hdg|alt|lat|lon|obs|cdi|brng|nav_f|com_f
                    valores = msg.split(":")[1].split("|")
                    
                    if len(valores) == 12:
                        state["spd"] = float(valores[0])
                        state["pitch"] = float(valores[1])
                        state["roll"] = float(valores[2])
                        state["hdg"] = float(valores[3])
                        state["alt"] = float(valores[4])
                        state["lat"] = float(valores[5])
                        state["lon"] = float(valores[6])
                        state["nav1_obs"] = float(valores[7])
                        state["nav1_cdi"] = float(valores[8])
                        state["nav1_bearing"] = float(valores[9])
                        state["nav1_freq"] = float(valores[10])
                        state["com1_freq"] = float(valores[11])
                        
            except BlockingIOError:
                pass
            except Exception:
                # Ignorar paquetes corruptos si ocurre algún error de decodificación
                pass
finally:
    # --- GUARDADO DEFINITIVO DEL VUELO ---
    if len(ruta_vuelo) > 0:
        nombre_archivo = f"vuelo_sesion_{SESSION_ID}.json"
        ruta_completa = os.path.join(dir_vuelos, nombre_archivo)
        try:
            with open(ruta_completa, "w") as f:
                json.dump(ruta_vuelo, f)
        except Exception:
            pass
            
    sock.close()