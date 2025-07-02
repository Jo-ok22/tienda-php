<?php
include_once __DIR__ . '/../config/cors.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Usuario.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new Usuario($conn);

$data = json_decode(file_get_contents('php://input'), true);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
    $data = $_POST;
    if (empty($data)) {
        $data = json_decode(file_get_contents("php://input"), true);
    }

    $camposRequeridos = ['nombre', 'apellido', 'dni', 'email', 'password'];
    foreach ($camposRequeridos as $campo) {
        if (empty($data[$campo])) {
            echo json_encode(['success' => false, 'error' => "Falta el campo $campo"]);
            exit;
        }
    }

    $model->nombre = $data['nombre'];
    $model->apellido = $data['apellido'];
    $model->dni = $data['dni'];
    $model->email = $data['email'];
    $model->password = $data['password'];
    $model->rol = $data['rol'] ?? 'cliente';

    $uploadDir = __DIR__ . '/../utils/user_foto/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $mime = mime_content_type($_FILES['foto']['tmp_name']);
        $ext = in_array($mime, ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/jfif']) ? 'jpg' : pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . bin2hex(random_bytes(5)) . '.' . strtolower($ext);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
            $model->foto = 'utils/user_foto/' . $fileName;
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al subir la foto']);
            exit;
        }
    } else {
        $model->foto = 'utils/user_foto/default.jpg';
    }

    echo json_encode($model->registrar());
    break;


    case 'login':
        $model->email = $data['email'] ?? '';
        $model->password = $data['password'] ?? '';
        $user = $model->login();
        if ($user) {
            http_response_code(200);  // <- Agregar este código
            echo json_encode([
                "success" => true,
                "status" => 200, // <-- Esto lo agregás manualmente para verlo en el JSON
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
//funciona ✔
?>
