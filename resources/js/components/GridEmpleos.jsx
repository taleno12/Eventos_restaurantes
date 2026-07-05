// resources/js/components/GridEmpleos.jsx
/**
 * Requiere en Blade (antes de @vite):
 * <script>
 *   window.__GRID_EMPLEOS__ = {
 *     items: @json($empleos->items()),
 *     pagination: {
 *       current_page: {{ $empleos->currentPage() }},
 *       last_page:    {{ $empleos->lastPage() }},
 *       total:        {{ $empleos->total() }},
 *       per_page:     {{ $empleos->perPage() }},
 *     },
 *     filters: {
 *       search:       "{{ request('search') }}",
 *       departamento: "{{ request('departamento') }}",
 *     },
 *     departamentos: @json($departamentos),
 *     routes: {
 *       empleos:   "{{ route('empleos.index') }}",
 *       show_base: "{{ url('/empleos') }}",
 *     }
 *   };
 * </script>
 */

import { useState, useRef } from "react";

const cfg       = window.__GRID_EMPLEOS__ || {};
const initItems = cfg.items        || [];
const initPag   = cfg.pagination   || { current_page: 1, last_page: 1, total: 0 };
const initFilter= cfg.filters      || {};
const deptos    = cfg.departamentos || [];
const routes    = cfg.routes       || {};

export default function GridEmpleos() {
  const [items, setItems]           = useState(initItems);
  const [pagination, setPagination] = useState(initPag);
  const [filters, setFilters]       = useState(initFilter);
  const [loading, setLoading]       = useState(false);
  const topRef = useRef(null);

  const hasFilters = filters.search || filters.departamento;

  const fetchPage = async (newFilters, page = 1) => {
    setLoading(true);
    const params = new URLSearchParams();
    Object.entries(newFilters).forEach(([k, v]) => { if (v) params.set(k, v); });
    if (page > 1) params.set("page", page);
    params.set("json", "1");

    try {
      const res = await fetch(`${routes.empleos || "/empleos"}?${params.toString()}`, {
        headers: { "X-Requested-With": "XMLHttpRequest", Accept: "application/json" },
      });
      if (!res.ok) throw new Error("fetch failed");
      const data = await res.json();
      setItems(data.items);
      setPagination(data.pagination);
      window.history.pushState({}, "", `?${params.toString()}`);
      topRef.current?.scrollIntoView({ behavior: "smooth", block: "start" });
    } catch {
      window.location.href = `${routes.empleos}?${params.toString()}`;
    } finally {
      setLoading(false);
    }
  };

  const updateFilter = (key, value) => {
    const next = { ...filters, [key]: value };
    setFilters(next);
    fetchPage(next, 1);
  };

  const clearFilters = () => {
    setFilters({});
    fetchPage({}, 1);
  };

  const deptoLabel = (id) => deptos.find((d) => d.id == id)?.nombre || id;

  return (
    <div ref={topRef}>
      {/* Barra de filtros activos */}
      {hasFilters && (
        <div style={{
          marginBottom: 32,
          display: "flex", flexWrap: "wrap", alignItems: "center", gap: 8,
          padding: "14px 20px",
          background: "white", border: "1px solid #e7e5e4", borderRadius: 16,
        }}>
          <span style={{ fontSize: 11, fontWeight: 800, letterSpacing: "0.12em",
            textTransform: "uppercase", color: "#a8a29e" }}>Filtros:</span>

          {filters.search && (
            <FilterPill icon="fa-search" label={`"${filters.search}"`}
              onRemove={() => updateFilter("search", "")} />
          )}
          {filters.departamento && (
            <FilterPill icon="fa-map-marker-alt" label={deptoLabel(filters.departamento)}
              onRemove={() => updateFilter("departamento", "")} />
          )}

          <button onClick={clearFilters} style={{
            marginLeft: "auto", background: "none", border: "none", cursor: "pointer",
            fontSize: 12, color: "#ea580c", fontWeight: 700,
            display: "flex", alignItems: "center", gap: 4,
          }}>
            <i className="fas fa-times-circle" style={{ fontSize: 10 }} /> Limpiar todo
          </button>
        </div>
      )}

      {/* Header de sección */}
      <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between", marginBottom: 28 }}>
        <div>
          <p style={{ fontSize: 10, fontWeight: 800, letterSpacing: "0.2em",
            textTransform: "uppercase", color: "#ea580c", margin: "0 0 4px" }}>
            <i className="fas fa-fire-alt" style={{ marginRight: 4 }} /> Vacantes disponibles
          </p>
          <h2 style={{ fontFamily: "'Playfair Display', serif", fontSize: 24,
            fontWeight: 800, color: "#1c1917", margin: 0 }}>Ofertas de empleo</h2>
        </div>
        {pagination.total > 0 && (
          <span style={{
            background: "#fff7ed", border: "1px solid #fed7aa", color: "#c2410c",
            fontSize: 11, fontWeight: 700, padding: "6px 14px", borderRadius: 999,
          }}>
            {pagination.total} {pagination.total === 1 ? "oferta" : "ofertas"}
          </span>
        )}
      </div>

      {/* Grid / Loading / Empty */}
      {loading ? (
        <LoadingSkeleton count={6} />
      ) : items.length === 0 ? (
        <EmptyState hasFilters={hasFilters} onClear={clearFilters} />
      ) : (
        <div style={{
          display: "grid",
          gridTemplateColumns: "repeat(auto-fill, minmax(340px, 1fr))",
          gap: 20,
        }}>
          {items.map((empleo) => (
            <JobCard key={empleo.id} empleo={empleo} />
          ))}
        </div>
      )}

      {/* Paginación */}
      {!loading && pagination.last_page > 1 && (
        <Pagination pagination={pagination} onPage={(p) => fetchPage(filters, p)} />
      )}
    </div>
  );
}

