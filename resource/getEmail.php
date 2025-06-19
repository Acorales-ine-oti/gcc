<?php

$destinatario = $_POST['email'];

$para      = $destinatario; // Dirección de correo del destinatario
$asunto    = 'PRUEBA DE ENVIO DE EMAIL CON SIRI';   // Asunto del correo
$mensaje   = 'Hola, este es un correo de prueba enviado desde SIRI.'; // Contenido del correo
$cabeceras = 'From: asdrubal.corales@ine.gob.ve' . "\r\n" . // La dirección de correo del remitente
             'Reply-To: asdrubal.corales@ine.gob.ve' . "\r\n" . // A dónde responder si el destinatario pulsa "responder"
             'X-Mailer: PHP/' . phpversion(); // Información sobre el software que envía el correo

  echo 'Email Capturado: '.$destinatario;
          
if (mail($para, $asunto, $mensaje, $cabeceras)) {
    echo 'El correo se envió correctamente.';
} else {
    echo 'Hubo un error al enviar el correo.';
}

?>