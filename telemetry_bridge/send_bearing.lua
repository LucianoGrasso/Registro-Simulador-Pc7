local socket = require("socket")
local udp = socket.udp()
local ip = "172.31.208.1"
local port = 49005

-- Extraemos todos los DataRefs necesarios
dataref("SPD", "sim/flightmodel/position/indicated_airspeed", "read_only")
dataref("PITCH", "sim/flightmodel/position/theta", "read_only")
dataref("ROLL", "sim/flightmodel/position/phi", "read_only")
dataref("HDG", "sim/flightmodel/position/magpsi", "read_only")
dataref("ALT_M", "sim/flightmodel/position/elevation", "read_only") -- Viene en metros nativamente
dataref("LAT", "sim/flightmodel/position/latitude", "read_only")
dataref("LON", "sim/flightmodel/position/longitude", "read_only")

dataref("NAV1_OBS", "sim/cockpit2/radios/actuators/nav1_obs_deg_mag_pilot", "read_only")
dataref("NAV1_CDI_DOTS", "sim/cockpit/radios/nav1_hdef_dot", "read_only")
dataref("NAV1_BRNG", "sim/cockpit2/radios/indicators/nav1_bearing_deg_mag", "read_only")
dataref("NAV1_FREQ", "sim/cockpit2/radios/actuators/nav1_frequency_hz", "read_only")
dataref("COM1_FREQ", "sim/cockpit2/radios/actuators/com1_frequency_hz", "read_only")

local last_time = os.clock()

function send_telemetry_data()
    local current_time = os.clock()
    
    -- Enviar datos a ~20Hz (cada 0.05 segundos) para que sea fluido pero no sature la red
    if current_time - last_time >= 0.05 then
        -- Conversiones necesarias
        local alt_ft = ALT_M * 3.28084 -- Metros a Pies
        local nav_f = NAV1_FREQ / 100  -- Ej: 10930 pasa a ser 109.30
        local com_f = COM1_FREQ / 100

        -- Empaquetamos todo en una sola cadena de texto: TLM:spd|pitch|roll|hdg|alt|lat|lon|obs|cdi|brng|nav_f|com_f
        local payload = string.format("TLM:%.1f|%.2f|%.2f|%.1f|%.1f|%.6f|%.6f|%.1f|%.3f|%.1f|%.2f|%.2f",
            SPD, PITCH, ROLL, HDG, alt_ft, LAT, LON, NAV1_OBS, NAV1_CDI_DOTS, NAV1_BRNG, nav_f, com_f)
            
        udp:sendto(payload, ip, port)
        last_time = current_time
    end
end

-- Ejecutamos la evaluación en cada frame de X-Plane
do_every_frame("send_telemetry_data()")