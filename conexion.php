<?php
/*
$servername = "localhost"; // Cambia esto si tu servidor MySQL está en otro lugar
$username = "root"; // Reemplaza con tu nombre de usuario de MySQL
$password = ""; // Reemplaza con tu contraseña de MySQL
$database = "terminoDeUso"; // Reemplaza con el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}*/
?>
<?php
// db_connect.php
$servername = "localhost"; // Generalmente es 'localhost' para XAMPP
$username = "root";      // Nombre de usuario por defecto en XAMPP
$password = "";          // Contraseña por defecto en XAMPP (suele estar vacía)
$database = "contratos_confidencialidad"; // **ASEGÚRATE DE QUE ESTE NOMBRE COINCIDA EXACTAMENTE CON TU BASE DE DATOS**

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// **Verificar conexión y mostrar un error específico para depuración**
if ($conn->connect_error) {
    error_log("Error de conexión a la base de datos: " . $conn->connect_error); // Registra el error en los logs del servidor
    die("Lo sentimos, no pudimos conectar con la base de datos. Por favor, inténtalo de nuevo más tarde. (DB Connection Error)"); // Mensaje para el usuario
}

// **Crucial para los acentos: Establecer el conjunto de caracteres a UTF-8**
$conn->set_charset("utf8mb4");

?>