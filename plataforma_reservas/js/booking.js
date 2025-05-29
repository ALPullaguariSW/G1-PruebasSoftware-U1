// js/booking.js
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalHabitacionInfo');
    const modalContent = document.getElementById('modalContenidoInfo');
    const closeModalButton = document.getElementById('cerrarModalHabitacion');

    // Delegación de eventos para los botones de información de habitación
    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('info-btn-modal')) {
            const habJson = event.target.getAttribute('data-habitacion');
            if (habJson) {
                try {
                    const hab = JSON.parse(habJson);
                    mostrarInfoHabitacionModal(hab);
                } catch (e) {
                    console.error("Error al parsear JSON de habitación:", e);
                }
            }
        }
    });
    
    function mostrarInfoHabitacionModal(hab) {
        if (!modal || !modalContent) return;

        const defaultImage = 'images/default_room.jpg'; // Asegúrate que esta ruta sea correcta desde la raíz del sitio
                                                    // o usa una URL completa si es externa

        modalContent.innerHTML = `
            <div class="modal-header">
                <h4 id="modalHabitacionTitle">Habitación ${hab.numero} (${hab.tipo})</h4>
                <button type="button" class="modal-close" id="cerrarModalInterno" aria-label="Cerrar">×</button>
            </div>
            <div class="modal-body">
                <img src="${hab.imagen || defaultImage}" alt="Habitación ${hab.numero}" style="width:100%;max-height:250px;object-fit:cover;border-radius:var(--border-radius-sm);margin-bottom:1rem;">
                <p class="precio-modal"><strong>Precio por noche:</strong> $${parseFloat(hab.precio).toFixed(2)}</p>
                <p><strong>Descripción:</strong> ${hab.descripcion || 'No hay descripción disponible.'}</p>
                <p><strong>Servicios:</strong> ${hab.servicios || 'No se especifican servicios.'}</p>
            </div>
        `;
        modal.classList.add('active');
        
        // Añadir event listener al nuevo botón de cerrar dentro del modal
        document.getElementById('cerrarModalInterno').addEventListener('click', cerrarModalInfo);
    }

    function cerrarModalInfo() {
        if (modal) {
            modal.classList.remove('active');
        }
    }

    if (closeModalButton) {
        closeModalButton.addEventListener('click', cerrarModalInfo);
    }

    // Cerrar modal si se hace clic fuera del contenido
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                cerrarModalInfo();
            }
        });
    }

    // Validación de fechas en el formulario de reserva
    const fechaInicioInput = document.querySelector('input[name="fecha_inicio"]');
    const fechaFinInput = document.querySelector('input[name="fecha_fin"]');
    const formConsulta = document.getElementById('formConsultaDisponibilidad'); // Asigna ID al form

    if (fechaInicioInput && fechaFinInput) {
        const today = new Date().toISOString().split('T')[0];
        fechaInicioInput.setAttribute('min', today);
        
        fechaInicioInput.addEventListener('change', function() {
            if (fechaFinInput.value && fechaFinInput.value < this.value) {
                fechaFinInput.value = this.value;
            }
            fechaFinInput.setAttribute('min', this.value);
        });

        // Para el botón de consultar
        if (formConsulta) {
            formConsulta.addEventListener('submit', function(e) {
                if (fechaFinInput.value < fechaInicioInput.value) {
                    alert('La fecha de salida no puede ser anterior a la fecha de entrada.');
                    e.preventDefault();
                }
                 if (!fechaInicioInput.value || !fechaFinInput.value) {
                    alert('Por favor, seleccione ambas fechas.');
                    e.preventDefault();
                }
            });
        }
    }
    
    // Lógica para el formulario de selección de habitación
    const formSeleccionHabitacion = document.getElementById('formSeleccionHabitacion');
    if (formSeleccionHabitacion) {
        formSeleccionHabitacion.addEventListener('submit', function(e) {
            const selectedRadio = formSeleccionHabitacion.querySelector('input[name="habitacion_id"]:checked');
            if (!selectedRadio) {
                alert('Por favor, seleccione una habitación disponible para reservar.');
                e.preventDefault();
            }
            if (!fechaInicioInput.value || !fechaFinInput.value) {
                 alert('Las fechas de entrada y salida son requeridas para reservar.');
                 e.preventDefault();
            }
        });
    }

});