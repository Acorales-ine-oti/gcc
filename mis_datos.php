<?php
session_start();

// Habilitar la visualización de errores para depuración.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de conexión a la base de datos.
require_once 'conexion.php';

// Verificar si la conexión a la base de datos es exitosa
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Verificar si el usuario ha iniciado sesión y si su ID está en la sesión.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Datos del usuario desde la sesión
$user_id_logged = $_SESSION['user_id'];
$cedula_usuario = htmlspecialchars($_SESSION['cedula'] ?? 'N/A');
$nombre_usuario_sesion = htmlspecialchars($_SESSION['nombre'] ?? 'Usuario');
$correo_usuario = htmlspecialchars($_SESSION['correo'] ?? 'N/A');

// Variables para prellenar el formulario
$nombre_formulario = '';
$apellido_formulario = '';
$telefono_formulario = '';
$foto_perfil_existente = ''; // Ruta de la foto de perfil desde la DB
$huella_dactilar_existente = ''; // Ruta de la huella dactilar desde la DB
$cargo_formulario = '';
$dependencia_formulario = '';

$mensaje_modal_inicial = "";
$datos_completos_existentes = false;

// Cargar los datos existentes del usuario desde la base de datos
if ($stmt = $conn->prepare("SELECT nombre, apellido, telefono, foto_perfil, huella_dactilar, cargo, dependencia FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $user_id_logged);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $datos_db = $result->fetch_assoc();
        $nombre_formulario = htmlspecialchars($datos_db['nombre'] ?? '');
        $apellido_formulario = htmlspecialchars($datos_db['apellido'] ?? '');
        $telefono_formulario = htmlspecialchars($datos_db['telefono'] ?? '');
        $foto_perfil_existente = htmlspecialchars($datos_db['foto_perfil'] ?? '');
        $huella_dactilar_existente = htmlspecialchars($datos_db['huella_dactilar'] ?? '');
        $cargo_formulario = htmlspecialchars($datos_db['cargo'] ?? '');
        $dependencia_formulario = htmlspecialchars($datos_db['dependencia'] ?? '');

        if (
            !empty($nombre_formulario) && $nombre_formulario !== '____________________' &&
            !empty($apellido_formulario) && $apellido_formulario !== '____________________' &&
            !empty($telefono_formulario) &&
            !empty($cargo_formulario) &&
            !empty($dependencia_formulario)
        ) {
            $datos_completos_existentes = true;
            $mensaje_modal_inicial = "Tus datos personales ya están registrados. Puedes actualizarlos si es necesario.";
        } else {
            $mensaje_modal_inicial = "Parece que tus datos personales no están completos o son los valores por defecto. Por favor, ingrésalos o actualízalos.";
        }
    } else {
        $mensaje_modal_inicial = "No hemos encontrado tus datos personales. Por favor, ingrésalos para completar tu perfil.";
    }
    $stmt->close();
} else {
    $mensaje_modal_inicial = "Error al intentar cargar tus datos (consulta DB fallida): " . $conn->error;
}

// Cerrar la conexión a la DB aquí. Es importante cerrarla después de usarla.
$conn->close();

// --- Lógica para mostrar el modal de notificación después de la redirección ---
$show_notification_modal = false;
$notification_message = "";
$notification_type = "";

