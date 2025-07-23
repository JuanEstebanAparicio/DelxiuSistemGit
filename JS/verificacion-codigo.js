// validacion-codigo.js

document.addEventListener('DOMContentLoaded', () => {
  const digits = document.querySelectorAll('.code-digit');
  const msg = document.getElementById('mensajeCodigo');

  digits.forEach((input, idx) => {
    input.addEventListener('input', () => {
      if (input.value.length === 1 && idx < digits.length - 1) {
        digits[idx + 1].focus();
      }
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && input.value === '' && idx > 0) {
        digits[idx - 1].focus();
      }
    });
  });

  window.verificarCodigo = function () {
    const codigo = Array.from(digits).map(i => i.value.trim()).join('');
    const correo = document.getElementById('correo_verificar').value;

    if (codigo.length !== 6 || !/^[0-9]{6}$/.test(codigo)) {
      msg.textContent = '❌ Código inválido';
      msg.style.color = 'red';
      return;
    }

    const formData = new FormData();
    formData.append('codigo', codigo);
    formData.append('correo', correo);

    fetch('../Controller/verificar_codigo.php', {
      method: 'POST',
      body: formData
    })
      .then(res => {
        if (!res.ok) {
          throw new Error(`HTTP ${res.status} - ${res.statusText}`);
        }
        return res.text();
      })
      .then(res => {
        console.log('Respuesta del servidor:', res);

        if (res.includes('ok')) {
          msg.textContent = '🎉 Usuario creado con éxito';
          msg.style.color = 'green';
        } else if (res.includes('codigo_invalido')) {
          msg.textContent = '❌ Código incorrecto';
          msg.style.color = 'red';
        } else if (res.includes('codigo_expirado')) {
          msg.textContent = '❌ Código expirado';
          msg.style.color = 'red';
        } else if (res.includes('error_insert')) {
          msg.textContent = '❌ Error al crear el usuario';
          msg.style.color = 'red';
        } else {
          msg.textContent = '❌ Error desconocido';
          msg.style.color = 'red';
        }
      })
      .catch(error => {
        console.error('Error en fetch:', error);
        msg.textContent = '❌ Error de conexión o servidor';
        msg.style.color = 'red';
      });
  };
});

