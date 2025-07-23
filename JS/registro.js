function togglePass(id, btn) {
  const input = document.getElementById(id)
  const isVisible = input.type === 'text'
  input.type = isVisible ? 'password' : 'text'

  // Cambiar el SVG dinámicamente
  btn.innerHTML = isVisible
    ? `
    <!-- SVG: ojo abierto -->
    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
    </svg>
    `
    : `
    <!-- SVG: ojo cerrado -->
    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.228-3.592m3.495-2.494A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.035 10.035 0 01-4.222 5.17M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
    </svg>
    `
}

document.addEventListener('DOMContentLoaded', () => {
  const btnCrear = document.getElementById('btn-crear-cuenta');

  btnCrear.addEventListener('click', function () {
    const nombre = document.getElementById('nombre_usuario').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const clave = document.getElementById('clave').value;
    const confirmar = document.getElementById('confirmar_clave').value;
    const restaurante = document.getElementById('nombre_restaurante').value.trim();
    const errorPass = document.getElementById('errorPass');

    // Validar campos obligatorios
    if (!nombre || !correo || !clave || !confirmar || !restaurante) {
      alert('Todos los campos son obligatorios');
      return;
    }

    // Validar contraseña y confirmación
    if (clave !== confirmar) {
      errorPass.style.display = 'block';
      return;
    } else {
      errorPass.style.display = 'none';
    }

    // Preparar datos para enviar
    const formData = new FormData();
    formData.append('nombre_usuario', nombre);
    formData.append('correo', correo);
    formData.append('clave', clave);
    formData.append('nombre_restaurante', restaurante);

    fetch('../Controller/registro_temp.php', {

      method: 'POST',
      body: formData
    })
      .then(res => res.text())
      .then(res => {
        
  if (res.includes('enviado')) {
  document.getElementById('correo_verificar').value = correo;  // 🔴 CRUCIAL
  hideModal('modalRegistro');
  showModal('modalCodigo');
}else {
          alert('Error al registrar: ' + res);
        }
      })
      .catch(err => {
        alert('Error de conexión: ' + err);
      });
  });
});
