<?php
include_once __DIR__ . '/./config/cors.php';

// Manejo de preflight (solicitud OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header("Content-Type: application/json");

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? '';

if (!$controller || !$action) {
    echo json_encode(["error" => "Debes indicar controller y action"]);
    exit;
}

// Mapear controlador a archivo
$controllerFile = __DIR__ . "/controllers/" . ucfirst($controller) . "Controller.php";

if (file_exists($controllerFile)) {
    // Incluye el controller
    include_once $controllerFile;
    
    // El controller se encargará del resto (ya que en cada uno ya puse el switch)
} else {
    echo json_encode(["error" => "Controller no encontrado"]);
}
//funciona ✔
?>