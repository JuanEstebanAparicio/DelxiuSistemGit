<?php
session_start();

$baseDir = dirname(dirname(__DIR__));
require_once($baseDir . '/Model/Entity/productos.php');
require_once($baseDir . '/Model/Crud/almacen_crud.php');

try {
    // Validar si vienen datos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $cantidad = $_POST['cantidad'] ?? 0;
        $cantidad_minima = $_POST['cantidad_minima'] ?? 0;
        $unidad = $_POST['unidad'] ?? '';
        $costo_unitario = $_POST['costo_unitario'] ?? 0;
        $categoria = $_POST['categoria'] ?? '';
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
        $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
        $lote = $_POST['lote'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $ubicacion = $_POST['ubicacion'] ?? '';
        $estado = $_POST['estado'] ?? '';
        $proveedor = $_POST['proveedor'] ?? '';

        // Subida de imagen
        $fotoNombre = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fotoTmp = $_FILES['foto']['tmp_name'];
            $fotoNombre = basename($_FILES['foto']['name']);
            $rutaDestino = $baseDir . '/Media/' . $fotoNombre;
            move_uploaded_file($fotoTmp, $rutaDestino);
        }

        // Crear objeto Ingrediente
        $insumo = new Ingrediente(
            $nombre,
            $cantidad,
            $cantidad_minima,
            $unidad,
            $costo_unitario,
            $categoria,
            $fecha_ingreso,
            $fecha_vencimiento,
            $lote,
            $descripcion,
            $ubicacion,
            $estado,
            $proveedor,
            $fotoNombre
        );

        // Llamar al CRUD para insertar
        $crud = new AlmacenCrud();
        $resultado = $crud->agregarProducto($nombre, $cantidad, $costo_unitario);

        if ($resultado) {
            $_SESSION['mensaje_exito'] = "Insumo registrado correctamente.";
        } else {
            $_SESSION['mensaje_error'] = "Ocurrió un error al registrar el insumo.";
        }

        header("Location: ../View/gestor_ingredientes.php");
        exit();
    } else {
        throw new Exception("Método de solicitud no válido.");
    }

} catch (Exception $e) {
    error_log("❌ Error en insertar_ingrediente.php: " . $e->getMessage());
    $_SESSION['mensaje_error'] = "Error al procesar el registro.";
    header("Location: ../View/gestor_ingredientes.php");
    exit();
}
?>
