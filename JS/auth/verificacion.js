// ../JS/auth/verificacion.js
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('verifyForm');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const url = form.getAttribute('action');

    fetch(url, {
      method: 'POST',
      body: new FormData(form)
    })
      .then(async (res) => {
        const isJson = res.headers.get('content-type')?.includes('application/json');
        if (!isJson) throw new Error('Respuesta no JSON del servidor.');
        return res.json();
      })
      .then((data) => {
        if (data.status === 'success') {
          hideModal('modalCodigo');
          showAlertModal('Cuenta verificada. ¡Bienvenido!', 'success');
          // aquí podrías redirigir: window.location.href = '/dashboard';
        } else {
          showAlertModal(data.message || 'Código inválido.', 'error');
        }
      })
      .catch((err) => {
        console.error('Error en la petición:', err);
        showAlertModal('Ocurrió un error inesperado, intenta más tarde.', 'error');
      });
  });
});