<style>
.gn-footer {
    background: #0f172a;
    color: #cbd5e1;
    border-top: 1px solid #1e293b;
}
.gn-footer-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 48px 24px 32px;
}
.gn-footer-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;
    margin-bottom: 40px;
}
@media (min-width: 640px) { .gn-footer-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1024px) { .gn-footer-grid { grid-template-columns: repeat(12, 1fr); } }

.gn-footer-brand { grid-column: span 1; }
@media (min-width: 640px) { .gn-footer-brand { grid-column: span 2; } }
@media (min-width: 1024px) { .gn-footer-brand { grid-column: span 4; } }

.gn-footer-logo {
    font-family: 'Playfair Display', serif;
    font-style: italic; font-weight: 700;
    font-size: 20px; color: white; margin-bottom: 16px;
}
.gn-footer-logo .accent { color: #3b82f6; }

.gn-footer-desc { font-size: 14px; line-height: 1.7; color: #94a3b8; font-weight: 300; margin-bottom: 16px; }

.gn-footer-social { display: flex; align-items: center; gap: 12px; }
.gn-footer-social a {
    width: 32px; height: 32px; border-radius: 50%;
    background: #1e293b; display: flex; align-items: center; justify-content: center;
    color: #94a3b8; font-size: 12px; text-decoration: none;
    transition: all 0.2s;
}
.gn-footer-social a:hover { background: #2563eb; color: white; }

.gn-footer-col { grid-column: span 1; }
@media (min-width: 1024px) { .gn-footer-col { grid-column: span 2; } }
.gn-footer-col h4 {
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.05em; color: white; margin-bottom: 16px;
}
.gn-footer-col ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 14px; }
.gn-footer-col ul a { color: #94a3b8; text-decoration: none; transition: color 0.2s; }
.gn-footer-col ul a:hover { color: #60a5fa; }

.gn-footer-destinos { grid-column: span 1; }
@media (min-width: 1024px) { .gn-footer-destinos { grid-column: span 3; } }
.gn-footer-destinos h4 {
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.05em; color: white; margin-bottom: 16px;
}
.gn-footer-destinos-grid {
    display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;
    font-size: 14px; color: #94a3b8; font-weight: 300;
}
.gn-footer-destinos-grid span { cursor: pointer; transition: color 0.2s; }
.gn-footer-destinos-grid span:hover { color: white; }
.gn-footer-destinos-grid i { color: #3b82f6; font-size: 9px; margin-right: 6px; }

.gn-footer-bottom {
    border-top: 1px solid #1e293b; padding-top: 24px;
    text-align: center; font-size: 12px; color: #64748b; font-weight: 300;
    display: flex; flex-direction: column; gap: 12px; align-items: center; justify-content: space-between;
}
@media (min-width: 640px) { .gn-footer-bottom { flex-direction: row; } }
.gn-footer-bottom-links { display: flex; gap: 16px; }
.gn-footer-bottom-links a { color: #64748b; text-decoration: none; }
.gn-footer-bottom-links a:hover { color: #94a3b8; }
</style>

<footer class="gn-footer">
    <div class="gn-footer-inner">
        <div class="gn-footer-grid">
            <div class="gn-footer-brand">
                <div class="gn-footer-logo">Gastro<span class="accent">Nicaragua</span></div>
                <p class="gn-footer-desc">
                    La plataforma líder en promoción turística y eventos culinarios de Nicaragua.
                    Descubre los mejores platillos, sabores tradicionales y experiencias únicas en todo el país.
                </p>
                <div class="gn-footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="gn-footer-col">
                <h4>Portal</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('restaurantes.index') }}">Restaurantes</a></li>
                    <li><a href="{{ route('gastrobares.index') }}">Gastrobares</a></li>
                    <li><a href="{{ route('empleos.index') }}">Bolsa de Empleos</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </div>
            <div class="gn-footer-destinos">
                <h4>Destinos Destacados</h4>
                <div class="gn-footer-destinos-grid">
                    <span><i class="fas fa-chevron-right"></i>Masaya</span>
                    <span><i class="fas fa-chevron-right"></i>Granada</span>
                    <span><i class="fas fa-chevron-right"></i>León</span>
                    <span><i class="fas fa-chevron-right"></i>San Juan del Sur</span>
                    <span><i class="fas fa-chevron-right"></i>Estelí</span>
                    <span><i class="fas fa-chevron-right"></i>Matagalpa</span>
                </div>
            </div>
        </div>
        <div class="gn-footer-bottom">
            <p>&copy; {{ date('Y') }} Gastro Nicaragua. Todos los derechos reservados.</p>
            <div class="gn-footer-bottom-links">
                <a href="#">Política de Privacidad</a>
                <a href="#">Términos de Servicio</a>
            </div>
        </div>
    </div>
</footer>
