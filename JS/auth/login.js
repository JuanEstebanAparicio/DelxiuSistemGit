// archivo: JS/login.js

document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.querySelector('#modalLogin form');

  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const correo = loginForm.querySelector('input[type="email"]').value.trim();
    const clave = loginForm.querySelector('input[type="password"]').value.trim();

    const formData = new FormData();
    formData.append('correo', correo);
    formData.append('clave', clave);

    try {
      const response = await fetch('../Controller/LoginController.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.status === 'ok') {
        alert('✅ Bienvenido');
        hideModal('modalLogin');
        window.location.href = '../View/dashboard.php'; // ajusta si usas otra página de inicio
      } else {
        alert(result.msg || '❌ Error al iniciar sesión.');
      }
    } catch (err) {
      alert('❌ Error del servidor.');
      console.error(err);
    }
  });
});
