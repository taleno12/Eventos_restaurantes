// resources/js/components/NavbarRestaurantes.jsx
import { useState, useEffect, useRef } from "react";

/**
 * Props inyectadas desde Blade via window.__RESTAURANTES_NAV__
 * window.__RESTAURANTES_NAV__ = {
 *   departamentos: [{id, nombre}],
 *   municipios:    [{id, nombre, departamento_id}],
 *   filters: { departamento, municipio, especialidad, search },
 *   routes: { home, restaurantes, empleos, contacto, dashboard, logout },
 *   auth: { isAdmin: bool, loggedIn: bool },
 *   counts: { restaurantes: N, empleos: N }
 * }
 */

const data = window.__RESTAURANTES_NAV__ || {};
const departamentos = data.departamentos || [];
const municipios    = data.municipios    || [];
const filters       = data.filters       || {};
const routes        = data.routes        || {};
const auth          = data.auth          || {};
const counts        = data.counts        || {};

export default function NavbarRestaurantes() {
  const [mobileOpen, setMobileOpen]           = useState(false);
  const [activeStep, setActiveStep]           = useState(null); // 'destino'|'municipio'|'especialidad'|'nombre'
  const [deskDepto, setDeskDepto]             = useState(filters.departamento || "");
  const [deskMunicipio, setDeskMunicipio]     = useState(filters.municipio    || "");
  const [deskEspecialidad, setDeskEspecialidad] = useState(filters.especialidad || "");
  const [deskSearch, setDeskSearch]           = useState(filters.search        || "");
  const [mobDepto, setMobDepto]               = useState(filters.departamento || "");
  const [mobMunicipio, setMobMunicipio]       = useState(filters.municipio    || "");
  const [mobEspecialidad, setMobEspecialidad] = useState(filters.especialidad || "");
  const [mobSearch, setMobSearch]             = useState(filters.search        || "");

  const panelRef = useRef(null);
  const toggleRef = useRef(null);

  // Municipios filtrados
  const munsFiltradosDesk = municipios.filter(m => m.departamento_id == deskDepto);
  const munsFiltradosMob  = municipios.filter(m => m.departamento_id == mobDepto);

  // Cerrar panel al click externo
  useEffect(() => {
    const handler = (e) => {
      if (
        panelRef.current && !panelRef.current.contains(e.target) &&
        toggleRef.current && !toggleRef.current.contains(e.target)
      ) {
        setMobileOpen(false);
        setActiveStep(null);
      }
    };
    document.addEventListener("mousedown", handler);
    return () => document.removeEventListener("mousedown", handler);
  }, []);

  // Reset municipio cuando cambia depto
  useEffect(() => { setDeskMunicipio(""); }, [deskDepto]);
  useEffect(() => { setMobMunicipio(""); }, [mobDepto]);

  const buildQuery = (params) => {
    const q = new URLSearchParams();
    Object.entries(params).forEach(([k, v]) => { if (v) q.set(k, v); });
    return q.toString() ? `?${q.toString()}` : "";
  };

  const deskSubmit = (e) => {
    e.preventDefault();
    const qs = buildQuery({ departamento: deskDepto, municipio: deskMunicipio, especialidad: deskEspecialidad, search: deskSearch });
    window.location.href = (routes.restaurantes || "/restaurantes") + qs;
  };

  const mobSubmit = (e) => {
    e.preventDefault();
    const qs = buildQuery({ departamento: mobDepto, municipio: mobMunicipio, especialidad: mobEspecialidad, search: mobSearch });
    window.location.href = (routes.restaurantes || "/restaurantes") + qs;
    setMobileOpen(false);
  };

  const hasFilters = filters.departamento || filters.municipio || filters.especialidad || filters.search;

  // ── Etiqueta del destino seleccionado (desktop)
  const deptoLabel = (id) => departamentos.find(d => d.id == id)?.nombre || "";
  const munLabel   = (id) => municipios.find(m => m.id == id)?.nombre || "";

  return (
    <nav className="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex h-20 items-center gap-3">

          {/* ── Logo ── */}
          <a href={routes.home || "/"} className="flex items-center gap-2.5 shrink-0 no-underline">
            <div className="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
              <i className="fas fa-utensils text-white text-sm" />
            </div>
            <span className="text-xl font-bold tracking-tight italic text-stone-900 hidden lg:block"
                  style={{ fontFamily: "'Playfair Display', serif" }}>
              Gastro<span className="text-orange-600">Nicaragua</span>
            </span>
          </a>

          {/* ── Search Bar Desktop ── */}
          <form onSubmit={deskSubmit}
                className="hidden md:flex flex-1 min-w-0"
                style={{
                  background: "#f8f7f6", border: "1.5px solid #e7e5e4",
                  borderRadius: 18, overflow: "hidden",
                  transition: "border-color .2s, box-shadow .2s",
                }}>

            {/* Destino */}
            <SearchSegment label="Destino" icon="fa-map-marker-alt"
                           style={{ minWidth: 120 }}>
              <select value={deskDepto} onChange={e => setDeskDepto(e.target.value)}
                      style={segSelectStyle}>
                <option value="">Todos</option>
                {departamentos.map(d => (
                  <option key={d.id} value={d.id}>{d.nombre}</option>
                ))}
              </select>
            </SearchSegment>

            {/* Municipio */}
            <SearchSegment label="Municipio" icon="fa-city"
                           style={{ minWidth: 120, borderLeft: "1px solid #e7e5e4" }}>
              <select value={deskMunicipio} onChange={e => setDeskMunicipio(e.target.value)}
                      disabled={!deskDepto}
                      style={{ ...segSelectStyle, opacity: deskDepto ? 1 : 0.45 }}>
                <option value="">{deskDepto ? "Todos" : "Elige destino..."}</option>
                {munsFiltradosDesk.map(m => (
                  <option key={m.id} value={m.id}>{m.nombre}</option>
                ))}
              </select>
            </SearchSegment>

            {/* Especialidad */}
            <SearchSegment label="Especialidad" icon="fa-utensils"
                           style={{ minWidth: 110, borderLeft: "1px solid #e7e5e4" }}>
              <input value={deskEspecialidad} onChange={e => setDeskEspecialidad(e.target.value)}
                     placeholder="Asados..." style={segInputStyle} />
            </SearchSegment>

            {/* Nombre */}
            <SearchSegment label="Nombre" icon="fa-store"
                           style={{ minWidth: 110, borderLeft: "1px solid #e7e5e4" }}>
              <input value={deskSearch} onChange={e => setDeskSearch(e.target.value)}
                     placeholder="Restaurante..." style={segInputStyle} />
            </SearchSegment>

            <button type="submit" style={searchBtnStyle}
                    onMouseEnter={e => e.currentTarget.style.background = "#c2410c"}
                    onMouseLeave={e => e.currentTarget.style.background = "#ea580c"}>
              <i className="fas fa-search" style={{ fontSize: 11 }} />
              <span>Buscar</span>
            </button>
          </form>

          {/* ── Acciones ── */}
          <div className="flex items-center gap-2 shrink-0">

            {/* Toggle búsqueda mobile */}
            <button ref={toggleRef}
                    onClick={() => { setMobileOpen(o => !o); setActiveStep(null); }}
                    className="md:hidden w-9 h-9 rounded-full flex items-center justify-center transition-colors border-0 cursor-pointer"
                    style={{
                      background: mobileOpen ? "#ea580c" : "#f5f5f4",
                      color: mobileOpen ? "#fff" : "#57534e",
                    }}>
              <i className={`fas ${mobileOpen ? "fa-times" : "fa-search"} text-sm`} />
            </button>

            {/* Inicio */}
            <NavBtn href={routes.home || "/"} icon="fa-home" label="Inicio"
                    variant="ghost" className="hidden sm:flex" />

            {/* Restaurantes (activo) */}
            <NavBtn href={routes.restaurantes || "/restaurantes"} icon="fa-store" label="Restaurantes"
                    variant="active" className="hidden sm:flex" />

            {/* Empleos */}
            <NavBtn href={routes.empleos || "/empleos"} icon="fa-briefcase" label="Empleos"
                    count={counts.empleos} variant="outline" className="hidden sm:flex" />

            {/* Contacto */}
            <a href={routes.contacto || "/contacto"}
               className="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors px-2 no-underline hidden lg:inline">
              Contacto
            </a>

            {auth.isAdmin && (
              <a href={routes.dashboard || "/dashboard"}
                 className="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors no-underline px-2 hidden lg:inline">
                Panel
              </a>
            )}

            {auth.loggedIn ? (
              <form method="POST" action={routes.logout || "/logout"} className="inline m-0">
                <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.content || ""} />
                <button type="submit"
                        className="text-sm font-semibold text-stone-500 hover:text-red-500 transition-colors bg-transparent border-0 cursor-pointer px-2">
                  Salir
                </button>
              </form>
            ) : (
              <a href={routes.login || "/login"}
                 className="text-sm font-semibold text-stone-600 hover:text-orange-600 transition-colors no-underline px-2">
                Ingresar
              </a>
            )}
          </div>
        </div>
      </div>

      {/* ══ PANEL MÓVIL ══ */}
      <MobileSearchPanel
        ref={panelRef}
        open={mobileOpen}
        activeStep={activeStep}
        setActiveStep={setActiveStep}
        departamentos={departamentos}
        municipios={municipios}
        munsFiltrados={munsFiltradosMob}
        depto={mobDepto} setDepto={setMobDepto}
        municipio={mobMunicipio} setMunicipio={setMobMunicipio}
        especialidad={mobEspecialidad} setEspecialidad={setMobEspecialidad}
        search={mobSearch} setSearch={setMobSearch}
        onSubmit={mobSubmit}
        deptoLabel={deptoLabel}
        munLabel={munLabel}
        hasFilters={hasFilters}
        filters={filters}
      />
    </nav>
  );
}

