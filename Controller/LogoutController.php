<?php
// archivo: Controller/LogoutController.php

session_start();

// Elimina todos los datos de la sesión
session_unset();
session_destroy();

// Redirige al inicio (o login)
header('Location: ../View/inicio_de_pag.php');
exit;
