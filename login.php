<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Include Bootstrap CSS from a CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inter font for modern aesthetics -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* General body styling */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Light grey background */
        }
        /* Card styling for consistent appearance */
        .card {
            border-radius: 0.75rem; /* More rounded corners for the card */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Pronounced shadow */
        }
        /* Card header styling */
        .card-header {
            background-color: #3498db; /* Blue header color, matching initial form */
            color: white;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            padding: 1.5rem;
        }
        /* Form control (input) styling */
        .form-control {
            border-radius: 0.5rem; /* Rounded corners for inputs */
        }
        /* Primary button styling (e.g., for Login) */
        .btn-primary {
            background-color: #28a745; /* Green button, matching initial form */
            border-color: #28a745;
            border-radius: 0.5rem; /* Rounded corners for the button */
            font-weight: 600; /* Bolder text */
            padding: 0.75rem 1.5rem; /* More padding for a better click area */
        }
        .btn-primary:hover {
            background-color: #218838; /* Darker green on hover */
            border-color: #1e7e34;
        }
        /* Helper text styling */
        .form-text {
            color: #6c757d;
        }


        footer {
            background-color: #e9ecef; /* Un color ligeramente diferente para el footer */
            color: #343a40; /* Color de texto más oscuro */
        }
        footer .text-dark:hover {
            color: #0056b3 !important; /* Cambio de color al pasar el mouse */
        }


        .navbar {
            background-color: #34495e !important; /* Un color más oscuro para el navbar */
        }
        .navbar-brand {
            font-weight: 600;
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
    </header><br><br><br>




    <main class="container d-flex justify-content-center align-items-center">
        <div class="row w-100">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0">Inicio de Sesión</h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="loginForm" action="process_login.php" method="post">
                            <div class="mb-3">
                                <label for="cedulaInput" class="form-label">Cédula de Identidad:</label>
                                <input type="text" class="form-control" id="cedulaInput" name="cedula"
                                    placeholder="Ej: 12345678" 
                                    title="Formato: 12345678">
                                <div id="cedulaHelp" class="form-text">
                                    Introduce tu cédula de identidad registrada.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="passwordInput" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="passwordInput" name="password"
                                    placeholder="Tu contraseña" required>
                                <div id="passwordHelp" class="form-text">
                                    Ingresa tu contraseña para acceder.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>
                        <div id="loginMessage" class="mt-4 text-center">
                            <!-- This div can be used to display login success or error messages -->
                            <!-- Example: <div class="alert alert-danger">Cédula o contraseña incorrecta.</div> -->
                        </div>
                        <p class="text-center mt-3">
                            <!--¿No tienes cuenta? <a href="index.html">Regístrate aquí</a>-->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main><br><br><br>



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






    <!-- Include Bootstrap JS (Bundle with Popper) at the end of the body for better performance -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