/* ══════════════════════════════════════
   PANEL MÓVIL — diseño step-by-step
   ══════════════════════════════════════ */
import { forwardRef } from "react";

const MobileSearchPanel = forwardRef(function MobileSearchPanel({
  open, activeStep, setActiveStep,
  departamentos, municipios, munsFiltrados,
  depto, setDepto, municipio, setMunicipio,
  especialidad, setEspecialidad,
  search, setSearch,
  onSubmit, deptoLabel, munLabel,
  hasFilters, filters,
}, ref) {

  if (!open) return null;

  const filledCount = [depto, municipio, especialidad, search].filter(Boolean).length;

  return (
    <div ref={ref} style={{
      position: "absolute", top: "100%", left: 0, right: 0,
      background: "rgba(255,255,255,0.98)", backdropFilter: "blur(16px)",
      borderTop: "1px solid #e7e5e4",
      boxShadow: "0 20px 60px rgba(28,25,23,0.12)",
      zIndex: 40, overflow: "hidden",
    }}>

      {/* Header del panel */}
      <div style={{
        padding: "14px 20px 10px",
        background: "linear-gradient(135deg, #fff7ed 0%, #fff 60%)",
        borderBottom: "1px solid #f1ede8",
        display: "flex", alignItems: "center", justifyContent: "space-between",
      }}>
        <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
          <div style={{
            width: 32, height: 32, background: "#ea580c", borderRadius: 10,
            display: "flex", alignItems: "center", justifyContent: "center",
          }}>
            <i className="fas fa-sliders-h" style={{ color: "#fff", fontSize: 12 }} />
          </div>
          <div>
            <p style={{ margin: 0, fontSize: 13, fontWeight: 800, color: "#1c1917" }}>
              Filtrar Restaurantes
            </p>
            <p style={{ margin: 0, fontSize: 10, color: "#a8a29e", letterSpacing: "0.1em", textTransform: "uppercase", fontWeight: 600 }}>
              {filledCount > 0 ? `${filledCount} filtro${filledCount > 1 ? "s" : ""} activo${filledCount > 1 ? "s" : ""}` : "Sin filtros aplicados"}
            </p>
          </div>
        </div>
        {hasFilters && (
          <a href="/restaurantes" style={{
            fontSize: 10, fontWeight: 700, color: "#ea580c", textDecoration: "none",
            letterSpacing: "0.1em", textTransform: "uppercase",
            display: "flex", alignItems: "center", gap: 4,
          }}>
            <i className="fas fa-times-circle" style={{ fontSize: 11 }} /> Limpiar
          </a>
        )}
      </div>

      {/* Chips de pasos */}
      <div style={{
        display: "flex", gap: 8, padding: "12px 20px",
        overflowX: "auto", scrollbarWidth: "none",
      }}>
        {[
          { key: "destino",      icon: "fa-map-marker-alt", label: depto ? deptoLabel(depto) : "Destino",       filled: !!depto },
          { key: "municipio",    icon: "fa-city",           label: municipio ? munLabel(municipio) : "Municipio", filled: !!municipio, disabled: !depto },
          { key: "especialidad", icon: "fa-utensils",       label: especialidad || "Especialidad",               filled: !!especialidad },
          { key: "nombre",       icon: "fa-store",          label: search || "Nombre",                           filled: !!search },
        ].map(({ key, icon, label, filled, disabled }) => (
          <button key={key}
                  disabled={disabled}
                  onClick={() => setActiveStep(activeStep === key ? null : key)}
                  style={{
                    display: "flex", alignItems: "center", gap: 6,
                    padding: "7px 14px", borderRadius: 999, border: "1.5px solid",
                    whiteSpace: "nowrap", cursor: disabled ? "not-allowed" : "pointer",
                    fontSize: 12, fontWeight: 700, flexShrink: 0,
                    transition: "all .2s",
                    borderColor: activeStep === key ? "#ea580c" : filled ? "#fed7aa" : "#e7e5e4",
                    background: activeStep === key ? "#ea580c" : filled ? "#fff7ed" : "#fafaf9",
                    color: activeStep === key ? "#fff" : filled ? "#c2410c" : disabled ? "#c4bfbb" : "#57534e",
                    opacity: disabled ? 0.5 : 1,
                  }}>
            <i className={`fas ${icon}`} style={{ fontSize: 10 }} />
            <span style={{ maxWidth: 90, overflow: "hidden", textOverflow: "ellipsis" }}>{label}</span>
            {filled && activeStep !== key && (
              <span style={{
                width: 6, height: 6, background: "#ea580c", borderRadius: "50%", flexShrink: 0,
              }} />
            )}
          </button>
        ))}
      </div>

      {/* Paneles de cada paso */}
      <form onSubmit={onSubmit}>
        <div style={{ padding: "0 20px 16px" }}>

          {/* Paso: Destino */}
          {activeStep === "destino" && (
            <StepPanel title="¿A qué destino vas?" icon="fa-map-marker-alt">
              <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 8 }}>
                <OptionChip
                  label="Todos los destinos" value=""
                  selected={depto === ""}
                  onClick={() => { setDepto(""); setActiveStep("municipio"); }}
                />
                {departamentos.map(d => (
                  <OptionChip key={d.id} label={d.nombre} value={d.id}
                              selected={depto == d.id}
                              onClick={() => { setDepto(d.id); setActiveStep("municipio"); }} />
                ))}
              </div>
            </StepPanel>
          )}

          {/* Paso: Municipio */}
          {activeStep === "municipio" && (
            <StepPanel title="¿Qué municipio?" icon="fa-city"
                       subtitle={depto ? `Departamento: ${deptoLabel(depto)}` : ""}>
              {!depto ? (
                <p style={{ fontSize: 13, color: "#a8a29e", textAlign: "center", padding: "12px 0" }}>
                  Primero elige un destino
                </p>
              ) : (
                <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 8 }}>
                  <OptionChip label="Todos" value=""
                              selected={municipio === ""}
                              onClick={() => { setMunicipio(""); setActiveStep(null); }} />
                  {munsFiltrados.map(m => (
                    <OptionChip key={m.id} label={m.nombre} value={m.id}
                                selected={municipio == m.id}
                                onClick={() => { setMunicipio(m.id); setActiveStep(null); }} />
                  ))}
                </div>
              )}
            </StepPanel>
          )}

          {/* Paso: Especialidad */}
          {activeStep === "especialidad" && (
            <StepPanel title="¿Qué tipo de comida?" icon="fa-utensils">
              <input
                type="text" value={especialidad}
                onChange={e => setEspecialidad(e.target.value)}
                placeholder="Ej: Asados, mariscos, fritanga..."
                autoFocus
                style={mobileInputStyle}
              />
              <div style={{ display: "flex", flexWrap: "wrap", gap: 8, marginTop: 10 }}>
                {["Asados","Mariscos","Fritanga","Pizza","Buffet","Sushi","Típico"].map(tag => (
                  <button key={tag} type="button"
                          onClick={() => { setEspecialidad(tag); setActiveStep(null); }}
                          style={{
                            padding: "5px 14px", borderRadius: 999, fontSize: 12,
                            fontWeight: 600, cursor: "pointer", border: "1.5px solid #e7e5e4",
                            background: especialidad === tag ? "#ea580c" : "#fafaf9",
                            color: especialidad === tag ? "#fff" : "#57534e",
                          }}>
                    {tag}
                  </button>
                ))}
              </div>
            </StepPanel>
          )}

          {/* Paso: Nombre */}
          {activeStep === "nombre" && (
            <StepPanel title="Buscar por nombre" icon="fa-store">
              <input
                type="text" value={search}
                onChange={e => setSearch(e.target.value)}
                placeholder="Nombre del restaurante..."
                autoFocus
                style={mobileInputStyle}
              />
            </StepPanel>
          )}

          {/* Sin paso activo — resumen */}
          {!activeStep && (
            <div style={{ paddingTop: 4 }}>
              {filledCount > 0 ? (
                <div style={{
                  background: "#f8f7f6", borderRadius: 14, padding: "10px 14px",
                  display: "flex", flexWrap: "wrap", gap: 6, marginBottom: 12,
                }}>
                  {depto && <FilterTag label={deptoLabel(depto)} onRemove={() => setDepto("")} />}
                  {municipio && <FilterTag label={munLabel(municipio)} onRemove={() => setMunicipio("")} />}
                  {especialidad && <FilterTag label={especialidad} onRemove={() => setEspecialidad("")} />}
                  {search && <FilterTag label={`"${search}"`} onRemove={() => setSearch("")} />}
                </div>
              ) : (
                <p style={{ fontSize: 12, color: "#a8a29e", textAlign: "center", margin: "4px 0 12px" }}>
                  Toca un filtro de arriba para comenzar
                </p>
              )}
            </div>
          )}
        </div>

        {/* Botón de búsqueda */}
        <div style={{ padding: "0 20px 20px" }}>
          <button type="submit" style={{
            width: "100%", padding: "13px", borderRadius: 14,
            background: "linear-gradient(135deg, #ea580c 0%, #c2410c 100%)",
            color: "#fff", border: "none", cursor: "pointer",
            fontSize: 14, fontWeight: 800, letterSpacing: "0.04em",
            display: "flex", alignItems: "center", justifyContent: "center", gap: 8,
            boxShadow: "0 4px 14px rgba(234,88,12,0.35)",
          }}>
            <i className="fas fa-search" style={{ fontSize: 12 }} />
            Buscar Restaurantes
          </button>
        </div>
      </form>
    </div>
  );
});

