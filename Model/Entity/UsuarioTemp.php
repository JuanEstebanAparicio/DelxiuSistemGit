<?php
class UsuarioTemp {
    private $nombre;
    private $correo;
    private $restaurante;
    private $clave;
    private $codigo;
    private $creado_en;

    public function __construct($nombre, $correo, $restaurante, $clave, $codigo, $creado_en = null) {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->restaurante = $restaurante;
        $this->clave = $clave;
        $this->codigo = $codigo;
        $this->creado_en = $creado_en;
    }

    // Getters
    public function getNombre() { return $this->nombre; }
    public function getCorreo() { return $this->correo; }
    public function getRestaurante() { return $this->restaurante; }
    public function getClave() { return $this->clave; }
    public function getCodigo() { return $this->codigo; }
    public function getCreadoEn() { return $this->creado_en; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCorreo($correo) { $this->correo = $correo; }
    public function setRestaurante($restaurante) { $this->restaurante = $restaurante; }
    public function setClave($clave) { $this->clave = $clave; }
    public function setCodigo($codigo) { $this->codigo = $codigo; }
    public function setCreadoEn($creado_en) { $this->creado_en = $creado_en; }
}
?>
