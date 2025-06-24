<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Usuario.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new Usuario($conn);

$data = $_POST;
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        foreach ($data as $key => $value) { 
            $model->$key = $value; 
        }

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../utils/user_foto/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['foto']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
                $model->foto = 'utils/user_foto/' . $fileName;
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al subir la foto']);
                exit;
            }
        } else {
            $model->foto = null; // si no sube foto
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
