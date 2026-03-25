import socket
import struct
import requests
import time
import threading
from queue import Queue

# --- CONFIGURACIÓN ---
UDP_IP = "0.0.0.0"
UDP_PORT = 49005
LARAVEL_URL = "http://localhost/api/telemetry"
XPLANE_IP = "172.31.208.1" 

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
sock.bind((UDP_IP, UDP_PORT))
sock.setblocking(0)
sock.setsockopt(socket.SOL_SOCKET, socket.SO_RCVBUF, 65536)

state = {
    "spd": 0.0, "pitch": 0.0, "roll": 0.0, "hdg": 0.0, "alt": 0.0,
    "nav1_obs": 0.0, "nav1_cdi": 0.0, "nav1_bearing": 0.0, "nav1_freq":0.0, "com1_freq":0.0
}
data_queue = Queue(maxsize=1)

def laravel_sender():
    while True:
        try:
            payload = data_queue.get()
            requests.post(LARAVEL_URL, json=payload, timeout=0.04)
        except Exception:
            pass
        time.sleep(0.04)

threading.Thread(target=laravel_sender, daemon=True).start()

last_queue_update = 0
UPDATE_INTERVAL = 0.04 

try:
    while True:
        try:
            while True:
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
                        # Comentamos el bloque 99 para que no sobrescriba el dato de LUA
                        # elif bloque_id == 99: state["nav1_cdi"] = round(valores[0], 3)
                        elif bloque_id == 3: state["spd"] = round(valores[0], 1)
                        elif bloque_id == 20: state["alt"] = round(valores[2], 1)
                        elif bloque_id == 97:
                            state["nav1_freq"] = valores[0] / 100
                        elif bloque_id == 96:
                            state["com1_freq"] = valores[0] / 100
                else:
                    # Datos de FlyWithLua (BRNG:XXX.X o CDI:X.XXX)
                    try:
                        msg = data.decode('utf-8').strip()
                        if "BRNG:" in msg:
                            state["nav1_bearing"] = float(msg.split(":")[1])
                        elif "CDI:" in msg:
                            # Capturamos el CDI de Lua con toda su precisión decimal
                            state["nav1_cdi"] = float(msg.split(":")[1])
                    except: pass
        except BlockingIOError:
            pass

        curr_time = time.time()
        if curr_time - last_queue_update >= UPDATE_INTERVAL:
            if data_queue.full():
                data_queue.get_nowait()
            data_queue.put(state.copy())
            last_queue_update = curr_time
        
        time.sleep(0.001)

except KeyboardInterrupt:
    sock.close()