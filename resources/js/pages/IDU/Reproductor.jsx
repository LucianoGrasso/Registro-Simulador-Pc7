// resources/js/Pages/IDU/Reproductor.jsx
import React, { useState, useEffect } from 'react';
import InstrumentosIDU from '@/components/InstrumentosIDU';

export default function Reproductor({ historialVuelo }) {
    const [reproduciendo, setReproduciendo] = useState(false);
    const [indiceActual, setIndiceActual] = useState(0);
    const [velocidad, setVelocidad] = useState(1); // Nueva variable para el multiplicador de velocidad

    const datosValidos = historialVuelo && historialVuelo.length > 0;
    const telemetriaActual = datosValidos ? historialVuelo[indiceActual] : {};

    // Asumimos que los datos se guardaron cada 100ms (10 frames por segundo)
    const msPorFrame = 100; 

    useEffect(() => {
        let temporizador;
        if (reproduciendo && datosValidos && indiceActual < historialVuelo.length - 1) {
            // El intervalo se divide por la velocidad (ej: si es 2x, el intervalo es 50ms en vez de 100ms)
            temporizador = setInterval(() => {
                setIndiceActual(prev => prev + 1);
            }, msPorFrame / velocidad); 
        } else if (indiceActual >= historialVuelo.length - 1) {
            setReproduciendo(false);
        }

        return () => clearInterval(temporizador);
    }, [reproduciendo, indiceActual, datosValidos, historialVuelo, velocidad]);

    // Función para convertir los "frames" en formato de minutos:segundos (MM:SS)
    const formatearTiempo = (indice) => {
        const segundosTotales = Math.floor((indice * msPorFrame) / 1000);
        const minutos = Math.floor(segundosTotales / 60);
        const segundos = segundosTotales % 60;
        return `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    };

    if (!datosValidos) return <div className="text-white text-center p-10 font-mono">No hay datos de vuelo para reproducir.</div>;

    return (
        <div className="bg-black min-h-screen relative flex flex-col">
            
            {/* LA PANTALLA IDU OCUPA TODO EL ESPACIO */}
            <div className="flex-1 flex items-center justify-center pb-20"> 
                <InstrumentosIDU telemetria={telemetriaActual} />
            </div>

            {/* TU NUEVO REPRODUCTOR FLOTANTE EN LA PARTE INFERIOR */}
            <div className="fixed bottom-4 left-1/2 -translate-x-1/2 w-full max-w-4xl px-4 z-50">
                <div className="bg-[#1a1a1a] border border-zinc-800 rounded-lg shadow-2xl p-4 transition-colors">
                    <div className="flex items-center gap-4">
                        
                        {/* Botón Play/Pausa */}
                        <button 
                            onClick={() => setReproduciendo(!reproduciendo)}
                            className={`flex-shrink-0 text-black rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition transform hover:scale-105 focus:outline-none ${
                                reproduciendo ? 'bg-amber-500 hover:bg-amber-400' : 'bg-[#00ff00] hover:bg-green-400'
                            }`}
                        >
                            <span className="text-lg ml-1">{reproduciendo ? '⏸' : '▶'}</span>
                        </button>

                        {/* Tiempo Actual */}
                        <div className="flex-shrink-0 w-12 text-center">
                            <span className="text-sm font-mono font-bold text-[#00ff00]">
                                {formatearTiempo(indiceActual)}
                            </span>
                        </div>

                        {/* Barra de Progreso (Timeline) */}
                        <div className="flex-1 w-full relative flex items-center">
                            <input 
                                type="range" 
                                min="0" 
                                max={historialVuelo.length - 1} 
                                value={indiceActual}
                                onChange={(e) => {
                                    setReproduciendo(false); // Se pausa automáticamente al arrastrar
                                    setIndiceActual(parseInt(e.target.value));
                                }}
                                className="w-full h-2 bg-zinc-700 rounded-lg appearance-none cursor-pointer accent-[#00ff00] hover:accent-green-400 transition-all outline-none"
                            />
                        </div>

                        {/* Tiempo Total */}
                        <div className="flex-shrink-0 w-12 text-center">
                            <span className="text-sm font-mono font-bold text-zinc-500">
                                {formatearTiempo(historialVuelo.length - 1)}
                            </span>
                        </div>

                        {/* Selector de Velocidad */}
                        <div className="flex-shrink-0 border-l border-zinc-700 pl-4">
                            <select 
                                value={velocidad}
                                onChange={(e) => setVelocidad(Number(e.target.value))}
                                className="bg-zinc-800 hover:bg-zinc-700 border border-zinc-600 text-black rounded text-xs font-bold py-2 px-3 cursor-pointer outline-none focus:ring-1 focus:ring-[#00ff00]"
                            >
                                <option value="1">1x</option>
                                <option value="2">2x</option>
                                <option value="5">5x</option>
                                <option value="10">10x</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}