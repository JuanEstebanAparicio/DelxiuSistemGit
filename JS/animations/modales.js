function showModal(id) {
  document.getElementById(id).classList.remove('hidden')
}

function hideModal(id) {
  document.getElementById(id).classList.add('hidden')
}

function showAlertModal(message, type = 'error') {
  const modal = document.getElementById('alertModal');
  if (!modal) { alert(message); return; }
  const body = modal.querySelector('.modal-body');
  if (body) body.textContent = message;
  modal.dataset.type = type; // útil para estilos por tipo
  showModal('alertModal');
}

