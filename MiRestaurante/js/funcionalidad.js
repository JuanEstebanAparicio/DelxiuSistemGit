function toggleMenu() {
    const menu = document.getElementById('configMenu');
    menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
  }
  
  function toggleDarkMode() {
    const body = document.body;
    const isDark = body.classList.toggle("modo-oscuro");
    if (isDark) {
      body.style.background = "#2c3e50";
      body.style.color = "#ecf0f1";
    } else {
      body.style.background = "linear-gradient(to right, #ffe4e6, #ffe5b4)";
      body.style.color = "#333";
    }
  }
  
  