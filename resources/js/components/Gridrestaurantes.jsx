// resources/js/components/GridRestaurantes.jsx
/**
 * Requiere en Blade:
 * <script>
 *   window.__GRID_RESTAURANTES__ = {
 *     items: @json($restaurantes->items()),   // array de restaurantes de la página actual
 *     pagination: {
 *       current_page: {{ $restaurantes->currentPage() }},
 *       last_page:    {{ $restaurantes->lastPage() }},
 *       total:        {{ $restaurantes->total() }},
 *       per_page:     {{ $restaurantes->perPage() }},
 *     },
 *     filters: {
 *       departamento: "{{ request('departamento') }}",
 *       municipio:    "{{ request('municipio') }}",
 *       especialidad: "{{ request('especialidad') }}",
 *       search:       "{{ request('search') }}",
 *     },
 *     departamentos: @json($departamentos),
 *     municipios:    @json($municipios),
 *     routes: {
 *       restaurantes:  "{{ route('restaurantes.index') }}",
 *       show_base:     "{{ url('/restaurantes') }}",
 *       storage_base:  "{{ asset('storage') }}",
 *     }
 *   };
 * </script>
 */

import { useState, useEffect, useRef } from "react";

const cfg        = window.__GRID_RESTAURANTES__ || {};
const initItems  = cfg.items        || [];
const initPag    = cfg.pagination   || { current_page: 1, last_page: 1, total: 0 };
const initFilter = cfg.filters      || {};
const deptos     = cfg.departamentos || [];
const munis      = cfg.municipios   || [];
const routes     = cfg.routes       || {};
const storageBase = routes.storage_base || "/storage";

export default function GridRestaurantes() {
  const [items, setItems]           = useState(initItems);
  const [pagination, setPagination] = useState(initPag);
  const [filters, setFilters]       = useState(initFilter);
  const [loading, setLoading]       = useState(false);
  const [viewMode, setViewMode]     = useState("grid"); // "grid" | "list"
  const topRef = useRef(null);

  const hasFilters = filters.departamento || filters.municipio || filters.especialidad || filters.search;

  // Sync URL + fetch cuando cambian filtros/página
  const fetchPage = async (newFilters, page = 1) => {
    setLoading(true);
    const params = new URLSearchParams();
    Object.entries(newFilters).forEach(([k, v]) => { if (v) params.set(k, v); });
    if (page > 1) params.set("page", page);
    params.set("json", "1"); // señal para el controller de responder JSON

    try {
      const res  = await fetch(`${routes.restaurantes || "/restaurantes"}?${params.toString()}`, {
        headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" },
      });
      if (!res.ok) throw new Error("fetch failed");
      const data = await res.json();
      setItems(data.items);
      setPagination(data.pagination);
      window.history.pushState({}, "", `?${params.toString()}`);
      topRef.current?.scrollIntoView({ behavior: "smooth", block: "start" });
    } catch {
      // fallback: recarga normal
      window.location.href = `${routes.restaurantes}?${params.toString()}`;
    } finally {
      setLoading(false);
    }
  };

  const updateFilter = (key, value) => {
    const next = { ...filters, [key]: value };
    if (key === "departamento") next.municipio = "";
    setFilters(next);
    fetchPage(next, 1);
  };

  const clearFilters = () => {
    setFilters({});
    fetchPage({}, 1);
  };

  const goPage = (p) => fetchPage(filters, p);

  const deptoLabel  = (id) => deptos.find(d => d.id == id)?.nombre || id;
  const munLabel    = (id) => munis.find(m => m.id == id)?.nombre  || id;

  return (
    <div ref={topRef}>
      {/* ── Barra de filtros activos ── */}
      <FiltersBar
        filters={filters} hasFilters={hasFilters}
        deptoLabel={deptoLabel} munLabel={munLabel}
        onRemove={updateFilter} onClear={clearFilters}
        total={pagination.total}
        viewMode={viewMode} setViewMode={setViewMode}
      />

      {/* ── Grid / List ── */}
      {loading ? (
        <LoadingSkeleton count={6} />
      ) : items.length === 0 ? (
        <EmptyState hasFilters={hasFilters} onClear={clearFilters} />
      ) : (
        <div className={
          viewMode === "grid"
            ? "grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
            : "flex flex-col gap-5"
        }>
          {items.map((r, i) => (
            viewMode === "grid"
              ? <RestCardGrid key={r.id} r={r} delay={i % 3} />
              : <RestCardList key={r.id} r={r} />
          ))}
        </div>
      )}

      {/* ── Paginación ── */}
      {!loading && pagination.last_page > 1 && (
        <Pagination pagination={pagination} onPage={goPage} />
      )}
    </div>
  );
}