/* ── Subcomponentes ── */

function SearchSegment({ label, icon, children, style }) {
  return (
    <div style={{
      display: "flex", flexDirection: "column", justifyContent: "center",
      padding: "7px 12px", minWidth: 0, flex: 1, ...style,
    }}>
      <label style={{
        fontSize: 9, fontWeight: 900, letterSpacing: "0.14em", textTransform: "uppercase",
        color: "#a8a29e", marginBottom: 2, display: "flex", alignItems: "center", gap: 4,
        cursor: "pointer",
      }}>
        <i className={`fas ${icon}`} style={{ color: "#ea580c", fontSize: 8 }} />
        {label}
      </label>
      {children}
    </div>
  );
}

function NavBtn({ href, icon, label, variant = "ghost", count, className = "" }) {
  const styles = {
    ghost:   { border: "1px solid #e5e7eb", color: "#57534e", background: "#fff",      hoverBg: "#1c1917", hoverColor: "#fff"    },
    active:  { border: "1px solid #ea580c", color: "#fff",    background: "#ea580c",   hoverBg: "#c2410c", hoverColor: "#fff"    },
    outline: { border: "1px solid #fed7aa", color: "#ea580c", background: "#fff7ed",   hoverBg: "#ea580c", hoverColor: "#fff"    },
  };
  const s = styles[variant];
  return (
    <a href={href}
       className={`items-center gap-1.5 px-3 py-2 rounded-full text-sm font-semibold shadow-sm no-underline ${className}`}
       style={{ border: s.border, color: s.color, background: s.background, display: "flex", transition: "all .2s" }}
       onMouseEnter={e => { e.currentTarget.style.background = s.hoverBg; e.currentTarget.style.color = s.hoverColor; }}
       onMouseLeave={e => { e.currentTarget.style.background = s.background; e.currentTarget.style.color = s.color; }}>
      <i className={`fas ${icon} text-xs`} />
      <span className="hidden lg:inline">{label}</span>
      {count > 0 && (
        <span style={{
          display: "flex", alignItems: "center", justifyContent: "center",
          width: 18, height: 18, borderRadius: "50%",
          background: variant === "active" ? "rgba(255,255,255,0.25)" : "#ea580c",
          color: "#fff", fontSize: 9, fontWeight: 800,
        }} className="hidden lg:flex">{count}</span>
      )}
    </a>
  );
}

