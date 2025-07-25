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
<script src="../JS/Inicio_pag.js"></script>



<!-- Modal para Iniciar sesion -->
<div id="modalLogin" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalLogin')">&times;</button>
    <h2>Iniciar Sesión</h2>
    <form>
    <Label>Ingrese su correo</Label>    
    <input type="email" placeholder="Correo" required />
    <Label>Ingrese su contraseña</Label>
    <input type="password" placeholder="Contraseña" required />
      <button type="submit" class="modal-btn">Entrar</button>
    </form>
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
    <p id="mensajeCodigo" style="margin-top:10px;"></p>
  </div>
</div>


  
<!-- Al final de tu HTML, justo antes del </body> -->
 <script src="../JS/login.js"></script>
<script src="../JS/registro.js"></script>
  <script src="../JS/modales.js"></script>
  <script src="../JS/verificacion-codigo.js"></script>
</body>
</html>
