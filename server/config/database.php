<?php

// conection = mysql -u root -h localhost -p

class Database {
    private $host;
    private $username;
    private $password;
    private $db_name;
    public $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
        $this->db_name = getenv('DB_NAME');
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












// conection = mysql -u root -h localhost -p





















