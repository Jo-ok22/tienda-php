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
//funciona ✔
?>
