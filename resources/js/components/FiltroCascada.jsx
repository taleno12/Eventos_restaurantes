import React, { useState, useEffect } from 'react';

/**
 * FiltroCascada
 * Formulario de búsqueda con cascada departamento → municipio.
 * Props:
 *   departamentos      — [{ id, nombre }]
 *   municipios         — [{ id, nombre, departamento_id }]
 *   deptoSeleccionado  — id del departamento activo (string|number|"")
 *   munSeleccionado    — id del municipio activo (string|number|"")
 *   especialidadInicial — string
 *   searchInicial      — string
 *   actionUrl          — URL del form (default "/restaurantes")
 *   modo               — "desktop" | "mobile"
 *   onClose            — callback para cerrar el panel mobile (opcional)
 */
export default function FiltroCascada({
    departamentos = [],
    municipios    = [],
    deptoSeleccionado   = '',
    munSeleccionado     = '',
    especialidadInicial = '',
    searchInicial       = '',
    actionUrl           = '/restaurantes',
    modo    = 'desktop',
    onClose = null,
}) {
    const [depto,       setDepto]       = useState(String(deptoSeleccionado));
    const [municipio,   setMunicipio]   = useState(String(munSeleccionado));
    const [especialidad,setEspecialidad]= useState(especialidadInicial);
    const [search,      setSearch]      = useState(searchInicial);

    /* municipios filtrados según el depto elegido */
    const munFiltrados = municipios.filter(
        m => !depto || String(m.departamento_id) === depto
    );

    /* si cambia el depto, resetear municipio si ya no aplica */
    useEffect(() => {
        if (municipio && !munFiltrados.find(m => String(m.id) === municipio)) {
            setMunicipio('');
        }
    }, [depto]);

    /* ── estilos compartidos ── */
    const inputBase = {
        background: '#f8f7f6',
        border: '1.5px solid #e7e5e4',
        borderRadius: 12,
        padding: '10px 14px',
        fontSize: 13,
        color: '#1c1917',
        fontFamily: "'Instrument Sans', sans-serif",
        fontWeight: 600,
        width: '100%',
        outline: 'none',
        appearance: 'none',
        transition: 'border-color .2s, box-shadow .2s',
    };

    const labelBase = {
        fontSize: 10,
        fontWeight: 900,
        letterSpacing: '0.14em',
        textTransform: 'uppercase',
        color: '#a8a29e',
        marginBottom: 4,
        display: 'flex',
        alignItems: 'center',
        gap: 5,
    };

    /* ── focus handler via className trick ── */
    const focusStyle = {
        borderColor: '#ea580c',
        boxShadow: '0 0 0 3px rgba(234,88,12,0.12)',
        background: '#fff',
    };

    function FocusInput({ style, ...props }) {
        const [focused, setFocused] = useState(false);
        return (
            <input
                {...props}
                style={{ ...inputBase, ...style, ...(focused ? focusStyle : {}) }}
                onFocus={() => setFocused(true)}
                onBlur={() => setFocused(false)}
            />
        );
    }

    function FocusSelect({ style, disabled, children, ...props }) {
        const [focused, setFocused] = useState(false);
        return (
            <select
                {...props}
                disabled={disabled}
                style={{
                    ...inputBase,
                    ...style,
                    ...(focused && !disabled ? focusStyle : {}),
                    opacity: disabled ? 0.45 : 1,
                    cursor: disabled ? 'not-allowed' : 'pointer',
                }}
                onFocus={() => setFocused(true)}
                onBlur={() => setFocused(false)}
            >
                {children}
            </select>
        );
    }

    /* ── render ── */
    return (
        <form
            action={actionUrl}
            method="GET"
            style={{ display: 'flex', flexDirection: 'column', gap: 12 }}
        >
            {/* Destino */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
                <label style={labelBase}>
                    <i className="fas fa-map-marker-alt" style={{ color: '#ea580c', fontSize: 9 }} />
                    Destino
                </label>
                <FocusSelect
                    name="departamento"
                    value={depto}
                    onChange={e => { setDepto(e.target.value); setMunicipio(''); }}
                >
                    <option value="">Todos los destinos</option>
                    {departamentos.map(d => (
                        <option key={d.id} value={String(d.id)}>{d.nombre}</option>
                    ))}
                </FocusSelect>
            </div>

            {/* Municipio */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
                <label style={labelBase}>
                    <i className="fas fa-city" style={{ color: '#ea580c', fontSize: 9 }} />
                    Municipio
                </label>
                <FocusSelect
                    name="municipio"
                    value={municipio}
                    disabled={!depto}
                    onChange={e => setMunicipio(e.target.value)}
                >
                    <option value="">{depto ? 'Todos los municipios' : 'Primero elige destino…'}</option>
                    {munFiltrados.map(m => (
                        <option key={m.id} value={String(m.id)}>{m.nombre}</option>
                    ))}
                </FocusSelect>
            </div>

            {/* Especialidad */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
                <label style={labelBase}>
                    <i className="fas fa-utensils" style={{ color: '#ea580c', fontSize: 9 }} />
                    Especialidad
                </label>
                <FocusInput
                    type="text"
                    name="especialidad"
                    value={especialidad}
                    onChange={e => setEspecialidad(e.target.value)}
                    placeholder="Asados, mariscos…"
                />
            </div>

            {/* Nombre */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
                <label style={labelBase}>
                    <i className="fas fa-store" style={{ color: '#ea580c', fontSize: 9 }} />
                    Nombre
                </label>
                <FocusInput
                    type="text"
                    name="search"
                    value={search}
                    onChange={e => setSearch(e.target.value)}
                    placeholder="Buscar restaurante…"
                />
            </div>

            {/* Botón */}
            <button
                type="submit"
                style={{
                    background: '#ea580c',
                    color: '#fff',
                    border: 'none',
                    borderRadius: 12,
                    padding: '11px 0',
                    fontSize: 13,
                    fontWeight: 700,
                    cursor: 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: 8,
                    fontFamily: "'Instrument Sans', sans-serif",
                    transition: 'background .2s',
                    marginTop: 2,
                }}
                onMouseEnter={e => e.currentTarget.style.background = '#c2410c'}
                onMouseLeave={e => e.currentTarget.style.background = '#ea580c'}
            >
                <i className="fas fa-search" style={{ fontSize: 11 }} />
                Filtrar Restaurantes
            </button>

            {/* Botón cerrar panel (solo mobile, si se pasa onClose) */}
            {modo === 'mobile' && onClose && (
                <button
                    type="button"
                    onClick={onClose}
                    style={{
                        background: 'transparent',
                        border: '1.5px solid #e7e5e4',
                        borderRadius: 12,
                        padding: '9px 0',
                        fontSize: 12,
                        fontWeight: 600,
                        color: '#78716c',
                        cursor: 'pointer',
                        fontFamily: "'Instrument Sans', sans-serif",
                    }}
                >
                    Cancelar
                </button>
            )}
        </form>
    );
}