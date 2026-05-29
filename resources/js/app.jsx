// resources/js/app.jsx

import "./components/bootstrap";

import Alpine from 'alpinejs';
import React, { useState, useEffect, useRef } from 'react';
import { createRoot } from 'react-dom/client';

import FiltroCascada    from '@/components/FiltroCascada';
import MobileSearch     from '@/components/MobileSearch';
import GridRestaurantes from '@/components/GridRestaurantes';
import GridEmpleos      from '@/components/GridEmpleos';
import Countdown        from '@/components/Countdown';

window.Alpine = Alpine;
Alpine.start();

// ── 1. Grid de restaurantes ────────────────────────────────────────────────
const elGrid = document.getElementById('grid-restaurantes-root');
if (elGrid) {
    createRoot(elGrid).render(<GridRestaurantes />);
}

// ── 2. Búsqueda móvil — restaurantes ──────────────────────────────────────
const elMobile = document.getElementById('mobile-search-root');
if (elMobile) {
    const d = window.__FILTRO_DATA__ || {};
    createRoot(elMobile).render(
        <MobileSearch
            departamentos       = {d.departamentos       ?? []}
            municipios          = {d.municipios          ?? []}
            deptoSeleccionado   = {d.deptoSeleccionado   ?? ''}
            munSeleccionado     = {d.munSeleccionado     ?? ''}
            especialidadInicial = {d.especialidadInicial ?? ''}
            searchInicial       = {d.searchInicial       ?? ''}
            actionUrl           = {d.actionUrl           ?? '/restaurantes'}
        />
    );
}

// ── 3. Grid de empleos ────────────────────────────────────────────────────
const elGridEmpleos = document.getElementById('grid-empleos-root');
if (elGridEmpleos) {
    createRoot(elGridEmpleos).render(<GridEmpleos />);
}

// ── 4. Búsqueda móvil — empleos ───────────────────────────────────────────
const elMobileEmpleos = document.getElementById('mobile-search-empleos-root');
if (elMobileEmpleos) {
    const d = window.__FILTRO_EMPLEOS__ || {};
    createRoot(elMobileEmpleos).render(
        <MobileSearchEmpleos
            actionUrl        = {d.actionUrl        ?? '/empleos'}
            searchInicial    = {d.searchInicial    ?? ''}
            departamentos    = {d.departamentos    ?? []}
            deptoSeleccionado= {d.deptoSeleccionado ?? ''}
        />
    );
}

// ── 5. Countdowns de eventos ──────────────────────────────────────────────
document.querySelectorAll('[data-countdown]').forEach(el => {
    const fecha = el.getAttribute('data-countdown');
    createRoot(el).render(<Countdown fechaExpire={fecha} />);
});


/* ═══════════════════════════════════════════════════════════════════════════
   COMPONENTE INLINE: MobileSearchEmpleos
   Versión simplificada de MobileSearch solo para la página de empleos.
   Sin municipios ni especialidad — solo texto + departamento.
   ═══════════════════════════════════════════════════════════════════════════ */
