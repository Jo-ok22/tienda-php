<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $email;
    public $password;
    public $rol;
    public $foto;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar() {
        $query = "INSERT INTO $this->table (nombre, apellido, dni, email, password, rol, foto)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $hashed = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bind_param("sssssss", $this->nombre, $this->apellido, $this->dni, $this->email, $hashed, $this->rol, $this->foto);

        if ($stmt->execute()) {
            return [
                "success" => true,
                "id" => $this->conn->insert_id,
                "nombre" => $this->nombre,
                "apellido" => $this->apellido,
                "dni" => $this->dni,
                "email" => $this->email,
                "rol" => $this->rol,
                "foto" => $this->foto
            ];
        } else {
            return [
                "success" => false,
                "error" => $stmt->error
            ];
        }
    }

    public function login() {
        $query = "SELECT * FROM $this->table WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            if (password_verify($this->password, $row['password'])) {
                unset($row['password']); // no devolver el hash
                return $row;
            }
        }
        return false;
    }
}
//funciona ✔
?>