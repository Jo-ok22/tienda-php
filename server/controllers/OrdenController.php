<?php
include_once "../config/database.php";
include_once "../models/Orden.php";

header("Content-Type: application/json");

$db = new Database();
$conn = $db->conectar();
$model = new Orden($conn);

$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        $model->usuario_id = $data['usuario_id'];
        echo json_encode($model->crear());
        break;
    case 'pay':
        if ($data['codigo_pago'] === '123456789') {
            $model->id = $data['orden_id'];
            echo json_encode($model->pagar());
        } else {
            echo json_encode(false);
        }
        break;
    case 'getPending':
        $model->usuario_id = $data['usuario_id'];
        echo json_encode($model->obtenerPendiente());
        break;
}
?>