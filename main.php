<?php
session_start();

// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php'; // Asegúrate de que esta ruta sea correcta

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirigir a index.php si no está logueado
    exit();
}

// Datos del usuario desde la sesión
$user_id_logged = $_SESSION['user_id']; // Necesitamos el ID del usuario para la consulta

// Inicializar variables para asegurar que siempre estén definidas
$cedula_usuario = 'N/A';
$nombre_usuario = 'Usuario';
$correo_usuario = 'N/A';
$username_sesion = 'N/A'; // Para $_SESSION['username']

// Asignar valores desde la sesión si existen
if (isset($_SESSION['cedula'])) {
    $cedula_usuario = htmlspecialchars($_SESSION['cedula']);
}
if (isset($_SESSION['nombre'])) {
    $nombre_usuario = htmlspecialchars($_SESSION['nombre']);
}
if (isset($_SESSION['correo'])) {
    $correo_usuario = htmlspecialchars($_SESSION['correo']);
}
if (isset($_SESSION['username'])) {
    $username_sesion = htmlspecialchars($_SESSION['username']);
}


// Inicializar la variable para la foto de perfil desde la DB
$foto_perfil_db = '';

// *** Paso 1: Consultar la base de datos para obtener la ruta de la foto de perfil ***
// Usamos el ID del usuario para obtener su foto de perfil
if ($conn) { // Asegúrate de que $conn esté disponible de conexion.php
    $stmt = $conn->prepare("SELECT foto_perfil FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id_logged);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $foto_perfil_db = htmlspecialchars($user_data['foto_perfil'] ?? '');
        }
        $stmt->close();
    } else {
        // Manejar error en la preparación de la consulta si es necesario
        error_log("Error al preparar la consulta de foto de perfil: " . $conn->error);
    }
    // No cerrar la conexión aquí si otros includes posteriores la necesitarán.
    // Si esta es la única consulta a la DB para esta página, entonces puedes cerrar.
    // Para una aplicación web, a menudo es mejor cerrar la conexión al final del script principal.
    // $conn->close();
} else {
    error_log("La conexión a la base de datos no está disponible en main.php");
}

// Lógica para la foto de la cédula (si aún la quieres en el sidebar)
$ruta_base_cedulas = 'cedulas_fotos/';
$foto_cedula_existe = false;
$ruta_foto_cedula_mostrada = '';

// Buscar la foto de la cédula con extensiones comunes
$extensiones_cedula_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
foreach ($extensiones_cedula_permitidas as $ext) {
    $ruta_posible_cedula = $ruta_base_cedulas . $cedula_usuario . '.' . $ext;
    if (file_exists($ruta_posible_cedula)) {
        $ruta_foto_cedula_mostrada = $ruta_posible_cedula;
        $foto_cedula_existe = true;
        break;
    }
}

// Decidir qué imagen mostrar: primero la de perfil de la DB, si no, la de cédula, si no, el SVG.
$imagen_mostrar_sidebar = '';
if (!empty($foto_perfil_db) && file_exists($foto_perfil_db)) {
    $imagen_mostrar_sidebar = $foto_perfil_db;
} elseif ($foto_cedula_existe) {
    // Si no hay foto de perfil en la DB, pero hay una foto de cédula (menos común para un perfil de usuario)
    $imagen_mostrar_sidebar = $ruta_foto_cedula_mostrada;
}
// Si $imagen_mostrar_sidebar sigue vacía, se mostrará el SVG por defecto.

