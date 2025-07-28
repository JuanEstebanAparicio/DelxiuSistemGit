// archivo: JS/verificacion-codigo.js

document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('.code-digit');

  inputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
      const value = e.target.value;
      if (value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      } else if (value.length > 1) {
        const chars = value.split('');
        for (let i = 0; i < chars.length && index + i < inputs.length; i++) {
          inputs[index + i].value = chars[i];
        }
        const next = index + chars.length < inputs.length ? index + chars.length : inputs.length - 1;
        inputs[next].focus();
      }
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !input.value && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });

  const modalCodigo = document.getElementById('modalCodigo');
  const observer = new MutationObserver(() => {
    if (!modalCodigo.classList.contains('hidden')) {
      document.querySelectorAll('.code-digit').forEach(input => input.value = '');
      document.getElementById('mensajeCodigo').textContent = '';
      inputs[0].focus();
    }
  });

  observer.observe(modalCodigo, { attributes: true, attributeFilter: ['class'] });
});

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



