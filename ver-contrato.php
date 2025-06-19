<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php"); // Redirigir a index.php si no está logueado
    exit();
}

// Datos del usuario desde la sesión
$cedula_usuario = htmlspecialchars($_SESSION['cedula'] ?? 'N/A');
$nombre_usuario = htmlspecialchars($_SESSION['nombre'] ?? 'Usuario');
$correo_usuario = htmlspecialchars($_SESSION['correo'] ?? 'N/A'); // Asumiendo que también guardaste el correo

// Ruta para la foto de la cédula
// Asume que las imágenes están en una carpeta 'cedulas_fotos'
// dentro de tu directorio raíz y se llaman como la cédula (sin V- o E-)
// y tienen extensión .jpg. Ajusta esto según cómo las almacenes.
$ruta_foto_cedula = 'cedulas_fotos/' . str_replace(['V-', 'E-'], '', $cedula_usuario) . '.jpg';

// Verificar si la foto de la cédula existe
$foto_cedula_existe = file_exists($ruta_foto_cedula);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Light grey background */
            display: flex; /* Para el layout del sidebar */
            min-height: 100vh; /* Asegura que ocupe toda la altura de la ventana */
        }
        #sidebar {
            width: 250px;
            background-color: #34495e; /* Darker blue-grey for sidebar */
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center; /* Centrar contenido del sidebar */
        }
        #sidebar .user-info {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            width: 100%; /* Ocupar todo el ancho para el borde */
        }
        #sidebar .user-info img {
            width: 100px;
            height: 100px;
            border-radius: 50%; /* Circular image */
            object-fit: cover; /* Asegura que la imagen se vea bien */
            border: 3px solid #f8f9fa; /* Borde alrededor de la foto */
            margin-bottom: 10px;
        }
        #sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            border-radius: 0.5rem;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
            width: 100%; /* Ocupar todo el ancho del sidebar */
            text-align: left; /* Alinear texto a la izquierda */
        }
        #sidebar .nav-link:hover {
            background-color: #2c3e50; /* Darker on hover */
        }
        #content {
            flex-grow: 1; /* Ocupa el resto del espacio */
            padding: 20px;
        }
        .card {
            border-radius: 0.75rem; /* More rounded corners for the card */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Pronounced shadow */
        }


        .contenedorDocumentoLegal {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .indent {
            margin-left: 2em;
        }


        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Light grey background */
            display: flex; /* Para el layout del sidebar */
            min-height: 100vh; /* Asegura que ocupe toda la altura de la ventana */
        }
        #sidebar {
            width: 250px;
            background-color: #34495e; /* Darker blue-grey for sidebar */
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center; /* Centrar contenido del sidebar */
        }
        #sidebar .user-info {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            width: 100%; /* Ocupar todo el ancho para el borde */
        }
        #sidebar .user-info img {
            width: 100px;
            height: 100px;
            border-radius: 50%; /* Circular image */
            object-fit: cover; /* Asegura que la imagen se vea bien */
            border: 3px solid #f8f9fa; /* Borde alrededor de la foto */
            margin-bottom: 10px;
        }
        #sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            border-radius: 0.5rem;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
            width: 100%; /* Ocupar todo el ancho del sidebar */
            text-align: left; /* Alinear texto a la izquierda */
        }
        #sidebar .nav-link:hover {
            background-color: #2c3e50; /* Darker on hover */
        }
        #content {
            flex-grow: 1; /* Ocupa el resto del espacio */
            padding: 20px;
        }
        .card {
            border-radius: 0.75rem; /* More rounded corners for the card */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Pronounced shadow */
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <div class="user-info">
            <?php if ($foto_cedula_existe): ?>
                <img src="<?php echo $ruta_foto_cedula; ?>" alt="Foto de Cédula" class="img-fluid">
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
            <a class="nav-link active" href="#" onclick="loadContent('mis_datos.php'); return false;">Mis Datos</a>
            <a class="nav-link" href="#" onclick="loadContent('mainGetContrato.php'); return false;">Generar Contrato</a>
            <a class="nav-link" href="#" onclick="loadContent('ver_imprimir_contrato.php'); return false;">Ver e Imprimir Contrato</a>
            <a class="nav-link text-danger" href="logout.php">Cerrar Sesión</a>
        </nav>
    </div>

    <div id="content" class="container-fluid">
        <div class="card p-4">

            <div id="dynamic-content">

                <?php
                    // guardar_datos.php

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Verificar si los campos están definidos y no vacíos
                        if (isset($_POST["nombre_Form"]) && !empty($_POST["nombre_Form"]) &&
                            isset($_POST["apellido_Form"]) && !empty($_POST["apellido_Form"]) &&
                            isset($_POST["cedula_Form"]) && !empty($_POST["cedula_Form"]) &&
                            isset($_POST["funciones_Form"]) && !empty($_POST["funciones_Form"]) &&
                            isset($_POST["dependencia_Form"]) && !empty($_POST["dependencia_Form"]) &&
                            isset($_POST["terminos_Form"])) {

                            // Recibir y sanitize the data
                            $nombre = htmlspecialchars($_POST["nombre_Form"]);
                            $apellido = htmlspecialchars($_POST["apellido_Form"]);
                            $cedula = htmlspecialchars($_POST["cedula_Form"]);
                            $funciones = htmlspecialchars($_POST["funciones_Form"]);
                            $dependencia = htmlspecialchars($_POST["dependencia_Form"]);
                            $terminos = htmlspecialchars($_POST["terminos_Form"]);

                            // Combine name
                            $nombreCompleto = $nombre . " " . $apellido;

                            // Get the current date
                            $fechaActual = new DateTime();
                            $dia = $fechaActual->format('d');
                            $mes = $fechaActual->format('F'); // Full month name (e.g., January)
                            $anio = $fechaActual->format('Y');


                        } else {
                            $error = "Por favor, complete todos los campos del formulario.";
                        }
                    }

                    ?>



