<?php
class Conexion {
  public static function getConnection() {
    $host = 'localhost';
    $db   = 'bd_delix';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $port = 3306;

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
      return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
      exit('Error de conexión: ' . $e->getMessage());
    }
  }
}

