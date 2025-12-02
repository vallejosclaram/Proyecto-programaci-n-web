document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menuToggle');
    const closeBtn = document.getElementById('closeBtn');
    const formTicket = document.getElementById('formCrearTicket');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.add('open');
        body.classList.add('menu-open');
    });

    closeBtn.addEventListener('click', () => {
        sidebar.classList.remove('open');
        body.classList.remove('menu-open');
    });

    formTicket.addEventListener('submit', async (e) => {
        e.preventDefault();
        await crearTicket();
    });

    cargarTickets();
});

async function crearTicket() {
    const asunto = document.getElementById('asunto').value;
    const descripcion = document.getElementById('descripcion').value;

    const res = await fetch("crear-ticket.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ asunto, descripcion })
    });

    const data = await res.json();

    if (data.success) {
    
    const modalEl = document.getElementById('ticketExitoso');
    const modal = new bootstrap.Modal(modalEl); 
    modal.show(); 
    document.getElementById('formCrearTicket').reset();
    cargarTickets();

} else {
    console.log("No se pudo crear el ticket");
}

}



    


async function cargarTickets() {
    const contenedor = document.getElementById('misTickets');
    contenedor.innerHTML = "Cargando...";

    try {
        const res = await fetch('mis-tickets.php');
        const tickets = await res.json();

        if (tickets.error) {
            contenedor.innerHTML = `<p class="text-danger">${tickets.error}</p>`;
            return;
        }

        if (tickets.length === 0) {
            contenedor.innerHTML = "<p class='text-muted'>No has creado tickets aún.</p>";
            return;
        }

        let html = '';
        let currentTicket = null;

        tickets.forEach(ticket => {
            if (currentTicket !== ticket.id_ticket) {
                if (currentTicket !== null) html += '</div>'; // cierre de ticket anterior
                currentTicket = ticket.id_ticket;
                html += `
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white">
                            ${ticket.asunto} - <small>${ticket.fecha_creacion}</small>
                        </div>
                        <div class="card-body">
                            <p><strong>Descripción:</strong> ${ticket.ticket_descripcion}</p>
                            <h6>Respuestas:</h6>
                `;
            }

            if (ticket.respuesta) {
                html += `<p>${ticket.respuesta} <br><small class="text-muted">${ticket.fecha_respuesta}</small></p>`;
            } else {
                html += `<p class="text-muted">Aún no hay respuesta.</p>`;
            }
        });

        html += '</div>'; 
        contenedor.innerHTML = html;

    } catch (err) {
        console.error(err);
        contenedor.innerHTML = "<p class='text-danger'>Error al cargar los tickets.</p>";
    }
}


