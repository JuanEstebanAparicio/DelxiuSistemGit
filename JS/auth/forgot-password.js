document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formRecuperar');
  const correoInput = document.getElementById('recuperarCorreo');
  const mensaje = document.getElementById('recuperarMensaje');

  if (!form) return; // Evita errores si no está el modal en la vista actual

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const correo = correoInput.value;

    try {
      const res = await fetch('../Controller/Login/RecuperarPasswordController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ correo })
      });

      const data = await res.text();
      mensaje.textContent = data;
      mensaje.style.color = 'green';
      mensaje.style.display = 'block';
    } catch (err) {
      mensaje.textContent = 'Error al enviar solicitud. Intenta de nuevo.';
      mensaje.style.color = 'red';
      mensaje.style.display = 'block';
    }
  });
});
