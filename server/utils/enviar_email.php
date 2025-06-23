<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarFacturaPorEmail($emailCliente, $nombreCliente, $rutaPDF) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'standarcode@gmail.com';
        $mail->Password = 'omapoxiebtpkujot';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('standarcode@gmail.com', 'Tienda JEY');
        $mail->addAddress($emailCliente, $nombreCliente);

        $mail->isHTML(true);
        $mail->Subject = 'Tu factura de Tienda JEY';
        $mail->Body    = "Hola $nombreCliente,<br><br>Adjuntamos la factura de tu compra.<br><br>¡Gracias por elegirnos!";

        if (file_exists($rutaPDF)) {
            $mail->addAttachment($rutaPDF);
        } else {
            throw new Exception("El archivo PDF no existe: $rutaPDF");
        }

        $mail->send();
        return "Factura enviada a $emailCliente correctamente.";
    } catch (Exception $e) {
        return "Error al enviar el email: {$mail->ErrorInfo}";
    }
}
?>














<!-- ?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- Crear el PDF ---
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Tienda JEY');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Señor cliente, sus productos son:', 0, 1);
$pdf->Cell(0, 10, 'iPhone 16 - $2000', 0, 1);
$pdf->Cell(0, 10, 'Samsung Galaxy S25 - $2000', 0, 1);

// --- Guardar PDF ---
$nombreArchivo = "factura_tienda_jey_" . time() . ".pdf";
$rutaPDF = __DIR__ . "/pdfs/" . $nombreArchivo;
$pdf->Output('F', $rutaPDF);

// --- Enviar por email ---
function enviarFacturaPorEmail($emailCliente, $nombreCliente, $rutaPDF) {
    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'standarcode@gmail.com';
        $mail->Password = 'omapoxiebtpkujot';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('standarcode@gmail.com', 'Tienda JEY');
        $mail->addAddress($emailCliente, $nombreCliente);

        $mail->isHTML(true);
        $mail->Subject = 'Tu factura de Tienda JEY';
        $mail->Body    = "Hola $nombreCliente,<br><br>Adjuntamos la factura de tu compra.<br><br>¡Gracias por elegirnos!";

        // Adjuntar el PDF generado
        if (file_exists($rutaPDF)) {
            $mail->addAttachment($rutaPDF);
        } else {
            throw new Exception("El archivo PDF no existe: $rutaPDF");
        }

        $mail->send();
        echo "Factura enviada a $emailCliente correctamente.";
    } catch (Exception $e) {
        echo "Error al enviar el email: {$mail->ErrorInfo}";
    }
}

// --- Datos de prueba (normalmente vendrían de tu base de datos) ---
$emailCliente = "lunajosiasm@mail.com";
$nombreCliente = "Juan Perez";

enviarFacturaPorEmail($emailCliente, $nombreCliente, $rutaPDF); -->
