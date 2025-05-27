// Información de ejemplo, puedes reemplazarla por una consulta AJAX en el futuro
const infoHabitaciones = {
    1: {
        tipo: 'Simple',
        numero: '101',
        descripcion: 'Habitación simple con cama individual, baño privado, wifi, TV y escritorio.',
        servicios: 'Aire acondicionado, desayuno incluido, vista a la ciudad.',
        imagen: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80'
    },
    2: {
        tipo: 'Simple',
        numero: '102',
        descripcion: 'Habitación simple con cama individual, baño privado, wifi, TV y escritorio.',
        servicios: 'Aire acondicionado, desayuno incluido, vista a la ciudad.',
        imagen: 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80'
    },
    6: {
        tipo: 'Doble',
        numero: '201',
        descripcion: 'Habitación doble con dos camas, baño privado, wifi, TV y escritorio.',
        servicios: 'Aire acondicionado, desayuno incluido, vista al jardín.',
        imagen: 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=400&q=80'
    },
    11: {
        tipo: 'Suite',
        numero: '301',
        descripcion: 'Suite de lujo con cama king, sala de estar, jacuzzi, wifi, TV Smart.',
        servicios: 'Aire acondicionado, desayuno buffet, minibar, vista panorámica.',
        imagen: 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80'
    }
    // ...agrega más habitaciones según tus IDs
};

function mostrarInfoHabitacion(id) {
    const info = infoHabitaciones[id];
    if (!info) return;
    const modal = document.getElementById('modalHabitacion');
    const contenido = document.getElementById('modalContenido');
    contenido.innerHTML = `
        <img src="${info.imagen}" alt="Habitación ${info.numero}" style="width:100%;max-width:320px;border-radius:10px;margin-bottom:1rem;box-shadow:0 2px 12px #00e6ff33;">
        <h4>Habitación ${info.numero} (${info.tipo})</h4>
        <p><strong>Descripción:</strong> ${info.descripcion}</p>
        <p><strong>Servicios:</strong> ${info.servicios}</p>
    `;
    modal.classList.add('active');
}

function cerrarModalHabitacion() {
    document.getElementById('modalHabitacion').classList.remove('active');
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('cerrarModalHabitacion').onclick = cerrarModalHabitacion;
    document.getElementById('modalHabitacion').onclick = function(e) {
        if (e.target === this) cerrarModalHabitacion();
    };
});
