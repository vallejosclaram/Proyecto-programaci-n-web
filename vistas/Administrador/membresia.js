document.addEventListener("DOMContentLoaded", () => {
  let idSolicitud = null;
  let accion = null;

  const modalEl = document.getElementById("confirmModal");
  const confirmBtn = document.getElementById("confirmBtn");
  const confirmMessageEl = document.getElementById("confirmMessage");
  const alertsEl = document.getElementById("alerts");

  if (!modalEl || !confirmBtn || !confirmMessageEl) {
    console.error("Modal de confirmación no encontrado en el HTML.");
    return;
  }

  const modal = new bootstrap.Modal(modalEl);

  // Botones aceptar/rechazar
  document.querySelectorAll(".btn-accion").forEach((btn) => {
    btn.addEventListener("click", () => {
      idSolicitud = btn.dataset.id;
      accion = btn.dataset.accion;

      if (!idSolicitud || !accion) {
        showAlert("Acción inválida: faltan datos.", "danger");
        return;
      }

      const msg =
        accion === "aceptar"
          ? "¿Seguro que deseas aceptar esta solicitud de membresía? Se actualizará la membresía del jugador."
          : "¿Seguro que deseas rechazar esta solicitud de membresía? Se eliminará la solicitud.";

      confirmMessageEl.textContent = msg;
      modal.show();
    });
  });

  // Confirmar acción
  confirmBtn.addEventListener("click", () => {
    if (!idSolicitud || !accion) {
      showAlert("No se pudo procesar: faltan datos.", "danger");
      return;
    }

    modal.hide();

    fetch("membresia-process.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body:
        "id_solicitud=" +
        encodeURIComponent(idSolicitud) +
        "&accion=" +
        encodeURIComponent(accion),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data && data.success) {
          showAlert("Solicitud procesada correctamente.", "success");
          setTimeout(() => location.reload(), 1500);
        } else {
          const msg = data && data.error ? data.error : "Error desconocido";
          showAlert("Error: " + msg, "danger");
        }
      })
      .catch((err) => {
        showAlert("Error de conexión: " + err, "danger");
      });
  });

  function showAlert(text, type = "info") {
    if (!alertsEl) return;
    alertsEl.innerHTML = "";
    const div = document.createElement("div");
    div.className = `alert alert-${type} mt-3`;
    div.textContent = text;
    alertsEl.appendChild(div);
  }
});
