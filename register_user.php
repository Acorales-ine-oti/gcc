<?php
// Habilitar la visualización de errores para depuración.
// ¡Desactívalo en producción!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
// Define constants for database connection parameters.
define('DB_SERVER', 'localhost');          // Database server address, usually 'localhost'.
define('DB_USERNAME', 'root');             // Database username, as specified by the user.
define('DB_PASSWORD', '');                 // Database password, empty as specified.
define('DB_NAME', 'contratos_confidencialidad'); // <--- Nombre correcto de la DB

/**
 * Helper function to display an error message and terminate the script.
 * @param string $message The error message to display.
 * @param string $linkText The text for the "back" link.
 * @param string $linkHref The URL for the "back" link.
 */
function displayErrorAndExit(string $message, string $linkText = 'Volver', string $linkHref = 'index.php'): void {
    // Include Bootstrap CSS for consistent styling of messages.
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">';
    echo '<style>
            body {
                font-family: \'Inter\', sans-serif;
                background-color: #f8f9fa;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 600px;
            }
          </style>';
    echo "<div class='alert alert-danger text-center mt-5'>Error: " . htmlspecialchars($message) . " <a href='" . htmlspecialchars($linkHref) . "'>" . htmlspecialchars($linkText) . "</a></div>";
    exit();
}

// Attempt to connect to the MySQL database
$conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    displayErrorAndExit("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Verificar si la solicitud es de tipo POST y si los campos esperados existen.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asegúrate de que todos estos campos existan en tu formulario de registro HTML.
    // El campo 'nombre' NO ESTÁ EN LA IMAGEN QUE ENVIASTE. Debes añadirlo al formulario.
    if (isset($_POST['cedula']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nombre'])) { // <--- 'nombre' es esperado
        // Capturar y sanear los datos enviados por POST.
        $cedula = trim(htmlspecialchars($_POST['cedula']));
        $username = trim(htmlspecialchars($_POST['username']));
        $email = trim(htmlspecialchars($_POST['email']));
        $password = $_POST['password'];
        $nombre = trim(htmlspecialchars($_POST['nombre'])); // <--- Capturar 'nombre'

        // 1. Validate ID format
        if (!preg_match('/^\d+$/', $cedula)){
            displayErrorAndExit('Formato de cédula inválido. Debe contener solo números.', 'Volver', 'index.php');
        }

        // 2. Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            displayErrorAndExit('Formato de correo electrónico inválido.', 'Volver al registro', 'registration_form.html?cedula=' . urlencode($cedula));
        }

        // 3. Password strength (basic check)
        if (strlen($password) < 6) {
            displayErrorAndExit('La contraseña debe tener al menos 6 caracteres.', 'Volver al registro', 'formRegUsu.php?cedula=' . urlencode($cedula));
        }

        // Hashear la contraseña de forma segura
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Check if the ID (cedula) or username or email is already registered in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE cedula = ? OR username = ? OR correo = ?"); // Consulta en tabla 'users'
        if ($stmt === false) {
            displayErrorAndExit('Error al preparar la consulta de selección: ' . $conn->error);
        }
        $stmt->bind_param("sss", $cedula, $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            displayErrorAndExit('La cédula, usuario o correo electrónico ya está registrado. <a href="login.php">Ir al inicio de sesión</a>', 'Volver al registro', 'formRegUsu.php');
        } else {
            // Insert the new user into the database
            // Columnas: cedula, username, password_hash, nombre, correo
            // NOTA: 'apellido', 'telefono', 'foto_perfil', 'huella_dactilar', 'cargo', 'dependencia'
            // quedan como NULL por defecto si no se especifican en el INSERT y permiten NULL.
            $stmt_insert = $conn->prepare("INSERT INTO users (cedula, username, password_hash, nombre, correo) VALUES (?, ?, ?, ?, ?)");
            if ($stmt_insert === false) {
                displayErrorAndExit('Error al preparar la consulta de inserción: ' . $conn->error);
            }

            $stmt_insert->bind_param("sssss", $cedula, $username, $password_hash, $nombre, $email);

            if ($stmt_insert->execute()) {
                header('Location: login.php?registration=success');
                exit();
            } else {
                displayErrorAndExit('Error al registrar el usuario: ' . $stmt_insert->error);
            }
            $stmt_insert->close();
        }
        $stmt->close();

    } else {
        // Este mensaje se mostrará si falta alguno de los campos esperados en el POST.
        displayErrorAndExit('Faltan datos del formulario. Por favor, asegúrate de llenar todos los campos.', 'Volver al formulario', 'javascript:history.back()');
    }
} else {
    header('Location: index.php?error=access_denied_registration');
    exit();
}

$conn->close();
?>