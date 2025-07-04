<?php
include_once __DIR__ . '/../config/cors.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Productos.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new Producto($conn);

$data = $_POST ?: json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        // Asignar datos al modelo excepto la imagen
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        // Validar y procesar imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../utils/producto_foto/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            // Detectar MIME real
            $mime = mime_content_type($_FILES['imagen']['tmp_name']);

            // Forzar extensión .jpg para JPEG/JFIF
            if (in_array($mime, ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/jfif'])) {
                $ext = 'jpg';
            } else {
                $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $ext = strtolower($ext);
            }

            $fileName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
                // Guardar ruta relativa en el modelo
                $model->imagen = 'utils/producto_foto/' . $fileName;
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al subir la imagen']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Imagen requerida']);
            exit;
        }

        echo json_encode($model->crear());
        break;

    case 'read':
        echo json_encode($model->leer());
        break;

    case 'updateDatos':
    $data = $_POST ?: json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id']) || empty($data['id'])) {
        echo json_encode(['success' => false, 'error' => 'ID es requerido para actualizar']);
        exit;
    }

    $model->id = $data['id'];
    $model->nombre = $data['nombre'] ?? '';
    $model->precio = $data['precio'] ?? 0;
    $model->descripcion = $data['descripcion'] ?? '';
    $model->imagen = $data['imagen_actual'] ?? '';

    echo json_encode($model->actualizar());
    break;


case 'updateImagen':
    $data = $_POST;

    if (!isset($data['id']) || empty($data['id'])) {
        echo json_encode(['success' => false, 'error' => 'ID es requerido para actualizar imagen']);
        exit;
    }

    $model->id = $data['id'];
    $model->nombre = $data['nombre'] ?? '';
    $model->precio = $data['precio'] ?? 0;
    $model->descripcion = $data['descripcion'] ?? '';
    $model->imagen = $data['imagen_actual'] ?? '';

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../utils/producto_foto/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $mime = mime_content_type($_FILES['imagen']['tmp_name']);
        $ext = in_array($mime, ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/jfif']) ? 'jpg' : strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $fileName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
            $model->imagen = 'utils/producto_foto/' . $fileName;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Imagen no enviada o error al subir']);
        exit;
    }

    echo json_encode($model->actualizar());
    break;

    case 'delete':
        $model->id = $data['id'] ?? null;
        if ($model->id === null) {
            echo json_encode(['success' => false, 'error' => 'ID es requerido para eliminar']);
        } else {
            echo json_encode($model->eliminar());
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        break;
}
//funciona ✔✔
?>
