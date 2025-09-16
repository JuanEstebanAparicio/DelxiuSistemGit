<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Restaurante</title>
  <!-- Tus estilos -->
  <link rel="stylesheet" href="../CSS/Inicio_pag.css" />
  <link rel="stylesheet" href="../CSS/modales.css" />
  <link rel="stylesheet" href="../CSS/hidePassword.css" />
</head>
<body>
  <div id="vanta-bg"></div>
  <canvas id="emoji-canvas"></canvas>
  <div class="overlay"></div>

  <div class="content">
    <h1 class="animated-title">DELIXIUSYSTEM</h1>
    <p><span id="typed-text">Trae un servicio confiable</span></p>
    <button class="btn" onclick="showModal('modalLogin')">Iniciar Sesión</button>
    <br/>
    <button class="btn2" onclick="showModal('modalRegister')">Registrarse</button>
  </div>

  <!-- VANTA + THREE -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  <!-- Typed.js -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
  <!-- Emogis -->
  <script src="../JS/animations/Inicio_pag.js"></script>

  <!-- Modal: Login -->
  <div id="modalLogin" class="modal hidden">
    <div class="modal-content">
      <button class="close" onclick="hideModal('modalLogin')">&times;</button>
      <h2>Iniciar Sesión</h2>
      <form id="loginForm" class="form-vcenter">
        <label>Correo</label>
        <input type="email" id="login_email" placeholder="Correo" required />
        <label>Contraseña</label>
        <input type="password" id="login_pass" placeholder="Contraseña" required />
        <div class="row-center">
          <input type="checkbox" id="recordarme" />
          <label for="recordarme">Recordarme</label>
        </div>
        <button type="submit" class="modal-btn">Entrar</button>
      </form>
      <div class="text-center">
        <button class="btn3" onclick="hideModal('modalLogin'); showModal('modalRecuperar')">
          ¿Olvidaste tu contraseña?
        </button>
      </div>
      <p id="loginMsg" class="message"></p>
      <p id="loginErr" class="error"></p>
    </div>
  </div>

  <!-- Modal: Registro -->
  <div id="modalRegister" class="modal hidden">
    <div class="modal-content">
      <button class="close" onclick="hideModal('modalRegister')">&times;</button>
      <h2>Crear Cuenta</h2>
      <form id="registerForm" class="form-column">
        <input type="text"     id="user_name"       placeholder="Nombre de usuario" required />
        <input type="email"    id="user_email"      placeholder="Correo" required />
        <input type="text"     id="user_restaurant" placeholder="Nombre restaurante" required />
        <div class="password-wrapper">
          <input type="password" id="user_password" class="password-field" placeholder="Contraseña" required />
          <button type="button"   class="toggle-password-btn" aria-label="Mostrar u ocultar">
            👁️
          </button>
        </div>
        <input type="password" id="confirm_password" placeholder="Confirmar contraseña" required />
        <p id="errorPass" class="error" style="display: none;">❌ Las contraseñas no coinciden</p>
        <button type="submit" class="modal-btn">Registrarse</button>
      </form>
      <p id="registerMsg" class="message"></p>
      <p id="registerErr" class="error"></p>
    </div>
  </div>

 <!-- Modal: Ingresar código OTP -->
<div id="modalCodigo" class="modal hidden">
  <div class="modal-content" style="max-width: 400px; text-align: center;">
    <!-- Botón cerrar -->
    <button class="close" onclick="hideModal('modalCodigo')">&times;</button>

    <!-- Título -->
    <h3 style="margin-bottom: 10px;">Verificación por código</h3>

    <!-- Texto de instrucción -->
    <p style="margin-bottom: 5px;">
      Hemos enviado un código de <strong>6 dígitos</strong> al correo:
    </p>
    <p id="correo_mostrado_span" class="highlight" style="margin-bottom: 15px;"></p>

    <!-- Campo de código OTP -->
    <input
      type="text"
      id="verification_code_input"
      maxlength="6"
      placeholder="••••••"
      oninput="this.value=this.value.replace(/[^0-9]/g,'')"
      style="
        font-size: 1.5rem;
        text-align: center;
        letter-spacing: 8px;
        width: 180px;
        padding: 8px;
        margin-bottom: 15px;
      "
    />

    <!-- Botón de verificación -->
    <button id="btn-verificar-correo" class="modal-btn" style="width: 100%; margin-bottom: 10px;">
      Verificar código
    </button>

    <!-- Botón para reenviar código -->
    <button type="button" id="btn-reenviar-codigo" class="btn3" style="margin-bottom: 10px;">
      Reenviar código
    </button>

    <!-- Botón para volver -->
    <button onclick="hideModal('modalCodigo'); showModal('modalLogin')" class="btn3">
      Volver a Iniciar Sesión
    </button>

    <!-- Mensajes de estado -->
    <p id="mensajeCodigo" class="message" style="margin-top: 10px;"></p>
  </div>
</div>

  <!-- Modal: FormularioRecuperar contraseña (solicitar email) -->
  <div id="modalRecuperar" class="modal hidden">
    <div class="modal-content">
      <button class="close" onclick="hideModal('modalRecuperar')">&times;</button>
      <h2>Recuperar Contraseña</h2>
      <form id="formRecuperar" class="form-column">
        <label>Correo registrado</label>
        <input type="email" id="recover_email" placeholder="Correo" required />
        <button type="submit" class="modal-btn">Enviar enlace</button>
      </form>
      <p id="recoverMsg" class="message"></p>
      <p id="recoverErr" class="error"></p>
      <div class="text-center">
        <button class="btn3" onclick="hideModal('modalRecuperar'); showModal('modalLogin')">
          Volver a Iniciar Sesión
        </button>
      </div>
    </div>
  </div>

  <!-- Modal: Reestablecer contraseña (al llegar por link) -->
  <div id="modalResetPassword" class="modal hidden">
    <div class="modal-content">
      <button class="close" onclick="hideModal('modalResetPassword')">&times;</button>
      <h2>Cambiar contraseña</h2>
      <form id="resetForm" class="form-column">
        <input type="password" id="new-password" placeholder="Nueva contraseña" required />
        <button type="submit" class="modal-btn">Actualizar</button>
      </form>
      <p id="resetMsg" class="message"></p>
      <p id="resetErr" class="error"></p>
    </div>
  </div>

  <!-- Modal genérico de alertas -->
  <div id="alertModal" class="modal hidden">
    <div class="modal-content">
      <div class="modal-body"></div>
      <button onclick="hideModal('alertModal')">Cerrar</button>
    </div>
  </div>

  <!-- Scripts de UI -->
  <script src="../JS/animations/hidePassword.js"></script>
  <script src="../JS/animations/modales.js"></script>

  <!-- Único módulo: tu lógica de Auth -->
  <script type="module" src="../JS/auth.js"></script>


</body>
</html>