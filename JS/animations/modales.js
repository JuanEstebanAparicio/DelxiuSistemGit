function showModal(id) {
  document.getElementById(id).classList.remove('hidden')
}

function hideModal(id) {
  document.getElementById(id).classList.add('hidden')
}

function mostrarModalVerificacion(mensaje) {
    // mensaje podría ser el correo o algún texto extra que mandes desde PHP
    const modal = document.getElementById('modalCodigo');

    // si quieres mostrar el correo o info
    if (mensaje) {
        document.getElementById('correo_mostrado_span').textContent = mensaje;
    }


}

    function mostrarModalAlerta(mensaje) {
    const modal = document.getElementById('alertModal');
    modal.querySelector('.modal-body').textContent = mensaje;
    showModal('alertModal');
  }

// 🚀 Aquí enganchamos el fetch al formulario del modal de recuperación
document.getElementById('formRecuperar').addEventListener('submit', function(e) {
  e.preventDefault(); // Evita que se recargue la página

  const email = this.correo_recuperar.value.trim();

  fetch('../Controller/user_management/UserController.php', { // ajusta la ruta según tu estructura
    method: 'POST',
    body: new URLSearchParams({
      action: 'recover',
      user_email: email
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.ok) {
      mostrarModalAlerta(data.message);
      hideModal('modalRecuperar');
    } else {
      mostrarModalAlerta(data.error || 'Error al procesar la solicitud');
    }
  })
  .catch(err => {
    console.error('Error en la petición:', err);
    mostrarModalAlerta('Error de conexión con el servidor');
  });
});


