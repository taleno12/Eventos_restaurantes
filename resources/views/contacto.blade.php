<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gastro Nicaragua | Contacto</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,500,600" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            body { font-family: 'Instrument Sans', sans-serif; overflow-x: hidden; background-color: #1c1917; }
            .premium-title { font-family: 'Playfair Display', serif; }
            
            .animate-fade-in {
                animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="bg-stone-950 text-white min-h-screen flex flex-col justify-between">

        <nav class="w-full bg-stone-900/50 backdrop-blur-md border-b border-stone-800 fixed top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-900/40">
                        <i class="fas fa-utensils text-white"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight premium-title italic">Gastro<span class="text-orange-600">Nicaragua</span></span>
                </div>
                <a href="{{ route('home') }}" class="text-sm font-bold text-stone-400 hover:text-orange-500 transition-colors flex items-center gap-2">
                    <i class="fas fa-arrow-left text-xs"></i> Volver al Inicio
                </a>
            </div>
        </nav>

        <main class="max-w-6xl mx-auto px-6 py-32 flex-grow w-full flex items-center">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center w-full animate-fade-in">
                
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-950/50">
                            <i class="fas fa-envelope text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="premium-title text-4xl font-black tracking-tight">Hablemos de Gastronomía</h3>
                            <p class="text-stone-400 text-xs">¿Tienes dudas, sugerencias o quieres registrar tu local?</p>
                        </div>
                    </div>
                    
                    <p class="text-stone-400 leading-relaxed text-sm max-w-md pt-2">
                        Estamos comprometidos con expandir la cultura culinaria nicaragüense. Ponte en contacto con nuestro equipo de soporte técnico y comercial para resolver cualquier inquietud de inmediato.
                    </p>

                    <div class="space-y-4 pt-4">
                        <div class="flex items-center gap-4 text-stone-300">
                            <div class="w-9 h-9 bg-stone-900 border border-stone-800 rounded-xl flex items-center justify-center text-orange-500">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">Masaya, Nicaragua</span>
                        </div>
                        <div class="flex items-center gap-4 text-stone-300">
                            <div class="w-9 h-9 bg-stone-900 border border-stone-800 rounded-xl flex items-center justify-center text-orange-500">
                                <i class="fas fa-phone-alt text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">+505 8888-8888</span>
                        </div>
                        <div class="flex items-center gap-4 text-stone-300">
                            <div class="w-9 h-9 bg-stone-900 border border-stone-800 rounded-xl flex items-center justify-center text-orange-500">
                                <i class="fas fa-headset text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">soporte@gastronicaragua.ni</span>
                        </div>
                    </div>
                </div>

                <div class="bg-stone-900/60 border border-stone-800 p-8 md:p-10 rounded-[2.5rem] shadow-2xl">
                    
                    <form id="whatsappForm" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2">Nombre Completo</label>
                                <input type="text" id="nombre" required
                                       class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-sm text-white focus:border-orange-500 focus:outline-none transition-colors"
                                       placeholder="Ej: Juan Pérez">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2">Correo Electrónico</label>
                                <input type="email" id="email" required
                                       class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-sm text-white focus:border-orange-500 focus:outline-none transition-colors"
                                       placeholder="juan@ejemplo.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2">Asunto del Mensaje</label>
                            <input type="text" id="asunto" required
                                   class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-sm text-white focus:border-orange-500 focus:outline-none transition-colors"
                                   placeholder="Ej: Registro de nuevo restaurante">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2">Tu Mensaje</label>
                            <textarea id="mensaje" rows="4" required
                                      class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-sm text-white focus:border-orange-500 focus:outline-none transition-colors resize-none"
                                      placeholder="Escribe en detalle tu requerimiento aquí..."></textarea>
                        </div>

                        <button type="submit"
                                class="w-full bg-orange-600 text-white py-3.5 rounded-xl text-sm font-bold hover:bg-orange-700 transition-all shadow-lg shadow-orange-950/40 flex items-center justify-center gap-2">
                            <span>Enviar Mensaje Comercial</span>
                            <i class="fab fa-whatsapp text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
        </main>

        <footer class="bg-stone-950 text-stone-600 py-8 text-center border-t border-stone-900">
            <p class="text-xs tracking-widest uppercase font-bold">© 2026 Gastro Nicaragua — Experiencias Culinarias</p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            document.getElementById('whatsappForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Evita que la página se recargue

                // 1. TU NÚMERO DE WHATSAPP (Con el código de país de Nicaragua 505 al inicio)
                const telefono = "50585406068"; // <-- CAMBIA ESTO POR TU NÚMERO REAL

                // 2. Capturamos los datos que escribió el usuario en tu tarjeta
                const nombre = document.getElementById('nombre').value;
                const email = document.getElementById('email').value;
                const asunto = document.getElementById('asunto').value;
                const mensaje = document.getElementById('mensaje').value;

                // 3. Diseñamos el mensaje predeterminado estructurado y limpio
                const textoMensaje = `¡Hola Gastro Nicaragua! 👋%0A%0A` +
                                     `Me gustaría ponerme en contacto con ustedes. Aquí mis detalles:%0A%0A` +
                                     `📌 *Nombre:* ${nombre}%0A` +
                                     `📧 *Correo:* ${email}%0A` +
                                     `💼 *Asunto:* ${asunto}%0A%0A` +
                                     `💬 *Mensaje:* ${mensaje}`;

                // 4. Construimos la URL de la API oficial de WhatsApp
                const urlWhatsApp = `https://api.whatsapp.com/send?phone=${telefono}&text=${textoMensaje}`;

                // 5. Redireccionamos abriendo una nueva pestaña del navegador
                window.open(urlWhatsApp, '_blank');
            });
        </script>
    </body>
</html>