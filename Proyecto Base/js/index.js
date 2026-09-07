document.addEventListener("DOMContentLoaded", function () {

    var selectCiudad = document.getElementById("selectCiudad");
    var selectTipo = document.getElementById("selectTipo");

    fetch("./data.json")
        .then(function (respuesta) {
            if (!respuesta.ok) {
                throw new Error("No se pudo encontrar data.json");
            }

            return respuesta.json();
        })
        .then(function (datos) {

            var ciudades = [];
            var tipos = [];

            for (var i = 0; i < datos.length; i++) {

                if (ciudades.indexOf(datos[i].Ciudad) === -1) {
                    ciudades.push(datos[i].Ciudad);
                }

                if (tipos.indexOf(datos[i].Tipo) === -1) {
                    tipos.push(datos[i].Tipo);
                }
            }

            ciudades.sort();
            tipos.sort();

            // Agregar ciudades
            for (var i = 0; i < ciudades.length; i++) {

                var opcionCiudad = document.createElement("option");

                opcionCiudad.value = ciudades[i];
                opcionCiudad.textContent = ciudades[i];

                selectCiudad.appendChild(opcionCiudad);
            }

            // Agregar tipos
            for (var i = 0; i < tipos.length; i++) {

                var opcionTipo = document.createElement("option");

                opcionTipo.value = tipos[i];
                opcionTipo.textContent = tipos[i];

                selectTipo.appendChild(opcionTipo);
            }

            console.log("Ciudades:", ciudades);
            console.log("Tipos:", tipos);

        })
        .catch(function (error) {
            console.error("Error:", error);
        });


    // Slider de precios
    $("#rangoPrecio").ionRangeSlider({
        type: "double",
        grid: false,
        min: 0,
        max: 100000,
        from: 0,
        to: 100000,
        prefix: "$"
    });


    // Botón Mostrar Todos
    $("#mostrarTodos").click(function () {
        window.location.href = "buscador.php";
    });

});