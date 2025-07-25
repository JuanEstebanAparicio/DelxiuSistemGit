<?php
// archivo: Middleware/auth.php

session_start();

if (!isset($_SESSION['usuario'])) {
  header('Location: ../View/inicio_de_pag.php');
  exit();
}
