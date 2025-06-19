<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "contratos_confidencialidad"; // Nombre de tu base de datos

// Iniciar sesión para usar mensajes de error o éxito
session_start();

// Verificar si se ha enviado el número de cédula por POST
if (isset($_POST['cedula'])) {
    $cedula_raw = $_POST['cedula'];

    // Limpiar la cédula: asegurar que sea solo numérica.
    $cedula = filter_var($cedula_raw, FILTER_SANITIZE_NUMBER_INT);

    // Validar que la cédula limpiada sea realmente un número y tenga la longitud correcta
    if (!ctype_digit($cedula) || strlen($cedula) < 6 || strlen($cedula) > 9) {
        $_SESSION['message_type'] = 'error';
        $_SESSION['message_content'] = "El formato de la cédula no es válido. Solo se permiten números entre 6 y 9 dígitos.";
        header("Location: index.php"); // Redirigir de vuelta al formulario
        exit();
    }

    // Conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        $_SESSION['message_type'] = 'error';
        $_SESSION['message_content'] = "Error al conectar con la base de datos. Intente más tarde.";
        header("Location: index.php");
        exit();
    }

    // Preparar la consulta SQL para evitar inyecciones SQL
    // CORRECCIÓN AQUÍ: Cambiado 'usuarios' a 'users'
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE cedula = ?");
    $stmt->bind_param("s", $cedula); // "s" indica que el parámetro es un string

    // Ejecutar la consulta
    $stmt->execute();
    $stmt->bind_result($count); // Enlazar el resultado a la variable $count
    $stmt->fetch(); // Obtener el resultado

    // Cerrar el statement y la conexión lo antes posible
    $stmt->close();
    $conn->close();

    // Verificar si la cédula existe
    if ($count > 0) {
        // La cédula existe en la base de datos
        $_SESSION['message_type'] = 'info'; // Puede ser 'info' o 'warning'
        $_SESSION['message_content'] = "La Cédula Número: " . htmlspecialchars($cedula) . " ya posee un contrato.";
        
        // Redirigir a index.php para mostrar el mensaje
        header("Location: index.php");
        exit(); // Siempre usar exit() después de header()
    } else {
        // La cédula NO existe en la base de datos
        
        // Crear cookie con el número de cédula
        // Parámetros: nombre, valor, tiempo de expiración (aquí 1 hora), ruta válida para la cookie
        setcookie("numero_cedula", $cedula, time() + 3600, "/");
        
        // Redirigir a local_captcha.html
        header("Location: local_captcha.html");
        exit(); // Siempre usar exit() después de header()
    }


} else {
    // Si no se recibió el número de cédula (ej. acceso directo a este script)
    // Redirigir a la página del formulario de búsqueda con un mensaje de error
    $_SESSION['message_type'] = 'error';
    $_SESSION['message_content'] = "Debe ingresar un número de cédula para buscar.";
    header("Location: index.php"); // Asegúrate de que esta es la página del formulario
    exit();
}
?>