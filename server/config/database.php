<?php
// conection = mysql -u root -h localhost -p
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "tienda_jey";
    public $conn;

    public function conectar() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8");
        return $this->conn;
    }
}
?>





















