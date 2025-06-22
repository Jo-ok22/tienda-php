<?php
class Orden {
    private $conn;
    private $table = "ordenes";

    public $id;
    public $usuario_id;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO $this->table (usuario_id) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->usuario_id);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function pagar() {
        $query = "UPDATE $this->table SET estado='pagada' WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    public function obtenerPendiente() {
        $query = "SELECT * FROM $this->table WHERE usuario_id=? AND estado='pendiente' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>