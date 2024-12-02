<!DOCTYPE html> 
<html>
<head>
    <title>Dar Medicamento</title> 
    <link rel="stylesheet" type="text/css" href="DarMedicamento.css">
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
                        <div class="titulo">
                            <h2>Tratamiento</h2>
                            <a href="Tabajadores.php">
                                <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>

                        <label>Tipo de Tratamiento</label>
                        <select class="Opcion" name="tipo_tratamiento">
                            <option disabled selected>Selecciona una opción</option>
                            <option>Tratamiento Preventivo</option>
                            <option>Tratamiento Curativo</option>
                        </select>

                        <label>Medicamento</label>
                        <select class="Opcion" name="nombre_medicamento" required>
                            <option disabled selected>Selecciona un Medicamento</option>
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

                            // Consultar los nombres de los medicamentos
                            $sql = "SELECT Nombre FROM medicamento";
                            $result = mysqli_query($conexion, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . htmlspecialchars($row['Nombre']) . '">' . htmlspecialchars($row['Nombre']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay medicamentos disponibles</option>';
                            }

                            // Cerrar la conexión
                            mysqli_close($conexion);
                            ?>
                        </select>
                        <label>Cantidad (en ml)</label>
                        <input type="number" name="cantidad" required>
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

                            // Consultar los números de reemo de los animales
                            $sql = "SELECT N_Reemo FROM compraganado";
                            $result = mysqli_query($conexion, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . htmlspecialchars($row['N_Reemo']) . '">' . htmlspecialchars($row['N_Reemo']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay números de reemo disponibles</option>';
                            }

                            // Cerrar la conexión
                            mysqli_close($conexion);
                            ?>
                        </select>
                        <label>Nombre del Trabajador que Atendió</label>
                        <input type="text" name="nombre_trabajador" required>

                        <button type="submit">Tratar</button> 
                    </form>
                </div>
            </div>
        </section>
        <section class="derecha"></section>
    </main>
    <footer>
		<p>&copy; 2024 Ganaderia el Rosario. Todos los derechos reservados.</p>
	</footer>
</body>
</html>

<?php
// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración de la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "GANADERIA2";

    // Crear conexión
    $conexion = mysqli_connect($server, $user, $pass, $db);

    // Verificar conexión
    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Obtener los datos del formulario
    $nombreMedicamento = $_POST['nombre_medicamento'];
    $cantidad = $_POST['cantidad'];
    $numeroReemo = $_POST['N_Reemo']; // Número de reemo del animal

    // Consultar la cantidad disponible del medicamento y su costo unitario
    $query = "SELECT Cantidad, PrecioUnidad FROM medicamento WHERE Nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $nombreMedicamento);
    $stmt->execute();
    $stmt->bind_result($cantidadDisponible, $costoUnitario);
    $stmt->fetch();
    $stmt->close();

    // Verificar si hay suficiente cantidad del medicamento
    if ($cantidadDisponible >= $cantidad) {
        // Calcular el costo total del medicamento utilizado
        $costoTotal = $costoUnitario * $cantidad;

        // Descontar la cantidad utilizada
        $nuevaCantidad = $cantidadDisponible - $cantidad;
        $updateQuery = "UPDATE medicamento SET Cantidad = ? WHERE Nombre = ?";
        $updateStmt = $conexion->prepare($updateQuery);
        $updateStmt->bind_param("is", $nuevaCantidad, $nombreMedicamento);
        if ($updateStmt->execute()) {
            // Si la actualización del medicamento fue exitosa, actualizar la ganancia del animal

            // Obtener la ganancia actual del animal usando el número de reemo
            $gananciaActual = 0;
            $query = "SELECT a.Ganancia
                    FROM animales a
                    INNER JOIN compraganado c ON a.idCompraGanado = c.idCompraGanado
                    WHERE c.N_Reemo = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $numeroReemo);
            $stmt->execute();
            $stmt->bind_result($gananciaActual);
            if ($stmt->fetch()) {
                $stmt->close();
            } else {
                $gananciaActual = 0; // Si no se encuentra la ganancia actual, asumimos 0
            }

            // Calcular la nueva ganancia (sumando el costo del medicamento)
            $nuevaGanancia = $gananciaActual + $costoTotal;

            // Actualizar la ganancia del animal
            $query = "UPDATE animales SET Ganancia = ? WHERE idCompraGanado = (
            SELECT idCompraGanado FROM compraganado WHERE N_Reemo = ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("di", $nuevaGanancia, $numeroReemo);
            if ($stmt->execute()) {
                // Si todo fue exitoso, mostrar mensaje
                echo '<div class = "Mensaje">El Animal fue tratado de manera correcta.</div>';
            } else {
                echo "Error al actualizar la ganancia del animal: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error al actualizar la cantidad de medicamento: ". $updateStmt->error;
        }
        $updateStmt->close();
    } else {
        // Si no hay suficiente cantidad, mostrar mensaje de error
        echo '<div class = "Mensaje">No hay suficiente cantidad de medicamento para realizar el tratamiento.</div>';
    }

    // Cerrar la conexión
    mysqli_close($conexion);
}
?>