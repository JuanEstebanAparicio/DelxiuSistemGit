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
      
      <div id="modalRecuperar" class="modal hidden">
        <div class="modal-content">
          <button class="close" onclick="hideModal('modalRecuperar')">&times;</button>
          <h2>Recuperar Contraseña</h2>
          <form style="display: flex; flex-direction: column; align-items: center;">
            <label>Ingrese su correo registrado</label>
            <input type="email" name="correo_recuperar" placeholder="Correo" required style="width: 100%; max-width: 300px;" />
      
            <button type="submit" class="modal-btn" style="margin-top: 10px;">Enviar código</button>
          </form>
      
          <div style="margin-top: 10px; text-align: center;">
            <button class="btn3" onclick="hideModal('modalRecuperar'); showModal('modalLogin')">Volver a Iniciar Sesión</button>
          </div>
        </div>
      </div>
  <script src="../JS/animations/modales.js"></script>
  

</body>  <!-- Modal para Recuperar contraseña -->
</html>