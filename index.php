<?php
session_start(); // ¡ESTA LÍNEA DEBE IR AL PRINCIPIO DEL ARCHIVO!
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INE | Sistema Integral de Reserva de Información (SIRI)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Light grey background */
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ocupa toda la altura de la ventana */
        }
        main {
            flex-grow: 1; /* Permite que el contenido principal ocupe el espacio restante */
            padding-top: 50px; /* Ajuste para el navbar fijo si lo tienes */
            padding-bottom: 40px;
        }
        .navbar {
            background-color: #34495e !important; /* Un color más oscuro para el navbar */
        }
        .navbar-brand {
            font-weight: 600;
        }
        .card {
            border-radius: 0.75rem; /* Más redondeado */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1); /* Sombra suave */
        }
        .card-header {
            background-color: #3498db !important; /* Color azul para el header de la tarjeta */
            color: white;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            font-weight: 600;
        }
        footer {
            background-color: #e9ecef; /* Un color ligeramente diferente para el footer */
            color: #343a40; /* Color de texto más oscuro */
        }
        footer .text-dark:hover {
            color: #0056b3 !important; /* Cambio de color al pasar el mouse */
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2874a6;
            border-color: #2874a6;
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    INE | Gestor de Contratos de Confidencialidad (GCC)
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="login.php">Acceder</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card mt-5"> <div class="card-header text-center">
                        <h4 class="mb-0">Búsqueda de Cédula de Identidad</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        // Mostrar mensajes de sesión si existen
                        if (isset($_SESSION['message_content'])) {
                            $alert_class = 'alert-info'; // Default para 'info'
                            if (isset($_SESSION['message_type'])) {
                                switch ($_SESSION['message_type']) {
                                    case 'success':
                                        $alert_class = 'alert-success';
                                        break;
                                    case 'error':
                                        $alert_class = 'alert-danger';
                                        break;
                                    case 'warning':
                                        $alert_class = 'alert-warning';
                                        break;
                                    case 'info':
                                    default:
                                        $alert_class = 'alert-info';
                                        break;
                                }
                            }
                            echo '<div class="alert ' . $alert_class . ' alert-dismissible fade show" role="alert">';
                            echo $_SESSION['message_content'];
                            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                            echo '</div>';
                            // Limpiar los mensajes de la sesión después de mostrarlos
                            unset($_SESSION['message_content']);
                            unset($_SESSION['message_type']);
                        }
                        ?>

                        <form id="searchForm" action="redireccionar.php" method="post">
                            <div class="mb-3">
                                <label for="cedulaInput" class="form-label">Número de Cédula:</label>
                                <input type="text" class="form-control" id="cedulaInput" name="cedula" 
                                      placeholder="Ej: 12345678" 
                                      required pattern="^\d{6,9}$" 
                                      title="Solo números, entre 6 y 9 dígitos.">
                                <div id="cedulaHelp" class="form-text">
                                    Introduce solo los números de tu cédula (sin puntos, comas ni letras).
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Buscar</button>
                        </form>
                        <div id="searchResults" class="mt-4">
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-auto py-3"> <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">La Aplicación</h5>
                    <p>
                        Se presenta el Gestor de Contratos de Confidencialidad (GCC), diseñado para la administración y control de los contratos de confidencialidad y reserva absoluta de los datos e información del Instituto Nacional de Estadística (INE).
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Enlaces Útiles</h5>
                    <ul class="list mb-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#services">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contacto</a>
                        </li>
                    </ul>
                </div>
                </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2025 Instituto Nacional de Estadísticas. Todos los derechos reservados.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>