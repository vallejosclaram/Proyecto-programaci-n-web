document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("adminLoginForm");
  const passInput = document.getElementById("adminPass");
  const showPass = document.getElementById("showAdminPass");

  showPass.addEventListener("change", () => {
    passInput.type = showPass.checked ? "text" : "password";
  });

  form.addEventListener("submit", (e) => {
    let valid = true;

    const userInput = document.getElementById("adminUser");
    if (userInput.value.trim() === "") {
      userInput.classList.add("is-invalid");
      valid = false;
    } else {
      userInput.classList.remove("is-invalid");
    }

    if (passInput.value.trim() === "") {
      passInput.classList.add("is-invalid");
      valid = false;
    } else {
      passInput.classList.remove("is-invalid");
    }

    if (!valid) {
      e.preventDefault();
    }
  });
});
