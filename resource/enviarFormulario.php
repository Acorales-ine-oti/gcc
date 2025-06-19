<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Obtener el email del usuario
    $to_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
        die("Dirección de email inválida.");
    }

    // 2. Definir los detalles del correo
    $subject = "Completa tus Datos Personales";
    $from_name = "Instituto Nacional de Estadìstica"; // Cambia esto
    $from_email = "no-reply@tudominio.com"; // Cambia esto a un email válido de tu dominio

    echo 'Email:'.$to_email;

    // 3. Construir el contenido HTML del formulario a enviar
    // *** CAMBIO AQUÍ: Se agrega un campo hidden para el email original ***
    $message = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario de Datos Personales</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
                color: #333;
            }
            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            h2 {
                color: #007bff;
                text-align: center;
                margin-bottom: 25px;
            }
            p {
                margin-bottom: 15px;
                line-height: 1.6;
            }
            .form-group {
                margin-bottom: 15px;
            }
            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            input[type="text"],
            input[type="email"],
            input[type="tel"] {
                width: calc(100% - 22px);
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
            }
            input[type="submit"] {
                background-color: #28a745;
                color: white;
                padding: 12px 25px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                display: block;
                width: 100%;
                margin-top: 25px;
                transition: background-color 0.3s ease;
            }
            input[type="submit"]:hover {
                background-color: #218838;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                font-size: 0.9em;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <h2>¡Hola! Por favor, completa tus datos personales</h2>
            <p>Hemos recibido tu solicitud y necesitamos que completes la siguiente información para poder procesarla.</p>
            <p>Por favor, llena el siguiente formulario con tus datos:</p>
            <form action="http://tudominio.com/process_personal_data.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido Completo:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="cedula">Cédula de Identidad/ID:</label>
                    <input type="text" id="cedula" name="cedula" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono">
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion">
                </div>
                <input type="hidden" name="user_email_original" value="' . htmlspecialchars($to_email) . '">
                <input type="submit" value="Enviar Datos">
            </form>
            <div class="footer">
                <p>Este es un correo automático, por favor no lo respondas.</p>
                <p>&copy; ' . date("Y") . ' Tu Empresa/Sitio Web. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>
    ';

    // 4. Establecer las cabeceras del correo para indicar que es HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . $from_name . ' <' . $from_email . '>' . "\r\n";
    $headers .= 'Reply-To: ' . $from_email . "\r\n";

    // 5. Enviar el correo
    if (mail($to_email, $subject, $message, $headers)) {
        echo "¡Excelente! Se ha enviado un formulario de datos personales a tu dirección de correo: " . htmlspecialchars($to_email) . ". Por favor, revisa tu bandeja de entrada (y la carpeta de spam).";
    } else {
        echo "Hubo un problema al enviar el correo. Por favor, inténtalo de nuevo más tarde.";
    }

} else {
    header("Location: email_input_form.html");
    exit();
}
?>