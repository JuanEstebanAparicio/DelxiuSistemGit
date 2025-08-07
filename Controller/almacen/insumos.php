<?php
$baseDir = dirname(dirname(__DIR__));
require_once($baseDir . '/Model/Entity/productos.php');
require_once($baseDir . '/Model/Crud/almacen_crud.php');

// Conexión PDO
$host = 'localhost';
$db   = 'bd_delix';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Validar si se enviaron datos POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fotoNombre = null;
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $fotoNombre = uniqid() . "_" . $_FILES["foto"]["name"];
        $rutaDestino = $baseDir . '/Media/' . $fotoNombre;

        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaDestino)) {
            die("Error al subir la imagen.");
        }
    }

    // Crear objeto productos (minúscula)
    $insumo = new productos(
        $_POST['nombre'],
        $_POST['cantidad'],
        $_POST['cantidad_minima'],
        $_POST['unidad'],
        $_POST['costo_unitario'],
        $_POST['categoria'],
        $_POST['fecha_ingreso'],
        $_POST['fecha_vencimiento'],
        $_POST['lote'],
        $_POST['descripcion'],
        $_POST['ubicacion'],
        $_POST['estado'],
        $_POST['proveedor'],
        $fotoNombre
    );

    // Llamar al CRUD para registrar el insumo
    try {
        $crud = new Insumo_crud($pdo);
        $crud->crearInsumo($insumo);
        header("Location: http://localhost/Proyecto_de_aula/View/gestor_ingredientes.php?success=1");
        exit();
    } catch (Exception $e) {
        die("Error al registrar: " . $e->getMessage());
    }
} else {
    http_response_code(403);
    echo "Este recurso solo acepta solicitudes POST.";
}
?>
