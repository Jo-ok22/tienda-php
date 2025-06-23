<?php
require 'vendor/autoload.php'; // O require 'ruta/a/fpdf.php' si lo usas sin composer

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, '¡Hola, mundo!');
$pdf->Output('test.pdf', 'F'); // Guarda el PDF en el disco

echo "PDF generado exitosamente.";
