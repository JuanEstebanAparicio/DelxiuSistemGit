<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

define('BASE_PATH', __DIR__ . '/../../');

// PHPMailer
require_once BASE_PATH . 'Libreries/phpMailer-Master/src/PHPMailer.php';
require_once BASE_PATH . 'Libreries/phpMailer-Master/src/SMTP.php';
require_once BASE_PATH . 'Libreries/phpMailer-Master/src/Exception.php';

class EmailService
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function sendVerificationEmail(string $toEmail, string $nombre): ?string
    {
        $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // 🔹 NUEVO: definir la ruta de la plantilla aquí dentro
        $tplPath = BASE_PATH . 'CSS/verification_code.php';

        // Cargar plantilla si existe
        if (file_exists($tplPath)) {
            require_once $tplPath; // Debe definir plantillaCodigo($nombre, $codigo): string
        }

        $mail = new PHPMailer(true);
        try {
            // Depuración (puedes quitarlo en producción)
            $mail->SMTPDebug   = 0;
            $mail->Debugoutput = 'error_log';

            // SMTP
            $mail->isSMTP();
            $mail->Host     = $this->config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['smtp_user'];
            $mail->Password = $this->config['smtp_pass'];
            $mail->Port     = (int)$this->config['smtp_port'];

            $secure = strtolower($this->config['smtp_secure'] ?? 'tls');
            if ($secure === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($secure === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            // From / To
            $mail->setFrom(
                $this->config['from_email'] ?? $this->config['smtp_user'],
                $this->config['from_name']  ?? 'DelixiuSystem'
            );
            $mail->addAddress($toEmail);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Código de verificación - DelixiuSystem';

            // Si la función existe, usamos el HTML de la plantilla
            if (function_exists('plantillaCodigo')) {
                $mail->Body = plantillaCodigo($nombre, $codigo);
            } else {
                // Fallback actual
                $mail->Body = "Hola $nombre,<br>Tu código de verificación es: <b>$codigo</b>";
            }

            // Versión texto plano
            $mail->AltBody = "Tu código de verificación es: $codigo";

            $mail->send();
            return $codigo;

        } catch (Exception $e) {
            
            $info = $mail->ErrorInfo ?: $e->getMessage();
            error_log("Error al enviar correo: " . $info);
            
            return null;
            
        }
    }
}
