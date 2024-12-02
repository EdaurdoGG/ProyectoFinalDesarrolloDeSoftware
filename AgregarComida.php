<!DOCTYPE html>
<html>
<head>
    <title>Registro de Alimento</title> 
    <link rel="stylesheet" type="text/css" href="AgregarComida.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <main>
        <section class="izquierda">
        </section>
        <section class="centro">
            <br>
            <br>
            <div class="log">
                <div class="login">
                    <form method="POST" action="">
                        <div class="titulo">
                            <h2>Agregar Alimento</h2>
                            <a href="InventarioComida.php">
                                <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>
                        <label>Nombre del Alimento</label>
                        <input type="text" name="NombreIngrediente" required>
                        <label>Cantidad de Unidades</label>
                        <input type="number" name="Cantidad" required>
                        <label>Unidad de Medida</label>
                        <select class="Opcion" name="UnidadMedida">
                            <option disabled selected>Selecciona una Opcion</option>
                            <option>Kg</option>
                        </select>
                        <label>Unidad de Medida</label>
                        <label>Precio por Unidad</label>
                        <input type="number" name="PrecioUnidad" required>
                        <label>Fecha de Compra</label>
                        <input type="date" name="Fecha" required>
                        <button type="submit">Agregar</button>
                    </form>
                </div>
            </div>
        </section>
        <section class="derecha">
        </section>
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

        // Comprobar conexión
        if (!$conexion) {
            die("Conexión fallida: " . mysqli_connect_error());
        }

        // Obtener datos del formulario
        $nombreIngrediente = $_POST['NombreIngrediente'];
        $cantidad = $_POST['Cantidad'];
        $unidadMedida = $_POST['UnidadMedida'];
        $precioUnidad = $_POST['PrecioUnidad'];
        $fecha = $_POST['Fecha'];

        // Calcular el PrecioTotal (PrecioUnidad * Cantidad)
        $precioTotal = $precioUnidad * $cantidad;

        // Verificar si el ingrediente ya existe en la base de datos
        $query = "SELECT * FROM ingredientes WHERE NombreIngrediente = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $nombreIngrediente);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Si el ingrediente ya existe, actualizar los datos
            $updateQuery = "UPDATE ingredientes SET Cantidad = ?, UnidadMedida = ?, PrecioUnidad = ?, PrecioTotal = ?, Fecha = ? WHERE NombreIngrediente = ?";
            $updateStmt = $conexion->prepare($updateQuery);
            $updateStmt->bind_param("isiiss", $cantidad, $unidadMedida, $precioUnidad, $precioTotal, $fecha, $nombreIngrediente);
            
            if ($updateStmt->execute()) {
                echo '<div class = "Mensaje">Datos actualizados correctamente</div>';
            } else {
                echo "Error al actualizar los datos: " . $updateStmt->error;
            }
            $updateStmt->close();
        } else {
            // Si el ingrediente no existe, insertar un nuevo registro
            $insertQuery = "INSERT INTO ingredientes (NombreIngrediente, Cantidad, UnidadMedida, PrecioUnidad, PrecioTotal, Fecha) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $conexion->prepare($insertQuery);
            $insertStmt->bind_param("sisiis", $nombreIngrediente, $cantidad, $unidadMedida, $precioUnidad, $precioTotal, $fecha);
            
            if ($insertStmt->execute()) {
                echo '<div class = "Mensaje">Alimento Agregado Correctamente</div>';
            } else {
                echo "Error al registrar: " . $insertStmt->error;
            }
            $insertStmt->close();
        }

        // Cerrar la conexión
        $conexion->close();
    }
?>