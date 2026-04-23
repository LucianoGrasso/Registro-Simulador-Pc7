// resources/js/Pages/IDU/Welcome.jsx
import React, { useState, useEffect } from 'react';
import Echo from 'laravel-echo';
import InstrumentosIDU from '@/components/InstrumentosIDU';

export default function Welcome() {
    // Agrupamos todo en un solo objeto de estado inicializado en cero
    const [telemetria, setTelemetria] = useState({
        pitch: 0, roll: 0, alt: 0, spd: 0, hdg: 0,
        oat: 15, windDir: 0, windSpd: 0,
        nav1Freq: 0, nav1Obs: 360, nav1Cdi: 0, nav1Bearing: 0, dmeDist: 0, com1Freq: 0
    });

    useEffect(() => {
        if (window.Echo) {
            const channel = window.Echo.channel('telemetry-stream')
                .listen('TelemetryUpdated', (e) => {
                    // Actualizamos el estado fusionando los datos nuevos
                    setTelemetria(prevDatos => ({
                        ...prevDatos,
                        ...e.data,
                        alt: e.data.alt !== undefined ? Math.round(e.data.alt) : prevDatos.alt,
                        spd: e.data.spd !== undefined ? Math.round(e.data.spd) : prevDatos.spd,
                        hdg: e.data.hdg !== undefined ? Math.round(e.data.hdg) : prevDatos.hdg,
                    }));
                });

            return () => {
                window.Echo.leave('telemetry-stream');
            };
        } else {
            console.error("Laravel Echo no está inicializado. Verifica bootstrap.js");
        }
    }, []);

    // Pasamos el paquete completo de datos a nuestra pantalla
    return <InstrumentosIDU telemetria={telemetria} />;
}