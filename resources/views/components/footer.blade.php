<footer class="brand-footer">
    <div class="brand-footer-inner">
        <div class="brand-footer-top">
            <div class="brand-footer-main">
                <a href="{{ url('/') }}" class="brand-footer-logo">
                    <span class="brand-footer-mark">
                        <img src="{{ asset('images/logo.png') }}" alt="DYANTIGUA">
                    </span>
                    <span>
                        <strong>DYANTIGUA</strong>
                        <small>Tours & Transport</small>
                    </span>
                </a>

                <p>
                    Traslados privados para descubrir Guatemala con puntualidad, comodidad y una experiencia clara desde la reserva hasta el destino.
                </p>
            </div>

            <div class="brand-footer-columns">
                <div>
                    <h4>Navegación</h4>
                    <a href="{{ url('/') }}">Inicio</a>
                    <a href="{{ url('/reservar-traslado') }}">Reservar traslado</a>
                    <a href="{{ url('/destinos') }}">Destinos</a>
                    <a href="{{ url('/informacion-del-servicio') }}">Nosotros</a>
                </div>

                <div>
                    <h4>Servicios</h4>
                    <span>Aeropuerto</span>
                    <span>Traslados privados</span>
                    <span>Viajes familiares</span>
                    <span>Rutas turísticas</span>
                </div>

                <div>
                    <h4>Contacto</h4>
                    <a href="tel:+50235977809"><i class="fas fa-phone"></i> +502 3597-7809</a>
                    <a href="mailto:info@diyantigua.com"><i class="fas fa-envelope"></i> info@diyantigua.com</a>
                    <span><i class="fas fa-location-dot"></i> Antigua Guatemala</span>
                </div>
            </div>
        </div>

        <div class="brand-footer-bottom">
            <span>© {{ date('Y') }} DYANTIGUA. Todos los derechos reservados.</span>
            <div>
                <a href="#">Términos</a>
                <a href="#">Privacidad</a>
                <a href="{{ url('/ayuda') }}">Ayuda</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .brand-footer {
        position: relative;
        overflow: hidden;
        color: #f7f4ec;
        background:
            linear-gradient(90deg, #FCCA00 0 10px, transparent 10px),
            linear-gradient(135deg, #080806 0%, #171713 58%, #29251a 100%);
        border-top: 1px solid rgba(252, 202, 0, .28);
    }

    .brand-footer::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
        background-size: 44px 44px;
        opacity: .45;
        pointer-events: none;
    }

    .brand-footer-inner {
        position: relative;
        max-width: 1180px;
        margin: 0 auto;
        padding: 54px 24px 28px;
    }

    .brand-footer-top {
        display: grid;
        grid-template-columns: minmax(260px, .95fr) 1.65fr;
        gap: 48px;
    }

    .brand-footer-logo {
        display: inline-flex;
        align-items: center;
        gap: 14px;
        color: #fff;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .brand-footer-mark {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,.18);
        background: rgba(255,255,255,.06);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .brand-footer-mark img {
        width: 42px;
        height: 42px;
        object-fit: contain;
    }

    .brand-footer-logo strong {
        display: block;
        font-size: 22px;
        line-height: 1;
        font-weight: 950;
        letter-spacing: .02em;
    }

    .brand-footer-logo small {
        display: block;
        margin-top: 5px;
        color: #FCCA00;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .2em;
        text-transform: uppercase;
    }

    .brand-footer-main p {
        max-width: 390px;
        color: rgba(247,244,236,.68);
        font-size: 14px;
        line-height: 1.75;
        margin: 0;
    }

    .brand-footer-columns {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 28px;
    }

    .brand-footer h4 {
        color: #FCCA00;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .16em;
        margin: 0 0 16px;
    }

    .brand-footer a,
    .brand-footer span {
        display: block;
        color: rgba(247,244,236,.72);
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        margin-bottom: 11px;
        transition: color .2s ease, transform .2s ease;
    }

    .brand-footer a:hover {
        color: #ffffff;
        transform: translateX(3px);
    }

    .brand-footer i {
        color: #FCCA00;
        margin-right: 8px;
        width: 14px;
    }

    .brand-footer-bottom {
        margin-top: 42px;
        padding-top: 22px;
        border-top: 1px solid rgba(255,255,255,.10);
        display: flex;
        justify-content: space-between;
        gap: 18px;
        color: rgba(247,244,236,.52);
        font-size: 13px;
        font-weight: 700;
    }

    .brand-footer-bottom div {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
    }

    .brand-footer-bottom a {
        margin: 0;
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .brand-footer-top,
        .brand-footer-columns {
            grid-template-columns: 1fr;
        }

        .brand-footer-bottom {
            flex-direction: column;
        }
    }
</style>
