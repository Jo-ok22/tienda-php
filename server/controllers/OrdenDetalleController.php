<?php
include_once "../config/database.php";
include_once "../models/OrdenDetalle.php";

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new OrdenDetalle($conn);

$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        foreach ($data as $key => $value) { $model->$key = $value; }
        echo json_encode($model->agregar());
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
?>