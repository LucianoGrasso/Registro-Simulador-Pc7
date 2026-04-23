// resources/js/components/InstrumentosIDU.jsx
import React, { useState, useEffect, useRef } from 'react';
import { Head } from '@inertiajs/react';

const TapeScale = ({ value, step, max, pixelsPerUnit, label }) => {
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
                <div className="tape-center-box">
                    <div className="bg-black border-2 border-[#00ff00] px-2 py-1 rounded-sm shadow-[0_0_10px_rgba(0,255,0,0.5)]">
                        {Math.round(value)}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default function InstrumentosIDU({ telemetria }) {
    // CORRECCIÓN: Nombres exactos al JSON de Python (snake_case)
    const {
        pitch = 0, roll = 0, alt = 0, spd = 0, hdg = 0,
        oat = 15, wind_dir = 0, wind_spd = 0,
        nav1_freq = 0, nav1_obs = 360, nav1_cdi = 0, 
        nav1_bearing = 0, com1_freq = 0
    } = telemetria;

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

    return (
        <div className="flex items-center justify-center min-h-screen bg-black font-mono select-none">
            <Head title="Genesys IDU Digital Mirror" />
            
            <div className="relative w-[850px] h-[920px] bg-black flex flex-col overflow-hidden border-2 border-zinc-800 shadow-2xl">
                
                {/* 1. HEADER */}
                <div className="h-8 bg-[#111] flex justify-between items-center px-6 border-b border-zinc-800">
                    <div className="flex items-center gap-4">
                        <a 
                            href="http://localhost/dashboard" 
                            className="text-[10px] bg-zinc-800 hover:bg-zinc-700 text-white py-1 px-3 rounded border border-zinc-600 transition-colors"
                        >
                            ← VOLVER AL REGISTRO
                        </a>
                        <div className="text-xs text-[#00ff00] font-bold">COM1 <span className="text-white ml-2">{com1_freq.toFixed(2)}</span></div>
                    </div>
                    <div className="text-xs text-[#00ff00] font-bold">NAV1 <span className="text-white ml-2">{nav1_freq.toFixed(2)}</span></div>
                </div>

                {/* 2. PFD AREA */}
                <div className="relative h-[450px] flex items-center justify-around px-8 bg-[#080808]">
                    
                    {/* CINTA DE VELOCIDAD */}
                    <div className="flex flex-col items-center relative">
                        <span className="text-[12px] text-zinc-400 font-bold mb-2">IAS (KTS)</span>
                        <TapeScale value={spd} step={10} max={500} pixelsPerUnit={5} />
                    </div>

                    {/* ÁREA DEL HORIZONTE */}
                    <div className="relative w-[500px] h-[430px] overflow-hidden bg-black rounded-[50px] border-2 border-zinc-700 mx-4">
                        {/* INDICADOR DE ROLL */}
                        <div className="absolute top-4 left-1/2 -translate-x-1/2 z-50 pointer-events-none">
                            <svg width="320" height="150" viewBox="0 0 320 150">
                                <path d="M 73.4 80 A 100 100 0 0 1 246.6 80" stroke="white" strokeWidth="2" fill="none" opacity="0.2" />
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
                                            key={deg} x1={x1} y1={y1} x2={x2} y2={y2}
                                            stroke="white" strokeWidth={isMain ? "2.5" : (isTen ? "1.5" : "1")}
                                            opacity={isMain ? "1" : (isTen ? "0.7" : "0.4")}
                                        />
                                    );
                                })}
                                <path 
                                    d="M -8 0 L 0 -14 L 8 0 Z" 
                                    fill="#ffff00" 
                                    style={{ 
                                        transform: `translate(160px, 30px) rotate(${Math.max(-60, Math.min(60, roll))}deg)`,
                                        transformOrigin: '0px 100px', 
                                        transition: 'transform 0.1s linear'
                                    }} 
                                />
                            </svg>
                        </div>

                        {/* Capa de Movimiento (Cielo/Tierra) */}
                        <div 
                            className="absolute inset-[-150%] transition-transform duration-75 ease-linear"
                            style={{ transform: `rotate(${-roll}deg) translateY(${pitch * 10}px)` }}
                        >
                            <div className="h-1/2 w-full bg-[#0072bc]"></div>
                            <div className="h-1/2 w-full bg-[#d97300] border-t-[3px] border-white/70"></div>
                            
                            {/* Pitch Ladder */}
                            <div className="absolute inset-0 flex flex-col items-center justify-center">
                                {[30, 20, 10, 0, -10, -20, -30].map(v => (
                                    <div key={v} className="flex items-center w-80 justify-between my-8 relative">
                                        <span className="text-sm text-white font-bold drop-shadow-[0_0_2px_black] w-8 text-right">
                                            {v !== 0 ? Math.abs(v) : ''}
                                        </span>
                                        <div className="relative flex items-center justify-center flex-1 mx-4">
                                            <div className={`h-[2px] bg-white shadow-[0_0_3px_rgba(255,255,255,0.5)] ${v === 0 ? 'w-full' : 'w-24'}`}>
                                                {v !== 0 && (
                                                    <>
                                                        <div className={`absolute left-0 w-[2px] h-3 bg-white ${v > 0 ? 'top-0' : 'bottom-0'}`}></div>
                                                        <div className={`absolute right-0 w-[2px] h-3 bg-white ${v > 0 ? 'top-0' : 'bottom-0'}`}></div>
                                                    </>
                                                )}
                                            </div>
                                        </div>
                                        <span className="text-sm text-white font-bold drop-shadow-[0_0_2px_black] w-8 text-left">
                                            {v !== 0 ? Math.abs(v) : ''}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Símbolo W Central */}
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

                {/* 3. ND / HSI */}
                <div className="flex-1 bg-black relative flex flex-col items-center pt-6 border-t border-zinc-800">
                    
                    {/* INFO DE VIENTO */}
                    <div className="absolute left-6 top-6 flex flex-col items-start border-l border-white/20 pl-2">
                        <div className="text-[11px] text-zinc-400 font-bold">WIND</div>
                        <div className="flex items-center">
                            <div 
                                className="text-[#00ffff] mr-1 text-lg"
                                style={{ transform: `rotate(${wind_dir - hdg}deg)` }}
                            >
                                ↑
                            </div>
                            <div className="text-white font-bold text-sm">
                                {wind_dir}° / {wind_spd} <span className="text-[9px]">KTS</span>
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

                    {/* INDICADOR DE RUMBO ACTUAL */}
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

                        {/* AGUJA DE BEARING (CORREGIDA A nav1_bearing) */}
                        <div 
                            className="absolute inset-0 flex items-center justify-center transition-transform duration-75 ease-linear pointer-events-none" 
                            style={{ transform: `rotate(${nav1_bearing}deg)` }}
                        >
                            <div className="absolute h-[270px] w-full flex flex-col items-center justify-between py-1">
                                <div className="relative w-6 h-5">
                                    <div className="absolute top-0 left-1/2 -translate-x-full w-[2px] h-7 bg-[#00ffff] origin-top rotate-[-30deg]"></div>
                                    <div className="absolute top-0 left-1/2 w-[2px] h-7 bg-[#00ffff] origin-top rotate-[30deg]"></div>
                                </div>
                                <div className="w-0.5 h-14 bg-[#00ffff]"></div>
                                <div className="flex-grow"></div>
                                <div className="w-0.5 h-14 bg-[#00ffff]"></div>
                                <div className="relative w-6 h-5">
                                    <div className="absolute top-0 left-1/2 -translate-x-full w-[2px] h-7 bg-[#00ffff] origin-top rotate-[-30deg]"></div>
                                    <div className="absolute top-0 left-1/2 w-[2px] h-7 bg-[#00ffff] origin-top rotate-[30deg]"></div>
                                </div>
                            </div>
                        </div>

                        {/* FLECHA GRUESA (OBS / CURSO - CORREGIDA A nav1_obs) */}
                        <div 
                            className="absolute inset-0 flex items-center justify-center transition-transform duration-200" 
                            style={{ transform: `rotate(${nav1_obs}deg)` }}
                        >
                            <div className="absolute h-[230px] w-full flex flex-col justify-between items-center">
                                <div className="w-0 h-0 border-l-[10px] border-l-transparent border-r-[10px] border-r-transparent border-b-[20px] border-b-[#00ffff]"></div>
                                <div className="w-1 h-14 bg-[#00ffff]"></div>
                                <div className="flex-grow"></div>
                                <div className="w-1 h-14 bg-[#00ffff]"></div>
                            </div>

                            {/* BARRA DE DESVIACIÓN (CDI - CORREGIDA A nav1_cdi) */}
                            <div 
                                className="absolute flex flex-col items-center transition-transform duration-300 ease-out z-10"
                                style={{ 
                                    transform: `translateX(${Math.max(-65, Math.min(65, nav1_cdi * 30))}px)`,
                                    transition: 'transform 0.1s linear' 
                                }}
                            >
                                <div className="w-0 h-0 border-l-[6px] border-l-transparent border-r-[6px] border-r-transparent border-b-[10px] border-b-[#00ffff] mb-[-2px]"></div>
                                <div className="w-[2.5px] h-14 bg-[#00ffff] shadow-[0_0_8px_#00ffff]"></div>
                            </div>

                            {/* Escala de puntos */}
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