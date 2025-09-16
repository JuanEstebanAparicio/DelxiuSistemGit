// auth.js
import { supabase } from '../config/supabaseClient.js';

// Registro
document.getElementById('registerForm').addEventListener('submit', async e => {
  e.preventDefault();
  const name       = document.getElementById('user_name').value;
  const email      = document.getElementById('user_email').value;
  const restaurant = document.getElementById('user_restaurant').value;
  const password   = document.getElementById('user_pass').value;
  document.getElementById('registerMsg').textContent = '';
  document.getElementById('registerErr').textContent = '';

  // signUp con metadata adicional
  const { data, error } = await supabase.auth.signUp(
    { email, password },
    {
      data: { name, restaurant },
      // redirige aquí tras confirmar email
      emailRedirectTo: 'http://localhost/Proyecto_aula/View/start_page.php'
    }
  );

  if (error) {
    document.getElementById('registerErr').textContent = error.message;
  } else {
    document.getElementById('registerMsg').textContent =
      'Revisa tu correo y haz clic en el enlace de confirmación.';
  }
});

// Login
document.getElementById('loginForm').addEventListener('submit', async e => {
  e.preventDefault();
  const email    = document.getElementById('login_email').value;
  const password = document.getElementById('login_pass').value;
  document.getElementById('loginMsg').textContent = '';
  document.getElementById('loginErr').textContent = '';

  const { data, error } = await supabase.auth.signInWithPassword({ email, password });
  if (error) {
    document.getElementById('loginErr').textContent = error.message;
  } else {
    document.getElementById('loginMsg').textContent =
      `Bienvenido ${data.user.email}!`;
    // aquí rediriges a tu dashboard
    // window.location.href = '/dashboard.php';
  }
});

// Recuperar contraseña
document.getElementById('recoverForm').addEventListener('submit', async e => {
  e.preventDefault();
  const email = document.getElementById('recover_email').value;
  document.getElementById('recoverMsg').textContent = '';
  document.getElementById('recoverErr').textContent = '';

  const { data, error } = await supabase.auth.resetPasswordForEmail(email, {
    redirectTo: 'http://localhost/Proyecto_aula/View/start_page.php'
  });

  if (error) {
    document.getElementById('recoverErr').textContent = error.message;
  } else {
    document.getElementById('recoverMsg').textContent =
      'Revisa tu correo para restablecer tu contraseña.';
  }
});