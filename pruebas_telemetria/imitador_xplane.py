import socket
import struct
import time
import math

# Configuración
UDP_IP = "127.0.0.1"
UDP_PORT = 49000

print(f"--- IMITADOR DE VUELO RECTO ---")
print("El avión volará hacia el NORTE.")
print("Perfil: Despegue -> 3000ft (Verde) -> Aterrizaje (Rojo)")
print("Presiona Ctrl+C cuando quieras guardar.")

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)

# Coordenadas iniciales (Cerca de Torquemada)
lat = -32.9550
lon = -71.5500
step = 0

try:
    while True:
        # 1. Movimiento RECTO hacia el Norte (Solo sumamos latitud)
        lat += 0.00015  # Avanza al norte
        lon -= 0.00002  # Se mueve un pelín al oeste para seguir la línea costera
        
        # 2. Perfil de Altitud (Subir y Bajar)
        # Usamos una onda senoidal para que suba suave y baje suave
        # El ciclo completo durará unos 60 segundos aprox
        alt = 10 + 1500 * (1 + math.sin((step / 150) * math.pi - math.pi/2))
        
        # Limitamos para que no pase de 3000 ni baje de 0
        if alt > 3000: alt = 3000
        if alt < 10: alt = 10
            
        step += 1
        
        # 3. Empaquetar
        header = b'DATA\0'
        index = 20 
        data_bytes = struct.pack('<i8f', index, lat, lon, alt, alt, 0.0, 0.0, 0.0, 0.0)
        message = header + data_bytes
        
        # 4. Enviar
        sock.sendto(message, (UDP_IP, UDP_PORT))
        
        # Velocidad de actualización
        time.sleep(0.2) 
        
        # Feedback en consola para que sepas cuándo cortar
        color = "ROJO" if alt < 500 else ("VERDE" if alt > 1500 else "AMARILLO")
        print(f"Lat: {lat:.4f} | Altitud: {alt:.0f} ft ({color})")

except KeyboardInterrupt:
    print("\nSimulación finalizada.")