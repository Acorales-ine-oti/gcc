<?php
session_start();

// Habilitar la visualización de errores para depuración (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si la solicitud es un POST y si proviene del formulario de contrato
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['generar_contrato'])) {
    header("Location: mainGetContrato.php"); // Redirigir si no es un POST válido
    exit();
}

// Verificar si el usuario ha iniciado sesión y si su ID está en la sesión.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php?status=error&message=No ha iniciado sesión o su sesión ha expirado.");
    exit();
}

// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php'; // Asegúrate de que esta ruta sea correcta

// Verificar la conexión a la base de datos
if ($conn->connect_error) {
    // Si la conexión falla, redirigir con un mensaje de error
    header("Location: mainGetContrato.php?status=error&message=Error de conexión a la base de datos: " . urlencode($conn->connect_error));
    exit();
}

$user_id_logged = $_SESSION['user_id'];

// Verificar si el checkbox de aceptación de términos fue marcado
if (!isset($_POST['acepto_terminos']) || $_POST['acepto_terminos'] !== 'on') {
    $conn->close();
    header("Location: mainGetContrato.php?status=error&message=" . urlencode("Debes aceptar los términos y condiciones para generar el contrato."));
    exit();
}

// Lógica para registrar el contrato
$fecha_contrato_db = date('Y-m-d'); // Formato YYYY-MM-DD para la base de datos

// Establecer una fecha de expiración (ej. 1 año después), un contenido básico, rutas vacías y estado activo.
$expiration_date = date('Y-m-d', strtotime('+1 year', strtotime($fecha_contrato_db)));

// Generar el contenido del contrato. Puedes recuperar los datos del usuario de la DB aquí si es necesario
// o pasarlos como campos ocultos desde mainGetContrato.php si son fijos al momento de generar el contrato
// Para simplificar, usamos un contenido por defecto y luego puedes adaptarlo
$contract_content_default = "Contrato de confidencialidad estándar generado el " . $fecha_contrato_db . " para el usuario con ID: " . $user_id_logged . ".";

// Estas rutas suelen generarse o subirse en un proceso aparte (ej. subida de firma o generación de PDF)
// Por ahora, las mantenemos vacías como en tu código original.
$signature_image_path = '';
$pdf_file_path = '';
$status = 'active';

// Consulta para insertar el contrato
$insert_sql = "INSERT INTO contracts (user_id, contract_date, expiration_date, contract_content, signature_image_path, pdf_file_path, status) VALUES (?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($insert_sql)) {
    // 'issssss' => int, string, string, string, string, string, string
    $stmt->bind_param("issssss", $user_id_logged, $fecha_contrato_db, $expiration_date, $contract_content_default, $signature_image_path, $pdf_file_path, $status);

    if ($stmt->execute()) {
        // Contrato registrado exitosamente, redirigir al historial
        $stmt->close();
        $conn->close();
        header("Location: main.php?status=success&message=" . urlencode("Contrato generado y registrado exitosamente."));
        exit();
    } else {
        // Error en la ejecución de la consulta
        $error_message = "Error al registrar el contrato: " . $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: mainGetContrato.php?status=error&message=" . urlencode($error_message));
        exit();
    }
} else {
    // Error al preparar la consulta
    $error_message = "Error al preparar la consulta de inserción: " . $conn->error;
    $conn->close();
    header("Location: mainGetContrato.php?status=error&message=" . urlencode($error_message));
    exit();
}

// En caso de que algo inesperado ocurra, asegurar que se cierre la conexión
$conn->close();
?>