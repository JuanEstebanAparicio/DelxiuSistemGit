// auth.js
import { supabase } from '../config/supabaseClient.js';

let otpAction = '';
let otpEmail = '';

// 3.Registro

// 3.1. Al enviar el formulario de registro:
document.getElementById('registerForm').addEventListener('submit', async e => {
  e.preventDefault();
  const name       = document.getElementById('user_name').value.trim();
  const email      = document.getElementById('user_email').value.trim();
  const restaurant = document.getElementById('user_restaurant').value.trim();
  const pass       = document.getElementById('user_password').value;
  const confirm    = document.getElementById('confirm_password').value;
  document.getElementById('registerErr').textContent = '';
  
  if (pass !== confirm) {
    document.getElementById('errorPass').style.display = 'block';
    return;
  }
  document.getElementById('errorPass').style.display = 'none';

  // 3.2. Envía OTP y crea usuario si no existe
  const { error } = await supabase.auth.signInWithOtp(
    { email },
    {
      shouldCreateUser: true,
      data: { name, restaurant },
      // sin redirectTo => se envía un código, no link
    }
  );
  if (error) {
    document.getElementById('registerErr').textContent = error.message;
    return;
  }

  // 3.3. Mostrar modal de código
  otpAction = 'signup';
  otpEmail  = email;
  document.getElementById('correo_mostrado_span').textContent = email;
  showModal('modalCodigo');
});

document.getElementById('loginForm').addEventListener('submit', async e => {
  e.preventDefault();
  const email = document.getElementById('login_email').value.trim();
  document.getElementById('loginErr').textContent = '';

  const { error } = await supabase.auth.signInWithOtp({ email });
  if (error) {
    document.getElementById('loginErr').textContent = error.message;
    return;
  }

  otpAction = 'login';
  otpEmail  = email;
  showModal('modalCodigo');
});

// Recuperar contraseña
document.getElementById('formRecuperar').addEventListener('submit', async e => {
  e.preventDefault();
  const email = document.getElementById('recover_email').value.trim();
  document.getElementById('recoverErr').textContent = '';

  const { error } = await supabase.auth.signInWithOtp(
    { email },
    { type: 'recovery' }
  );
  if (error) {
    document.getElementById('recoverErr').textContent = error.message;
    return;
  }

  otpAction = 'recovery';
  otpEmail  = email;
  showModal('modalCodigo');
});


document.getElementById('btn-verificar-correo').addEventListener('click', async () => {
  const code = document.getElementById('verification_code_input').value.trim();
  document.getElementById('mensajeCodigo').textContent = '';

  // Tipo para verifyOtp: 'signup'|'magiclink'|'recovery'
  // En nuestro caso 'signup', 'magiclink' para login OTP y 'recovery'
  const type = (otpAction === 'signup')
    ? 'signup'
    : (otpAction === 'recovery')
      ? 'recovery'
      : 'magiclink'; 

  const { error } = await supabase.auth.verifyOtp({
    email: otpEmail,
    token: code,
    type
  });

  if (error) {
    document.getElementById('mensajeCodigo').textContent = error.message;
    return;
  }

  // Éxito según acción
  if (otpAction === 'signup') {
    document.getElementById('mensajeCodigo').textContent =
      '✅ Registro confirmado. Ahora inicia sesión.';
    hideModal('modalRegister');
  }
  else if (otpAction === 'login') {
    document.getElementById('mensajeCodigo').textContent =
      `¡Bienvenido ${otpEmail}!`;
    hideModal('modalLogin');
  }
  else if (otpAction === 'recovery') {
    // Oculta modalCodigo y abre modalResetPassword
    hideModal('modalCodigo');
    showModal('modalResetPassword');
  }
});



// Cuando abres modalResetPassword tras OTP recovery...
document.getElementById('resetForm').addEventListener('submit', async e => {
  e.preventDefault();
  const newPass = document.getElementById('new-password').value;
  document.getElementById('resetErr').textContent = '';

  const { error } = await supabase.auth.updateUser({ password: newPass });
  if (error) {
    document.getElementById('resetErr').textContent = error.message;
  } else {
    document.getElementById('resetMsg').textContent =
      'Contraseña actualizada. Ya puedes iniciar sesión.';
  }
});

