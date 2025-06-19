<?php
session_start();
require_once 'conexion.php'; // Asegúrate de que la ruta sea correcta

// Habilitar la visualización de errores para depuración.
// ¡Desactívalo en producción!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Obtener la cédula original del usuario desde la sesión
// Esto es CRUCIAL para identificar el registro actual del usuario
$cedula_original_sesion = $_SESSION['cedula']; // Asume que la cédula del usuario está en la sesión

// Verificar que los datos del formulario hayan sido enviados por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nueva_cedula = htmlspecialchars($_POST['cedula'] ?? ''); // Ahora la cédula viene del formulario y puede ser nueva
    $nombre = htmlspecialchars($_POST['nombre'] ?? '');
    $apellido = htmlspecialchars($_POST['apellido'] ?? '');
    $telefono = htmlspecialchars($_POST['telefono'] ?? '');
    $cargo = htmlspecialchars($_POST['cargo'] ?? '');
    $dependencia = htmlspecialchars($_POST['dependencia'] ?? '');

    // --- MANEJO DE ARCHIVOS (Foto de Perfil y Huella Dactilar) ---
    // Variables para las rutas de los archivos existentes (si las hay)
    $foto_perfil_path = '';
    $huella_dactilar_path = '';

    // Primero, obtener las rutas actuales de los archivos si existen en la DB para no perderlas
    $stmt_fetch_paths = $conn->prepare("SELECT foto_perfil, huella_dactilar FROM users WHERE cedula = ?");
    if ($stmt_fetch_paths) {
        $stmt_fetch_paths->bind_param("s", $cedula_original_sesion);
        $stmt_fetch_paths->execute();
        $stmt_fetch_paths->bind_result($foto_perfil_existente_db, $huella_dactilar_existente_db);
        $stmt_fetch_paths->fetch();
        $stmt_fetch_paths->close();

        $foto_perfil_path = $foto_perfil_existente_db;
        $huella_dactilar_path = $huella_dactilar_existente_db;
    } else {
        header("Location: mis_datos.php?status=error&message=Error al obtener rutas de archivos existentes.");
        exit();
    }


    // Directorio donde se guardarán las imágenes
    $target_dir = "uploads/"; // Asegúrate de que esta carpeta exista y tenga permisos de escritura
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Procesar Foto de Perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES["foto_perfil"]["name"]);
        $target_file = $target_dir . uniqid() . '_' . $file_name; // Generar nombre único
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Validar tipo de archivo
        $check = getimagesize($_FILES["foto_perfil"]["tmp_name"]);
        if($check !== false) {
            // Permitir solo ciertos formatos de imagen
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowed_types)) {
                header("Location: mis_datos.php?status=error&message=Solo se permiten archivos JPG, JPEG, PNG y GIF para la foto de perfil.");
                exit();
            }
            if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
                // Si había una foto antigua, eliminarla para no acumular archivos
                if (!empty($foto_perfil_existente_db) && file_exists($foto_perfil_existente_db)) {
                    unlink($foto_perfil_existente_db);
                }
                $foto_perfil_path = $target_file;
            } else {
                header("Location: mis_datos.php?status=error&message=Error al subir la foto de perfil.");
                exit();
            }
        } else {
            header("Location: mis_datos.php?status=error&message=El archivo de foto de perfil no es una imagen válida.");
            exit();
        }
    }

    // Procesar Imagen de Huella Dactilar
    if (isset($_FILES['huella_dactilar']) && $_FILES['huella_dactilar']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES["huella_dactilar"]["name"]);
        $target_file = $target_dir . uniqid() . '_' . $file_name; // Generar nombre único
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Validar tipo de archivo
        $check = getimagesize($_FILES["huella_dactilar"]["tmp_name"]);
        if($check !== false) {
            // Permitir solo ciertos formatos de imagen
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowed_types)) {
                header("Location: mis_datos.php?status=error&message=Solo se permiten archivos JPG, JPEG, PNG y GIF para la huella dactilar.");
                exit();
            }
            if (move_uploaded_file($_FILES["huella_dactilar"]["tmp_name"], $target_file)) {
                // Si había una huella antigua, eliminarla para no acumular archivos
                if (!empty($huella_dactilar_existente_db) && file_exists($huella_dactilar_existente_db)) {
                    unlink($huella_dactilar_existente_db);
                }
                $huella_dactilar_path = $target_file;
            } else {
                header("Location: mis_datos.php?status=error&message=Error al subir la imagen de la huella dactilar.");
                exit();
            }
        } else {
            header("Location: mis_datos.php?status=error&message=El archivo de huella dactilar no es una imagen válida.");
            exit();
        }
    }

    // --- ACTUALIZAR/INSERTAR DATOS EN LA BASE DE DATOS ---
    // Verificar si el usuario ya tiene un registro completo (basado en la cédula original de la sesión)
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE cedula = ?");
    if ($stmt_check) {
        $stmt_check->bind_param("s", $cedula_original_sesion);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $user_exists = $result_check->num_rows > 0;
        $stmt_check->close();
    } else {
        header("Location: main.php?status=error&message=Error de preparación al verificar usuario.");
        exit();
    }

    if ($user_exists) {
        // Actualizar datos existentes
        $sql = "UPDATE users SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, foto_perfil = ?, huella_dactilar = ?, cargo = ?, dependencia = ? WHERE cedula = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssssss", $nombre, $apellido, $nueva_cedula, $telefono, $foto_perfil_path, $huella_dactilar_path, $cargo, $dependencia, $cedula_original_sesion);
            if ($stmt->execute()) {
                // Actualizar la sesión si la cédula ha cambiado
                $_SESSION['cedula'] = $nueva_cedula;
                $_SESSION['nombre'] = $nombre; // Actualizar el nombre en la sesión también
                header("Location: main.php?status=success&message=¡Datos actualizados exitosamente!");
                exit();
            } else {
                header("Location: main.php?status=error&message=Error al actualizar datos: " . $stmt->error);
                exit();
            }
            $stmt->close();
        } else {
            header("Location: mis_datos.php?status=error&message=Error en la preparación de la consulta de actualización: " . $conn->error);
            exit();
        }
    } else {
        // En este escenario, si el usuario no existe, podría significar que el registro inicial no se completó.
        // Si el 'id' en la sesión es el mismo que el id del registro, entonces actualizamos por ID.
        // Si no, podríamos insertar. Pero el flujo actual de mis_datos.php sugiere que siempre habrá un user_id_logged.
        // Asumiendo que el usuario ya existe en la tabla 'users' al iniciar sesión, y esta es una actualización de perfil.
        // Si 'users' solo contiene 'id', 'username', 'password', 'cedula', etc., pero 'nombre', 'apellido', etc., están vacíos,
        // entonces esto sigue siendo una 'actualización' en el sentido de completar los datos por primera vez.
        // Mantenemos la lógica de UPDATE para la cédula original de la sesión.
        // Si la intención es insertar un *nuevo* usuario desde aquí, el flujo sería diferente (lo cual no es el caso actual).
        header("Location: main.php?status=error&message=No se encontró un registro existente para actualizar. Por favor, asegúrese de que su sesión esté activa y que su cédula esté registrada.");
        exit();
    }

} else {
    // Si la solicitud no es POST, redirigir
    header("Location: main.php");
    exit();
}

$conn->close();
?>