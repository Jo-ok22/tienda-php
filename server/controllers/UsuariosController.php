<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Usuario.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new Usuario($conn);

$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        foreach ($data as $key => $value) { 
            $model->$key = $value; 
        }
        echo json_encode($model->registrar());
        break;

    case 'login':
        $model->email = $data['email'] ?? '';
        $model->password = $data['password'] ?? '';
        $user = $model->login();
        if ($user) {
            echo json_encode([
                "success" => true,
                "user" => $user
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Credenciales inválidas"
            ]);
        }
        break;

    default:
        echo json_encode([
            "success" => false,
            "message" => "Acción no válida"
        ]);
        break;
}
?>