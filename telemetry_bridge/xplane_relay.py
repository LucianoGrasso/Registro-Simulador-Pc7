import socket
import struct
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
        
        # 1. Enviar a Laravel
        try:
            requests.post(LARAVEL_URL, json=current_state, timeout=0.04)
        except Exception:
            pass
            
        # 2. Guardar en memoria para el JSON
        curr_time = time.time()
        if curr_time - ultimo_guardado >= 0.2:
            punto = current_state.copy()
            punto["ts"] = int(curr_time * 1000)
            ruta_vuelo.append(punto)
            ultimo_guardado = curr_time

threading.Thread(target=laravel_sender, daemon=True).start()

# --- BUCLE PRINCIPAL ---
try:
    while running:
        
        # --- LA PIEZA FALTANTE: Revisar el archivo STOP de Laravel ---
        if os.path.exists(archivo_stop):
            running = False
            try:
                os.remove(archivo_stop) # Borramos la bandera
            except Exception:
                pass
            break # Salimos del bucle para ir a guardar
        # -------------------------------------------------------------

        ready = select.select([sock], [], [], 0.1)
        
        if ready[0]:
            try:
                data, addr = sock.recvfrom(2048)
                header = data[0:4]
                
                if header == b'DATA':
                    for i in range(5, len(data), 36):
                        if i + 36 > len(data): break
                        bloque_id = struct.unpack('<i', data[i:i+4])[0]
                        valores = struct.unpack('<8f', data[i+4:i+36])

                        if bloque_id == 17:
                            state["pitch"], state["roll"] = round(valores[0], 2), round(valores[1], 2)
                            state["hdg"] = round(valores[3], 1)
                        elif bloque_id == 98: state["nav1_obs"] = round(valores[0], 1)
                        elif bloque_id == 3: state["spd"] = round(valores[0], 1)
                        elif bloque_id == 20: 
                            state["alt"] = round(valores[2], 1)
                            state["lat"] = round(valores[0], 6) 
                            state["lon"] = round(valores[1], 6) 
                        elif bloque_id == 97: state["nav1_freq"] = round(valores[0] / 100, 2)
                        elif bloque_id == 96: state["com1_freq"] = round(valores[0] / 100, 2)
                else:
                    try:
                        msg = data.decode('utf-8').strip()
                        if "BRNG:" in msg: state["nav1_bearing"] = float(msg.split(":")[1])
                        elif "CDI:" in msg: state["nav1_cdi"] = float(msg.split(":")[1])
                        elif "OBS:" in msg: state["nav1_obs"] = float(msg.split(":")[1])
                    except: pass
            except BlockingIOError:
                pass
finally:
    # --- GUARDADO DEFINITIVO DEL VUELO ---
    # Esto se ejecutará siempre, ya sea que Laravel ponga el archivo .txt
    # o que cierres la consola a la fuerza.
    if len(ruta_vuelo) > 0:
        nombre_archivo = f"vuelo_sesion_{SESSION_ID}.json"
        ruta_completa = os.path.join(dir_vuelos, nombre_archivo)
        try:
            with open(ruta_completa, "w") as f:
                json.dump(ruta_vuelo, f)
        except Exception:
            pass
            
    sock.close()