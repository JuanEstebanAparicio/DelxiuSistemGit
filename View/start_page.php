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

  <!-- Modal: Confirma tu correo -->
  <div id="modalCodigo" class="modal hidden">
    <div class="modal-content">
      <button class="close" onclick="hideModal('modalCodigo')">&times;</button>
      <h3>Verifica tu correo</h3>
      <p>
        Hemos enviado un enlace de confirmación a:
        <span id="correo_mostrado_span" class="highlight"></span>
      </p>
      <button id="btn-verificar-correo" class="modal-btn">Ya confirmé</button>
      <button onclick="hideModal('modalCodigo'); showModal('modalLogin')" class="btn3">
        Volver a Iniciar Sesión
      </button>
      <p id="mensajeCodigo" class="message"></p>
    </div>
  </div>

  <!-- Modal: Recuperar contraseña (solicitar email) -->
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

  <!-- Lógica de Auth con Supabase -->
  <script type="module">
// auth.js
import { supabase } from '../JS/config.js';

    // 2. Registra al usuario
    const registerForm = document.getElementById('registerForm');
    registerForm.addEventListener('submit', async e => {
      e.preventDefault();
      const name        = document.getElementById('user_name').value.trim();
      const email       = document.getElementById('user_email').value.trim();
      const restaurant  = document.getElementById('user_restaurant').value.trim();
      const password    = document.getElementById('user_password').value;
      const confirmPass = document.getElementById('confirm_password').value;
      document.getElementById('registerErr').textContent = '';
      document.getElementById('registerMsg').textContent = '';

      if (password !== confirmPass) {
        document.getElementById('errorPass').style.display = 'block';
        return;
      }
      document.getElementById('errorPass').style.display = 'none';

      const { data, error } = await supabase.auth.signUp(
        { email, password },
        {
          data: { name, restaurant },
          emailRedirectTo: window.location.origin + window.location.pathname
        }
      );
      if (error) {
        document.getElementById('registerErr').textContent = error.message;
      } else {
        // Mostrar modal de confirmación
        document.getElementById('correo_mostrado_span').textContent = email;
        showModal('modalCodigo');
      }
    });

    // 3. Verificar si el usuario ha confirmado su email
    document.getElementById('btn-verificar-correo').addEventListener('click', async () => {
      const { data: { user }, error } = await supabase.auth.getUser();
      if (error) {
        document.getElementById('mensajeCodigo').textContent = error.message;
        return;
      }
      if (user && user.email_confirmed_at) {
        document.getElementById('mensajeCodigo').textContent = 
          '✅ Correo confirmado. Ya puedes iniciar sesión.';
      } else {
        document.getElementById('mensajeCodigo').textContent = 
          '❌ Aún no has confirmado tu correo.';
      }
    });

    // 4. Login
    document.getElementById('loginForm').addEventListener('submit', async e => {
      e.preventDefault();
      const email    = document.getElementById('login_email').value.trim();
      const password = document.getElementById('login_pass').value;
      document.getElementById('loginErr').textContent = '';
      document.getElementById('loginMsg').textContent = '';

      const { data, error } = await supabase.auth.signInWithPassword({ email, password });
      if (error) {
        document.getElementById('loginErr').textContent = error.message;
      } else {
        document.getElementById('loginMsg').textContent = `¡Bienvenido ${data.user.email}!`;
        // window.location.href = '/dashboard.php';
      }
    });

    // 5. Solicitar recuperación de contraseña
    document.getElementById('formRecuperar').addEventListener('submit', async e => {
      e.preventDefault();
      const email = document.getElementById('recover_email').value.trim();
      document.getElementById('recoverErr').textContent = '';
      document.getElementById('recoverMsg').textContent = '';

      const { data, error } = await supabase.auth.resetPasswordForEmail(email, {
        redirectTo: window.location.origin + window.location.pathname
      });
      if (error) {
        document.getElementById('recoverErr').textContent = error.message;
      } else {
        document.getElementById('recoverMsg').textContent =
          'Revisa tu correo para cambiar tu contraseña.';
      }
    });

    // 6. Al cargar: si venimos del link de recuperación, abrimos el modal de reset
    window.addEventListener('DOMContentLoaded', async () => {
      const params = new URLSearchParams(window.location.search);
      if (params.get('type') === 'recovery' && params.get('access_token')) {
        // Establece la sesión temporal
        await supabase.auth.setSession({
          access_token: params.get('access_token')
        });
        showModal('modalResetPassword');
      }
    });

    // 7. Actualizar nueva contraseña
    document.getElementById('resetForm').addEventListener('submit', async e => {
      e.preventDefault();
      const newPass = document.getElementById('new-password').value;
      document.getElementById('resetErr').textContent = '';
      document.getElementById('resetMsg').textContent = '';

      const { data, error } = await supabase.auth.updateUser({ password: newPass });
      if (error) {
        document.getElementById('resetErr').textContent = error.message;
      } else {
        document.getElementById('resetMsg').textContent =
          'Contraseña actualizada. Ya puedes iniciar sesión.';
      }
    });
  </script>
</body>
</html>