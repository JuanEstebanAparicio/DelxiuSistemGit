 <!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurante</title>
    <link rel="stylesheet" href="../CSS/Inicio_pag.css" />
    <link rel ="stylesheet" href="../CSS/modales.css" />
  <link rel="stylesheet" href="../CSS/password-wrapper.css" />
</head>
<body>
  <div id="vanta-bg"></div>
  <canvas id="emoji-canvas"></canvas>
  <div class="overlay"></div>
  <div class="content">
    <h1 class="animated-title">DELIXIUSYSTEM</h1>
    <p><span id="typed-text">Trae un servicio confiable</span></p>
    <button class="btn" onclick="showModal('modalLogin')">Iniciar Sesion</button>
    <br>
    <button class="btn2" onclick="showModal('modalRegistro')">Registrarse</button>
  </div>



  <!-- VANTA + THREE -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  
  <!-- Efecto maquina de escribir -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
  
  <!-- Script de emogis-->
<script src="../JS/animations/Inicio_pag.js"></script>



<!-- Modal para Iniciar sesión -->
<div id="modalLogin" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalLogin')">&times;</button>
    <h2>Iniciar Sesión</h2>
    <form style="display: flex; flex-direction: column; align-items: center;">
      <label>Ingrese su correo</label>
      <input type="email" name="correo" placeholder="Correo" required style="width: 100%; max-width: 300px;" />

      <label>Ingrese su contraseña</label>
      <input type="password" name="clave" placeholder="Contraseña" required style="width: 100%; max-width: 300px;" />

      <div style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 10px;">
        <input type="checkbox" id="recordarme" name="recordarme" />
        <label for="recordarme" style="margin: 0;">Recordarme</label>
      </div>

      <button type="submit" class="modal-btn" style="margin-top: 10px;">Entrar</button>
    </form>

    <div style="margin-top: 10px; text-align: center;">
      <a href="#" onclick="showModal('modalRecuperar')">¿Olvidaste tu contraseña?</a>
    </div>
  </div>
</div>


<!-- Modal para Registrarse -->
<div id="modalRegistro" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalRegistro')">&times;</button>
    <h2>Registrarse</h2>
    <form id="registroForm">
      Nombre de usuario
  <input type="text" name="nombre_usuario" placeholder="Usuario" id="nombre_usuario" required />
     Correo electrónico
  <input type="email" name="correo" id="correo" placeholder="Correo" required />
    Nombre de restaurante
  <input type="text" id="nombre_restaurante" name="nombre_restaurante" placeholder="Nombre del restaurante" required />
  Contraseña
  <div class="password-wrapper">
  <input type="password" id="clave" name="clave" placeholder="Contraseña" required />
  <button type="button" class="toggle-pass" onclick="togglePass('clave', this)">
    <!-- SVG: ojo abierto -->
    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
    </svg>
  </button>
</div>
  Confirmar contraseña
<div class="password-wrapper">
  <input type="password" id="confirmar_clave" placeholder="Confirmar Contraseña" required />
  <button type="button" class="toggle-pass" onclick="togglePass('confirmar_clave', this)">
    <!-- SVG: ojo abierto -->
    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
    </svg>
  </button>
</div>

  <p id="errorPass" style="color: red; display: none;">❌ Las contraseñas no coinciden</p>

<button id="btn-crear-cuenta" type="button" class="modal-btn">Crear Cuenta</button>
</form>
  </div>
</div>

<!-- Modal: Código de verificación -->
<div id="modalCodigo" class="modal hidden">
  <div class="modal-content">
    <h3>Verifica tu correo</h3>
    <p>Introduce el código que enviamos a tu correo:</p>
    <span id="correo_mostrado_span" style="font-weight: bold; color: #009688;"></span>

    <!-- 🔴 ESTO ES LO QUE FALTABA -->
    <input type="hidden" id="correo_verificar" />

    <div class="code-input-container">
      <input type="text" maxlength="1" class="code-digit" />
      <input type="text" maxlength="1" class="code-digit" />
      <input type="text" maxlength="1" class="code-digit" />
      <input type="text" maxlength="1" class="code-digit" />
      <input type="text" maxlength="1" class="code-digit" />
      <input type="text" maxlength="1" class="code-digit" />
    </div>

    <button onclick="verificarCodigo()">Verificar Código</button>
    <button onclick="volverARegistro()" style="margin-top: 10px; background-color: #ccc; color: #333;">
  ⬅️ Volver
</button>

    <p id="mensajeCodigo" style="margin-top:10px;"></p>
  </div>
</div>

<!-- Modal Recuperar Contraseña -->
<div id="modalRecuperar" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalRecuperar')">&times;</button>
    <h2>Recuperar Contraseña</h2>
    <form id="formRecuperar">
      <label>Correo registrado</label>
      <input type="email" id="recuperarCorreo" name="correo" placeholder="Ingresa tu correo" required />
      <button type="submit" class="modal-btn">Enviar enlace de recuperación</button>
    </form>
    <div id="recuperarMensaje" style="margin-top: 10px; color: green; display: none;"></div>
  </div>
</div>
  
<!-- Al final de tu HTML, justo antes del </body> -->
 <script src="../JS/auth/login.js"></script>
<script src="../JS/auth/registro.js"></script>
  <script src="../JS/animations/modales.js"></script>
  <script src="../JS/auth/verificacion-codigo.js"></script>
  <script src="../JS/auth/forgot-password.js" defer></script>

<script>
  // Cuando se abra el modal de código, consultar y mostrar el correo
  const modalCodigo = document.getElementById('modalCodigo');

  const observer = new MutationObserver(() => {
    if (!modalCodigo.classList.contains('hidden')) {
      fetch('../Controller/ObtenerCorreoTemp.php')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'ok') {
            document.getElementById('correo_mostrado_span').textContent = data.correo;
          }
        });
    }
  });

  observer.observe(modalCodigo, {
    attributes: true,
    attributeFilter: ['class']
  });
</script>

<script>
  function volverARegistro() {
    hideModal('modalCodigo');
    showModal('modalRegistro');
  }
</script>


</body>
</html>
