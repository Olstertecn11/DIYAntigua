import { createContext, useContext, useEffect, useMemo, useState } from 'react';

const LanguageContext = createContext({
    language: 'ES',
    setLanguage: () => {},
    toggleLanguage: () => {},
    t: (spanish) => spanish,
});

const dictionary = {
    EN: {
        'Reservar': 'Book',
        'Destinos': 'Destinations',
        'Nosotros': 'About',
        'Tours': 'Tours',
        'Mis reservas': 'My bookings',
        'Perfil': 'Profile',
        'Nueva reserva': 'New booking',
        'Salir': 'Sign out',
        'Iniciar sesion': 'Sign in',
        'Registrarse': 'Create account',
        'Mi cuenta': 'My account',
        'Navegacion': 'Navigation',
        'Reservar traslado': 'Book transfer',
        'Cuenta': 'Account',
        'Contacto': 'Contact',
        'Atencion para traslados privados': 'Support for private transfers',
        'Traslados privados en Guatemala para aeropuerto, Antigua, Lago de Atitlan, Quetzaltenango y rutas turísticas.': 'Private transfers in Guatemala for the airport, Antigua, Lake Atitlan, Quetzaltenango and tourist routes.',
        'Traslados privados en Guatemala': 'Private transfers in Guatemala',
        'Viaja comodo, ': 'Travel comfortably, ',
        'seguro': 'safely',
        ' y sin complicaciones.': ' and without complications.',
        'Reserva tu traslado privado entre Ciudad de Guatemala, Antigua Guatemala, Panajachel, Quetzaltenango y mas destinos. Precio claro antes de confirmar, conductores verificados y atencion personalizada.': 'Book your private transfer between Guatemala City, Antigua Guatemala, Panajachel, Quetzaltenango and more destinations. Clear pricing before confirming, verified drivers and personalized support.',
        'Reservar ahora': 'Book now',
        'Ver como funciona': 'How it works',
        'Reserva rapida': 'Quick booking',
        'Cotiza tu traslado': 'Quote your transfer',
        'Selecciona origen, destino, fecha y numero de pasajeros.': 'Select origin, destination, date and number of passengers.',
        'Origen': 'Origin',
        'Destino': 'Destination',
        'Fecha': 'Date',
        'Hora': 'Time',
        'Pasajeros': 'Passengers',
        'Selecciona origen': 'Select origin',
        'No hay rutas disponibles': 'No routes available',
        'Selecciona destino': 'Select destination',
        'Elige origen primero': 'Choose origin first',
        'Ver tarifas disponibles': 'View available fares',
        'Buscando tarifas...': 'Searching fares...',
        'Por que elegirnos': 'Why choose us',
        'Una experiencia diseñada para viajar tranquilo.': 'An experience designed for peaceful travel.',
        'Ideal para turistas, familias, ejecutivos, grupos pequeños y viajeros que buscan seguridad, puntualidad y una reserva sencilla.': 'Ideal for tourists, families, executives, small groups and travelers who want safety, punctuality and simple booking.',
        'Conductores verificados': 'Verified drivers',
        'Personal profesional, puntual y con conocimiento de rutas turisticas y zonas urbanas.': 'Professional, punctual drivers who know tourist routes and urban areas.',
        'Precio claro': 'Clear pricing',
        'Visualiza la tarifa antes de confirmar. Sin cargos sorpresa ni negociacion incomoda.': 'See the fare before confirming. No surprise fees or awkward negotiation.',
        'Reserva facil': 'Easy booking',
        'Reserva como invitado o con cuenta. Recibe tu codigo para consultar el estado del servicio.': 'Book as a guest or with an account. Receive your code to check service status.',
        'Proceso simple': 'Simple process',
        'Reserva tu traslado en tres pasos.': 'Book your transfer in three steps.',
        'La plataforma esta pensada para que cualquier persona pueda cotizar, elegir, confirmar y consultar su reserva sin complicaciones.': 'The platform is designed so anyone can quote, choose, confirm and check their booking without complications.',
        'Elegir': 'Choose',
        'Elige ruta, fecha, hora, pasajeros y el vehiculo ideal.': 'Choose route, date, time, passengers and the ideal vehicle.',
        'Confirmar': 'Confirm',
        'Confirma datos, verifica tu correo y paga de forma segura.': 'Confirm details, verify your email and pay securely.',
        'Visualizar en tiempo real tu reserva sin complicaciones': 'View your booking in real time without complications',
        'Consulta estado, comprobante y cuenta regresiva desde Mis reservas.': 'Check status, receipt and countdown from My bookings.',
        'Servicio privado': 'Private service',
        'Del aeropuerto a tu destino sin estres.': 'From the airport to your destination without stress.',
        'Perfecto para llegadas, salidas, tours, reuniones o viajes familiares.': 'Perfect for arrivals, departures, tours, meetings or family trips.',
        'Nuestra flota': 'Our fleet',
        'Opciones para cada tipo de viaje.': 'Options for every kind of trip.',
        'Vehiculos privados para traslados ejecutivos, familiares, turismo y grupos.': 'Private vehicles for executive, family, tourist and group transfers.',
        'Destinos destacados': 'Featured destinations',
        'Descubre Guatemala': 'Discover Guatemala',
        'Cotizar destino': 'Quote destination',
    },
};

export function LanguageProvider({ children }) {
    const [language, setLanguage] = useState(() => {
        if (typeof window === 'undefined') {
            return 'ES';
        }

        return window.localStorage.getItem('dyantigua:language') || 'ES';
    });

    useEffect(() => {
        document.documentElement.lang = language === 'EN' ? 'en' : 'es';
        window.localStorage.setItem('dyantigua:language', language);
    }, [language]);

    const value = useMemo(() => ({
        language,
        setLanguage,
        toggleLanguage: () => setLanguage((current) => (current === 'ES' ? 'EN' : 'ES')),
        t: (spanish) => dictionary[language]?.[spanish] || spanish,
    }), [language]);

    return <LanguageContext.Provider value={value}>{children}</LanguageContext.Provider>;
}

export function useLanguage() {
    return useContext(LanguageContext);
}
