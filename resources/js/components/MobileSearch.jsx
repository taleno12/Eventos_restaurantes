import React, { useState, useEffect, useRef } from 'react';
import FiltroCascada from './FiltroCascada';

/**
 * MobileSearch
 * Botón lupa + panel desplegable animado — solo visible en móvil.
 *
 * Props (todas pasan directo a FiltroCascada):
 *   departamentos      — [{ id, nombre }]
 *   municipios         — [{ id, nombre, departamento_id }]
 *   deptoSeleccionado  — id activo
 *   munSeleccionado    — id activo
 *   especialidadInicial
 *   searchInicial
 *   actionUrl
 */
export default function MobileSearch({
    departamentos      = [],
    municipios         = [],
    deptoSeleccionado  = '',
    munSeleccionado    = '',
    especialidadInicial = '',
    searchInicial       = '',
    actionUrl           = '/restaurantes',
}) {
    const [abierto,   setAbierto]   = useState(false);
    const [animating, setAnimating] = useState(false); // controla clase CSS de entrada
    const panelRef   = useRef(null);
    const btnRef     = useRef(null);

    /* ── cerrar al hacer click fuera ── */
    useEffect(() => {
        function handleOutside(e) {
            if (
                panelRef.current && !panelRef.current.contains(e.target) &&
                btnRef.current   && !btnRef.current.contains(e.target)
            ) {
                cerrar();
            }
        }
        if (abierto) {
            document.addEventListener('mousedown', handleOutside);
        }
        return () => document.removeEventListener('mousedown', handleOutside);
    }, [abierto]);

    /* ── bloquear scroll del body cuando el panel está abierto (móvil) ── */
    useEffect(() => {
        document.body.style.overflow = abierto ? 'hidden' : '';
        return () => { document.body.style.overflow = ''; };
    }, [abierto]);

    function abrir() {
        setAbierto(true);
        // pequeño delay para que el DOM pinte el panel antes de animar
        requestAnimationFrame(() => {
            requestAnimationFrame(() => setAnimating(true));
        });
    }

    function cerrar() {
        setAnimating(false);
        // esperar que termine la transición antes de desmontar
        setTimeout(() => setAbierto(false), 280);
    }

    function toggle(e) {
        e.stopPropagation();
        abierto ? cerrar() : abrir();
    }

    /* ── keyframe en <style> inline (no requiere CSS externo) ── */
    const keyframes = `
        @keyframes msSlideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes msSlideUp {
            from { opacity: 1; transform: translateY(0); }
            to   { opacity: 0; transform: translateY(-10px); }
        }
    `;

    const panelStyle = {
        position: 'fixed',
        top: 80,           /* altura de la navbar */
        left: 0,
        right: 0,
        bottom: 0,
        zIndex: 45,
        display: 'flex',
        flexDirection: 'column',
        pointerEvents: abierto ? 'all' : 'none',
    };

    const overlayStyle = {
        position: 'absolute',
        inset: 0,
        background: 'rgba(0,0,0,0.35)',
        backdropFilter: 'blur(2px)',
        animation: `${animating ? 'msSlideDown' : 'msSlideUp'} 280ms ease forwards`,
        opacity: 0,
    };

    const sheetStyle = {
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        background: 'rgba(255,255,255,0.99)',
        backdropFilter: 'blur(16px)',
        borderBottom: '1px solid #e7e5e4',
        padding: '1.25rem 1.25rem 1.5rem',
        boxShadow: '0 12px 32px rgba(0,0,0,0.10)',
        animation: `${animating ? 'msSlideDown' : 'msSlideUp'} 280ms cubic-bezier(0.16,1,0.3,1) forwards`,
        opacity: 0,
        overflowY: 'auto',
        maxHeight: 'calc(100vh - 80px)',
    };

    return (
        <>
            <style>{keyframes}</style>

            {/* ── Botón lupa ── */}
            <button
                ref={btnRef}
                onClick={toggle}
                aria-label="Buscar restaurantes"
                aria-expanded={abierto}
                style={{
                    width: 36,
                    height: 36,
                    borderRadius: '50%',
                    border: 'none',
                    cursor: 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    background: abierto ? '#fff7ed' : '#f5f5f4',
                    color: abierto ? '#ea580c' : '#57534e',
                    transition: 'background .2s, color .2s, transform .2s',
                    transform: abierto ? 'scale(1.08)' : 'scale(1)',
                    /* solo visible en md hacia abajo — la navbar maneja esto con CSS,
                       pero como componente React no tiene acceso a Tailwind en este file;
                       el parent (Navbar) lo envuelve en un div.md:hidden */
                }}
            >
                <i
                    className={abierto ? 'fas fa-times' : 'fas fa-search'}
                    style={{ fontSize: 13, transition: 'all .2s' }}
                />
            </button>

            {/* ── Panel desplegable ── */}
            {abierto && (
                <div style={panelStyle} aria-modal="true" role="dialog">
                    {/* Overlay oscuro */}
                    <div style={overlayStyle} onClick={cerrar} />

                    {/* Sheet con el formulario */}
                    <div ref={panelRef} style={sheetStyle}>
                        {/* Encabezado del panel */}
                        <div style={{
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'space-between',
                            marginBottom: 14,
                        }}>
                            <span style={{
                                fontSize: 11,
                                fontWeight: 900,
                                letterSpacing: '0.14em',
                                textTransform: 'uppercase',
                                color: '#a8a29e',
                                fontFamily: "'Instrument Sans', sans-serif",
                            }}>
                                <i className="fas fa-sliders-h" style={{ color: '#ea580c', marginRight: 6 }} />
                                Filtrar restaurantes
                            </span>
                            <button
                                onClick={cerrar}
                                style={{
                                    background: '#f5f5f4',
                                    border: 'none',
                                    borderRadius: '50%',
                                    width: 28,
                                    height: 28,
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    cursor: 'pointer',
                                    color: '#78716c',
                                    fontSize: 12,
                                }}
                            >
                                <i className="fas fa-times" />
                            </button>
                        </div>

                        <FiltroCascada
                            departamentos={departamentos}
                            municipios={municipios}
                            deptoSeleccionado={deptoSeleccionado}
                            munSeleccionado={munSeleccionado}
                            especialidadInicial={especialidadInicial}
                            searchInicial={searchInicial}
                            actionUrl={actionUrl}
                            modo="mobile"
                            onClose={cerrar}
                        />
                    </div>
                </div>
            )}
        </>
    );
}