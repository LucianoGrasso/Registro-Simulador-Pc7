import React, { useState, useEffect, useRef } from 'react';
import { Head } from '@inertiajs/react';
import Echo from 'laravel-echo';
import { router } from '@inertiajs/react';

const TapeScale = ({ value, step, max, pixelsPerUnit, label }) => {
    // Calculamos el desplazamiento relativo al centro del viewport
    const translateY = value * pixelsPerUnit;
    
    const ticks = [];
    for (let i = 0; i <= max; i += step) {
        ticks.push(i);
    }

    return (
        <div className="flex flex-col items-center mx-1">
            <span className="text-[10px] text-zinc-400 font-bold mb-1">{label}</span>
            <div className="tape-viewport">
                <div 
                    className="tape-moving-part" 
                    style={{ transform: `translateY(${translateY}px)` }}
                >
                    {ticks.map(t => (
                        <div 
                            key={t} 
                            className="tape-tick" 
                            style={{ height: `${step * pixelsPerUnit}px` }}
                        >
                            <span className="mr-2">{t % (step * 2) === 0 ? t : '-'}</span>
                            <div className={`h-[1px] bg-white ${t % (step * 2) === 0 ? 'w-4' : 'w-2'}`}></div>
                        </div>
                    ))}
                </div>
                {/* Cuadro central fijo */}
                <div className="tape-center-box">
                    <div className="bg-black border-2 border-[#00ff00] px-2 py-1 rounded-sm shadow-[0_0_10px_rgba(0,255,0,0.5)]">
                        {Math.round(value)}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default function Welcome( dataReal ) {
    const [pitch, setPitch] = useState(0); 
    const [roll, setRoll] = useState(0);
    const [alt, setAlt] = useState(0);
    const [spd, setSpd] = useState(0);
    const [hdg, setHdg] = useState(0);
    const [oat, setOat] = useState(15);
    const [windDir, setWindDir] = useState(0);
    const [windSpd, setWindSpd] = useState(0);

    // --- LÓGICA PARA EVITAR GIRO BRUSCO EN 360 ---
    const [rotation, setRotation] = useState(0);
    const prevHdg = useRef(0);

    useEffect(() => {
        let diff = hdg - prevHdg.current;
        if (diff > 180) diff -= 360;
        if (diff < -180) diff += 360;
        setRotation(prev => prev + diff);
        prevHdg.current = hdg;
    }, [hdg]);
    // --------------------------------------------

    // --- NUEVAS VARIABLES DE ESTADO PARA IFR (AGREGADO) ---
    const [nav1Freq, setNav1Freq] = useState(0);
    const [nav1Obs, setNav1Obs] = useState(360);
    const [nav1Cdi, setNav1Cdi] = useState(0);
    const [nav1Bearing, setNav1Bearing] = useState(0); // <-- ESTE APUNTA AL VOR
    const [dmeDist, setDmeDist] = useState(0);
    const [com1Freq, setCom1Freq] = useState(0.0);
    // -----------------------------------------------------

    useEffect(() => {

        if (window.Echo) {
            const channel = window.Echo.channel('telemetry-stream')
                .listen('TelemetryUpdated', (e) => {
                    console.log("Datos recibidos:", e.data);
                    if (e.data.pitch !== undefined) setPitch(e.data.pitch);
                    if (e.data.roll !== undefined) setRoll(e.data.roll);
                    if (e.data.alt !== undefined) setAlt(Math.round(e.data.alt));
                    if (e.data.spd !== undefined) setSpd(Math.round(e.data.spd));
                    if (e.data.hdg !== undefined) setHdg(Math.round(e.data.hdg));
                    if (e.data.oat !== undefined) setOat(e.data.oat);
                    if (e.data.wind_dir !== undefined) setWindDir(e.data.wind_dir);
                    if (e.data.wind_spd !== undefined) setWindSpd(e.data.wind_spd);

                    // --- ACTUALIZACIÓN DE DATOS IFR (AGREGADO) ---
                    if (e.data.nav1_freq !== undefined) setNav1Freq(e.data.nav1_freq);
                    if (e.data.nav1_obs !== undefined) setNav1Obs(e.data.nav1_obs);
                    if (e.data.nav1_cdi !== undefined) setNav1Cdi(e.data.nav1_cdi);
                    if (e.data.nav1_bearing !== undefined) setNav1Bearing(e.data.nav1_bearing);
                    if (e.data.dme_dist !== undefined) setDmeDist(e.data.dme_dist);
                    if (e.data.com1_freq !== undefined) setCom1Freq(e.data.com1_freq);
                });

            return () => {
                window.Echo.leave('telemetry-stream');
            };
        } else {
            console.error("Laravel Echo no está inicializado. Verifica bootstrap.js");
        }
    }, []);

    return (
        <div className="flex items-center justify-center min-h-screen bg-black font-mono select-none">
            <Head title="Genesys IDU Digital Mirror" />
            
            {/* CONTENEDOR MÁS GRANDE: 900px de ancho */}
            <div className="relative w-[850px] h-[920px] bg-black flex flex-col overflow-hidden border-2 border-zinc-800 shadow-2xl">
                
                {/* 1. HEADER (Simplificado) */}
                <div className="h-8 bg-[#111] flex justify-between items-center px-6 border-b border-zinc-800">
                    <div className="flex items-center gap-4">
                        {/* Botón de Retorno al Registro */}
                        <a 
                            href="http://localhost/dashboard" 
                            className="text-[10px] bg-zinc-800 hover:bg-zinc-700 text-white py-1 px-3 rounded border border-zinc-600 transition-colors"
                        >
                            ← VOLVER AL REGISTRO
                        </a>
                        <div className="text-xs text-[#00ff00] font-bold">COM1 <span className="text-white ml-2">{com1Freq.toFixed(2)}</span></div>
                    </div>
                    {/* AQUÍ MUESTRO LA FRECUENCIA REAL DE NAV1 */}
                    <div className="text-xs text-[#00ff00] font-bold">NAV1 <span className="text-white ml-2">{nav1Freq.toFixed(2)}</span></div>
                </div>
                {/* 2. PFD AREA (Más espacioso) */}
                <div className="relative h-[450px] flex items-center justify-around px-8 bg-[#080808]">
                    
                    {/* CINTA DE VELOCIDAD */}
                    <div className="flex flex-col items-center relative">
                        <span className="text-[12px] text-zinc-400 font-bold mb-2">IAS (KTS)</span>
                        <TapeScale value={spd} step={10} max={500} pixelsPerUnit={5} />
                    </div>

                    {/* ÁREA DEL HORIZONTE (Más ancha y redondeada) */}
                    <div className="relative w-[500px] h-[430px] overflow-hidden bg-black rounded-[50px] border-2 border-zinc-700 mx-4">
                        {/* INDICADOR DE ROLL AMPLIADO (±60°) */}
                        <div className="absolute top-4 left-1/2 -translate-x-1/2 z-50 pointer-events-none">
                            <svg width="320" height="150" viewBox="0 0 320 150">
                                {/* 1. Arco de referencia ampliado a 120 grados totales (±60°) */}
                                <path 
                                    d="M 73.4 80 A 100 100 0 0 1 246.6 80" 
                                    stroke="white" 
                                    strokeWidth="2" 
                                    fill="none" 
                                    opacity="0.2" 
                                />
                                
                                {/* 2. Generación de Ticks extendida hasta 60° */}
                                {[-60, -55, -50, -45, -40, -35, -30, -25, -20, -15, -10, -5, 0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60].map(deg => {
                                    const rad = (deg - 90) * (Math.PI / 180);
                                    const isTen = deg % 10 === 0;
                                    const isMain = deg === 0 || Math.abs(deg) === 30 || Math.abs(deg) === 60;
                                    
                                    const innerR = 100;
                                    const outerR = isMain ? 118 : (isTen ? 112 : 106); 

                                    const x1 = 160 + Math.cos(rad) * innerR;
                                    const y1 = 130 + Math.sin(rad) * innerR;
                                    const x2 = 160 + Math.cos(rad) * outerR;
                                    const y2 = 130 + Math.sin(rad) * outerR;

                                    return (
                                        <line 
                                            key={deg}
                                            x1={x1} y1={y1} x2={x2} y2={y2}
                                            stroke="white"
                                            strokeWidth={isMain ? "2.5" : (isTen ? "1.5" : "1")}
                                            opacity={isMain ? "1" : (isTen ? "0.7" : "0.4")}
                                        />
                                    );
                                })}

                                {/* 3. Puntero Amarillo con nuevo límite de ±60° */}
                                <path 
                                    d="M -8 0 L 0 -14 L 8 0 Z" 
                                    fill="#ffff00" 
                                    style={{ 
                                        /* El puntero ahora se detiene visualmente en los 60 grados */
                                        transform: `translate(160px, 30px) rotate(${Math.max(-60, Math.min(60, roll))}deg)`,
                                        transformOrigin: '0px 100px', 
                                        transition: 'transform 0.1s linear'
                                    }} 
                                />
                            </svg>
                        </div>

                        {/* 2.2 Capa de Movimiento (Cielo/Tierra) */}
                        <div 
                            className="absolute inset-[-150%] transition-transform duration-75 ease-linear"
                            style={{ transform: `rotate(${-roll}deg) translateY(${pitch * 10}px)` }}
                        >
                            <div className="h-1/2 w-full bg-[#0072bc]"></div>
                            <div className="h-1/2 w-full bg-[#d97300] border-t-[3px] border-white/70"></div>
                            
                            {/* Pitch Ladder Pro: Con ganchos y números destacados */}
                            <div className="absolute inset-0 flex flex-col items-center justify-center">
                                {[30, 20, 10, 0, -10, -20, -30].map(v => (
                                    <div key={v} className="flex items-center w-80 justify-between my-8 relative">
                                        {/* Número Izquierdo con sombra para resaltar */}
                                        <span className="text-sm text-white font-bold drop-shadow-[0_0_2px_black] w-8 text-right">
                                            {v !== 0 ? Math.abs(v) : ''}
                                        </span>

                                        {/* Línea con "Ganchos" en los extremos */}
                                        <div className="relative flex items-center justify-center flex-1 mx-4">
                                            <div className={`h-[2px] bg-white shadow-[0_0_3px_rgba(255,255,255,0.5)] ${v === 0 ? 'w-full' : 'w-24'}`}>
                                                {/* El gancho: línea vertical pequeña al final de cada barra de pitch */}
                                                {v !== 0 && (
                                                    <>
                                                        <div className={`absolute left-0 w-[2px] h-3 bg-white ${v > 0 ? 'top-0' : 'bottom-0'}`}></div>
                                                        <div className={`absolute right-0 w-[2px] h-3 bg-white ${v > 0 ? 'top-0' : 'bottom-0'}`}></div>
                                                    </>
                                                )}
                                            </div>
                                        </div>

                                        {/* Número Derecho */}
                                        <span className="text-sm text-white font-bold drop-shadow-[0_0_2px_black] w-8 text-left">
                                            {v !== 0 ? Math.abs(v) : ''}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* 2.3 Símbolo W Central */}
                        <div className="absolute inset-0 flex items-center justify-center z-40 pointer-events-none">
                            <div className="flex items-center">
                                <div className="w-20 h-2.5 bg-[#ffff00] border border-black shadow-lg"></div>
                                <div className="w-8 h-8 border-[5px] border-[#ffff00] mx-3 rotate-45 border-t-0 border-l-0 shadow-lg"></div>
                                <div className="w-20 h-2.5 bg-[#ffff00] border border-black shadow-lg"></div>
                            </div>
                        </div>
                    </div>

                    {/* CINTA DE ALTITUD */}
                    <div className="flex flex-col items-center relative">
                        <span className="text-[12px] text-zinc-400 font-bold mb-2">ALT (FT)</span>
                        <TapeScale value={alt} step={100} max={50000} pixelsPerUnit={0.5} />
                    </div>
                </div>

                {/* 3. ND / HSI (Diseño Profesional Genesys) */}
                <div className="flex-1 bg-black relative flex flex-col items-center pt-6 border-t border-zinc-800">
                    
                    {/* INFO DE VIENTO */}
                    <div className="absolute left-6 top-6 flex flex-col items-start border-l border-white/20 pl-2">
                        <div className="text-[11px] text-zinc-400 font-bold">WIND</div>
                        <div className="flex items-center">
                            <div 
                                className="text-[#00ffff] mr-1 text-lg"
                                style={{ transform: `rotate(${windDir - hdg}deg)` }}
                            >
                                ↑
                            </div>
                            <div className="text-white font-bold text-sm">
                                {windDir}° / {windSpd} <span className="text-[9px]">KTS</span>
                            </div>
                        </div>
                    </div>

                    {/* BLOQUE DE TEMPERATURA Y CÁLCULOS */}
                    <div className="absolute left-6 bottom-10 flex flex-col space-y-0.5 text-[11px] font-bold">
                        <div className="text-zinc-500">OAT <span className="text-white ml-2">{oat}°C</span></div>
                        <div className="text-zinc-500">ISA <span className="text-white ml-2">{oat - 15}°C</span></div>
                        <div className="text-zinc-500">TAS <span className="text-[#00ff00] ml-2">{Math.round(spd * 1.05)}</span></div>
                        <div className="text-zinc-400 mt-1">GS <span className="text-[#00ff00] text-lg ml-2">{Math.round(spd * 0.95)}</span></div>
                    </div>


                    {/* INDICADOR DE RUMBO ACTUAL (EXTERNO A LA ROSA) */}
                    <div className="absolute top-[10px] flex flex-col items-center z-50">
                        <div className="bg-black border border-[#00ff00] px-3 py-1 text-[#00ff00] font-bold text-xl shadow-[0_0_10px_rgba(0,255,0,0.3)]">
                            {Math.round(hdg).toString().padStart(3, '0')}
                        </div>
                        <div className="w-0 h-0 border-l-[10px] border-l-transparent border-r-[10px] border-r-transparent border-t-[14px] border-t-[#00ff00]"></div>
                    </div>

                    {/* Rosa de los Vientos */}
                    <div 
                        className="relative w-[300px] h-[300px] border-2 border-zinc-800 rounded-full flex items-center justify-center transition-transform duration-75 ease-linear overflow-visible mt-10"
                        style={{ transform: `rotate(${-rotation}deg)` }}
                    >
                        {Array.from({ length: 180 }).map((_, i) => {
                            const angle = i * 2;
                            const isTen = angle % 10 === 0;
                            const isThirty = angle % 30 === 0;
                            
                            let label = null;
                            if (isThirty) {
                                label = angle === 0 ? '36' : (angle / 10).toString().padStart(2, '0');
                            }

                            return (
                                <div 
                                    key={i} 
                                    className="absolute h-full w-8 flex flex-col items-center"
                                    style={{ transform: `rotate(${angle}deg)` }}
                                >
                                    <div className={`bg-white ${
                                        isThirty ? 'w-[3px] h-5' : 
                                        isTen ? 'w-[2px] h-3' : 
                                        'w-[1px] h-1.5 opacity-50'
                                    }`}></div>
                                    
                                    {label && (
                                        <div 
                                            className="absolute top-[-40px] font-bold text-white flex items-center justify-center w-10 text-lg"
                                            style={{ transform: `rotate(${hdg - angle}deg)` }}
                                        >
                                            {label}
                                        </div>
                                    )}
                                </div>
                            );
                        })}

                        {/* AGUJA DE BEARING (DELGADA) - PUNTAS EN AMBOS EXTREMOS (MISMA DIRECCIÓN) */}
                        <div 
                            className="absolute inset-0 flex items-center justify-center transition-transform duration-75 ease-linear pointer-events-none" 
                            style={{ transform: `rotate(${nav1Bearing}deg)` }}
                        >
                            <div className="absolute h-[270px] w-full flex flex-col items-center justify-between py-1">
                                {/* Punta Superior (Hacia la estación) */}
                                <div className="relative w-6 h-5">
                                    <div className="absolute top-0 left-1/2 -translate-x-full w-[2px] h-7 bg-[#00ffff] origin-top rotate-[-30deg]"></div>
                                    <div className="absolute top-0 left-1/2 w-[2px] h-7 bg-[#00ffff] origin-top rotate-[30deg]"></div>
                                </div>
                                <div className="w-0.5 h-14 bg-[#00ffff]"></div>

                                
                                {/* Línea central */}
                                <div className="flex-grow"></div>
                                
                                <div className="w-0.5 h-14 bg-[#00ffff]"></div>
                                {/* Punta Inferior (Atrás, pero con la misma orientación de flecha) */}
                                <div className="relative w-6 h-5">
                                    <div className="absolute top-0 left-1/2 -translate-x-full w-[2px] h-7 bg-[#00ffff] origin-top rotate-[-30deg]"></div>
                                    <div className="absolute top-0 left-1/2 w-[2px] h-7 bg-[#00ffff] origin-top rotate-[30deg]"></div>
                                </div>
                            </div>
                        </div>

                        {/* FLECHA GRUESA (OBS / CURSO) - CENTRO HUECO CON CDI MEJORADO */}
                        <div 
                            className="absolute inset-0 flex items-center justify-center transition-transform duration-200" 
                            style={{ transform: `rotate(${nav1Obs}deg)` }}
                        >
                            <div className="absolute h-[230px] w-full flex flex-col justify-between items-center">
                                {/* Cabeza de la flecha sólida */}
                                <div className="w-0 h-0 border-l-[10px] border-l-transparent border-r-[10px] border-r-transparent border-b-[20px] border-b-[#00ffff]"></div>
                                <div className="w-1 h-14 bg-[#00ffff]"></div>
                                
                                {/* Espacio vacío en el centro */}
                                <div className="flex-grow"></div>
                                
                                {/* Cola sólida (FROM) */}
                                <div className="w-1 h-14 bg-[#00ffff]"></div>
                            </div>

                            {/* BARRA DE DESVIACIÓN (CDI) - MÁS CORTA Y CON PUNTA PROPIA */}
                            <div 
                                className="absolute flex flex-col items-center transition-transform duration-300 ease-out z-10"
                                style={{ 
                                    // Multiplicamos por 30 para que 1 punto = 30px, 2 puntos = 60px
                                    transform: `translateX(${Math.max(-65, Math.min(65, nav1Cdi * 30))}px)`,
                                    transition: 'transform 0.1s linear' 
                                }}
                            >
                                {/* Punta pequeña en el CDI para indicar el desvío */}
                                <div className="w-0 h-0 border-l-[6px] border-l-transparent border-r-[6px] border-r-transparent border-b-[10px] border-b-[#00ffff] mb-[-2px]"></div>
                                {/* Barra vertical más corta */}
                                <div className="w-[2.5px] h-14 bg-[#00ffff] shadow-[0_0_8px_#00ffff]"></div>
                            </div>

                            {/* Escala de puntos (Dots de referencia) */}
                            <div className="absolute flex gap-8 z-0">
                                <div className="w-2 h-2 rounded-full border border-white/40"></div>
                                <div className="w-2 h-2 rounded-full border border-white/40"></div>
                                <div className="w-12"></div> 
                                <div className="w-2 h-2 rounded-full border border-white/40"></div>
                                <div className="w-2 h-2 rounded-full border border-white/40"></div>
                            </div>
                        </div>

                        {/* AVIÓN HSI CENTRAL */}
                        <div 
                            className="absolute inset-0 flex items-center justify-center pointer-events-none z-30 transition-transform duration-75 ease-linear"
                            style={{ transform: `rotate(${rotation}deg)` }} 
                        >
                            <svg width="40" height="40" viewBox="0 0 100 100" fill="none">
                                <path 
                                    d="M50 12 L54 30 L54 55 L90 75 L54 70 L54 85 L68 94 L52 92 L48 92 L32 94 L46 85 L46 70 L10 75 L46 55 L46 30 Z" 
                                    fill="white"
                                    fillOpacity="1.0"
                                    stroke="white" 
                                    strokeWidth="1.5" 
                                    strokeLinejoin="round" 
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                {/* 4. FOOTER */}
                <div className="h-8 bg-[#111] border-t border-zinc-800 flex justify-around items-center">
                    <span className="text-[#00ff00] text-xs font-bold font-mono">HDG: {hdg}°</span>
                    <span className="text-white/50 text-[10px]">{new Date().toISOString().slice(11, 19)}Z</span>
                </div>
            </div>
        </div>
    );
}