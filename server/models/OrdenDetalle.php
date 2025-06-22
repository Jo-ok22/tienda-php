<?php
class OrdenDetalle {
    private $conn;
    private $table = "orden_detalle";

    public $id;
    public $orden_id;
    public $producto_id;
    public $cantidad;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function agregar() {
        $query = "INSERT INTO $this->table (orden_id, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $this->orden_id, $this->producto_id, $this->cantidad);
        return $stmt->execute();
    }

    public function eliminar() {
        $query = "DELETE FROM $this->table WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    public function obtenerPorOrden() {
        $query = "SELECT od.id, od.cantidad, p.nombre, p.precio FROM $this->table od
                  JOIN productos p ON od.producto_id = p.id
                  WHERE od.orden_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->orden_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>