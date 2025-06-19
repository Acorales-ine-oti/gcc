<?php
session_start();

// Habilitar la visualización de errores para depuración (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario ha iniciado sesión.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit("Acceso denegado. Por favor, inicie sesión.");
}

// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php'; // Asegúrate de que esta ruta sea correcta

// Incluir la librería TCPDF
require_once('tcpdf/tcpdf.php'); 

// Obtener el ID del contrato desde la URL
$contract_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($contract_id === 0) {
    exit("ID de contrato no especificado o inválido.");
}

// Obtener el ID del usuario logueado para verificar la propiedad del contrato
$user_id_logged = $_SESSION['user_id'];

// --- Consultar datos del contrato y del usuario en la base de datos ---
$contract_data = null;

// La consulta ya está correcta para 'u.huella_dactilar'
$sql_contract = "SELECT c.contract_date, 
                        u.nombre, u.apellido, u.cedula, u.telefono, u.correo, u.cargo, u.dependencia, u.huella_dactilar
                 FROM contracts c
                 JOIN users u ON c.user_id = u.id
                 WHERE c.id = ? AND c.user_id = ?"; 

if ($stmt = $conn->prepare($sql_contract)) {
    $stmt->bind_param("ii", $contract_id, $user_id_logged);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $contract_data = $result->fetch_assoc();
    }
    $stmt->close();
} else {
    exit("Error al preparar la consulta del contrato: " . htmlspecialchars($conn->error));
}

// Cerrar la conexión a la base de datos
$conn->close();

if (!$contract_data) {
    exit("Contrato no encontrado o no autorizado para este usuario.");
}

// --- Preparar los datos para la plantilla del PDF ---
$nombre_completo = htmlspecialchars($contract_data['nombre'] . ' ' . $contract_data['apellido']);
$cedula = htmlspecialchars($contract_data['cedula']);
$cargo = htmlspecialchars($contract_data['cargo']);
$dependencia = htmlspecialchars($contract_data['dependencia']);
$telefono = htmlspecialchars($contract_data['telefono']);
$correo = htmlspecialchars($contract_data['correo']);

// *** VERIFICACIÓN DE LA FECHA DE LA BASE DE DATOS ***
$fecha_contrato = htmlspecialchars($contract_data['contract_date']); // ESTA ES LA FECHA DE LA DB
error_log("DEBUG: Fecha de la base de datos para el contrato " . $contract_id . ": " . $fecha_contrato);


// La variable ahora es 'huella_dactilar' y la ruta 'uploads/'
$huella_filename = $contract_data['huella_dactilar'] ?? null;
$huella_path = ''; // Inicializar vacío

// Define la carpeta donde se guardan las huellas (corregida a 'uploads/')
$huellas_dir = 'uploads/'; 

// --- DEPURACIÓN DE LA RUTA DE LA HUELLA ---
error_log("DEBUG: huella_filename desde DB: " . ($huella_filename ?? 'NULL'));
error_log("DEBUG: huellas_dir: " . $huellas_dir);
error_log("DEBUG: Ruta completa esperada para la huella: " . $huellas_dir . $huella_filename);

if ($huella_filename && file_exists($huellas_dir . $huella_filename)) {
    $huella_path = $huellas_dir . $huella_filename;
    error_log("DEBUG: Huella encontrada en: " . $huella_path);
} else {
    $huella_path = ''; // Asegurarse de que no haya una ruta inválida si el archivo no existe
    error_log("DEBUG: Huella NO encontrada o filename vacío. Verifique la existencia del archivo: " . $huellas_dir . $huella_filename);
}
// --- FIN DEPURACIÓN DE LA RUTA DE LA HUELLA ---


// Cargamos la plantilla de contrato HTML desde el archivo `templates/contrato_confidencialidad_tpl.php`.
// Es crucial que las variables se definan antes de incluir este archivo.
ob_start(); // Iniciar el buffer de salida
include 'templates/contrato_confidencialidad_tpl.php'; // Incluir la plantilla HTML/PHP
$contenido_contrato_html = ob_get_clean(); // Obtener el contenido del buffer y limpiar


// --- Crear nuevo documento PDF ---
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Contratos');
$pdf->SetTitle('Contrato de Confidencialidad - ' . $nombre_completo);
$pdf->SetSubject('Acuerdo de Confidencialidad');
$pdf->SetKeywords('Contrato, Confidencialidad, PDF, PHP, TCPDF');

// Establecer cabecera y pie de página (opcional)
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'CONTRATO DE CONFIDENCIALIDAD', 'Generado el: ' . date('d/m/Y H:i:s'));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// Establecer fuentes de cabecera y pie de página
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Establecer margenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Establecer auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Establecer factor de escala de imagen
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Establecer idioma (para caracteres especiales)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// Establecer fuente (ej. DejaVuSans es una buena opción para soporte UTF-8)
$pdf->SetFont('dejavusans', '', 10); // 'dejavusans' para mejor soporte UTF-8

// Añadir una página
$pdf->AddPage();

// Escribir el contenido HTML
$pdf->writeHTML($contenido_contrato_html, true, false, true, false, '');

// --- Insertar la imagen de la huella dactilar si existe ---
if (!empty($huella_path)) {
    // Calcular la posición Y para la huella.
    // Esto intentará colocarla un poco debajo de la última línea de texto del HTML
    // en el bloque de firma. Ajusta el offset (+5) si es necesario.
    $y_position_for_huella = $pdf->GetY() + 5; 
    
    // Centrar la imagen en la página (width es el ancho de la página, asumiendo márgenes de 15mm)
    $page_width = $pdf->GetPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT;
    $image_width = 30; // Ancho deseado de la imagen de la huella en mm
    $x_center = PDF_MARGIN_LEFT + ($page_width - $image_width) / 2;

    // *** TIPO DE IMAGEN: CAMBIADO A 'JPG' ***
    $pdf->Image($huella_path, $x_center, $y_position_for_huella, $image_width, 0, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    
    // Avanzar el cursor Y después de la imagen para dejar espacio si hay más contenido
    $pdf->SetY($y_position_for_huella + $image_width + 5); 
}


// ---------------------------------------------------------

// Cerrar y generar el documento PDF
// 'D' fuerza la descarga del archivo. 'I' lo muestra en el navegador. 'F' lo guarda en el servidor.
$file_name = 'Contrato_Confidencialidad_' . $cedula . '_' . date('Ymd') . '.pdf';
$pdf->Output($file_name, 'D');

exit();
?>