<?php
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
?>