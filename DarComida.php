<!DOCTYPE html>
<html>
<head>
    <title>Dar Alimento</title>
    <link rel="stylesheet" type="text/css" href="DarComida.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <main>
        <section class="izquierda"></section>
        <section class="centro">
            <br><br>
            <div class="log">
                <div class="login">
                    <form method="POST" action="">
                        <br><br>
                        <div class="titulo">
                            <h2>Almacen Alimento</h2>
                            <a href="Tabajadores.php">
                                <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>
                        <label>Tipo Alimento</label>
                        <select class="Opcion" name="Opcion">
                            <option disabled selected>Selecciona una Opcion</option>
                            <option value="Abasto">Abasto</option>
                            <option value="Inicio">Inicio</option>
                            <option value="Desarrollo">Desarrollo</option>
                            <option value="Engorda">Engorda</option>
                            <option value="Finalidad">Finalidad</option>
                        </select>
                        <label>Numero de Reemo</label>
                        <select class="Opcion" name="N_Reemo">
                            <option disabled selected>Selecciona el Numero de Reemo</option>
                            <?php
                            // Conexión a la base de datos
                            $server = "localhost";
                            $user = "root";
                            $pass = "";
                            $db = "GANADERIA2";

                            $conexion = mysqli_connect($server, $user, $pass, $db);
                            if (!$conexion) {
                                die("Conexión fallida: " . mysqli_connect_error());
                            }

                            // Consultar los números de reemo
                            $sql = "SELECT N_Reemo FROM compraganado";
                            $result = mysqli_query($conexion, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . htmlspecialchars($row['N_Reemo']) . '">' . htmlspecialchars($row['N_Reemo']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay reemos disponibles</option>';
                            }

                            // Cerrar la conexión
                            mysqli_close($conexion);
                            ?>
                        </select>
                        <label>Nombre del Trabajador que Alimento</label>
                        <input type="text" name="Nombre" required>
                        <button type="submit">Alimentar</button>
                    </form>
                </div>
            </div>
            <!-- Mostrar mensaje de éxito o error -->
            <?php if (isset($mensaje)) { echo "<p>$mensaje</p>"; } ?>
        </section>
        <section class="derecha"></section>
    </main>
    <footer>
		<p>&copy; 2024 Ganaderia el Rosario. Todos los derechos reservados.</p>
	</footer>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración de la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "GANADERIA2";

    // Crear conexión
    $conexion = mysqli_connect($server, $user, $pass, $db);

    // Comprobar conexión
    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Obtener los valores del formulario
    $idAnimal = $_POST['N_Reemo'];  // Ahora usamos N_Reemo como identificador del animal
    $tipoAlimento = $_POST['Opcion'];

    // Verifica si el animal existe en la tabla CompraGanado
    function verificarAnimal($conexion, $idAnimal) {
        $query = "SELECT N_Reemo FROM compraganado WHERE N_Reemo = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $idAnimal);  // 'i' es para números enteros
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            return false;  // Si no existe el animal
        }

        $stmt->close();
        return true;  // El animal existe
    }

    // Función para obtener el precio de cada ingrediente
    function obtenerPrecioIngrediente($conexion, $nombreIngrediente) {
        // Inicializamos $precioUnidad como 0 (en caso de no encontrar el ingrediente)
        $precioUnidad = 0;

        // Preparar la consulta SQL para obtener el precio del ingrediente
        $query = "SELECT PrecioUnidad FROM ingredientes WHERE NombreIngrediente = ?";
        $stmt = $conexion->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        // Vincular el parámetro (el nombre del ingrediente)
        $stmt->bind_param("s", $nombreIngrediente);

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            $stmt->close();  // Cerrar la declaración en caso de error
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Vincular el resultado (asignar el precio de la unidad)
        $stmt->bind_result($precioUnidad);

        // Verificar si se obtuvieron resultados
        if ($stmt->fetch()) {
            // Si hay resultados, devolver el precio
            $stmt->close();  // Asegurarnos de cerrar el recurso después de usarlo
            return $precioUnidad;
        } else {
            // Si no se encontró el ingrediente, devolver 0 o algún valor por defecto
            $stmt->close();  // Asegurarnos de cerrar el recurso
            return 0;  // O podrías devolver un valor como 'null' o un mensaje de error
        }
    }

    // Función para manejar el alimento
    function manejarAlimento($conexion, $idAnimal, $tipoAlimento) {
        $alimentos = [];
        $gananciaTotal = 0;  // Variable para almacenar la ganancia total

        // Definir los ingredientes para cada tipo de alimento
        switch ($tipoAlimento) {
            case 'Abasto':
                $alimentos = [
                    'Rastrojo' => 9.35,
                    'Maiz' => 1.1,
                    'Sal' => 0.44,
                    'Electrolitos' => 0.11
                ];
                break;
            case 'Inicio':
                $alimentos = [
                    'Rastrojo' => 2.6829,
                    'Maiz Roaldo' => 2.1463,
                    'Maiz' => 3.4341,
                    'Soya' => 1.0731,
                    'Salvado Trigo' => 0.5365,
                    'Alfalfa Molida' => 0.5365,
                    'Melaza' => 0.3219,
                    'Macrominerales' => 0.2146,
                    'Urea' => 0.0536
                ];
                break;
            case 'Desarrollo':
                $alimentos = [
                    'Rastrojo' => 2.1463,
                    'Maiz Roaldo' => 2.1463,
                    'Maiz' => 3.4341,
                    'Soya' => 1.0731,
                    'Salvado Trigo' => 0.5365,
                    'Alfalfa Molida' => 0.5365,
                    'Melaza' => 0.3219,
                    'Macrominerales' => 0.2146,
                    'Urea' => 0.0536
                ];
                break;
            case 'Engorda':
                $alimentos = [
                    'Rastrojo' => 2.1890,
                    'Maiz Roaldo' => 3.2835,
                    'Maiz' => 2.7363,
                    'Soya' => 1.0945,
                    'Salvado Trigo' => 0.5472,
                    'Alfalfa Molida' => 0.5472,
                    'Melaza' => 0.3283,
                    'Macrominerales' => 0.2189,
                    'Urea' => 0.0547
                ];
                break;
            case 'Finalidad':
                $alimentos = [
                    'Rastrojo' => 2.189,
                    'Maiz Roaldo' => 3.2835,
                    'Maiz' => 3.2835,
                    'Soya' => 0.5467,
                    'Salvado Trigo' => 0.5467,
                    'Alfalfa Molida' => 0.5467,
                    'Melaza' => 0.3278,
                    'Macrominerales' => 0.2189,
                    'Urea' => 0.055,
                    'Zilpaterol' => 0.0013
                ];
                break;
            default:
                return "Selecciona un tipo de alimento válido.";
        }

        // Calcular la ganancia y actualizar inventario
        foreach ($alimentos as $nombreAlimento => $cantidadRestada) {
            // Obtener el precio unitario de cada ingrediente
            $precioUnidad = obtenerPrecioIngrediente($conexion, $nombreAlimento);
            if ($precioUnidad === null) {
                return "No se encontró el precio para el ingrediente: $nombreAlimento";
            }

            // Calcular el costo del ingrediente consumido
            $costoIngrediente = $cantidadRestada * $precioUnidad;
            $gananciaTotal += $costoIngrediente;

            // Actualizar inventario (restar la cantidad consumida)
            $query = "UPDATE ingredientes SET Cantidad = Cantidad - ? WHERE NombreIngrediente = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ds", $cantidadRestada, $nombreAlimento);
            if (!$stmt->execute()) {
                return "Error al actualizar el inventario para $nombreAlimento: " . $stmt->error;
            }
            $stmt->close();
        }

        // Obtener la ganancia actual del animal (utilizando un JOIN entre las tablas 'animales' y 'compraganado')
        $gananciaActual = 0;
        $query = "SELECT a.Ganancia
                  FROM animales a
                  INNER JOIN compraganado c ON a.idCompraGanado = c.idCompraGanado
                  WHERE c.N_Reemo = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $idAnimal);
        $stmt->execute();
        $stmt->bind_result($gananciaActual);
        if ($stmt->fetch()) {
            $stmt->close();
        } else {
            $gananciaActual = 0; // Si no se encuentra la ganancia actual, asumimos 0
        }

    // Sumar la ganancia actual con la nueva ganancia calculada
    $nuevaGanancia = $gananciaActual + $gananciaTotal;

    // Actualizar la ganancia en la tabla animales utilizando idCompraGanado (en lugar de N_Reemo)
    $query = "UPDATE animales SET Ganancia = ? WHERE idCompraGanado = (
                SELECT idCompraGanado FROM compraganado WHERE N_Reemo = ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("di", $nuevaGanancia, $idAnimal);
    if (!$stmt->execute()) {
        return "Error al actualizar la ganancia del animal: " . $stmt->error;
    }

    // Mensaje de éxito
    echo '<div class = "Mensaje">El animal se alimentó correctamente</div>';
    }

    // Verificar si el animal existe
    if (verificarAnimal($conexion, $idAnimal)) {
        $mensaje = manejarAlimento($conexion, $idAnimal, $tipoAlimento);
    } else {
        $mensaje = "El número de reemo no existe en la tabla compraganado.";
    }

    // Cerrar la conexión
    mysqli_close($conexion);
}

?>