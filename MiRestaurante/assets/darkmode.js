// darkmode.js

document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("darkModeToggle");
  
    // Cargar estado previo desde localStorage
    const darkMode = localStorage.getItem("darkMode");
    if (darkMode === "enabled") {
      document.body.classList.add("dark-mode");
      toggleBtn.innerText = "🌙";
    } else {
      toggleBtn.innerText = "☀️";
    }
  
    // Evento para cambiar tema
    toggleBtn.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
      const isDark = document.body.classList.contains("dark-mode");
      toggleBtn.innerText = isDark ? "🌙" : "☀️";
      localStorage.setItem("darkMode", isDark ? "enabled" : "disabled");
    });
  });
  