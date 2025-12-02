import socket
import struct
import json
import time
import os
import sys

# --- CONFIGURACIÓN ---
UDP_IP = "0.0.0.0"
UDP_PORT = 49000

# 1. Recibimos el ID de la sesión desde Laravel (Argumento de consola)
if len(sys.argv) < 2:
    print("Error: Debes indicar el ID de la sesión.")
    sys.exit(1)

SESSION_ID = sys.argv[1]

# Definimos rutas clave
# Ruta actual del script
dir_script = os.path.dirname(os.path.abspath(__file__))
# Ruta donde Laravel pondrá la señal de STOP (storage/app/flags)
dir_flags = os.path.join(dir_script, "..", "storage", "app", "flags")
# Ruta donde guardaremos el JSON final (public/vuelos)
dir_vuelos = os.path.join(dir_script, "..", "public", "vuelos")

# Nombre del archivo de señal que esperaremos
archivo_stop = os.path.join(dir_flags, f"stop_{SESSION_ID}.txt")

# Aseguramos que existan las carpetas
if not os.path.exists(dir_flags): os.makedirs(dir_flags)
if not os.path.exists(dir_vuelos): os.makedirs(dir_vuelos)

print(f"--- GRABANDO VUELO PARA SESIÓN {SESSION_ID} ---")
print(f"Esperando señal de parada en: {archivo_stop}")

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
sock.bind((UDP_IP, UDP_PORT))
sock.settimeout(0.5) # Timeout corto para revisar el archivo STOP frecuentemente

ruta_vuelo = []

try:
    while True:
        # A. ¿ME MANDARON A PARAR?
        if os.path.exists(archivo_stop):
            print("¡Señal de STOP recibida!")
            # Borramos el archivo de señal para limpiar
            try:
                os.remove(archivo_stop)
            except:
                pass
            break # Salimos del bucle para guardar

        # B. ESCUCHAR X-PLANE
        try:
            data, addr = sock.recvfrom(1024)
            
            if data[0:4] == b'DATA':
                indice = struct.unpack('<i', data[5:9])[0]
                if indice == 20: 
                    valores = struct.unpack('<8f', data[9:41])
                    lat, lon, alt = valores[0], valores[1], valores[2]
                    
                    ruta_vuelo.append({
                        "lat": lat, "lon": lon, "alt": alt, "ts": time.time()
                    })
                    
        except socket.timeout:
            continue # Si no llega nada, seguimos el bucle para chequear el STOP

except Exception as e:
    print(f"Error: {e}")

# --- GUARDADO FINAL ---
nombre_archivo = f"vuelo_sesion_{SESSION_ID}.json"
ruta_completa = os.path.join(dir_vuelos, nombre_archivo)

with open(ruta_completa, "w") as f:
    json.dump(ruta_vuelo, f)

print(f"Vuelo guardado: {nombre_archivo}")