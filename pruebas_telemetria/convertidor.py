import sys
import os
import json
import time

# --- CONFIGURACIÓN ---
# ¡IMPORTANTE! Pega aquí la ruta donde está instalado X-Plane 11
# Ejemplo: "C:\\X-Plane 11"
RUTA_XPLANE = "C:\\Users\\PC-cockpit\\Desktop\\X-Plane 11" 

# Archivo de datos nativo de X-Plane
archivo_data = os.path.join(RUTA_XPLANE, "Data.txt")

# Recibimos el ID de la sesión desde Laravel
if len(sys.argv) < 2:
    print("Error: Falta ID de sesión")
    sys.exit(1)
SESSION_ID = sys.argv[1]

# Rutas de salida (Laravel)
dir_script = os.path.dirname(os.path.abspath(__file__))
dir_vuelos = os.path.join(dir_script, "..", "public", "vuelos")
if not os.path.exists(dir_vuelos): os.makedirs(dir_vuelos, exist_ok=True)

ruta_json_final = os.path.join(dir_vuelos, f"vuelo_sesion_{SESSION_ID}.json")

print(f"--- PROCESANDO CAJA NEGRA SESIÓN {SESSION_ID} ---")

if not os.path.exists(archivo_data):
    print(f"Error: No se encontró {archivo_data}")
    # Creamos un JSON vacío para que no de error 500 en la web
    with open(ruta_json_final, "w") as f: json.dump([], f)
    sys.exit(0)

ruta_vuelo = []

try:
    with open(archivo_data, "r") as f:
        lines = f.readlines()
        
        for line in lines:
            parts = line.strip().split('|')
            # El formato de Data.txt es complejo, buscamos líneas que tengan datos
            # Normalmente separadas por | y con muchos números
            # Estructura típica X-Plane Data.txt:
            # 20, -32.955, -71.550, 350.0, ...
            
            # Limpiamos espacios y separamos por comas si es el formato antiguo, 
            # o analizamos la estructura de columnas si es el nuevo.
            # X-Plane 11 suele usar columnas de ancho fijo o separadas por |
            
            # PARSEO SIMPLE (Busca la fila 20)
            if " 20 " in line: 
                # Esto es un hack rápido, el Data.txt es columnas separadas por espacios/tabs
                # Ejemplo: 20 -32.9490 -71.5540 1500.0 ...
                columns = line.split()
                
                # La columna 0 es el índice (20)
                if len(columns) > 3 and columns[0] == '20':
                    try:
                        lat = float(columns[1])
                        lon = float(columns[2])
                        alt = float(columns[3])
                        
                        ruta_vuelo.append({
                            "lat": lat, "lon": lon, "alt": alt, "ts": 0 # Timestamp relativo
                        })
                    except:
                        continue

    # Guardamos el JSON para la web
    with open(ruta_json_final, "w") as f:
        json.dump(ruta_vuelo, f)
    print(f"Convertidos {len(ruta_vuelo)} puntos.")

    # --- LIMPIEZA ---
    # Borramos el Data.txt para que el próximo vuelo empiece limpio
    # (X-Plane lo volverá a crear automáticamente)
    try:
        os.remove(archivo_data)
        print("Caja negra reiniciada (Data.txt borrado).")
    except Exception as e:
        print(f"No se pudo borrar Data.txt: {e}")

except Exception as e:
    print(f"Error procesando: {e}")