/* ══ JOB CARD ══ */
function JobCard({ empleo }) {
  const [hov, setHov] = useState(false);

  const salarioBadge = empleo.salario
    ? { bg: "#f0fdf4", color: "#15803d", border: "1px solid #bbf7d0",
        icon: "fa-wallet", text: `C$ ${Number(empleo.salario).toLocaleString()}` }
    : { bg: "#f5f5f4", color: "#78716c", border: "1px solid #e7e5e4",
        icon: "fa-handshake", text: "En entrevista" };

  return (
    <article
      onMouseEnter={() => setHov(true)}
      onMouseLeave={() => setHov(false)}
      style={{
        background: "white",
        border: `1px solid ${hov ? "#fed7aa" : "#f1f0ee"}`,
        borderRadius: 24,
        padding: 28,
        display: "flex", flexDirection: "column", gap: 20,
        transition: "all 0.35s cubic-bezier(0.16,1,0.3,1)",
        transform: hov ? "translateY(-4px)" : "translateY(0)",
        boxShadow: hov ? "0 20px 48px rgba(28,25,23,0.09)" : "none",
        position: "relative", overflow: "hidden",
      }}
    >
      {/* Barra superior al hover */}
      <div style={{
        position: "absolute", top: 0, left: 0, right: 0, height: 3,
        background: "linear-gradient(90deg,#ea580c,#f59e0b)",
        transform: hov ? "scaleX(1)" : "scaleX(0)",
        transformOrigin: "left",
        transition: "transform 0.35s ease",
      }} />

      {/* Encabezado */}
      <div style={{ display: "flex", alignItems: "flex-start", gap: 14 }}>
        <div style={{
          width: 48, height: 48,
          background: hov ? "#fff7ed" : "#f5f5f4",
          border: `1px solid ${hov ? "#fed7aa" : "#e7e5e4"}`,
          borderRadius: 14,
          display: "flex", alignItems: "center", justifyContent: "center",
          flexShrink: 0, transition: "all 0.3s",
        }}>
          <i className="fas fa-store" style={{ color: hov ? "#ea580c" : "#a8a29e", fontSize: 18 }} />
        </div>
        <div style={{ flex: 1, minWidth: 0 }}>
          <span style={{
            fontSize: 10, fontWeight: 800, letterSpacing: "0.15em",
            textTransform: "uppercase", color: "#ea580c", display: "block", marginBottom: 5,
          }}>
            {empleo.restaurante?.nombre}
          </span>
          <h3 style={{
            fontFamily: "'Playfair Display', serif", fontSize: 18, fontWeight: 800,
            color: hov ? "#ea580c" : "#1c1917", lineHeight: 1.25,
            transition: "color 0.2s", margin: 0,
          }}>
            {empleo.titulo}
          </h3>
        </div>
      </div>

      {/* Descripción */}
      <p style={{
        color: "#78716c", fontSize: 13, lineHeight: 1.7,
        display: "-webkit-box", WebkitLineClamp: 3,
        WebkitBoxOrient: "vertical", overflow: "hidden", margin: 0,
      }}>
        {empleo.descripcion}
      </p>

      {/* Badges */}
      <div style={{ display: "flex", flexWrap: "wrap", gap: 8 }}>
        {empleo.tipo_contrato && (
          <Badge icon="fa-clock" text={empleo.tipo_contrato}
            bg="#f5f5f4" color="#57534e" />
        )}
        <Badge icon={salarioBadge.icon} text={salarioBadge.text}
          bg={salarioBadge.bg} color={salarioBadge.color}
          border={salarioBadge.border} />
        {empleo.departamento && (
          <Badge icon="fa-map-marker-alt"
            text={empleo.municipio
              ? `${empleo.departamento.nombre} · ${empleo.municipio.nombre}`
              : empleo.departamento.nombre}
            bg="#eff6ff" color="#1d4ed8" />
        )}
      </div>

      {/* Footer */}
      <div style={{
        display: "flex", alignItems: "center", justifyContent: "space-between",
        paddingTop: 18, borderTop: "1px solid #f5f5f4", marginTop: "auto",
      }}>
        <div style={{ display: "flex", alignItems: "center", gap: 6,
          color: "#a8a29e", fontSize: 11, fontWeight: 500 }}>
          <i className="far fa-calendar-alt" />
          {empleo.created_at_human || empleo.created_at?.slice(0, 10)}
        </div>
        <a href={`${routes.show_base || "/empleos"}/${empleo.id}`} style={{
          display: "inline-flex", alignItems: "center", gap: 6,
          background: hov ? "#ea580c" : "#0c0a09",
          color: "white", textDecoration: "none",
          fontSize: 12, fontWeight: 700,
          padding: "9px 18px", borderRadius: 999,
          transition: "all 0.25s ease",
          boxShadow: hov ? "0 6px 20px rgba(234,88,12,0.3)" : "none",
        }}>
          Ver oferta <i className="fas fa-arrow-right" style={{ fontSize: 10 }} />
        </a>
      </div>
    </article>
  );
}

