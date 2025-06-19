<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro y Aceptación de Términos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="estiloConfidencialidda.css">
    <style>
        .footer-collapse {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .footer-collapse h5 {
            cursor: pointer;
        }
        .contenedorDocumentoLegal {
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
             <center> INE | CONTRATO DE CONFIDENCIALIDAD Y RESERVA DE INFORMACIÓN. </center>
            </div>

            <!--========================================ACORDIÓN=============================================-->

            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            VER TÉRMINOS DE CONFIDENCIALIDAD Y RESERVA DE INFORMACIÓN.
                        </button>
                    </h2>

                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                          Mediante el presente Contrato, usted declara estar comprometido o comprometida en mantener la  <strong> CONFIDENCIALIDAD Y RESERVA ABSOLUTA </strong> de los datos e información de los cuales tenga conocimiento por cualquier medio de los órganos o entidades del sector público en el ejercicio de sus funciones como Contratado, en el Instituto Nacional de Estadísticas (INE), tal y como se encuentra instituido en el artículo 19 del Decreto con Rango, Valor y Fuerza de Ley de la Función Pública de Estadísticas de la República Bolivariana de Venezuela, Publicada en la Gaceta Oficial de la República Bolivariana de Venezuela Nº 37.321 de fecha 09 de noviembre de 2001, el cual tutela el secreto estadístico, cuando señala:<br><br>

                          <ul>
                            <li>
                              Artículo 19 “Están amparados por el secreto estadístico los datos personales obtenidos directamente o por medio de información administrativa, que por su contenido, estructura o grado de desagregación identifiquen a los informantes”.
                            </li>
                          </ul>
                          De igual forma, usted se compromete a mantener la <strong> CONFIDENCIALIDAD Y RESERVA ABSOLUTA</strong>  en el ejercicio de las actividades y desarrollo del XV Censo Nacional de Población y Vivienda, conforme a lo establecido en el artículo 17 de la misma Ley que expresamente señala:<br><br>
                            <ul>
                              <li>
                                Artículo 17: “La Información estadística de interés público tendrá carácter oficial cuando el Instituto Nacional de Estadística la certifique y se haga pública a través de los órganos estadísticos. El personal de los órganos estadísticos no podrá suministrar información estadística parcial o total, provisional o definitiva, que conozca por razón de su trabajo, hasta tanto la misma se haya hecho oficialmente pública.”
                              </li>
                            </ul>
                            Igualmente, debe declarar CONFIDENCIALIDAD Y RESERVA ABSOLUTA del secreto estadístico una vez culminada las labores y actividades debidamente encomendadas por el Instituto Nacional de Estadísticas, tal como se encuentra establecido en el artículo 23 del Decreto con Rango, Valor y Fuerza de la Ley de la Función Pública de Estadísticas, el cual señala:<br><br>  
                            <ul>
                              <li>
                                Articulo 23. “Toda persona natural o jurídica, pública o privada que intervenga en la actividad estadística del Sistema Estadístico Nacional o tenga conocimiento de datos amparados tiene la obligación de mantener  el secreto estadístico, aún después de concluir sus actividades profesionales o su vinculación con los servicios estadísticos”.
                              </li>
                            </ul> 
                            Para efectos del presente compromiso, es importante destacar que la  “Información Confidencial” comprende toda la información divulgada por cualquiera de las partes ya sea en forma oral, visual, escrita, grabada en medios magnéticos o en cualquier otra forma tangible y que se encuentre claramente marcada como tal al ser entregada a la parte receptora.<br><br>
                            Quedando usted sujeto (a) a las sanciones establecidas en el Decreto con Rango, Valor y Fuerza de Ley de la Función Estadística y a cualquier otra norma legal  homologa aplicable por el cumplimiento de lo aquí declarado.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                          RAGÍSTRO DEL CONTRATO DE CONFIDENCIALIDAD Y RESERVA DE INFORMACIÓN.
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                        <div class="accordion-body">

                          <!--========================================FOMULARIO=============================================-->

                            <div class="card-body">
                            <form id="registroForm" method="post" action="ver-contrato.php">
                                <div class="form-group">
                                    <label for="nombre_Form">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre_Form" name="nombre_Form" placeholder="Ingrese su nombre" required>
                                </div>
                                <div class="form-group">
                                    <label for="apellido_Form">Apellido:</label>
                                    <input type="text" class="form-control" id="apellido_Form" name="apellido_Form" placeholder="Ingrese su apellido" required>
                                </div>
                                <div class="form-group">
                                    <label for="cedula_Form">Cédula:</label>
                                    <input type="text" class="form-control" id="cedula_Form" name="cedula_Form" placeholder="Ingrese su cédula" required>
                                </div>
                                <div class="form-group">
                                    <label for="funciones_Form">Funciones:</label>
                                    <input type="text" class="form-control" id="funciones_Form" name="funciones_Form" placeholder="Ingrese sus funciones" required>
                                </div>
                                <div class="form-group">
                                    <label for="dependencia_Form">Dependencia:</label>
                                    <input type="text" class="form-control" id="dependencia_Form" name="dependencia_Form" placeholder="Ingrese su dependencia" required>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="terminos_Form" name="terminos_Form" required>
                                    <label class="form-check-label" for="terminos_Form">Acepto los términos</label>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">VER CONTRATO</button>
                            </form>
                          </div>
                        </div>
                    </div>
                </div>

            </div>

              
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>