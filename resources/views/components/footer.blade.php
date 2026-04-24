<footer class="bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">

            {{-- Marca --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-5">
                    <div
                        class="h-11 w-11 rounded-2xl bg-gradient-to-br from-yellow-300 to-orange-400 flex items-center justify-center font-black text-slate-950">
                        DIY
                    </div>

                    <div>
                        <h3 class="text-xl font-black">DIY Antigua</h3>
                        <p class="text-xs text-slate-400">Private Transfers</p>
                    </div>
                </div>

                <p class="text-sm text-slate-400 leading-relaxed text-justify">
                    Traslados privados, seguros y cómodos entre Ciudad de Guatemala,
                    Antigua Guatemala, Panajachel, Quetzaltenango y otros destinos.
                </p>
            </div>

            {{-- Navegación --}}
            <div>
                <h4 class="font-black mb-5 text-yellow-300">Navegación</h4>

                <ul class="space-y-3 text-sm text-slate-400">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-yellow-300 transition">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="#booking" class="hover:text-yellow-300 transition">
                            Reservar
                        </a>
                    </li>
                    <li>
                        <a href="#como-funciona" class="hover:text-yellow-300 transition">
                            Cómo funciona
                        </a>
                    </li>
                    <li>
                        <a href="#destinos" class="hover:text-yellow-300 transition">
                            Destinos
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Servicios --}}
            <div>
                <h4 class="font-black mb-5 text-yellow-300">Servicios</h4>

                <ul class="space-y-3 text-sm text-slate-400">
                    <li>Traslados privados</li>
                    <li>Servicio desde aeropuerto</li>
                    <li>Viajes familiares</li>
                    <li>Traslados grupales</li>
                    <li>Viajes corporativos</li>
                </ul>
            </div>

            {{-- Contacto --}}
            <div>
                <h4 class="font-black mb-5 text-yellow-300">Contacto</h4>

                <ul class="space-y-3 text-sm text-slate-400">
                    <li class="flex gap-3">
                        <span class="text-yellow-300">📍</span>
                        <span>Antigua Guatemala, Guatemala</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="text-yellow-300">📞</span>
                        <a href="tel:+50200000000" class="hover:text-yellow-300 transition">
                            +502 0000-0000
                        </a>
                    </li>

                    <li class="flex gap-3">
                        <span class="text-yellow-300">✉️</span>
                        <a href="mailto:info@diyantigua.com" class="hover:text-yellow-300 transition">
                            info@diyantigua.com
                        </a>
                    </li>
                </ul>

                <div class="flex gap-3 mt-6">
                    <a href="#"
                        class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-yellow-300 hover:text-slate-950 transition"
                        aria-label="Facebook">
                        f
                    </a>

                    <a href="#"
                        class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-yellow-300 hover:text-slate-950 transition"
                        aria-label="Instagram">
                        ig
                    </a>

                    <a href="#"
                        class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-yellow-300 hover:text-slate-950 transition"
                        aria-label="WhatsApp">
                        wa
                    </a>
                </div>
            </div>

        </div>

        <div
            class="border-t border-white/10 mt-12 pt-6 flex flex-col md:flex-row justify-between gap-4 text-sm text-slate-500">
            <p>
                © {{ date('Y') }} DIY Antigua. Todos los derechos reservados.
            </p>

            <div class="flex flex-wrap gap-5">
                <a href="#" class="hover:text-yellow-300 transition">
                    Términos
                </a>

                <a href="#" class="hover:text-yellow-300 transition">
                    Privacidad
                </a>

                <a href="{{ url('/ayuda') }}" class="hover:text-yellow-300 transition">
                    Ayuda
                </a>

                <a href="{{ url('/ayuda') }}" class="hover:text-yellow-300 transition">
                    Soporte
                </a>
            </div>
        </div>
    </div>
</footer>