/* ══ HELPERS ══ */
function Badge({ icon, text, bg, color, border }) {
  return (
    <span style={{
      display: "inline-flex", alignItems: "center", gap: 6,
      fontSize: 11, fontWeight: 700,
      padding: "5px 12px", borderRadius: 999,
      background: bg, color, border: border || "none",
    }}>
      <i className={`fas ${icon}`} style={{ fontSize: 10 }} />
      {text}
    </span>
  );
}

function FilterPill({ icon, label, onRemove }) {
  return (
    <span style={{
      display: "inline-flex", alignItems: "center", gap: 6,
      background: "#fff7ed", border: "1.5px solid #fed7aa",
      color: "#c2410c", fontSize: 11, fontWeight: 700,
      padding: "5px 10px 5px 12px", borderRadius: 999,
    }}>
      <i className={`fas ${icon}`} style={{ fontSize: 9, opacity: 0.7 }} />
      {label}
      <button onClick={onRemove} style={{
        background: "rgba(194,65,12,0.12)", border: "none", cursor: "pointer",
        width: 16, height: 16, borderRadius: "50%",
        display: "flex", alignItems: "center", justifyContent: "center",
        color: "#c2410c", fontSize: 10, padding: 0, flexShrink: 0,
      }}>×</button>
    </span>
  );
}

