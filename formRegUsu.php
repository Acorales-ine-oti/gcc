<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* General body styling */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Light grey background */
        }
        /* Card styling for consistent appearance */
        .card {
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        /* Card header styling */
        .card-header {
            background-color: #17a2b8; /* Bootstrap Teal/Info color for header */
            color: white;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            padding: 1.5rem;
        }
        /* Form control (input) styling */
        .form-control {
            border-radius: 0.5rem;
        }
        /* Info button styling (e.g., for Register) */
        .btn-info {
            background-color: #17a2b8; /* Same color as header */
            border-color: #17a2b8;
            color: white;
            border-radius: 0.5rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        /* Helper text styling */
        .form-text {
            color: #6c757d;
        }

        .navbar {
            background-color: #34495e !important; /* Un color más oscuro para el navbar */
        }
        .navbar-brand {
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
                            <a class="nav-link" href="#about">Acerca de</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#services">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Acceder</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header><br><br><br>

    <main class="container d-flex justify-content-center align-items-center">
        <div class="row w-100">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0">Formulario de Registro</h4>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-center lead mb-4">
                            ¡Bienvenido! Completa tus datos para registrarte.
                        </p>
                        <form id="registrationForm" action="register_user.php" method="post">
                            <div class="mb-3">
                                <label for="cedulaRegInput" class="form-label">Cédula de Identidad:</label>
                                <input type="text" class="form-control" id="cedulaRegInput" name="cedula" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="nombreInput" class="form-label">Nombre Completo:</label>
                                <input type="text" class="form-control" id="nombreInput" name="nombre" placeholder="Tu Nombre y Apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="usernameInput" class="form-label">Usuario:</label>
                                <input type="text" class="form-control" id="usernameInput" name="username" placeholder="nuevoUsuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="emailInput" class="form-label">Correo Electrónico:</label>
                                <input type="email" class="form-control" id="emailInput" name="email" placeholder="tu_email@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="passwordInput" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="passwordInput" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-info w-100">Registrarse</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main><br><br>


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
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="contrato.php" target="_self" class="text-dark">Enlace 01</a>
                    </li>
                    <li>
                        <a href="#!" class="text-dark">Enlace 02</a>
                    </li>
                </ul>
            </div>
            </div>
    </div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2025 Instituto Nacional de Estadísticas. Todos los derechos reservados.
    </div>
</footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the ID from the URL (if it comes from verify_captcha.php)
            const urlParams = new URLSearchParams(window.location.search);
            const cedula = urlParams.get('cedula');
            if (cedula) {
                document.getElementById('cedulaRegInput').value = decodeURIComponent(cedula);
            } else {
                // If there's no ID in the URL (direct access or error), redirect to home
                // console.error('No se encontró el número de cédula en la URL. Redirigiendo al inicio.');
                // window.location.href = 'index.html'; // Uncomment to force redirection
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
