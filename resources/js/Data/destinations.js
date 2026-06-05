export const destinations = [
    {
        slug: 'antigua-guatemala',
        title: 'Antigua Guatemala',
        image: '/images/antigua_image.jpg',
        summary: 'Ciudad colonial, hoteles boutique, restaurantes, bodas, eventos y conexiones hacia otros destinos.',
        eyebrow: 'Ciudad colonial',
        headline: 'Antigua Guatemala',
        copy: 'Antigua es uno de los destinos mas solicitados para llegadas desde el aeropuerto, estadias familiares, eventos y viajes privados. Coordinamos traslados hacia hoteles, restaurantes, fincas, residencias y puntos cercanos con una experiencia clara desde la cotizacion.',
        highlights: ['Hoteles y Airbnb', 'Eventos y bodas', 'Conexiones al aeropuerto', 'Traslados familiares'],
        routes: ['Aeropuerto La Aurora', 'Ciudad de Guatemala', 'Panajachel', 'Quetzaltenango'],
    },
    {
        slug: 'panajachel',
        title: 'Panajachel',
        image: '/images/panajachel_image.jpg',
        summary: 'Traslados privados al Lago de Atitlan y pueblos cercanos con espacio para equipaje.',
        eyebrow: 'Lago de Atitlan',
        headline: 'Panajachel',
        copy: 'Panajachel es una puerta de entrada al Lago de Atitlan. El viaje requiere buena coordinacion de horario, equipaje y ruta, especialmente para familias o grupos que conectan con lancha hacia otros pueblos.',
        highlights: ['Lago de Atitlan', 'Conexiones por lancha', 'Viajes grupales', 'Rutas escenicas'],
        routes: ['Antigua Guatemala', 'Aeropuerto La Aurora', 'Ciudad de Guatemala', 'Quetzaltenango'],
    },
    {
        slug: 'ciudad-de-guatemala',
        title: 'Ciudad de Guatemala',
        image: '/images/guatemala_image.jpg',
        summary: 'Aeropuerto, hoteles, zonas empresariales, centros medicos y conexiones urbanas.',
        eyebrow: 'Capital y aeropuerto',
        headline: 'Ciudad de Guatemala',
        copy: 'La ciudad concentra aeropuerto, hoteles, centros de reuniones, hospitales y conexiones hacia el resto del pais. Un traslado privado ayuda a evitar esperas y coordinar el punto exacto de recogida o entrega.',
        highlights: ['Aeropuerto La Aurora', 'Zonas hoteleras', 'Reuniones empresariales', 'Centros medicos'],
        routes: ['Antigua Guatemala', 'Panajachel', 'Quetzaltenango', 'Aeropuerto La Aurora'],
    },
    {
        slug: 'quetzaltenango',
        title: 'Quetzaltenango',
        image: '/images/quetzaltenango_image.jpg',
        summary: 'Viajes largos con planificacion segura, comodidad y claridad en el precio.',
        eyebrow: 'Altiplano occidental',
        headline: 'Quetzaltenango',
        copy: 'Quetzaltenango requiere una ruta mas larga y planificada. Es ideal reservar con anticipacion para elegir un vehiculo comodo, confirmar horario y prever paradas segun las necesidades del pasajero.',
        highlights: ['Viajes largos', 'Planificacion de horario', 'Paradas coordinadas', 'Mayor comodidad'],
        routes: ['Antigua Guatemala', 'Ciudad de Guatemala', 'Panajachel', 'Aeropuerto La Aurora'],
    },
];

export function findDestination(slug) {
    return destinations.find((destination) => destination.slug === slug);
}
