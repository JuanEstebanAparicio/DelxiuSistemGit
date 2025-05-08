document.addEventListener("DOMContentLoaded", () => {
  const menu = document.getElementById('configMenu');
  const botonEngranaje = document.querySelector('.engranaje');
  const cambiarTema = document.getElementById('cambiarTema');

  // Restaurar modo oscuro si estaba activado
  const temaGuardado = localStorage.getItem("tema");
  if (temaGuardado === "oscuro") {
    document.body.classList.add("modo-oscuro");
    document.body.style.background = "#2c3e50";
    document.body.style.color = "#ecf0f1";
  }

  // Mostrar u ocultar el menú
  botonEngranaje.addEventListener('click', (e) => {
    e.stopPropagation(); // evita que el clic se propague al body
    menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
  });

  // Cerrar menú si se hace clic fuera
  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && !botonEngranaje.contains(e.target)) {
      menu.style.display = 'none';
    }
  });

  // Cambiar tema
  cambiarTema.addEventListener("click", (e) => {
    e.preventDefault();
    const body = document.body;
    const isDark = body.classList.toggle("modo-oscuro");

    if (isDark) {
      body.style.background = "#2c3e50";
      body.style.color = "#ecf0f1";
      localStorage.setItem("tema", "oscuro");
    } else {
      body.style.background = "linear-gradient(to right, #fff4e6, #ffe5b4)";
      body.style.color = "#333";
      localStorage.setItem("tema", "claro");
    }
  });
});


