<?php
// reset-password.php
require_once 'conexion.php';

$token = $_GET['token'] ?? '';
$error = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'], $_POST['clave'], $_POST['clave2'])) {
    $token = $_POST['token'];
    $clave = $_POST['clave'];
    $clave2 = $_POST['clave2'];

    if ($clave !== $clave2) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($clave) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        $claveHash = password_hash($clave, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE usuarios SET usuario_clave = ?, recuperar_token = NULL WHERE recuperar_token = ?");
        $stmt->execute([$claveHash, $token]);

        if ($stmt->rowCount() > 0) {
            $ok = true;
        } else {
            $error = 'Token inválido o expirado.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer Contraseña</title>
  <style>
    body { font-family: Arial; background: #f5f5f5; padding: 30px; }
    .box { max-width: 400px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
    input { width: 100%; padding: 10px; margin: 10px 0; }
    button { width: 100%; padding: 10px; background: #007BFF; color: white; border: none; border-radius: 5px; }
    .error { color: red; }
    .success { color: green; }
  </style>
</head>
<body>
<div class="box">
  <h2>Restablecer Contraseña</h2>

  <?php if ($ok): ?>
    <p class="success">Tu contraseña ha sido cambiada exitosamente.</p>
  <?php elseif ($token): ?>
    <form method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <label>Nueva contraseña</label>
      <input type="password" name="clave" required>
      <label>Confirmar contraseña</label>
      <input type="password" name="clave2" required>
      <button type="submit">Cambiar contraseña</button>
      <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    </form>
  <?php else: ?>
    <p class="error">Token inválido o faltante.</p>
  <?php endif; ?>
</div>
</body>
</html>