// Cerrar la conexión si ya no se va a usar en este script o en archivos incluidos.
if ($conn) {
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            min-height: 100vh;
        }
        #sidebar {
            width: 250px;
            background-color: #34495e;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #sidebar .user-info {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            width: 100%;
        }
        #sidebar .user-info img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f8f9fa;
            margin-bottom: 10px;
        }
        #sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            border-radius: 0.5rem;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
            width: 100%;
            text-align: left;
            display: flex; /* Para alinear icono y texto */
            align-items: center;
        }
        #sidebar .nav-link i { /* Estilo para los iconos */
            margin-right: 10px;
            width: 20px; /* Ancho fijo para los iconos */
            text-align: center;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background-color: #2c3e50;
        }
        #content {
            flex-grow: 1;
            padding: 20px;
        }
        .card {
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            margin-top: 20px;
        }
        .text-danger {
            color: #dc3545 !important; /* Para que el color rojo se aplique consistentemente */
        }
        .text-danger:hover {
            background-color: #dc3545 !important;
            color: white !important;
        }
        /* Estilos para alertas emergentes */
        #alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050; /* Por encima de otros elementos */
            max-width: 400px;
            width: 100%;
        }
        #alert-container .alert {
            margin-bottom: 1rem; /* Espacio entre múltiples alertas si se muestran */
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <div class="user-info">
            <?php if (!empty($imagen_mostrar_sidebar)): ?>
                <img src="<?php echo htmlspecialchars($imagen_mostrar_sidebar); ?>" alt="Foto de Perfil" class="img-fluid">
            <?php else: ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-circle text-light mb-2" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                </svg>
            <?php endif; ?>
            <h5><?php echo $nombre_usuario; ?></h5>
            <p class="mb-0 small"><?php echo $cedula_usuario; ?></p>
            <p class="mb-0 small"><?php echo $correo_usuario; ?></p>
        </div>

        <nav class="nav flex-column w-100">
            <a class="nav-link active" href="#" onclick="loadContent('dashboard_content.php'); return false;">
                <i class="fas fa-home"></i> Inicio / Estadísticas
            </a>
            <a class="nav-link" href="#" onclick="loadContent('mis_datos.php'); return false;">
                <i class="fas fa-user-edit"></i> Mis Datos
            </a>
            <a class="nav-link" href="#" onclick="loadContent('mainGetContrato.php'); return false;">
                <i class="fas fa-file-signature"></i> Generar Contrato
            </a>
            </a>
            <a class="nav-link" href="#" onclick="loadContent('historico.php'); return false;">
                <i class="fas fa-history"></i> Histórico
            </a>
            <a class="nav-link text-danger" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </nav>
    </div>

    <div id="content" class="container-fluid">
        <div id="alert-container"></div>

        <div class="card p-4">
            <div id="dynamic-content">
                <p>Cargando contenido...</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar alertas emergentes
        function displayAlert(status, message) {
            const alertContainer = document.getElementById('alert-container');
            if (!alertContainer) {
                console.error('El contenedor de alertas (#alert-container) no fue encontrado.');
                return;
            }

            // Limpiar cualquier alerta existente antes de añadir una nueva
            alertContainer.innerHTML = '';

            let alertClass = '';
            if (status === 'success') {
                alertClass = 'alert-success';
            } else if (status === 'error' || status === 'danger') {
                alertClass = 'alert-danger';
            } else if (status === 'warning') {
                alertClass = 'alert-warning';
            } else {
                alertClass = 'alert-info';
            }

            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertContainer.innerHTML = alertHtml;

            // Opcional: Auto-descartar la alerta después de unos segundos
            setTimeout(() => {
                const currentAlert = alertContainer.querySelector('.alert');
                if (currentAlert) {
                    const bsAlert = bootstrap.Alert.getInstance(currentAlert) || new bootstrap.Alert(currentAlert);
                    bsAlert.close();
                }
            }, 5000); // La alerta desaparecerá después de 5 segundos
        }

        // Función para cargar contenido dinámicamente
        function loadContent(page) {
            fetch(page)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('dynamic-content').innerHTML = html;
                    // Vuelve a ejecutar los scripts dentro del contenido cargado
                    var scripts = document.getElementById('dynamic-content').getElementsByTagName('script');
                    // Itera hacia atrás para evitar problemas con la colección HTML en vivo
                    for (var i = scripts.length - 1; i >= 0; i--) {
                        var oldScript = scripts[i];
                        var newScript = document.createElement('script');
                        newScript.text = oldScript.text;
                        if (oldScript.src) {
                            newScript.src = oldScript.src;
                        }
                        // Copiar todos los atributos del script original al nuevo
                        for (let j = 0; j < oldScript.attributes.length; j++) {
                            const attr = oldScript.attributes[j];
                            newScript.setAttribute(attr.name, attr.value);
                        }

                        // Eliminar el script antiguo del DOM
                        if (oldScript.parentNode) {
                            oldScript.parentNode.removeChild(oldScript);
                        }
                        // Añadir el nuevo script para que se ejecute
                        document.getElementById('dynamic-content').appendChild(newScript);
                    }

                    // Remover la clase 'active' de todos los enlaces y añadirla al actual
                    const navLinks = document.querySelectorAll('#sidebar .nav-link');
                    navLinks.forEach(link => link.classList.remove('active'));
                    
                    // Activar el enlace del sidebar correspondiente a la página cargada
                    let activeLinkFound = false;
                    navLinks.forEach(link => {
                        const linkHref = link.getAttribute('onclick');
                        // Ajustar la comparación para que sea más flexible
                        if (linkHref && linkHref.includes(`loadContent('${page.split('?')[0]}')`)) {
                             link.classList.add('active');
                             activeLinkFound = true;
                        }
                    });
                    // Si por alguna razón no se activó el enlace (ej. página cargada con parámetros),
                    // asegúrate de que mis_datos.php se active si es la página cargada
                    if (!activeLinkFound && page.includes('mis_datos.php')) {
                        const misDatosLink = document.querySelector('a[onclick*="mis_datos.php"]');
                        if (misDatosLink) {
                            navLinks.forEach(link => link.classList.remove('active')); 
                            misDatosLink.classList.add('active');
                        }
                    }

                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                    document.getElementById('dynamic-content').innerHTML = '<div class="alert alert-danger">Error al cargar el contenido.</div>';
                });
        }

        // Cargar el contenido de inicio (gráficos y resumen) cuando la página se carga por primera vez
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const message = urlParams.get('message');
            const pageAfterAlert = urlParams.get('page_after_alert'); // Nuevo parámetro

            // Mostrar la alerta si existen los parámetros de estado y mensaje
            if (status && message) {
                displayAlert(status, message);
                // Limpiar los parámetros de la URL para que la alerta no reaparezca al recargar
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // Determinar qué contenido cargar
            let pageToLoad = 'dashboard_content.php'; // Página por defecto

            if (pageAfterAlert) {
                pageToLoad = pageAfterAlert; // Usar la página especificada si está en la URL
            } else if (status && message) {
                // Si hay un aviso pero no se especificó 'page_after_alert',
                // y se asume que el aviso es de una acción en 'mis_datos.php'
                pageToLoad = 'mis_datos.php';
            }

            // Cargar el contenido determinado
            loadContent(pageToLoad);
        });
    </script>
</body>
</html>