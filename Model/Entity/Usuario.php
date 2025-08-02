<?php
class Usuario {
    private $nombre;
    private $correo;
    private $clave;
    private $restaurante;
    private $estado;
    private $rol;
    private $token;
    private $creacion;

    public function __construct($nombre, $correo, $clave, $restaurante, $estado = 'activo', $rol = 'usuario', $token = null, $creacion = null) {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->restaurante = $restaurante;
        $this->estado = $estado;
        $this->rol = $rol;
        $this->token = $token;
        $this->creacion = $creacion;
    }

    // Getters
    public function getNombre() { return $this->nombre; }
    public function getCorreo() { return $this->correo; }
    public function getClave() { return $this->clave; }
    public function getRestaurante() { return $this->restaurante; }
    public function getEstado() { return $this->estado; }
    public function getRol() { return $this->rol; }
    public function getToken() { return $this->token; }
    public function getCreacion() { return $this->creacion; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCorreo($correo) { $this->correo = $correo; }
    public function setClave($clave) { $this->clave = $clave; }
    public function setRestaurante($restaurante) { $this->restaurante = $restaurante; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setRol($rol) { $this->rol = $rol; }
    public function setToken($token) { $this->token = $token; }
    public function setCreacion($creacion) { $this->creacion = $creacion; }
}
?>
