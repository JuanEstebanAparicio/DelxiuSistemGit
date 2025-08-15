<?php
// config.php
return [
  'smtp_host'   => 'smtp.gmail.com',      // Host SMTP correcto de Gmail
  'smtp_user'   => 'delixiusistem@gmail.com',
  'smtp_pass'   => 'ftlecotbnugecwpe',    // Esta debe ser una "Contraseña de aplicación", no la clave normal
  'smtp_port'   => 587,                   // Gmail usa 587 con STARTTLS
  'smtp_secure' => 'tls'                  // Añade este parámetro en tu config
];

