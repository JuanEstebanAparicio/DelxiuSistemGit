<?php
$baseDir = dirname(dirname(__DIR__));
require_once($baseDir . '/Model/Entity/productos.php');
require_once($baseDir . '/Model/Crud/almacen_crud.php');

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

    // Crear objeto Ingrediente
    $insumo = new Ingrediente(
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
        $crud = new Insumo_crud();
        $crud->crearInsumo($insumo);
        header("Location: ../View/gestor_ingredientes.php?success=1");
        exit();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Acceso no autorizado.");
}
?>
