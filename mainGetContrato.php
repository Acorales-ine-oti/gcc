<?php
session_start();

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario ha iniciado sesión y si su ID está en la sesión.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php';

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error crítico de conexión a la base de datos: " . $conn->connect_error);
}

// Datos del usuario desde la sesión
$user_id_logged = $_SESSION['user_id'] ?? null;
if ($user_id_logged === null) {
    die("Error: ID de usuario no encontrado en la sesión. Asegúrese de iniciar sesión correctamente.");
}
// La siguiente línea ha sido eliminada para no mostrar el ID de usuario logeado
// echo "ID de usuario logeado: " . htmlspecialchars($user_id_logged) . "<br>"; // Depuración

// --- Lógica para obtener los datos detallados del usuario logueado desde la DB ---
$userData = [];
if ($stmt = $conn->prepare("SELECT nombre, apellido, cedula, cargo, dependencia, huella_dactilar FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $user_id_logged);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    }
    $stmt->close();
} else {
    error_log("Error al preparar la consulta de usuario en mainGetContrato.php: " . $conn->error);
}

// Valores por defecto si no se encuentran datos del usuario o columnas específicas
$nombre_completo = htmlspecialchars($userData['nombre'] ?? '____________________') . ' ' . htmlspecialchars($userData['apellido'] ?? '____________________');
$cedula_completa = 'V- ' . htmlspecialchars($userData['cedula'] ?? '____________________');

$funcion_usuario = htmlspecialchars($userData['cargo'] ?? 'Función no disponible');
$dependencia_usuario = htmlspecialchars($userData['dependencia'] ?? 'Dependencia no disponible');
$ruta_huella_dactilar = htmlspecialchars($userData['huella_dactilar'] ?? '');

// Función para convertir número a letras (solo días del 1 al 31)
function numero_a_letras($num) {
    $unidades = array(
        0 => 'cero', 1 => 'uno', 2 => 'dos', 3 => 'tres', 4 => 'cuatro', 5 => 'cinco',
        6 => 'seis', 7 => 'siete', 8 => 'ocho', 9 => 'nueve', 10 => 'diez',
        11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince',
        16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho', 19 => 'diecinueve', 20 => 'veinte',
        21 => 'veintiuno', 22 => 'veintidós', 23 => 'veintitrés', 24 => 'veinticuatro', 25 => 'veinticinco',
        26 => 'veintiséis', 27 => 'veintisiete', 28 => 'veintiocho', 29 => 'veintinueve', 30 => 'treinta',
        31 => 'treinta y uno'
    );

    if ($num >= 0 && $num <= 31) {
        return $unidades[$num];
    } else {
        return (string)$num;
    }
}

// Fecha actual para el contrato
// Se ha eliminado el uso de IntlDateFormatter y setlocale() para evitar el error "Class 'IntlDateFormatter' not found" y la advertencia de deprecación de strftime().
$dia = date('d');
$mes_numero = date('n'); // Obtiene el número del mes (1 para enero, 12 para diciembre)
$anio = date('Y');

// Array de nombres de meses en español
$nombres_meses = array(
    1 => 'enero',
    2 => 'febrero',
    3 => 'marzo',
    4 => 'abril',
    5 => 'mayo',
    6 => 'junio',
    7 => 'julio',
    8 => 'agosto',
    9 => 'septiembre',
    10 => 'octubre',
    11 => 'noviembre',
    12 => 'diciembre'
);

$mes = $nombres_meses[$mes_numero]; // Obtiene el nombre del mes del array

$dia_en_letras = numero_a_letras((int)$dia);
$fecha_contrato_texto = "En Caracas, a los $dia_en_letras ($dia) días del mes de $mes de $anio.";

// Cerrar la conexión a la base de datos (después de obtener todos los datos necesarios para la visualización)
$conn->close();

// NOTA: La lógica para procesar la generación del contrato se ha movido a 'procesar_contrato.php'
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Contrato de Confidencialidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .contract-container {
            background-color: white;
            padding: 30px;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .contract-text {
            white-space: pre-wrap;
            font-size: 0.95rem;
            line-height: 1.8;
            text-align: justify;
        }
        .signature-area {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            width: 70%;
            margin: 0 auto 10px auto;
        }
        .signature-label {
            font-size: 0.9rem;
            margin-bottom: 3px;
            display: inline-block;
            margin-right: 20px;
            vertical-align: top;
        }
        .fingerprint-img {
            max-width: 100px;
            height: auto;
            display: block;
            margin: 10px auto;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container contract-container">
        <h2 class="mb-4 text-center">CONTRATO DE CONFIDENCIALIDAD Y RESERVA DE INFORMACIÓN</h2>

        <?php // El mensaje de error ahora vendría de la redirección de procesar_contrato.php
        if (isset($_GET['status']) && $_GET['status'] === 'error' && isset($_GET['message'])) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['message']) . '</div>';
        }
        ?>

        <form method="POST" action="procesar_contrato.php">
            <div class="contract-text">
Yo, <strong> <?php echo $nombre_completo; ?></strong>, de nacionalidad venezolana, mayor de edad, de este domicilio y titular de la cédula de identidad Nro. <strong><?php echo $cedula_completa; ?></strong> , en mis funciones como <strong> <?php echo $funcion_usuario; ?></strong>, adscrito a la <strong><?php echo $dependencia_usuario; ?></strong> del INE, mediante la presente declaro mi compromiso de mantener la  <strong> CONFIDENCIALIDAD Y RESERVA ABSOLUTA</strong> de los datos e información de los cuales tenga conocimiento por cualquier medio de los órganos o entidades del sector público en el ejercicio de mis funciones como Contratado, en el Instituto Nacional de Estadísticas (INE), tal y como se encuentra instituido en el artículo 19 del Decreto con Rango, Valor y Fuerza de Ley de la Función Pública de Estadísticas de la República Bolivariana de Venezuela, Publicada en la Gaceta Oficial de la República Bolivariana de Venezuela Nº 37.321 de fecha 09 de noviembre de 2001, el cual tutela el secreto estadístico, cuando señala:
Artículo 19 “Están amparados por el secreto estadístico los datos personales obtenidos directamente o por medio de información administrativa, que por su contenido, estructura o grado de desagregación identifiquen a los informantes”.

De igual forma, me comprometo a mantener la CONFIDENCIALIDAD Y RESERVA ABSOLUTA en el ejercicio de las actividades y desarrollo del XV Censo Nacional de Población y Vivienda, conforme a lo establecido en el artículo 17 de la misma Ley que expresamente señala:
Artículo 17: “La Información estadística de interés público tendrá carácter oficial cuando el Instituto Nacional de Estadística la certifique y se haga pública a través de los órganos estadísticos. El personal de los órganos estadísticos no podrá suministrar información estadística parcial o total, provisional o definitiva, que conozca por razón de su trabajo, hasta tanto la misma se haya hecho oficialmente públicamente.”

Igualmente, mediante la presente declaro la CONFIDENCIALIDAD Y RESERVA ABSOLUTA del secreto estadístico una vez culminada las labores y actividades debidamente encomendadas por el Instituto Nacional de Estadísticas, tal como se encuentra establecido en el artículo 23 del Decreto con Rango, Valor y Fuerza de la Ley de la Función Estadística, el cual señala:
Articulo 23. “Toda persona natural o jurídica, pública o privada que intervenga en la actividad estadística del Sistema Estadístico Nacional o tenga conocimiento de datos amparados tiene la obligación de mantener el secreto estadístico, aún después de concluir sus actividades profesionales o su vinculación con los servicios estadísticos”.

Para efectos del presente compromiso, es importante destacar que la “Información Confidencial” comprende toda la información divulgada por cualquiera de las partes ya sea en forma oral, visual, escrita, grabada en medios magnéticos o en cualquier otra forma tangible y que se encuentre claramente marcada como tal al ser entregada a la parte receptora.

Quedando sujeto (a) a las sanciones establecidas en el Decreto con Rango, Valor y Fuerza de Ley de la Función Estadística y a cualquier otra norma legal homologa aplicable por el cumplimiento de lo aquí declarado.

<?php echo $fecha_contrato_texto; ?>

<div class="signature-area">
    <p class="signature-line"></p>
    <p class="signature-label">Nombre: <?php echo $nombre_completo; ?></p>
    <p class="signature-label">CI: <?php echo $cedula_completa; ?></p>
    <p class="signature-label">Función: <?php echo $funcion_usuario; ?></p>
    <p class="signature-label">Dependencia: <?php echo $dependencia_usuario; ?></p>
    <p class="signature-label">Huella dactilar</p>
    <?php if (!empty($ruta_huella_dactilar) && file_exists($ruta_huella_dactilar)): ?>
        <img src="<?php echo htmlspecialchars($ruta_huella_dactilar); ?>" alt="Huella Dactilar" class="fingerprint-img">
    <?php else: ?>
        <p class="text-muted small">Huella dactilar no disponible.</p>
    <?php endif; ?>
</div>
            </div>

            <div class="form-check mt-5">
                <input class="form-check-input" type="checkbox" id="acepto_terminos" name="acepto_terminos" required>
                <label class="form-check-label" for="acepto_terminos">
                    Acepto los términos y condiciones de este contrato de confidencialidad.
                </label>
            </div>

            <button type="submit" name="generar_contrato" class="btn btn-success mt-4">Generar Contrato</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

