import socket
import struct
import json
import time
import os
import sys

# --- CONFIGURACIÓN ---
UDP_IP = "0.0.0.0"   # Escucha en todas las interfaces
UDP_PORT = 49005     # Debe coincidir con X-Plane

# ID de Sesión
if len(sys.argv) < 2:
    SESSION_ID = "test_manual"
else:
    SESSION_ID = sys.argv[1]

# Rutas
dir_script = os.path.dirname(os.path.abspath(__file__))
# Ajuste para subir desde 'pruebas_telemetria' a la raíz
dir_flags = os.path.join(dir_script, "..", "storage", "app", "flags")
dir_vuelos = os.path.join(dir_script, "..", "public", "vuelos")
archivo_stop = os.path.join(dir_flags, f"stop_{SESSION_ID}.txt")

# Crear carpetas si no existen
os.makedirs(dir_flags, exist_ok=True)
os.makedirs(dir_vuelos, exist_ok=True)

print(f"--- RECEPTOR UDP INICIADO (Puerto {UDP_PORT}) ---")
print(f"Sesión: {SESSION_ID}")

# Iniciar Socket
sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
sock.bind((UDP_IP, UDP_PORT))
sock.settimeout(1.0) # Timeout de 1 seg para revisar el archivo STOP

ruta_vuelo = []
ultimo_tiempo = 0

# Variables para mantener el estado actual de los instrumentos
ultimo_spd = 0.0
ultimo_hdg = 0.0

try:
    while True:
        # A. Revisar Señal de STOP
        if os.path.exists(archivo_stop):
            print("¡Señal de STOP recibida! Guardando...")
            try: os.remove(archivo_stop)
            except: pass
            break 

        # B. Escuchar Datos
        try:
            data, addr = sock.recvfrom(1024)
            
            # Procesamos el paquete X-Plane
            if data[0:4] == b'DATA':
                longitud = len(data)
                
                # Recorremos todos los bloques dentro del paquete
                for i in range(5, longitud, 36):
                    if i + 36 > longitud: break
                    
                    bloque_id = struct.unpack('<i', data[i:i+4])[0]
                    valores = struct.unpack('<8f', data[i+4:i+36])

                    # --- CAPTURA DE DATOS ---
                    
                    # Bloque 3: Velocidades
                    if bloque_id == 3:
                        # Index 0 suele ser V-ind (KIAS)
                        ultimo_spd = valores[0]

                    # Bloque 17: Pitch, Roll, Headings
                    elif bloque_id == 17:
                        # Index 3 es el Rumbo Magnético (mag psi)
                        ultimo_hdg = valores[3]

                    # Bloque 20: Latitud, Longitud, Altitud
                    elif bloque_id == 20:
                        lat, lon, alt = valores[0], valores[1], valores[2]
                        
                        # --- GUARDADO TEMPORAL (Solo 1 vez por segundo) ---
                        tiempo_actual = time.time()
                        if tiempo_actual - ultimo_tiempo >= 1.0:
                            # Guardamos el punto con la info combinada de los otros bloques
                            ruta_vuelo.append({
                                "lat": lat, 
                                "lon": lon, 
                                "alt": alt, 
                                "spd": ultimo_spd, # Agregado
                                "hdg": ultimo_hdg, # Agregado
                                "ts": tiempo_actual
                            })
                            ultimo_tiempo = tiempo_actual

        except socket.timeout:
            continue

except Exception as e:
    print(f"Error: {e}")

# Guardado Final
nombre_archivo = f"vuelo_sesion_{SESSION_ID}.json"
ruta_completa = os.path.join(dir_vuelos, nombre_archivo)

with open(ruta_completa, "w") as f:
    json.dump(ruta_vuelo, f)

print(f"Guardado: {nombre_archivo} ({len(ruta_vuelo)} puntos)")