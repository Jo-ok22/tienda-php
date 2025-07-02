<?php

class Database {
    private $host;
    private $username;
    private $password;
    private $db_name;
    public $conn;

    public function __construct() {
        // Configuración local de XAMPP
        $this->host = 'localhost';
        $this->username = 'root';
        $this->password = ''; // XAMPP por defecto no tiene contraseña
        $this->db_name = 'tienda_jey'; // CAMBIAR esto al nombre real
    }

    public function conectar() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8");
        return $this->conn;
    }
}
//funciona ✔

?>