<?php
include_once __DIR__ . '/../config/cors.php';
include_once __DIR__ . "/../config/database.php";
include_once __DIR__ . "/../models/OrdenDetalle.php";

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new OrdenDetalle($conn);

$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
    $orden_id = $data['orden_id'] ?? null;
    $productos = $data['productos'] ?? [];

    if (!$orden_id || !is_array($productos) || empty($productos)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }

    $errores = [];
    $model->orden_id = $orden_id;

    foreach ($productos as $item) {
        $model->producto_id = $item['producto_id'] ?? null;
        $model->cantidad = $item['cantidad'] ?? null;

        if (!$model->producto_id || !$model->cantidad) {
            $errores[] = [
                'producto_id' => $model->producto_id,
                'error' => 'Datos faltantes'
            ];
            continue;
        }

        if (!$model->agregar()) {
            $errores[] = [
                'producto_id' => $model->producto_id,
                'error' => 'Error al insertar en la base de datos'
            ];
        }
    }

    if (empty($errores)) {
        echo json_encode(['success' => true, 'message' => 'Todos los productos agregados correctamente']);
    } else {
        echo json_encode(['success' => false, 'errores' => $errores]);
    }
    break;

    case 'delete':
        $model->id = $data['id'];
        echo json_encode($model->eliminar());
        break;
    case 'viewByOrden':
        $model->orden_id = $data['orden_id'];
        echo json_encode($model->obtenerPorOrden());
        break;
}
//funciona ✔
?>