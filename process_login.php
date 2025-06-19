<?php
// Iniciar la sesión. Esto es crucial para almacenar datos del usuario
// y mantener su estado de sesión a lo largo del sitio.
session_start();

// --- Configuración de la base de datos ---
$servername = "localhost"; // Cambia esto si tu servidor MySQL está en otro lugar
$username = "root"; // Reemplaza con tu nombre de usuario de MySQL
$password = ""; // Reemplaza con tu contraseña de MySQL
$database = "contratos_confidencialidad"; // Reemplaza con el nombre de tu base de datos

// --- Conexión a la base de datos ---
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    // Si hay un error de conexión, redirige con un mensaje de error
    $_SESSION['login_error'] = "Error de conexión a la base de datos. Intente más tarde.";
    header("Location: login.php");
    exit();
}

// --- Captura de datos del formulario ---
// Siempre verifica si los datos POST existen antes de usarlos
if (!isset($_POST['cedula']) || !isset($_POST['password'])) {
    $_SESSION['login_error'] = "Por favor, complete todos los campos.";
    header("Location: login.php");
    exit();
}

$cedula = $_POST['cedula'];
$password_input = $_POST['password'];

// --- Consulta a la base de datos para verificar las credenciales ---
// CORRECCIÓN AQUÍ:
// 1. Cambiado 'login' a 'users' para que coincida con tu esquema SQL.
// 2. Cambiado 'contrasena' a 'password_hash' para que coincida con tu esquema SQL.
// 3. Seleccionamos también el 'id' del usuario para tenerlo en la sesión.
$sql = "SELECT id, cedula, username, nombre, apellido, correo, password_hash FROM users WHERE cedula = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Si hay un error al preparar la consulta, redirige con un mensaje de error
    $_SESSION['login_error'] = "Error interno del servidor. Intente más tarde.";
    header("Location: login.php");
    exit();
}

$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // --- VERIFICACIÓN DE CONTRASEÑA SEGURA ---
    // Usa password_verify() para comparar la contraseña ingresada con el hash almacenado.
    // Esto es CRUCIAL para la seguridad.
    if (password_verify($password_input, $user['password_hash'])) {
        // Contraseña correcta, el usuario ha iniciado sesión.

        // --- Crear variables de sesión ---
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id']; // Almacena el ID del usuario
        $_SESSION['cedula'] = $user['cedula'];
        $_SESSION['username'] = $user['username']; // Asegúrate de tener esta columna si la usas
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['apellido'] = $user['apellido']; // Almacena el apellido
        $_SESSION['correo'] = $user['correo'];

        // Redirigir al usuario a la página principal
        header("Location: main.php");
        exit();
    } else {
        // Contraseña incorrecta
        $_SESSION['login_error'] = "Cédula o contraseña incorrecta.";
        header("Location: login.php");
        exit();
    }
} else {
    // No se encontró ningún usuario con esa cédula o la contraseña era incorrecta
    // Es buena práctica dar un mensaje genérico para evitar dar pistas a atacantes
    $_SESSION['login_error'] = "Cédula o contraseña incorrecta.";
    header("Location: login.php");
    exit();
}

// Cerrar la sentencia y la conexión a la base de datos
$stmt->close();
$conn->close();
?>