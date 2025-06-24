<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Productos.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new Producto($conn);

$data = $_POST;
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../utils/producto_foto/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['imagen']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
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

    case 'update':
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../utils/producto_foto/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['imagen']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
                $model->imagen = 'utils/producto_foto/' . $fileName;
            }
            // Si no sube imagen nueva, podrías mantener la anterior
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
?>












