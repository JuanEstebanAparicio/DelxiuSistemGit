<?php

class productos {
    private $nombre;
    private $cantidad;
    private $cantidad_minima;
    private $unidad;
    private $costo_unitario;
    private $categoria;
    private $fecha_ingreso;
    private $fecha_vencimiento;
    private $lote;
    private $descripcion;
    private $ubicacion;
    private $estado;
    private $proveedor;
    private $foto;

    public function __construct($nombre, $cantidad, $cantidad_minima, $unidad, $costo_unitario, $categoria, $fecha_ingreso, $fecha_vencimiento, $lote, $descripcion, $ubicacion, $estado, $proveedor, $foto) {
        $this->nombre = $nombre;
        $this->cantidad = $cantidad;
        $this->cantidad_minima = $cantidad_minima;
        $this->unidad = $unidad;
        $this->costo_unitario = $costo_unitario;
        $this->categoria = $categoria;
        $this->fecha_ingreso = $fecha_ingreso;
        $this->fecha_vencimiento = $fecha_vencimiento;
        $this->lote = $lote;
        $this->descripcion = $descripcion;
        $this->ubicacion = $ubicacion;
        $this->estado = $estado;
        $this->proveedor = $proveedor;
        $this->foto = $foto;
    }

    // Getters
    public function getNombre() { return $this->nombre; }
    public function getCantidad() { return $this->cantidad; }
    public function getCantidadMinima() { return $this->cantidad_minima; }
    public function getUnidad() { return $this->unidad; }
    public function getCostoUnitario() { return $this->costo_unitario; }
    public function getCategoria() { return $this->categoria; }
    public function getFechaIngreso() { return $this->fecha_ingreso; }
    public function getFechaVencimiento() { return $this->fecha_vencimiento; }
    public function getLote() { return $this->lote; }
    public function getDescripcion() { return $this->descripcion; }
    public function getUbicacion() { return $this->ubicacion; }
    public function getEstado() { return $this->estado; }
    public function getProveedor() { return $this->proveedor; }
    public function getFoto() { return $this->foto; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
    public function setCantidadMinima($cantidad_minima) { $this->cantidad_minima = $cantidad_minima; }
    public function setUnidad($unidad) { $this->unidad = $unidad; }
    public function setCostoUnitario($costo_unitario) { $this->costo_unitario = $costo_unitario; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }
    public function setFechaIngreso($fecha_ingreso) { $this->fecha_ingreso = $fecha_ingreso; }
    public function setFechaVencimiento($fecha_vencimiento) { $this->fecha_vencimiento = $fecha_vencimiento; }
    public function setLote($lote) { $this->lote = $lote; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setUbicacion($ubicacion) { $this->ubicacion = $ubicacion; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setProveedor($proveedor) { $this->proveedor = $proveedor; }
    public function setFoto($foto) { $this->foto = $foto; }
}

?>