function StepPanel({ title, icon, subtitle, children }) {
  return (
    <div style={{
      background: "#fafaf9", borderRadius: 16, padding: "14px 16px",
      border: "1.5px solid #f1ede8",
    }}>
      <div style={{ display: "flex", alignItems: "center", gap: 8, marginBottom: 12 }}>
        <div style={{
          width: 26, height: 26, background: "#fff7ed", borderRadius: 8,
          display: "flex", alignItems: "center", justifyContent: "center",
          border: "1px solid #fed7aa",
        }}>
          <i className={`fas ${icon}`} style={{ color: "#ea580c", fontSize: 10 }} />
        </div>
        <div>
          <p style={{ margin: 0, fontSize: 13, fontWeight: 700, color: "#1c1917" }}>{title}</p>
          {subtitle && <p style={{ margin: 0, fontSize: 10, color: "#a8a29e" }}>{subtitle}</p>}
        </div>
      </div>
      {children}
    </div>
  );
}

function OptionChip({ label, value, selected, onClick }) {
  return (
    <button type="button" onClick={onClick} style={{
      padding: "8px 12px", borderRadius: 10, border: "1.5px solid",
      fontSize: 12, fontWeight: 600, cursor: "pointer", textAlign: "left",
      transition: "all .15s",
      borderColor: selected ? "#ea580c" : "#e7e5e4",
      background: selected ? "#fff7ed" : "#fff",
      color: selected ? "#c2410c" : "#57534e",
      display: "flex", alignItems: "center", gap: 6,
    }}>
      {selected && <i className="fas fa-check" style={{ fontSize: 9, color: "#ea580c" }} />}
      {label}
    </button>
  );
}

