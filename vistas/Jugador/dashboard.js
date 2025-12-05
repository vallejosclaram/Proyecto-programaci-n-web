function h(tag, attrs = {}, ...children) {
    const el = document.createElement(tag);
    for (let [k, v] of Object.entries(attrs)) {
        if (k === "class") el.className = v;
        else if (k === "html") el.innerHTML = v;
        else el.setAttribute(k, v);
    }
    for (let c of children) {
        if (typeof c === "string") el.appendChild(document.createTextNode(c));
        else if (c) el.appendChild(c);
    }
    return el;
}

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menuToggle');
  const closeBtn = document.getElementById('closeBtn');
  const body = document.body;

  menuToggle.addEventListener('click', () => {
    sidebar.classList.add('open');
    body.classList.add('menu-open');
  });
  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    body.classList.remove('menu-open');
  });

  cargarJugadoresDestacados();
    cargarJuegos();
    cargarTorneosHoy();
    initScrollButtons();
    initSearch();
});



function initSearch() {
    const input = document.getElementById("globalSearchInput");
    const scope = document.getElementById("searchScope");
    const clearBtn = document.getElementById("clearSearchBtn");

    input.addEventListener("input", () => {
        const q = input.value.trim();
        if (q.length === 0) return;

        fetch(`busqueda.php?q=${encodeURIComponent(q)}&scope=${scope.value}`)
            .then(res => res.json())
            .then(showSearchResults);
    });

    clearBtn.addEventListener("click", () => {
        input.value = "";
        document.getElementById("searchResults")?.remove();
    });
}

function showSearchResults(results) {
    let old = document.getElementById("searchResults");
    if (old) old.remove();

    const box = h("div", { id: "searchResults", class: "search-results-box" });

    if (results.length === 0) {
        box.appendChild(h("div", { class: "empty" }, "No se encontraron resultados"));
    } else {
        results.forEach(r => {
            const item = h("div", { class: "result-item" });
            item.innerHTML = `
                <strong>${r.titulo}</strong>
                <span class="badge bg-secondary">${r.tipo}</span>
            `;
            box.appendChild(item);
        });
    }

    document.querySelector(".banner-filters-floating").appendChild(box);
}



function cargarJugadoresDestacados() {
    fetch("destacado.php")
        .then(res => res.json())
        .then(data => renderJugadores(data))
        .catch(console.error);
}

function renderJugadores(players) {
    const cont = document.getElementById("playersList");
    cont.innerHTML = "";

    players.forEach(p => {
        const card = h("div", { class: "card player-card p-2", style: "min-width:220px; background:#1c1c1c; color:white;" });
        card.innerHTML = `
            <h5>${p.nombre} ${p.apellido}</h5>
            <p class="small">🌎 ${p.pais}</p>
            <p class="small">🔥 Puntaje: <strong>${p.puntaje}</strong></p>
            <button class="btn btn-sm btn-primary mt-auto" onclick="openPlayer(${p.usuario_id})">Ver perfil</button>
        `;
        cont.appendChild(card);
    });
}

function openPlayer(id) {
    window.location.href = `perfil/verjugador.php?id=${id}`;
}



function cargarJuegos() {
    fetch("juegos.php")
        .then(res => res.json())
        .then(data => renderGames(data))
        .catch(console.error);
}

function renderGames(games) {
    const cont = document.getElementById("gamesList");
    cont.innerHTML = "";

    games.forEach(g => {
        const card = h("div", { 
            class: "card game-card p-2 me-3", 
            style: "min-width:200px; background:#121212; color:white; cursor:pointer;"
        });

        card.innerHTML = `
            <img src="${g.imagen}" class="card-img-top" alt="${g.nombre}" style="height:150px; object-fit:cover;">
            <div class="card-body text-center">
                <h5 class="card-title">${g.nombre}</h5>
            </div>
        `;

        // Abrir modal al click
        card.addEventListener("click", () => {
            showGameInfo(g.nombre, g.descripcion);
        });

        cont.appendChild(card);
    });
}


function showGameInfo(title, text) {
    document.getElementById("modalTitle").textContent = title;
    document.getElementById("modalBody").textContent = text;
    new bootstrap.Modal(document.getElementById("modalInfo")).show();
}



function cargarTorneosHoy() {
    fetch("torneos-hoy.php")
        .then(res => res.json())
        .then(data => renderTournaments(data));
}

function renderTournaments(torneos) {
    const cont = document.getElementById("tournamentsList");
    cont.innerHTML = "";

    if (torneos.length === 0) {
        cont.appendChild(h("div", { class: "text-muted" }, "Hoy no hay torneos activos."));
        return;
    }

    torneos.forEach(t => {
        const card = h("div", { class: "card tournament-card p-3", style: "min-width:250px; background:#1f1f1f; color:white;" });
        card.innerHTML = `
            <h5>${t.nombre}</h5>
            <p>${t.descripcion}</p>
            <p class="small">⏳ ${t.fecha_inicio} → ${t.fecha_fin}</p>
            <button class="btn btn-outline-light btn-sm" onclick="openTournament(${t.id_torneo})">Ver torneo</button>
        `;
        cont.appendChild(card);
    });
}

function openTournament(id) {
    window.location.href = `torneo/procvess-torneo.php?id=${id}`;
}



function initScrollButtons() {
    document.querySelectorAll(".scroll-controls button").forEach(btn => {
        btn.addEventListener("click", () => {
            const target = document.getElementById(btn.dataset.target);
            const dir = parseInt(btn.dataset.dir);
            target.scrollLeft += dir * 350;
        });
    });
}
