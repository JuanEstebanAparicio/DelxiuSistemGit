<?php
session_start();
session_unset();  // Borra todas las variables de sesión
session_destroy(); // Destruye la sesión actual

header("Location: ../Ver/Login.php"); // Redirige al login
exit();
