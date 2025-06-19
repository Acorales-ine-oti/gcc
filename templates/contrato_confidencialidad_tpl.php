<?php
// Este archivo está diseñado para ser incluido en descargar_contrato.php o ver_contrato.php
// y espera que las variables como $nombre_completo, $cedula, $cargo, etc.,
// ya estén definidas en el ámbito de donde se incluye.

// Asegúrate de que todas las variables necesarias estén definidas antes de usar este template.
$nombre_completo = $nombre_completo ?? '[NOMBRE COMPLETO DEL EMPLEADO]';
$cedula = $cedula ?? '[CÉDULA DEL EMPLEADO]';
$cargo = $cargo ?? '[CARGO DEL EMPLEADO]';
$dependencia = $dependencia ?? '[DEPENDENCIA DEL EMPLEADO]';
// $fecha_contrato debe venir en formato YYYY-MM-DD desde el script principal (descargar_contrato.php o ver_contrato.php)
$fecha_contrato = $fecha_contrato ?? date('Y-m-d'); 

// Variables de la empresa (pueden ser dinámicas o estáticas según tu aplicación)
$nombre_empresa = 'Instituto Nacional de Estadísticas (INE)'; // Ejemplo
$direccion_empresa = 'Av. José Feliz Sosa, entre Av. Sur de ALtamira y Av. del Ávila, Torre Británica, Mezzanina 2, Altamira, Caracas, Distrito Capital.'; // Ejemplo
$representante_empresa_nombre = '[NOMBRE Y APELLIDO DEL REPRESENTANTE DE LA EMPRESA]';
$representante_empresa_cargo = '[CARGO DEL REPRESENTANTE DE LA EMPRESA]';

// --- LÓGICA PARA LA FECHA EN LETRAS Y NÚMEROS (YA CORRECTA) ---
$fecha_timestamp = strtotime($fecha_contrato); // Usar $fecha_contrato directamente
$dia_contrato_numero = date('d', $fecha_timestamp); // Día en número (ej. "17")
$ano_contrato = date('Y', $fecha_timestamp); // Año (ej. "2025")

// Arreglo manual con los meses en español
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$mes_contrato_numero = (int)date('m', $fecha_timestamp);
$mes_contrato_letras = $meses[$mes_contrato_numero] ?? '';

