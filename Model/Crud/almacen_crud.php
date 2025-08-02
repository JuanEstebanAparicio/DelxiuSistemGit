<?php
$baseDir = dirname(dirname(__DIR__));
require_once($baseDir . '../Model/Entity/productos.php'); 

class Insumo_crud {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function crearInsumo(productos $insumo) {
        if (empty($insumo->getNombre()) || empty($insumo->getCantidad()) || empty($insumo->getUnidad())) {
            throw new Exception("Los campos obligatorios no pueden estar vacíos.");
        }

        $sql = "INSERT INTO insumos 
            (nombre, cantidad, cantidad_minima, unidad, costo_unitario, categoria, fecha_ingreso, fecha_vencimiento, lote, descripcion, ubicacion, estado, proveedor, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $insumo->getNombre(),
            $insumo->getCantidad(),
            $insumo->getCantidadMinima(),
            $insumo->getUnidad(),
            $insumo->getCostoUnitario(),
            $insumo->getCategoria(),
            $insumo->getFechaIngreso(),
            $insumo->getFechaVencimiento(),
            $insumo->getLote(),
            $insumo->getDescripcion(),
            $insumo->getUbicacion(),
            $insumo->getEstado(),
            $insumo->getProveedor(),
            $insumo->getFoto()
        ]);

        return true;
    }
}
?>
