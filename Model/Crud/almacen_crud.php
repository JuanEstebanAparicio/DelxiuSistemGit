<?php
$baseDir = dirname(dirname(__DIR__));
require_once($baseDir . '/Model/entity/Conexion.php');
class AlmacenCrud {
    private $conexion;
    private $root; // Raíz del proyecto

    public function __construct() {
        $this->conexion = Conexion::obtenerConexion();
        $this->root = dirname(dirname(__DIR__)); // Ej: C:\xampp\htdocs\ProyectoAula
    }

    // Método para agregar un producto al almacén
    public function agregarProducto($nombre, $cantidad, $precio) {
        $sql_insert = "INSERT INTO productos (nombre, cantidad, precio) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql_insert);
        if (!$stmt) {
            error_log("❌ Error al preparar la inserción de producto: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("sid", $nombre, $cantidad, $precio);
        if (!$stmt->execute()) {
            error_log("⚠️ Error al agregar producto: " . $stmt->error);
            return false;
        }
        $stmt->close();
        error_log("✅ Producto agregado correctamente: $nombre");
        return true;
    }

    // Método para obtener todos los productos del almacén
    public function obtenerProductos() {
        $sql = "SELECT * FROM productos";
        $resultado = $this->conexion->query($sql);
        if (!$resultado) {
            error_log("❌ Error al obtener productos: " . $this->conexion->error);
            return [];
        }
        $productos = [];
        while ($row = $resultado->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }
}
?>