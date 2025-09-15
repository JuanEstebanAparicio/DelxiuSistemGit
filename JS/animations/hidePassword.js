// Lógica modular, atada a todas las instancias .toggle-password-btn
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".toggle-password-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const input = btn.previousElementSibling;
      const isHidden = input.type === "password";
      input.type = isHidden ? "text" : "password";
      btn.textContent = isHidden ? "🕶️" : "👁️";
      btn.classList.toggle("reveal", isHidden);
    });
  });
});
