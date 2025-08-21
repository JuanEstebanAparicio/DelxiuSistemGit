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
    showModal('alertModal');}