/* ══ BARRA DE FILTROS ══ */
function FiltersBar({ filters, hasFilters, deptoLabel, munLabel, onRemove, onClear, total, viewMode, setViewMode }) {
  return (
    <div className="mb-8 flex flex-wrap items-center gap-2">
      {hasFilters ? (
        <>
          <span className="text-stone-500 font-medium text-sm pl-1">Filtros:</span>
          {filters.departamento && (
            <FilterPill
              icon="fa-map-marker-alt"
              label={deptoLabel(filters.departamento)}
              onRemove={() => onRemove("departamento", "")}
            />
          )}
          {filters.municipio && (
            <FilterPill
              icon="fa-city"
              label={munLabel(filters.municipio)}
              onRemove={() => onRemove("municipio", "")}
            />
          )}
          {filters.especialidad && (
            <FilterPill
              icon="fa-utensils"
              label={filters.especialidad}
              onRemove={() => onRemove("especialidad", "")}
            />
          )}
          {filters.search && (
            <FilterPill
              icon="fa-store"
              label={`"${filters.search}"`}
              onRemove={() => onRemove("search", "")}
            />
          )}
          <button onClick={onClear} style={{
            display: "flex", alignItems: "center", gap: 4,
            background: "none", border: "none", cursor: "pointer",
            fontSize: 12, color: "#a8a29e", fontWeight: 600,
          }}>
            <i className="fas fa-times-circle" style={{ fontSize: 11 }} /> Limpiar todo
          </button>
        </>
      ) : null}

      {/* Contador + Toggle vista */}
      <div className="ml-auto flex items-center gap-3">
        <span style={{
          fontSize: 11, fontWeight: 800, letterSpacing: "0.12em",
          textTransform: "uppercase", color: "#a8a29e",
        }}>
          {total} local{total !== 1 ? "es" : ""}
        </span>
        <div style={{
          display: "flex", border: "1.5px solid #e7e5e4",
          borderRadius: 10, overflow: "hidden", background: "#fafaf9",
        }}>
          {[
            { mode: "grid", icon: "fa-th-large" },
            { mode: "list", icon: "fa-list" },
          ].map(({ mode, icon }) => (
            <button key={mode} onClick={() => setViewMode(mode)}
                    style={{
                      width: 34, height: 32, border: "none", cursor: "pointer",
                      background: viewMode === mode ? "#ea580c" : "transparent",
                      color: viewMode === mode ? "#fff" : "#a8a29e",
                      transition: "all .2s", fontSize: 11,
                    }}>
              <i className={`fas ${icon}`} />
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}

function FilterPill({ icon, label, onRemove }) {
  return (
    <span style={{
      display: "inline-flex", alignItems: "center", gap: 6,
      background: "#fff7ed", border: "1.5px solid #fed7aa",
      color: "#c2410c", fontSize: 11, fontWeight: 700,
      padding: "5px 10px 5px 12px", borderRadius: 999,
      boxShadow: "0 1px 3px rgba(0,0,0,0.04)",
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

/* ══ CARD GRID ══ */
function RestCardGrid({ r, delay }) {
  const [hov, setHov] = useState(false);
  return (
    <article
      onMouseEnter={() => setHov(true)}
      onMouseLeave={() => setHov(false)}
      style={{
        background: "#fff", border: `1px solid ${hov ? "rgba(234,88,12,0.15)" : "rgba(28,25,23,0.05)"}`,
        borderRadius: "2rem", overflow: "hidden",
        transform: hov ? "translateY(-8px)" : "translateY(0)",
        boxShadow: hov ? "0 30px 60px rgba(28,25,23,0.08)" : "none",
        transition: "all .4s cubic-bezier(0.16,1,0.3,1)",
      }}>

      {/* Imagen */}
      <div style={{ position: "relative", height: 240, overflow: "hidden", background: "#f5f5f4" }}>
        {r.foto_portada ? (
          <img src={`${storageBase}/${r.foto_portada}`} alt={r.nombre}
               style={{
                 width: "100%", height: "100%", objectFit: "cover",
                 transform: hov ? "scale(1.05)" : "scale(1)",
                 transition: "transform .8s cubic-bezier(0.16,1,0.3,1)",
               }} />
        ) : (
          <div style={{
            width: "100%", height: "100%", display: "flex", alignItems: "center",
            justifyContent: "center", background: "linear-gradient(135deg,#fafaf9,#f1ede8)",
            fontSize: 56,
          }}>🍴</div>
        )}
        {/* Badge ubicación */}
        {r.departamento && (
          <div style={{ position: "absolute", top: 14, left: 14 }}>
            <span style={{
              background: "rgba(28,25,23,0.75)", backdropFilter: "blur(8px)",
              color: "#fff", fontSize: 11, fontWeight: 500,
              padding: "5px 10px", borderRadius: 999,
              display: "inline-flex", alignItems: "center", gap: 6,
            }}>
              <i className="fas fa-map-marker-alt" style={{ color: "#fb923c", fontSize: 9 }} />
              {r.departamento.nombre}
              {r.municipio && <><span style={{ opacity: 0.5 }}>·</span>{r.municipio.nombre}</>}
            </span>
          </div>
        )}
        {/* Badge especialidad */}
        {r.especialidad && (
          <div style={{ position: "absolute", top: 14, right: 14 }}>
            <span style={{
              background: "rgba(255,255,255,0.9)", color: "#c2410c",
              fontSize: 10, fontWeight: 700, letterSpacing: "0.05em", textTransform: "uppercase",
              padding: "4px 12px", borderRadius: 999,
              border: "1px solid rgba(234,88,12,0.1)",
            }}>{r.especialidad}</span>
          </div>
        )}
      </div>

      {/* Contenido */}
      <div style={{ padding: "24px", display: "flex", flexDirection: "column", gap: 14 }}>
        <div>
          <h3 style={{
            fontFamily: "'Playfair Display', serif", fontSize: 20, fontWeight: 700,
            color: "#1c1917", marginBottom: 6, lineHeight: 1.3,
          }}>{r.nombre}</h3>
          {r.descripcion && (
            <p style={{
              color: "#78716c", fontSize: 13, lineHeight: 1.6,
              display: "-webkit-box", WebkitLineClamp: 2,
              WebkitBoxOrient: "vertical", overflow: "hidden",
              fontWeight: 400, margin: 0,
            }}>{r.descripcion}</p>
          )}
        </div>

        <div style={{
          display: "flex", flexDirection: "column", gap: 8,
          fontSize: 12, color: "#78716c",
          borderTop: "1px solid #f5f5f4", paddingTop: 12,
        }}>
          {r.direccion && (
            <InfoRow icon="fa-map-marker-alt" text={r.direccion} clamp />
          )}
          {r.telefono && <InfoRow icon="fa-phone" text={r.telefono} />}
          {r.horario  && <InfoRow icon="fa-clock" text={r.horario} />}
        </div>

        {r.eventos_count > 0 && (
          <div style={{
            background: "#fff7ed", border: "1px solid #fed7aa",
            borderRadius: 10, padding: "8px 12px",
            display: "flex", alignItems: "center", gap: 8,
            fontSize: 12, color: "#9a3412", fontWeight: 600,
          }}>
            <i className="fas fa-calendar-alt" style={{ color: "#ea580c" }} />
            {r.eventos_count} evento{r.eventos_count !== 1 ? "s" : ""} próximo{r.eventos_count !== 1 ? "s" : ""}
          </div>
        )}

        <div style={{
          display: "flex", alignItems: "center", justifyContent: "space-between",
          borderTop: "1px solid #f5f5f4", paddingTop: 12,
        }}>
          {r.precio_rango ? (
            <span style={{ fontSize: 11, fontWeight: 600, color: "#a8a29e" }}>
              <i className="fas fa-tags" style={{ marginRight: 4, opacity: 0.6 }} />
              {r.precio_rango}
            </span>
          ) : <span />}
          <a href={`${routes.show_base || "/restaurantes"}/${r.id}`}
             style={{
               background: hov ? "#ea580c" : "#1c1917",
               color: "#fff", fontSize: 12, fontWeight: 600,
               padding: "9px 18px", borderRadius: 999,
               textDecoration: "none", display: "flex", alignItems: "center", gap: 6,
               transition: "background .2s",
             }}>
            Ver perfil
            <i className="fas fa-arrow-right" style={{ fontSize: 9 }} />
          </a>
        </div>
      </div>
    </article>
  );
}

/* ══ CARD LIST ══ */
function RestCardList({ r }) {
  const [hov, setHov] = useState(false);
  return (
    <article
      onMouseEnter={() => setHov(true)}
      onMouseLeave={() => setHov(false)}
      style={{
        background: "#fff", border: `1px solid ${hov ? "rgba(234,88,12,0.15)" : "rgba(28,25,23,0.05)"}`,
        borderRadius: "1.5rem", overflow: "hidden",
        transform: hov ? "translateY(-3px)" : "translateY(0)",
        boxShadow: hov ? "0 12px 30px rgba(28,25,23,0.07)" : "none",
        transition: "all .3s cubic-bezier(0.16,1,0.3,1)",
        display: "flex",
      }}>

      {/* Imagen */}
      <div style={{ width: 160, minHeight: 140, flexShrink: 0, overflow: "hidden", background: "#f5f5f4", position: "relative" }}>
        {r.foto_portada ? (
          <img src={`${storageBase}/${r.foto_portada}`} alt={r.nombre}
               style={{ width: "100%", height: "100%", objectFit: "cover" }} />
        ) : (
          <div style={{ width: "100%", height: "100%", display: "flex", alignItems: "center", justifyContent: "center", fontSize: 40 }}>🍴</div>
        )}
        {r.especialidad && (
          <div style={{ position: "absolute", bottom: 8, left: 8 }}>
            <span style={{
              background: "rgba(28,25,23,0.8)", color: "#fff",
              fontSize: 9, fontWeight: 700, padding: "3px 8px", borderRadius: 999,
              textTransform: "uppercase", letterSpacing: "0.08em",
            }}>{r.especialidad}</span>
          </div>
        )}
      </div>

      {/* Info */}
      <div style={{ flex: 1, padding: "18px 20px", display: "flex", flexDirection: "column", justifyContent: "space-between", gap: 8 }}>
        <div>
          <div style={{ display: "flex", flexWrap: "wrap", alignItems: "center", gap: 6, marginBottom: 6 }}>
            {r.departamento && (
              <span style={{
                background: "#f5f5f4", color: "#57534e",
                fontSize: 10, fontWeight: 700, padding: "3px 10px", borderRadius: 999,
                display: "inline-flex", alignItems: "center", gap: 4,
              }}>
                <i className="fas fa-map-marker-alt" style={{ color: "#ea580c", fontSize: 8 }} />
                {r.departamento.nombre}{r.municipio ? ` · ${r.municipio.nombre}` : ""}
              </span>
            )}
          </div>
          <h3 style={{
            fontFamily: "'Playfair Display', serif", fontSize: 17, fontWeight: 700,
            color: "#1c1917", margin: "0 0 4px",
          }}>{r.nombre}</h3>
          {r.descripcion && (
            <p style={{
              color: "#78716c", fontSize: 12, lineHeight: 1.5, margin: 0,
              display: "-webkit-box", WebkitLineClamp: 1, WebkitBoxOrient: "vertical", overflow: "hidden",
            }}>{r.descripcion}</p>
          )}
        </div>
        <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between", flexWrap: "wrap", gap: 8 }}>
          <div style={{ display: "flex", flexWrap: "wrap", gap: 10, fontSize: 11, color: "#a8a29e" }}>
            {r.telefono && <span><i className="fas fa-phone" style={{ marginRight: 4 }} />{r.telefono}</span>}
            {r.horario  && <span><i className="fas fa-clock" style={{ marginRight: 4 }} />{r.horario}</span>}
            {r.eventos_count > 0 && (
              <span style={{ color: "#c2410c", fontWeight: 700 }}>
                <i className="fas fa-calendar-alt" style={{ marginRight: 4 }} />{r.eventos_count} evento{r.eventos_count !== 1 ? "s" : ""}
              </span>
            )}
          </div>
          <a href={`${routes.show_base || "/restaurantes"}/${r.id}`}
             style={{
               background: "#1c1917", color: "#fff", fontSize: 11, fontWeight: 700,
               padding: "7px 16px", borderRadius: 999, textDecoration: "none",
               display: "flex", alignItems: "center", gap: 5,
             }}>
            Ver perfil <i className="fas fa-arrow-right" style={{ fontSize: 8 }} />
          </a>
        </div>
      </div>
    </article>
  );
}

/* ══ PAGINACIÓN ══ */
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
    <div style={{
      marginTop: 56, display: "flex", alignItems: "center", justifyContent: "center", gap: 6,
    }}>
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
    <button onClick={onClick} disabled={disabled}
            style={{
              width: 36, height: 36, borderRadius: "50%", border: "1.5px solid",
              cursor: disabled ? "not-allowed" : "pointer",
              fontSize: 13, fontWeight: 600,
              transition: "all .2s",
              borderColor: active ? "#ea580c" : "#e7e5e4",
              background: active ? "#ea580c" : "#fff",
              color: active ? "#fff" : disabled ? "#d4cfc9" : "#57534e",
            }}>
      {children}
    </button>
  );
}

/* ══ HELPERS ══ */
function InfoRow({ icon, text, clamp }) {
  return (
    <span style={{
      display: "flex", alignItems: "flex-start", gap: 8,
      overflow: "hidden",
    }}>
      <i className={`fas ${icon}`} style={{ color: "#a8a29e", fontSize: 11, marginTop: 1, flexShrink: 0 }} />
      <span style={clamp ? {
        overflow: "hidden", textOverflow: "ellipsis", whiteSpace: "nowrap",
      } : {}}>{text}</span>
    </span>
  );
}

function LoadingSkeleton({ count }) {
  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      {Array.from({ length: count }).map((_, i) => (
        <div key={i} style={{
          background: "#fff", borderRadius: "2rem", overflow: "hidden",
          border: "1px solid rgba(28,25,23,0.05)",
        }}>
          <div style={{ height: 240, background: "linear-gradient(90deg,#f5f5f4 25%,#ece9e6 50%,#f5f5f4 75%)",
                        backgroundSize: "400% 100%",
                        animation: "shimmer 1.4s ease infinite",
          }} />
          <div style={{ padding: 24, display: "flex", flexDirection: "column", gap: 12 }}>
            {[80,60,40,100].map((w, j) => (
              <div key={j} style={{
                height: 14, borderRadius: 7, width: `${w}%`,
                background: "linear-gradient(90deg,#f5f5f4 25%,#ece9e6 50%,#f5f5f4 75%)",
                backgroundSize: "400% 100%",
                animation: "shimmer 1.4s ease infinite",
              }} />
            ))}
          </div>
          <style>{`@keyframes shimmer{0%{background-position:100% 0}100%{background-position:-100% 0}}`}</style>
        </div>
      ))}
    </div>
  );
}

function EmptyState({ hasFilters, onClear }) {
  return (
    <div style={{
      padding: "80px 0", display: "flex", flexDirection: "column",
      alignItems: "center", textAlign: "center", maxWidth: 380, margin: "0 auto",
    }}>
      <div style={{
        width: 72, height: 72, background: "#fafaf9",
        border: "1px solid #e7e5e4", borderRadius: "50%",
        display: "flex", alignItems: "center", justifyContent: "center",
        fontSize: 28, marginBottom: 20, position: "relative",
      }}>
        <i className="fas fa-store-slash" style={{ color: "#a8a29e" }} />
        <div style={{
          position: "absolute", bottom: -2, right: -2,
          width: 24, height: 24, background: "#ea580c",
          borderRadius: "50%", display: "flex", alignItems: "center",
          justifyContent: "center",
        }}>
          <i className="fas fa-times" style={{ color: "#fff", fontSize: 9 }} />
        </div>
      </div>
      <h3 style={{
        fontFamily: "'Playfair Display', serif", fontSize: 22, fontWeight: 700,
        color: "#1c1917", marginBottom: 8,
      }}>Sin resultados</h3>
      <p style={{ fontSize: 14, color: "#78716c", lineHeight: 1.6, marginBottom: 24 }}>
        No encontramos restaurantes con esos filtros.
      </p>
      {hasFilters && (
        <button onClick={onClear} style={{
          background: "#1c1917", color: "#fff", border: "none", cursor: "pointer",
          fontSize: 12, fontWeight: 700, letterSpacing: "0.1em", textTransform: "uppercase",
          padding: "10px 22px", borderRadius: 999,
          display: "flex", alignItems: "center", gap: 8,
        }}>
          <i className="fas fa-undo" style={{ fontSize: 10 }} /> Limpiar filtros
        </button>
      )}
    </div>
  );
}