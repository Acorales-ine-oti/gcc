<?php
// session_start() ya debería estar en main.php.
// Si este archivo se va a acceder directamente para depuración, es bueno tenerlo,
// pero si siempre se carga vía AJAX desde main.php, no es estrictamente necesario aquí.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Habilitar la visualización de errores para depuración.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario ha iniciado sesión.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger" role="alert">Acceso denegado. Por favor, inicie sesión.</div>';
    exit();
}

// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php'; // Asegúrate de que esta ruta sea correcta

// Obtener el ID del usuario desde la sesión
$user_id_logged = $_SESSION['user_id'];

// Consulta para obtener los contratos del usuario
// Asegúrate de que los nombres de las tablas y columnas coincidan con tu DB.
$sql = "SELECT id, contract_date FROM contracts WHERE user_id = ?";
$stmt = $conn->prepare($sql);

// Verificar si la preparación de la consulta fue exitosa
if ($stmt === false) {
    echo '<div class="alert alert-danger" role="alert">Error al preparar la consulta de contratos: ' . htmlspecialchars($conn->error) . '</div>';
    $conn->close();
    exit();
}

$stmt->bind_param("i", $user_id_logged);
$stmt->execute();
$result = $stmt->get_result();

?>

<style>
    /* Por ejemplo, si quieres un espaciado extra para la tabla */
    .table-container {
        margin-top: 20px;
    }
</style>

<div class="container mt-5">
    <h1>Historial de Contratos</h1>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID Contrato</th>
                    <th>Fecha del Contrato</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['contract_date']); ?></td>
                        <td>
                            <a href="ver_contrato.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary btn-sm" target="_blank">Ver Contrato</a>
                            <a href="descargar_contrato.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-secondary btn-sm">Descargar Contrato</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">No se encontraron contratos registrados para este usuario.</p>
    <?php endif; ?>
</div>

<?php
// Cerrar la conexión a la base de datos al final del script
$stmt->close();
$conn->close();
?>