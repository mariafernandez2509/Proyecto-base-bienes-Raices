<?php

// Leer el archivo JSON
$archivo = 'data.json';

if (!file_exists($archivo)) {
    die("No se encontró el archivo data.json");
}

$contenido = file_get_contents($archivo);
$propiedades = json_decode($contenido, true);

if ($propiedades === null) {
    die("No se pudo leer correctamente el archivo data.json");
}

// Obtener los filtros enviados
$ciudad = isset($_POST['ciudad']) ? $_POST['ciudad'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$precio = isset($_POST['precio']) ? $_POST['precio'] : '';

// Valores iniciales del rango
$precioMin = 0;
$precioMax = 100000;

// Leer rango de precios
if (!empty($precio)) {

    $rango = explode(';', $precio);

    if (count($rango) == 2) {

        $precioMin = (float) str_replace(
            ['$', ','],
            '',
            $rango[0]
        );

        $precioMax = (float) str_replace(
            ['$', ','],
            '',
            $rango[1]
        );
    }
}

// Filtrar propiedades
$resultados = [];

foreach ($propiedades as $propiedad) {

    // Convertir "$30,746" a 30746
    $precioPropiedad = (float) str_replace(
        ['$', ','],
        '',
        $propiedad['Precio']
    );

    // Filtro por precio
    $cumplePrecio =
        $precioPropiedad >= $precioMin &&
        $precioPropiedad <= $precioMax;

    // Filtro por ciudad
    $cumpleCiudad =
        $ciudad === '' ||
        $propiedad['Ciudad'] === $ciudad;

    // Filtro por tipo
    $cumpleTipo =
        $tipo === '' ||
        $propiedad['Tipo'] === $tipo;

    // Agregar si cumple todos los filtros
    if ($cumplePrecio && $cumpleCiudad && $cumpleTipo) {
        $resultados[] = $propiedad;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Resultados - Buscador</title>

    <!-- Materialize -->
    <link rel="stylesheet"
          href="css/materialize.min.css">

    <!-- Colores originales -->
    <link rel="stylesheet"
          href="css/customColors.css">

    <!-- CSS original -->
    <link rel="stylesheet"
          href="css/index.css">

    <!-- Estilos propios de resultados -->
    <style>

        body {
            margin: 0;
            padding: 0;
        }

        .contenedor-resultados {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }

        /* ENCABEZADO */

        .encabezado-resultados {
            background-color: #648C7D;
            color: #F2F2F2;
            text-align: center;
            padding: 25px 20px;
            border-radius: 4px 4px 0 0;
        }

        .encabezado-resultados h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 400;
        }

        .subtitulo {
            margin-top: 8px;
            font-size: 1.1rem;
        }

        /* PANEL DE INFORMACIÓN */

        .panel-resultados {
            background-color: #F2F2F2;
            padding: 20px;
            text-align: center;
            margin-bottom: 25px;
        }

        .cantidad-resultados {
            font-size: 1.2rem;
            margin: 5px 0 15px 0;
        }

        .cantidad-resultados strong {
            color: #648C7D;
            font-size: 1.4rem;
        }

        .nueva-busqueda {
            background-color: #648C7D;
            color: white;
        }

        .nueva-busqueda:hover {
            background-color: #527568;
        }

        /* TARJETAS */

        .propiedades {
            display: flex;
            flex-wrap: wrap;
        }

        .tarjeta-columna {
            margin-bottom: 25px;
        }

        .tarjeta-propiedad {
            margin: 0;
            height: 100%;
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .tarjeta-propiedad:hover {
            transform: translateY(-4px);
        }

        /* IMAGEN */

        .imagen-propiedad {
            width: 100%;
            height: 210px;
            overflow: hidden;
            background-color: #ddd;
        }

        .imagen-propiedad img {
            display: block !important;
            width: 100% !important;
            height: 210px !important;
            object-fit: cover;
        }

        /* INFORMACIÓN */

        .tarjeta-propiedad .card-content {
            padding: 20px;
        }

        .tarjeta-propiedad .card-title {
            color: #648C7D;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .dato-propiedad {
            margin: 9px 0;
            line-height: 1.4;
        }

        .dato-propiedad strong {
            color: #555;
        }

        /* PRECIO */

        .precio-propiedad {
            color: #ffab40;
            font-size: 1.6rem;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 0;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        /* SIN RESULTADOS */

        .sin-resultados {
            text-align: center;
            padding: 40px 20px;
        }

        .sin-resultados h4 {
            color: #648C7D;
        }

        /* PIE */

        .pie-resultados {
            text-align: center;
            margin: 30px 0;
            color: #777;
            font-size: 0.9rem;
        }

        /* RESPONSIVE */

        @media (max-width: 600px) {

            .contenedor-resultados {
                width: 95%;
                margin: 20px auto;
            }

            .encabezado-resultados h1 {
                font-size: 2rem;
            }

            .imagen-propiedad,
            .imagen-propiedad img {
                height: 220px !important;
            }
        }

    </style>

</head>

<body>

<div class="contenedor-resultados">

    <!-- ENCABEZADO -->

    <div class="card-panel encabezado-resultados">

        <h1>Buscador</h1>

        <div class="subtitulo">
            Resultados de la búsqueda
        </div>

    </div>


    <!-- INFORMACIÓN DE RESULTADOS -->

    <div class="card panel-resultados">

        <p class="cantidad-resultados">

            Se encontraron

            <strong>
                <?php echo count($resultados); ?>
            </strong>

            propiedades.

        </p>

        <a href="index.html"
           class="btn nueva-busqueda">

            Nueva búsqueda

        </a>

    </div>


    <!-- PROPIEDADES -->

    <div class="row propiedades">

        <?php if (count($resultados) > 0): ?>

            <?php foreach ($resultados as $propiedad): ?>

                <div class="col s12 m6 l4 tarjeta-columna">

                    <div class="card tarjeta-propiedad">

                        <!-- IMAGEN -->

                        <div class="imagen-propiedad">

                            <img src="img/home.jpg"
                                 alt="Imagen de propiedad">

                        </div>


                        <!-- INFORMACIÓN -->

                        <div class="card-content">

                            <span class="card-title">

                                <?php
                                echo htmlspecialchars(
                                    $propiedad['Tipo']
                                );
                                ?>

                            </span>


                            <p class="dato-propiedad">

                                <strong>Ciudad:</strong>

                                <?php
                                echo htmlspecialchars(
                                    $propiedad['Ciudad']
                                );
                                ?>

                            </p>


                            <p class="dato-propiedad">

                                <strong>Dirección:</strong>

                                <?php
                                echo htmlspecialchars(
                                    $propiedad['Direccion']
                                );
                                ?>

                            </p>


                            <p class="dato-propiedad">

                                <strong>Teléfono:</strong>

                                <?php
                                echo htmlspecialchars(
                                    $propiedad['Telefono']
                                );
                                ?>

                            </p>


                            <p class="dato-propiedad">

                                <strong>Código Postal:</strong>

                                <?php
                                echo htmlspecialchars(
                                    $propiedad['Codigo_Postal']
                                );
                                ?>

                            </p>


                            <h5 class="precio-propiedad">

                                <?php
                                echo htmlspecialchars(
                                    $propiedad['Precio']
                                );
                                ?>

                            </h5>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <!-- SIN RESULTADOS -->

            <div class="col s12">

                <div class="card sin-resultados">

                    <h4>
                        No se encontraron propiedades
                    </h4>

                    <p>
                        Intenta cambiar la ciudad,
                        el tipo de propiedad o el rango de precio.
                    </p>

                    <a href="index.html"
                       class="btn nueva-busqueda">

                        Nueva búsqueda

                    </a>

                </div>

            </div>

        <?php endif; ?>

    </div>


    <!-- PIE -->

    <div class="pie-resultados">

        Buscador de propiedades

    </div>

</div>

</body>

</html>