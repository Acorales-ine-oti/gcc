<?php
// Asegúrate de que la sesión ya esté iniciada en el archivo principal (dashboard.php)
// y que 'conexion.php' también esté incluido allí.
// Aquí solo necesitamos acceder a los datos de sesión.
session_start();

// Incluir la conexión a la DB. Es fundamental que 'conexion.php'
// inicialice la variable $conn (objeto mysqli)
require_once 'conexion.php';

// Verificar si el usuario está logueado y si el user_id está en la sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    // Si el usuario no está logueado o no hay user_id, redirigirlo a la página de login
    header("Location: login.php");
    exit();
}

$user_id_logged = $_SESSION['user_id']; // ID del usuario logueado
$cedula_usuario = htmlspecialchars($_SESSION['cedula'] ?? 'N/A'); // Cédula del usuario, si es necesaria para visualización
$nombre_usuario = htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); // Nombre del usuario para visualización

// --- LÓGICA PARA OBTENER ESTADÍSTICAS DEL USUARIO Y CONTRATOS ---

// 1. Obtener datos detallados del usuario logueado de la tabla 'users'
// Seleccionamos solo las columnas que existen en tu esquema 'users'
$userData = [];
if ($stmt = $conn->prepare("SELECT nombre, apellido, telefono, correo FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $user_id_logged); // 'i' porque id es un entero
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    }
    $stmt->close();
}

// 2. Obtener la cantidad total de contratos para este usuario
// Usamos 'user_id' de la tabla 'contracts'
$totalContratos = 0;
if ($stmt = $conn->prepare("SELECT COUNT(id) AS total FROM contracts WHERE user_id = ?")) {
    $stmt->bind_param("i", $user_id_logged); // 'i' porque user_id es un entero
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalContratos = $row['total'];
    }
    $stmt->close();
}

// 3. Obtener la cantidad de contratos por año (para el gráfico de evolución)
// Usamos 'contract_date' y 'user_id' de la tabla 'contracts'
$contratosPorAnio = [];
$sqlContratosPorAnio = "SELECT YEAR(contract_date) AS anio, COUNT(id) AS cantidad
                        FROM contracts
                        WHERE user_id = ?
                        GROUP BY anio
                        ORDER BY anio ASC";

if ($stmt = $conn->prepare($sqlContratosPorAnio)) {
    $stmt->bind_param("i", $user_id_logged); // 'i' porque user_id es un entero
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $contratosPorAnio[$row['anio']] = $row['cantidad'];
    }
    $stmt->close();
}

// Cerrar la conexión a la base de datos después de todas las consultas
// Es importante cerrar la conexión cuando ya no se necesita para liberar recursos.
$conn->close();

// Preparar los datos para Chart.js
$chartLabels = json_encode(array_keys($contratosPorAnio));
$chartData = json_encode(array_values($contratosPorAnio));

?>

<div class="row">
    <div class="col-md-6">
        <div class="card p-3 mb-3">
            <h5>Resumen de Contratos</h5>
            <p>Total de contratos firmados: <strong><?php echo $totalContratos; ?></strong></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 mb-3">
            <h5>Datos Adicionales del Usuario</h5>
            <p>Teléfono: <strong><?php echo htmlspecialchars($userData['telefono'] ?? 'N/A'); ?></strong></p>
            <!-- Eliminadas 'Función' y 'Dependencia' ya que no están en tu esquema de tabla 'users' -->
            <p>Correo: <strong><?php echo htmlspecialchars($userData['correo'] ?? 'N/A'); ?></strong></p>
        </div>
    </div>
</div>

<div class="card p-3 mt-4">
    <h5>Contratos firmados por año</h5>
    <div class="chart-container">
        <canvas id="contratosChart"></canvas>
    </div>
</div>

<script>
    // Usamos setTimeout con un pequeño retardo para dar tiempo al DOM a renderizarse
    // Opcional: Podrías usar un MutationObserver para detectar cuando el canvas se añade al DOM.
    setTimeout(function() {
        const ctx = document.getElementById('contratosChart');
        if (ctx) { // Solo si el canvas existe
            // Si ya existe una instancia de Chart en este canvas, destrúyela para evitar errores
            if (Chart.getChart(ctx)) {
                Chart.getChart(ctx).destroy();
            }

            const contratosChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: <?php echo $chartLabels; ?>,
                    datasets: [{
                        label: 'Cantidad de Contratos',
                        data: <?php echo $chartData; ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Número de Contratos'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Año'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Contratos firmados por año'
                        }
                    }
                }
            });
        } else {
            console.warn('Canvas "contratosChart" no encontrado en dashboard_content.php.');
        }
    }, 100); // Pequeño retardo de 100ms
</script>