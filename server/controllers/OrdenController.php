<?php
include_once __DIR__ . '/../config/cors.php';
include_once __DIR__ . "/../config/database.php";
include_once __DIR__ . "/../models/Orden.php";

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
    if (($data['codigo_pago'] ?? '') === '123456789') {
        $model->id = $data['orden_id'] ?? null;
        if ($model->pagar()) {
            include_once __DIR__ . '/../models/Usuario.php';
            include_once __DIR__ . '/../models/OrdenDetalle.php';
            include_once __DIR__ . '/../utils/factura_PDF.php';
            include_once __DIR__ . '/../utils/enviar_email.php';

            $usuarioModel = new Usuario($conn);
            $detalleModel = new OrdenDetalle($conn);

            $orden = $conn->query("SELECT * FROM ordenes WHERE id = {$model->id}")->fetch_assoc();
            $usuario_id = $orden['usuario_id'];

            $result = $conn->query("SELECT nombre, apellido, email FROM usuarios WHERE id = $usuario_id");
            $cliente = $result->fetch_assoc();

            $detalleModel->orden_id = $model->id;
            $productos = $detalleModel->obtenerPorOrden();

            $rutaPDF = generarFacturaPDF($cliente, $productos);
            $mensajeEmail = enviarFacturaPorEmail($cliente['email'], "{$cliente['nombre']} {$cliente['apellido']}", $rutaPDF);

            echo json_encode([
                'success' => true,
                'message' => 'Factura generada y enviada.',
                'detalle' => $mensajeEmail
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cambiar estado a pagado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Código de pago inválido']);
    }
    break;

    case 'getPending':
        $model->usuario_id = $data['usuario_id'];
        echo json_encode($model->obtenerPendiente());
        break;
}
//funciona ✔

?>