function FilterTag({ label, onRemove }) {
  return (
    <span style={{
      display: "inline-flex", alignItems: "center", gap: 6,
      background: "#fff7ed", border: "1px solid #fed7aa",
      color: "#c2410c", fontSize: 11, fontWeight: 700,
      padding: "4px 10px", borderRadius: 999,
    }}>
      {label}
      <button type="button" onClick={onRemove} style={{
        background: "none", border: "none", cursor: "pointer",
        color: "#ea580c", fontSize: 12, padding: 0, lineHeight: 1,
        display: "flex", alignItems: "center",
      }}>×</button>
    </span>
  );
}

/* ── Estilos inline reutilizables ── */
const segSelectStyle = {
  background: "transparent", border: "none", outline: "none",
  fontSize: 12, fontWeight: 600, color: "#1c1917",
  fontFamily: "'Instrument Sans', sans-serif", width: "100%", padding: 0, cursor: "pointer",
};
const segInputStyle = {
  background: "transparent", border: "none", outline: "none",
  fontSize: 12, fontWeight: 600, color: "#1c1917",
  fontFamily: "'Instrument Sans', sans-serif", width: "100%", padding: 0,
};
const searchBtnStyle = {
  display: "flex", alignItems: "center", gap: 6,
  background: "#ea580c", color: "white", border: "none",
  padding: "0 18px", fontSize: 13, fontWeight: 700,
  cursor: "pointer", transition: "background .2s",
  whiteSpace: "nowrap", borderRadius: "0 16px 16px 0", flexShrink: 0,
};
const mobileInputStyle = {
  width: "100%", padding: "10px 14px",
  background: "#fff", border: "1.5px solid #e7e5e4",
  borderRadius: 10, fontSize: 13, fontWeight: 600, color: "#1c1917",
  fontFamily: "'Instrument Sans', sans-serif", outline: "none",
  boxSizing: "border-box",
};