<?php 
define('BASE_URL', 'http://localhost/ProyectoAula');
class Conexion {
    private static $instancia = null;
    private $conexion;
    
    private function __construct() {
        $host = "localhost"; 
        $user = "root"; 
        $password = ""; 
        $database = "bd_delix";
        
        $this->conexion = mysqli_connect($host, $user, $password, $database);
        
        if (!$this->conexion) {
            throw new Exception(
                "Error de conexión MySQL: " . mysqli_connect_errno() . " - " . mysqli_connect_error()
            );
        }
        
        mysqli_set_charset($this->conexion, 'utf8mb4');
    }

    // Método para obtener la instancia de la conexión
    public static function obtenerConexion() {
        if (self::$instancia === null) {
            try {
                self::$instancia = new self();
            } catch (Exception $e) {
                error_log("Error de conexión: " . $e->getMessage());
                die("Lo sentimos, ocurrió un error al conectar con el sistema. Intenta nuevamente más tarde.");
            }
        }
        return self::$instancia->conexion;
    }
    // Evitamos la clonación del objeto
    public function __clone() {}
    
    // Evitamos la deserialización del objeto
    public function __wakeup() {}
}
?>