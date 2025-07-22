 <!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurante</title>
    <link rel="stylesheet" href="../CSS/Inicio_pag.css" />
    <link rel ="stylesheet" href="../CSS/modales.css" />
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

  <script src="../JS/modales.js"></script>

  <!-- VANTA + THREE -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  
  <!-- Efecto maquina de escribir -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
  
  <!-- Scrip de emogis-->
<script src="../JS/Inicio_pag.js"></script>

<!-- Modal para Iniciar sesion -->
<div id="modalLogin" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalLogin')">&times;</button>
    <h2>Iniciar Sesión</h2>
    <form>
    <Label>Ingrese su Usuario</Label>    
    <input type="text" placeholder="Usuario" required />
    <input type="password" placeholder="Contraseña" required />
      <button type="submit" class="modal-btn">Entrar</button>
    </form>
  </div>
</div>

<div id="modalRegistro" class="modal hidden">
  <div class="modal-content">
    <button class="close" onclick="hideModal('modalRegistro')">&times;</button>
    <h2>Registrarse</h2>
    <form>
    <Label>Ingrese su Usuario</Label>    
    <input type="text" placeholder="Usuario" required />
    <input type="password" placeholder="Contraseña" required />
      <button type="submit" class="modal-btn">Entrar</button>
    </form>
  </div>
</div>

</body>
</html>