function Pagination({ pagination, onPage }) {
  const { current_page, last_page } = pagination;
  const pages = [];
  for (let i = 1; i <= last_page; i++) {
    if (i === 1 || i === last_page || Math.abs(i - current_page) <= 1) {
      pages.push(i);
    } else if (pages[pages.length - 1] !== "…") {
      pages.push("…");
    }
  }

  return (
    <div style={{ marginTop: 56, display: "flex", alignItems: "center",
      justifyContent: "center", gap: 6 }}>
      <PageBtn onClick={() => onPage(current_page - 1)} disabled={current_page === 1}>
        <i className="fas fa-chevron-left" style={{ fontSize: 10 }} />
      </PageBtn>
      {pages.map((p, i) =>
        p === "…" ? (
          <span key={i} style={{ color: "#a8a29e", fontSize: 13, padding: "0 2px" }}>…</span>
        ) : (
          <PageBtn key={p} onClick={() => onPage(p)} active={p === current_page}>{p}</PageBtn>
        )
      )}
      <PageBtn onClick={() => onPage(current_page + 1)} disabled={current_page === last_page}>
        <i className="fas fa-chevron-right" style={{ fontSize: 10 }} />
      </PageBtn>
    </div>
  );
}

function PageBtn({ onClick, disabled, active, children }) {
  return (
    <button onClick={onClick} disabled={disabled} style={{
      width: 36, height: 36, borderRadius: "50%", border: "1.5px solid",
      cursor: disabled ? "not-allowed" : "pointer",
      fontSize: 13, fontWeight: 600, transition: "all .2s",
      borderColor: active ? "#ea580c" : "#e7e5e4",
      background: active ? "#ea580c" : "#fff",
      color: active ? "#fff" : disabled ? "#d4cfc9" : "#57534e",
    }}>
      {children}
    </button>
  );
}

function LoadingSkeleton({ count }) {
  return (
    <div style={{ display: "grid",
      gridTemplateColumns: "repeat(auto-fill, minmax(340px, 1fr))", gap: 20 }}>
      {Array.from({ length: count }).map((_, i) => (
        <div key={i} style={{
          background: "white", borderRadius: 24, overflow: "hidden",
          border: "1px solid #f1f0ee", padding: 28,
          display: "flex", flexDirection: "column", gap: 16,
        }}>
          {[60, 80, 100, 40, 70].map((w, j) => (
            <div key={j} style={{
              height: j === 0 ? 48 : 14, borderRadius: j === 0 ? 12 : 7,
              width: j === 0 ? 48 : `${w}%`,
              background: "linear-gradient(90deg,#f5f5f4 25%,#ece9e6 50%,#f5f5f4 75%)",
              backgroundSize: "400% 100%",
              animation: "shimmer 1.4s ease infinite",
            }} />
          ))}
          <style>{`@keyframes shimmer{0%{background-position:100% 0}100%{background-position:-100% 0}}`}</style>
        </div>
      ))}
    </div>
  );
}

function EmptyState({ hasFilters, onClear }) {
  return (
    <div style={{
      textAlign: "center", background: "white",
      border: "1px solid #e7e5e4", borderRadius: 28,
      padding: "64px 40px", maxWidth: 480, margin: "0 auto",
    }}>
      <div style={{
        width: 72, height: 72, background: "#fafaf9",
        border: "1px solid #e7e5e4", borderRadius: "50%",
        display: "flex", alignItems: "center", justifyContent: "center",
        margin: "0 auto 20px",
      }}>
        <i className="fas fa-briefcase" style={{ color: "#d6d3d1", fontSize: 28 }} />
      </div>
      <h3 style={{ fontFamily: "'Playfair Display', serif", fontSize: 22,
        fontWeight: 800, color: "#1c1917", marginBottom: 8 }}>
        No hay ofertas disponibles
      </h3>
      <p style={{ color: "#78716c", fontSize: 14, lineHeight: 1.7, margin: "0 0 24px" }}>
        {hasFilters
          ? "No encontramos vacantes que coincidan con tus filtros. Intenta con otros términos."
          : "Los establecimientos aún no han publicado vacantes activas. ¡Vuelve pronto!"}
      </p>
      {hasFilters && (
        <button onClick={onClear} style={{
          display: "inline-flex", alignItems: "center", gap: 8,
          background: "#ea580c", color: "white",
          padding: "11px 24px", borderRadius: 999,
          fontSize: 13, fontWeight: 700, border: "none", cursor: "pointer",
        }}>
          <i className="fas fa-times" style={{ fontSize: 11 }} /> Limpiar filtros
        </button>
      )}
    </div>
  );
}