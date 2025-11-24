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
} else {
    console.log("No se pudo crear el ticket");
}

}