function MobileSearchEmpleos({ actionUrl, searchInicial, departamentos, deptoSeleccionado }) {
    const [abierto,   setAbierto]   = useState(false);
    const [animating, setAnimating] = useState(false);
    const [search,    setSearch]    = useState(searchInicial);
    const [depto,     setDepto]     = useState(deptoSeleccionado);
    const panelRef = useRef(null);
    const btnRef   = useRef(null);

    useEffect(() => {
        function handleOutside(e) {
            if (
                panelRef.current && !panelRef.current.contains(e.target) &&
                btnRef.current   && !btnRef.current.contains(e.target)
            ) cerrar();
        }
        if (abierto) document.addEventListener('mousedown', handleOutside);
        return () => document.removeEventListener('mousedown', handleOutside);
    }, [abierto]);

    useEffect(() => {
        document.body.style.overflow = abierto ? 'hidden' : '';
        return () => { document.body.style.overflow = ''; };
    }, [abierto]);

    function abrir() {
        setAbierto(true);
        requestAnimationFrame(() => requestAnimationFrame(() => setAnimating(true)));
    }
    function cerrar() {
        setAnimating(false);
        setTimeout(() => setAbierto(false), 280);
    }
    function toggle(e) {
        e.stopPropagation();
        abierto ? cerrar() : abrir();
    }

    const inputBase = {
        background: '#f8f7f6', border: '1.5px solid #e7e5e4', borderRadius: 12,
        padding: '10px 14px', fontSize: 13, fontWeight: 600, color: '#1c1917',
        fontFamily: "'Instrument Sans', sans-serif", width: '100%', outline: 'none',
        appearance: 'none', boxSizing: 'border-box',
    };
    const labelBase = {
        fontSize: 10, fontWeight: 900, letterSpacing: '0.14em',
        textTransform: 'uppercase', color: '#a8a29e',
        marginBottom: 4, display: 'flex', alignItems: 'center', gap: 5,
    };

    const keyframes = `
        @keyframes empSlideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        @keyframes empSlideUp   { from { opacity:1; transform:translateY(0); } to { opacity:0; transform:translateY(-10px); } }
    `;

    return (
        <>
            <style>{keyframes}</style>

            {/* Botón lupa */}
            <button ref={btnRef} onClick={toggle} aria-label="Buscar empleos"
                style={{
                    width: 38, height: 38, borderRadius: '50%', border: 'none',
                    cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                    background: abierto ? '#fff7ed' : '#f5f5f4',
                    color:      abierto ? '#ea580c' : '#57534e',
                    transition: 'background .2s, color .2s, transform .2s',
                    transform:  abierto ? 'scale(1.08)' : 'scale(1)',
                }}>
                <i className={abierto ? 'fas fa-times' : 'fas fa-search'} style={{ fontSize: 13 }} />
            </button>

            {/* Panel */}
            {abierto && (
                <div style={{
                    position: 'fixed', top: 76, left: 0, right: 0, bottom: 0,
                    zIndex: 45, display: 'flex', flexDirection: 'column',
                    pointerEvents: 'all',
                }} aria-modal="true" role="dialog">

                    {/* Overlay */}
                    <div style={{
                        position: 'absolute', inset: 0,
                        background: 'rgba(0,0,0,0.35)', backdropFilter: 'blur(2px)',
                        animation: `${animating ? 'empSlideDown' : 'empSlideUp'} 280ms ease forwards`,
                        opacity: 0,
                    }} onClick={cerrar} />

                    {/* Sheet */}
                    <div ref={panelRef} style={{
                        position: 'absolute', top: 0, left: 0, right: 0,
                        background: 'rgba(255,255,255,0.99)', backdropFilter: 'blur(16px)',
                        borderBottom: '1px solid #e7e5e4',
                        padding: '1.25rem 1.25rem 1.5rem',
                        boxShadow: '0 12px 32px rgba(0,0,0,0.10)',
                        animation: `${animating ? 'empSlideDown' : 'empSlideUp'} 280ms cubic-bezier(0.16,1,0.3,1) forwards`,
                        opacity: 0,
                        overflowY: 'auto', maxHeight: 'calc(100vh - 76px)',
                    }}>
                        {/* Encabezado */}
                        <div style={{ display: 'flex', alignItems: 'center',
                            justifyContent: 'space-between', marginBottom: 14 }}>
                            <span style={{
                                fontSize: 11, fontWeight: 900, letterSpacing: '0.14em',
                                textTransform: 'uppercase', color: '#a8a29e',
                                fontFamily: "'Instrument Sans', sans-serif",
                            }}>
                                <i className="fas fa-briefcase" style={{ color: '#ea580c', marginRight: 6 }} />
                                Buscar empleos
                            </span>
                            <button onClick={cerrar} style={{
                                background: '#f5f5f4', border: 'none', borderRadius: '50%',
                                width: 28, height: 28, display: 'flex', alignItems: 'center',
                                justifyContent: 'center', cursor: 'pointer', color: '#78716c', fontSize: 12,
                            }}>
                                <i className="fas fa-times" />
                            </button>
                        </div>

                        {/* Formulario */}
                        <form action={actionUrl} method="GET"
                            style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>

                            {/* Puesto */}
                            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
                                <label style={labelBase}>
                                    <i className="fas fa-search" style={{ color: '#ea580c', fontSize: 9 }} /> Puesto
                                </label>
                                <input type="text" name="search" value={search}
                                    onChange={e => setSearch(e.target.value)}
                                    placeholder="Mesero, cocinero..."
                                    style={inputBase} />
                            </div>

                            {/* Departamento */}
                            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
                                <label style={labelBase}>
                                    <i className="fas fa-map-marker-alt" style={{ color: '#ea580c', fontSize: 9 }} /> Destino
                                </label>
                                <select name="departamento" value={depto}
                                    onChange={e => setDepto(e.target.value)}
                                    style={inputBase}>
                                    <option value="">Todos los destinos</option>
                                    {departamentos.map(d => (
                                        <option key={d.id} value={String(d.id)}>{d.nombre}</option>
                                    ))}
                                </select>
                            </div>

                            {/* Botón buscar */}
                            <button type="submit" style={{
                                background: '#ea580c', color: '#fff', border: 'none', borderRadius: 12,
                                padding: '11px 0', fontSize: 13, fontWeight: 700, cursor: 'pointer',
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                gap: 8, fontFamily: "'Instrument Sans', sans-serif", marginTop: 2,
                            }}>
                                <i className="fas fa-search" style={{ fontSize: 11 }} /> Buscar empleos
                            </button>

                            <button type="button" onClick={cerrar} style={{
                                background: 'transparent', border: '1.5px solid #e7e5e4',
                                borderRadius: 12, padding: '9px 0', fontSize: 12, fontWeight: 600,
                                color: '#78716c', cursor: 'pointer',
                                fontFamily: "'Instrument Sans', sans-serif",
                            }}>
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>
            )}
        </>
    );
}