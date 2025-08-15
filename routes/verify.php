<?php

require_once '../Controller/user_management/UserController.php';

$email = $_GET['email'] ?? null;

if (!$email) {
    die('Email no proporcionado');
}

$controller = new UserController();
$controller->showVerificationForm($email);

?>