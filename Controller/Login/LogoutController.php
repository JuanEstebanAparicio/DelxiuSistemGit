<?php
// archivo: Controller/LogoutController.php

session_start();

// Elimina todos los datos de la sesión
session_unset();
session_destroy();

// Eliminar cookie remember_token si existe
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Redirige al inicio (o login)
header('Location: /Proyecto%20de%20aula/View/inicio_de_pag.php');
exit;

