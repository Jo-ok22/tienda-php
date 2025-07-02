<?php
class Producto {
    private $conn;
    private $table = "productos";

    public $id;
    public $nombre;
    public $precio;
    public $descripcion;
    public $imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO $this->table (nombre, precio, descripcion, imagen)
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sdss", $this->nombre, $this->precio, $this->descripcion, $this->imagen);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'id' => $this->conn->insert_id,
                'nombre' => $this->nombre,
                'precio' => $this->precio,
                'descripcion' => $this->descripcion,
                'imagen' => $this->imagen
            ];
        } else {
            return [
                'success' => false,
                'error' => $stmt->error
            ];
        }
    }

    public function leer() {
        $result = $this->conn->query("SELECT * FROM $this->table");
        if ($result) {
            return [
                'success' => true,
                'data' => $result->fetch_all(MYSQLI_ASSOC)
            ];
        } else {
            return [
                'success' => false,
                'error' => $this->conn->error
            ];
        }
    }

    public function actualizar() {
        $query = "UPDATE $this->table SET nombre=?, precio=?, descripcion=?, imagen=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sdssi", $this->nombre, $this->precio, $this->descripcion, $this->imagen, $this->id);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'id' => $this->id,
                'nombre' => $this->nombre,
                'precio' => $this->precio,
                'descripcion' => $this->descripcion,
                'imagen' => $this->imagen
            ];
        } else {
            return [
                'success' => false,
                'error' => $stmt->error
            ];
        }
    }

    public function eliminar() {
        $query = "DELETE FROM $this->table WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => "Producto eliminado correctamente",
                'id' => $this->id
            ];
        } else {
            return [
                'success' => false,
                'error' => $stmt->error
            ];
        }
    }
}
//funciona ✔

?>

















