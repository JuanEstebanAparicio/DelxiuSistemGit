// archivo: JS/verificacion-codigo.js
async function verificarCodigo() {
  const inputs = document.querySelectorAll('.code-digit');
  const codigo = Array.from(inputs).map(i => i.value).join('').trim();
  const correo = document.getElementById('correo_verificar').value.trim();
  const mensaje = document.getElementById('mensajeCodigo');

  if (codigo.length !== 6) {
    mensaje.textContent = 'Código incompleto';
    return;
  }

  const formData = new FormData();
  formData.append('correo', correo);
  formData.append('codigo', codigo);

  const response = await fetch('../Controller/VerificacionController.php', {
    method: 'POST',
    body: formData
  });
  const result = await response.json();

  if (result.status === 'verificado') {
    mensaje.style.color = 'green';
    mensaje.textContent = '✅ Código verificado. Cuenta creada.';
    setTimeout(() => hideModal('modalCodigo'), 2000);
  } else {
    mensaje.style.color = 'red';
    mensaje.textContent = result.msg || '❌ Código incorrecto.';
  }
}