// Función para convertir número a palabra para el día (solo hasta 31)
function numeroApalabraDia($num) {
    $unidades = [
        0 => 'cero', 1 => 'uno', 2 => 'dos', 3 => 'tres', 4 => 'cuatro', 5 => 'cinco',
        6 => 'seis', 7 => 'siete', 8 => 'ocho', 9 => 'nueve', 10 => 'diez',
        11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince',
        16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho', 19 => 'diecinueve', 20 => 'veinte',
        21 => 'veintiuno', 22 => 'veintidós', 23 => 'veintitrés', 24 => 'veinticuatro', 25 => 'veinticinco',
        26 => 'veintiséis', 27 => 'veintisiete', 28 => 'veintiocho', 29 => 'veintinueve', 30 => 'treinta',
        31 => 'treinta y uno'
    ];
    return $unidades[$num] ?? '';
}
$dia_contrato_letras = numeroApalabraDia((int)$dia_contrato_numero);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plantilla de Contrato de Confidencialidad</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Usar DejaVu Sans para mejor soporte UTF-8 en TCPDF */
            font-size: 11pt;
            line-height: 1.6; /* Ajustado ligeramente para mejor lectura */
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            font-size: 14pt;
            margin-bottom: 20px;
            text-transform: uppercase; /* Para que coincida con el título del Word */
            margin-top: 0px; /* Asegura que no haya margen superior indeseado en la primera página */
        }
        h2 {
            font-size: 14pt;
            margin-top: 25px;
            margin-bottom: 10px;
        }
        p {
            margin-bottom: 10px;
            text-align: justify;
        }
        strong {
            font-weight: bold;
        }
        ul, ol {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        li {
            margin-bottom: 5px;
        }
        .signature-block {
            margin-top: 50px; /* Ajustado para más espacio entre el texto y la firma */
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid black;
            width: 70%;
            margin: 0 auto;
            margin-bottom: 5px;
        }
        .ci-line {
            margin-top: 5px;
        }
        /* No necesitas un contenedor para la huella si TCPDF la inserta directamente.
           Solo el texto "Huella dactilar:" es suficiente para indicarlo. */
    </style>
</head>
<body>
    <h1>CONTRATO DE CONFIDENCIALIDAD Y RESERVA DE INFORMACIÓN</h1>

    <p>Yo, <strong><?php echo $nombre_completo; ?></strong>, de nacionalidad venezolana, mayor de edad, de este domicilio y titular de la cédula de identidad Nro. V- <strong><?php echo $cedula; ?></strong>, en mis funciones como <strong><?php echo $cargo; ?></strong>, adscrito a la <strong><?php echo $dependencia; ?></strong> del INE, mediante la presente declaro mi compromiso de mantener la <strong>CONFIDENCIALIDAD Y RESERVA ABSOLUTA</strong> de los datos e información de los cuales tenga conocimiento por cualquier medio de los órganos o entidades del sector público en el ejercicio de mis funciones como Contratado, en el Instituto Nacional de Estadísticas (INE), tal y como se encuentra instituido en el artículo 19 del Decreto con Rango, Valor y Fuerza de Ley de la Función Pública de Estadísticas de la República Bolivariana de Venezuela, Publicada en la Gaceta Oficial de la República Bolivariana de Venezuela Nº 37.321 de fecha 09 de noviembre de 2001, el cual tutela el secreto estadístico, cuando señala:</p>

    <p><strong>Artículo 19</strong> “Están amparados por el secreto estadístico los datos personales obtenidos directamente o por medio de información administrativa, que por su contenido, estructura o grado de desagregación identifiquen a los informantes”.</p>

    <p>De igual forma, me comprometo a mantener la <strong>CONFIDENCIALIDAD Y RESERVA ABSOLUTA</strong> en el ejercicio de las actividades y desarrollo del <strong>XV Censo Nacional de Población y Vivienda</strong>, conforme a lo establecido en el artículo 17 de la misma Ley que expresamente señala:</p>

    <p><strong>Artículo 17:</strong> “La Información estadística de interés público tendrá carácter oficial cuando el Instituto Nacional de Estadística la certifique y se haga pública a través de los órganos estadísticos. El personal de los órganos estadísticos no podrá suministrar información estadística parcial o total, provisional o definitiva, que conozca por razón de su trabajo, hasta tanto la misma se haya hecho oficialmente pública.”</p>

    <p>Igualmente, mediante la presente declaro la <strong>CONFIDENCIALIDAD Y RESERVA ABSOLUTA</strong> del secreto estadístico una vez culminadas las labores y actividades debidamente encomendadas por el Instituto Nacional de Estadísticas, tal como se encuentra establecido en el artículo 23 del Decreto con Rango, Valor y Fuerza de la Ley de la Función Pública de Estadísticas, el cual señala:</p>

    <p><strong>Artículo 23:</strong> “Toda persona natural o jurídica, pública o privada que intervenga en la actividad estadística del Sistema Estadístico Nacional o tenga conocimiento de datos amparados tiene la obligación de mantener el secreto estadístico, aún después de concluir sus actividades profesionales o su vinculación con los servicios estadísticos”.</p>

    <p>Para efectos del presente compromiso, es importante destacar que la <strong>“Información Confidencial”</strong> comprende toda la información divulgada por cualquiera de las partes ya sea en forma oral, visual, escrita, grabada en medios magnéticos o en cualquier otra forma tangible y que se encuentre claramente marcada como tal al ser entregada a la parte receptora.</p>

    <p>Quedando sujeto (a) a las sanciones establecidas en el Decreto con Rango, Valor y Fuerza de Ley de la Función Estadística y a cualquier otra norma legal homóloga aplicable por el cumplimiento de lo aquí declarado.</p>

    <p>En Caracas, a los <strong><?php echo $dia_contrato_letras; ?></strong> (<?php echo $dia_contrato_numero; ?>) días del mes de <strong><?php echo $mes_contrato_letras; ?></strong> de <?php echo $ano_contrato; ?>.</p>

    <br><br>

    <div class="signature-block">
        <p class="signature-line"></p>
        <p><strong><?php echo $nombre_completo; ?></strong></p>
        <p class="ci-line">C.I. Nro. V- <?php echo $cedula; ?></p>
        <p>Huella dactilar:</p>
    </div>
    
    <div class="signature-block">
        <p class="signature-line"></p>
        <p><strong><?php echo $representante_empresa_nombre; ?></strong></p>
        <p><strong><?php echo $representante_empresa_cargo; ?></strong></p>
        <p>Por: <?php echo $nombre_empresa; ?></p>
    </div>

</body>
</html>