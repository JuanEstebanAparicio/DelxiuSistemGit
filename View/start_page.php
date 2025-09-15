 <!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurante</title>
    <link rel="stylesheet" href="../CSS/Inicio_pag.css" />
    <link rel ="stylesheet" href="../CSS/modales.css" />
  <link rel="stylesheet" href="../CSS/hidePassword.css" />
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
    <button class="btn2" onclick="showModal('modalRegister')">Registrarse</button>
  </div>



  <!-- VANTA + THREE -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  
  <!-- Efecto maquina de escribir -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
  
  <!-- Script de emogis-->
<script src="../JS/animations/Inicio_pag.js"></script>



<!-- Tu modal de login -->
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
       <button type="button" class="btn3" onclick="hideModal('modalLogin'); showModal('modalRecuperar')">
    ¿Olvidaste tu contraseña?
  </button>
    </div>
  </div>
</div>

<!-- Modal para Recuperar contraseña -->
<div id="modalRecuperar" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalRecuperar')">&times;</button>
    <h2>Recuperar Contraseña</h2>
    <form id="formRecuperar" style="display: flex; flex-direction: column; align-items: center;">
  <label>Ingrese su correo registrado</label>
  <input type="email" name="correo_recuperar" placeholder="Correo" required style="width: 100%; max-width: 300px;" />
  <button type="submit" class="modal-btn" style="margin-top: 10px;">Enviar código</button>
</form>


    <div style="margin-top: 10px; text-align: center;">
      <button type="button" class="btn3" onclick="hideModal('modalRecuperar'); showModal('modalLogin')">
        Volver a Iniciar Sesión
      </button>
    </div>
  </div>
</div>



<!-- Modal for Registration -->
<div id="modalRegister" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalRegister')">&times;</button>
    <h2>Iniciar Sesion</h2>
    <form id="registerForm" method="POST">
      
    Nombre de usuario
      <input type="text" name="user_name" id="user_name" placeholder="Username" required />
     
      Correo
      <input type="email" name="user_email" id="user_email" placeholder="Email" required />
     
      Nombre de restaurante
      <input type="text" name="user_restaurant" id="user_restaurant" placeholder="Restaurant Name" required />

      Clave o Contraseña
      <!–– Vista HTML ––>
<div class="password-wrapper">
  <input
    type="password"
    name ="user_password"
    class="password-field"
    placeholder="Clave secreta 🔐"
    required
  />
  <button
    type="button"
    class="toggle-password-btn"
    aria-label="Mostrar u ocultar contraseña"
  >
    👁️
  </button>
</div>
      Confirmar Contraseña
      <input type="password" id="confirm_password" placeholder="Confirm Password" required />
      <p id="errorPass" style="color: red; display: none;">❌ Passwords do not match</p>
      <button type="submit" class="modal-btn">Create Account</button>
    </form>
  </div>


<!-- Modal: Código de verificación -->
<div id="modalCodigo" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalCodigo')">&times;</button>
    <h3>Verifica tu correo</h3>
    <p>Introduce el código que enviamos a tu correo:</p>
    <span id="correo_mostrado_span" style="font-weight: bold; color: #009688;"></span>


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


<!-- Modal de alerta genérica -->
<div id="alertModal" class="modal hidden">
  <div class="modal-content">
    <div class="modal-body"></div>
    <button onclick="hideModal('alertModal')">Cerrar</button>
  </div>
</div>



<script src="../JS/animations/hidePassword.js"></script>
<script src="../JS/animations/modales.js"></script>
<script src="../JS/auth/register.js"></script>



</body>
</html>