<div class="contenedorDocumentoLegal">
            <h2 class="text-center mb-4">CONTRATO DE CONFIDENCIALIDAD Y RESERVA DE INFORMACIÓN</h2>
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <p class="text-justify">
                Yo, <strong><span id="nombre_Form_contrato"><?php if (isset($nombreCompleto)) echo strtoupper($nombreCompleto); ?></span></strong>,
                de nacionalidad venezolana, mayor de edad, de este domicilio y titular de la cédula de identidad Nro.
                <strong>V-<span id="cedula_Form_contrato"><?php if (isset($cedula)) echo strtoupper($cedula); ?></span></strong>,
                en mis funciones como
                <strong><span id="funciones_Form_contrato"><?php if (isset($funciones)) echo strtoupper($funciones); ?></span></strong>,
                adscrito a la
                <strong><span id="dependencia_Form_contrato"><?php if (isset($dependencia)) echo strtoupper($dependencia); ?></span></strong>
                del INE, mediante la presente declaro mi
                compromiso de mantener la CONFIDENCIALIDAD Y RESERVA ABSOLUTA de los datos e información de los cuales tenga
                conocimiento por cualquier medio de los órganos o entidades del sector público en el ejercicio de mis funciones
                como Contratado, en el Instituto Nacional de Estadísticas (INE), tal y como se encuentra instituido en el
                artículo 19 del Decreto con Rango, Valor y Fuerza de Ley de la Función Pública de Estadísticas de la República
                Bolivariana de Venezuela, Publicada en la Gaceta Oficial de la República Bolivariana de Venezuela Nº 37.321 de
                fecha 09 de noviembre de 2001, el cual tutela el secreto estadístico, cuando señala:
            </p>
            <p class="indent text-justify">
                <strong>Artículo 19:</strong> “Están amparados por el secreto estadístico los datos personales obtenidos
                directamente o por medio de información administrativa, que por su contenido, estructura o grado de
                desagregación identifiquen a los informantes”.
            </p>
            <p class="text-justify">
                De igual forma, me comprometo a mantener la CONFIDENCIALIDAD Y RESERVA ABSOLUTA en el ejercicio de las
                actividades y desarrollo del XV Censo Nacional de Población y Vivienda, conforme a lo establecido en el
                artículo 17 de la misma Ley que expresamente señala:
            </p>
            <p class="indent ms-3 text-justify">
                <strong>Artículo 17:</strong> “La Información estadística de interés público tendrá carácter oficial cuando el
                Instituto Nacional de Estadística la certifique y se haga pública a través de los órganos estadísticos. El
                personal de los órganos estadísticos no podrá suministrar información estadística parcial o total, provisional
                o definitiva, que conozca por razón de su trabajo,
                hasta tanto la misma se haya hecho oficialmente pública.”
            </p>
            <p class="text-justify">
                Igualmente, mediante la presente declaro la CONFIDENCIALIDAD Y RESERVA ABSOLUTA del secreto estadístico una
                vez culminada las labores y actividades debidamente encomendadas por el Instituto Nacional de Estadísticas, tal
                como se encuentra establecido en el artículo 23 del Decreto con Rango, Valor y Fuerza de la Ley de la Función
                Pública de Estadísticas, el cual señala:
            </p>
            <p class="indent ms-3 text-justify">
                <strong>Artículo 23:</strong> “Toda persona natural o jurídica, pública o privada que intervenga en la actividad
                estadística del Sistema Estadístico Nacional o tenga conocimiento de datos amparados tiene la obligación de
                mantener el secreto estadístico, aún después de concluir sus actividades profesionales o su vinculación con los
                servicios estadísticos”.
            </p>
            <p class="text-justify">
                Para efectos del presente compromiso, es importante destacar que la “Información Confidencial” comprende toda la
                información divulgada por cualquiera de las partes ya sea en forma oral, visual, escrita, grabada en medios
                magnéticos o en cualquier otra forma tangible y que se encuentre claramente marcada como tal al ser entregada a
                la parte receptora.
            </p>
            <p class="text-justify">
                Quedando sujeto (a) a las sanciones establecidas en el Decreto con Rango, Valor y Fuerza de Ley de la Función
                Estadística y a cualquier otra norma legal homologa aplicable por el cumplimiento de lo aquí declarado.
            </p>
            <p class="text-center">
                En Caracas, a los <span id="dia_contrato"><?php if (isset($dia)) echo $dia; ?></span> días del mes de <span
                    id="mes_contrato"><?php if (isset($mes)) echo $mes; ?></span> de <span
                    id="anio_contrato"><?php if (isset($anio)) echo $anio; ?></span>.
            </p>

            <form action="registra_db.php" method="post">
                <input type="hidden" name="nombre" value="<?php if (isset($nombre)) echo $nombre; ?>">
                <input type="hidden" name="apellido" value="<?php if (isset($apellido)) echo $apellido; ?>">
                <input type="hidden" name="cedula" value="<?php if (isset($cedula)) echo $cedula; ?>">
                <input type="hidden" name="funciones" value="<?php if (isset($funciones)) echo $funciones; ?>">
                <input type="hidden" name="dependencia" value="<?php if (isset($dependencia)) echo $dependencia; ?>">
                <input type="hidden" name="terminos" value="<?php if (isset($terminos)) echo $terminos; ?>">
                <input type="hidden" name="fecha_dia" value="<?php if (isset($dia)) echo $dia; ?>">
                <input type="hidden" name="fecha_mes" value="<?php if (isset($mes)) echo $mes; ?>">
                <input type="hidden" name="fecha_anio" value="<?php if (isset($anio)) echo $anio; ?>">


                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th class="text-center">Nombre y Apellido</th>
                            <th class="text-center">Cédula</th>
                            <th class="text-center">Huella Dactilar/Firma</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" id="nombreApellido_tabla_footer"><?php if (isset($nombreCompleto)) echo strtoupper($nombreCompleto); ?></td>
                            <td class="text-center" id="cedula_tabla_footer">V-<?php if (isset($cedula)) echo strtoupper($cedula); ?></td>
                            <td class="text-center" id="huellaFirma_tabla_footer">Pendiente</td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-success mt-3" >Aceptar</button>
                <a href="main.php" class="btn btn-secondary mt-3">Rechazar</a>  </div>
            </form>
        </div>











            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Función para cargar contenido dinámicamente en el div #dynamic-content
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
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                    document.getElementById('dynamic-content').innerHTML = '<div class="alert alert-danger">Error al cargar el contenido.</div>';
                });

            // Opcional: Remover la clase 'active' de todos los enlaces y añadirla al actual
            const navLinks = document.querySelectorAll('#sidebar .nav-link');
            navLinks.forEach(link => link.classList.remove('active'));
            // Usar 'this' para el enlace clickeado, o buscarlo por href si la función se llama de otra forma
            const clickedLink = document.querySelector(`a[href="#" onclick="loadContent('${page}'); return false;"]`);
            if (clickedLink) {
                clickedLink.classList.add('active');
            }
        }
    </script>
</body>
</html>






    <!--================================================================================-->


