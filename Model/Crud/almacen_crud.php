<?php
$baseDir = dirname(dirname(__DIR__));
require_once($baseDir . '../Model/Entity/Conexion.php');
require_once($baseDir . '../Model/Entity/productos.php'); 

class Insumo_crud {
    private $conexion;

    public function __construct() {
        try {
            $this->conexion = Conexion::obtenerConexion();
        } catch (Exception $e) {
            error_log("Error al obtener conexión en Insumo_crud: " . $e->getMessage());
            throw new Exception("Error interno. Intente más tarde.");
        }
    }

    public function crearInsumo(Ingrediente $insumo) {
        if (empty($insumo->getNombre()) || empty($insumo->getCantidad()) || empty($insumo->getUnidad())) {
            throw new Exception("Los campos obligatorios no pueden estar vacíos.");
        }

        $sql = "INSERT INTO insumos 
        (nombre, cantidad, cantidad_minima, unidad, costo_unitario, categoria, fecha_ingreso, fecha_vencimiento, lote, descripcion, ubicacion, estado, proveedor, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            error_log("❌ Error al preparar consulta de inserción: " . $this->conexion->error);
            throw new Exception("Error al preparar la inserción de insumo.");
        }

        $nombre = $insumo->getNombre();
        $cantidad = $insumo->getCantidad();
        $cantidad_minima = $insumo->getCantidadMinima();
        $unidad = $insumo->getUnidad();
        $costo_unitario = $insumo->getCostoUnitario();
        $categoria = $insumo->getCategoria();
        $fecha_ingreso = $insumo->getFechaIngreso();
        $fecha_vencimiento = $insumo->getFechaVencimiento();
        $lote = $insumo->getLote();
        $descripcion = $insumo->getDescripcion();
        $ubicacion = $insumo->getUbicacion();
        $estado = $insumo->getEstado();
        $proveedor = $insumo->getProveedor();
        $foto = $insumo->getFoto();

        $stmt->bind_param(
            "dddsssssssssss",
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
            $foto
        );

        if (!$stmt->execute()) {
            error_log("❌ Error al ejecutar inserción de insumo: " . $stmt->error);
            throw new Exception("No se pudo registrar el insumo.");
        }

        $stmt->close();
        return true;
    }
}
?>
