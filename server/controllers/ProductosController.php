<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Productos.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new Producto($conn);

$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        foreach ($data as $key => $value) {
            $model->$key = $value;
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

// include_once __DIR__ . '/../config/database.php';
// include_once __DIR__ . '/../models/Productos.php';;

// header("Content-Type: application/json");

// $db = new Database();
// $conn = $db->conectar();
// $model = new Producto($conn);

// $data = json_decode(file_get_contents("php://input"), true);
// $action = $_GET['action'] ?? '';

// switch ($action) {
//     case 'create':
//         foreach ($data as $key => $value) { $model->$key = $value; }
//         echo json_encode($model->crear());
//         break;
//     case 'read':
//         echo json_encode($model->leer());
//         break;
//     case 'update':
//         foreach ($data as $key => $value) { $model->$key = $value; }
//         echo json_encode($model->actualizar());
//         break;
//     case 'delete':
//         $model->id = $data['id'];
//         echo json_encode($model->eliminar());
//         break;
// }

?>















