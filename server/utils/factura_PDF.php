<?php
require_once __DIR__ . '/../vendor/autoload.php';

function generarFacturaPDF($cliente, $productos) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Factura - Tienda JEY', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Cliente: {$cliente['nombre']} {$cliente['apellido']}", 0, 1);
    $pdf->Cell(0, 10, "Email: {$cliente['email']}", 0, 1);
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(80, 10, 'Producto', 1);
    $pdf->Cell(30, 10, 'Cantidad', 1);
    $pdf->Cell(40, 10, 'Precio', 1);
    $pdf->Cell(40, 10, 'Subtotal', 1, 1);

    $pdf->SetFont('Arial', '', 12);
    $total = 0;
    foreach ($productos as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $total += $subtotal;
        $pdf->Cell(80, 10, $item['nombre'], 1);
        $pdf->Cell(30, 10, $item['cantidad'], 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . number_format($item['precio'], 2), 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . number_format($subtotal, 2), 1, 1, 'C');
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Total: $' . number_format($total, 2), 0, 1, 'R');
    $pdf->Cell(0, 10, 'Estado: PAGADO', 0, 1, 'R');

    $carpetaPDFs = __DIR__ . '/pdfs';
    if (!is_dir($carpetaPDFs)) {
        mkdir($carpetaPDFs, 0777, true);
    }

    $nombreArchivo = 'factura_tienda_jey_' . time() . '.pdf';
    $rutaPDF = $carpetaPDFs . '/' . $nombreArchivo;

    $pdf->Output('F', $rutaPDF);

    return $rutaPDF;
}
?>
