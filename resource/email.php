<?php
session_start(); // ¡ESTA LÍNEA DEBE IR AL PRINCIPIO DEL ARCHIVO!
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Cédula - Mi Aplicación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos.css">
    <style>
        /* Estilos adicionales si los necesitas, por ejemplo, para espaciado del main */
        main {
            padding-top: 20px; /* Para que el contenido no quede pegado al nav */
            padding-bottom: 40px;
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">INE | CONTRATO DE CONFIDENCIALIDAD Y RESERVA DE INFORMACIÓN 
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
                            <a class="nav-link" href="#">Acerca de</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contacto</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg mt-5">
                <div class="card-header bg-warning text-black">
                    <h4 class="mb-0">Introduzca su correo electrónico.</h4>
                </div>
                <div class="card-body">
                    <form id="emailForm" action="getEmail.php" method="post">
                        <div class="mb-3">
                            <label for="emailInput" class="form-label">Correo Electrónico:</label>
                            <input type="email" class="form-control" id="emailInput" name="email"
                                placeholder="Ej: nombre@gmail.com"
                                required>
                            <div id="emailHelp" class="form-text">
                                Introduce tu email con el formato correcto (Ej: nombre@gmail.com).
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar</button>
                    </form>
                    <div id="searchResults" class="mt-4">
                        </div>
                </div>
            </div>
        </div>
    </div>
</main>

    <footer class="bg-light text-center text-lg-start mt-5 py-3">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">La Aplicación</h5>
                    <p>
                        Esta es una aplicación para el regístro de datos para el CONTRATO DE CONFIDENCIALIDAD Y RESERVA ABSOLUTA de los datos e información del Instituto Nacional de Estadísticas (INE).
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
</body>
</html>