if (isset($_GET['status']) && isset($_GET['message'])) {
    $show_notification_modal = true;
    $notification_message = htmlspecialchars($_GET['message']);
    $notification_type = htmlspecialchars($_GET['status']);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .modal-body {
            text-align: center;
        }
        .modal-header.success {
            background-color: #d4edda;
            color: #155724;
        }
        .modal-header.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .profile-img-container, .fingerprint-img-container {
            margin-top: 20px;
            text-align: center;
        }
        .profile-img, .fingerprint-img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 50%; /* Para hacerla circular si es foto de perfil */
            object-fit: cover;
            border: 2px solid #ddd;
        }
        /* Nuevo estilo para la imagen de cédula/perfil en la sección de datos de sesión */
        .cedula-display-img {
            max-width: 200px; /* Tamaño adecuado para la cédula */
            height: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            object-fit: contain; /* Ajusta la imagen dentro del contenedor sin recortar */
        }
        .placeholder-img {
            width: 150px;
            height: 150px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #6c757d;
            margin: auto;
            border: 2px dashed #adb5bd;
        }
        /* Estilo para el contenedor del aviso en la columna, para centrarlo verticalmente */
        .align-alert-center {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        /* Asegura que el alert no tenga margen inferior que desalinee */
        .align-alert-center .alert {
            margin-bottom: 0;
            width: 100%;
        }

        /* Custom style for file input to allow horizontal alignment */
        .form-control-file-custom {
            display: inline-block; /* Make it an inline-block element */
            width: auto; /* Allow its width to adjust */
            /* Default Bootstrap file input styling */
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h3 class="mb-3">Bienvenido, <?php echo $nombre_usuario_sesion; ?>!</h3>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-2">Mis Datos de Sesión</h4>
                <ul class="list-unstyled">
                    <li><strong>Cédula:</strong> <?php echo $cedula_usuario; ?></li>
                    <li><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION['username'] ?? 'N/A'); ?></li>
                    <li><strong>Correo:</strong> <?php echo $correo_usuario; ?></li>
                </ul>
            </div>
            <div class="col-md-6 align-alert-center">
                <div class="text-center">
                    <h5>Tu Foto de Perfil:</h5>
                    <?php
                    // MODIFICACIÓN CLAVE AQUÍ: Mostrar la foto de perfil existente
                    if (!empty($foto_perfil_existente) && file_exists($foto_perfil_existente)):
                    ?>
                        <img src="<?php echo htmlspecialchars($foto_perfil_existente); ?>" alt="Foto de Perfil del Usuario" class="img-fluid rounded-circle profile-img">
                        <small class="form-text text-muted mt-2">Esta es tu foto de perfil actual.</small>
                    <?php else: ?>
                        <div class="placeholder-img">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="alert alert-warning w-100 mt-2" role="alert">
                            No se ha cargado una foto de perfil para este usuario.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <h2 class="mb-4">Editar Datos de Usuario y Fotos</h2>
        <form action="actualizar_perfil.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre" value="<?php echo $nombre_formulario; ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese el apellido" value="<?php echo $apellido_formulario; ?>" required>
            </div>
            <div class="form-group">
                <label for="cedula">Cédula:</label>
                <input type="text" class="form-control" id="cedula" name="cedula"
                        value="<?php echo $cedula_usuario; ?>" required>
                <small class="form-text text-muted">Edite la cédula si es necesario.</small>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese el teléfono (ej. 04121234567)" pattern="^\d{11}$" title="Ingrese 11 dígitos numéricos (ej. 04121234567)" value="<?php echo $telefono_formulario; ?>" required>
            </div>

            <div class="form-group">
                <label for="cargo">Cargo:</label>
                <input type="text" class="form-control" id="cargo" name="cargo" placeholder="Ingrese su cargo" value="<?php echo $cargo_formulario; ?>" required>
            </div>
            <div class="form-group">
                <label for="dependencia">Dependencia:</label>
                <input type="text" class="form-control" id="dependencia" name="dependencia" placeholder="Ingrese su dependencia" value="<?php echo $dependencia_formulario; ?>" required>
            </div>
            <div class="form-group">
                <label for="foto_perfil">Foto de Perfil:</label>
                <div class="profile-img-container">
                    <?php
                    // Muestra la imagen de perfil si existe y es accesible (para la sección de subida)
                    if (!empty($foto_perfil_existente) && file_exists($foto_perfil_existente)):
                    ?>
                        <img src="<?php echo htmlspecialchars($foto_perfil_existente); ?>" alt="Foto de Perfil" class="profile-img">
                    <?php else: ?>
                        <div class="placeholder-img">
                            <i class="fas fa-user-circle"></i> </div>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <input type="file" class="form-control-file-custom" id="foto_perfil" name="foto_perfil" accept="image/jpeg, image/png, image/gif">
                </div>
                <small class="form-text text-muted">Carga tu foto de perfil (JPG, PNG, GIF). Se recomienda un tamaño cuadrado.</small>
            </div>

            <div class="form-group">
                <label for="huella_dactilar">Imagen de Huella Dactilar:</label>
                <div class="fingerprint-img-container">
                    <?php
                    // Muestra la imagen de la huella si existe y es accesible
                    if (!empty($huella_dactilar_existente) && file_exists($huella_dactilar_existente)):
                    ?>
                        <img src="<?php echo htmlspecialchars($huella_dactilar_existente); ?>" alt="Imagen de Huella Dactilar" class="fingerprint-img">
                    <?php else: ?>
                        <div class="placeholder-img">
                            <i class="fas fa-fingerprint"></i> </div>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <input type="file" class="form-control-file-custom" id="huella_dactilar" name="huella_dactilar" accept="image/jpeg, image/png, image/gif">
                </div>
                <small class="form-text text-muted">Carga una imagen de tu huella dactilar.</small>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <?php echo $datos_completos_existentes ? 'Actualizar Datos' : 'Ingresar Datos'; ?>
                </button>
            </div>
            <a href="logout.php" class="btn btn-danger mt-3">Cerrar Sesión</a>
        </form>
    </div>
</div>

<div class="modal fade" id="datosModalInicial" tabindex="-1" role="dialog" aria-labelledby="datosModalInicialLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="datosModalInicialLabel">Información de Datos Personales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $mensaje_modal_inicial; ?>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <?php if (!$datos_completos_existentes): ?>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="document.getElementById('nombre').focus();">Ingresar Datos</button>
                <?php else: ?>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="document.getElementById('nombre').focus();">Actualizar Datos</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?php echo $notification_type; ?>">
                <h5 class="modal-title" id="notificationModalLabel">
                    <?php echo ($notification_type == 'success') ? '¡Éxito!' : 'Error'; ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $notification_message; ?>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
    $(document).ready(function(){
        // Muestra el modal inicial si los datos no están completos (o no existen)
        <?php if (!$datos_completos_existentes): ?>
            $('#datosModalInicial').modal('show');
        <?php endif; ?>

        // Muestra el modal de notificación si hay un mensaje en la URL
        <?php if ($show_notification_modal): ?>
            $('#notificationModal').modal('show');
            // Opcional: limpiar la URL después de mostrar el modal para que no se muestre de nuevo al recargar
            window.history.replaceState({}, document.title, window.location.pathname);
        <?php endif; ?>
    });
</script>

</body>
</html>