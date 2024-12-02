<?php
// Verifica si se ha pasado un idGanadero en la URL
if (isset($_GET['idGanadero'])) {
    $idGanadero = $_GET['idGanadero'];

    // Configuración de la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "GANADERIA2";

    // Crear conexión
    $conexion = mysqli_connect($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conexion === false) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Obtener los datos del ganadero
    $sql = "SELECT * FROM Ganaderos WHERE idGanadero = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idGanadero); // Pasar el idGanadero
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $ganadero = $resultado->fetch_assoc(); // Obtener los datos del ganadero
    } else {
        echo "Ganadero no encontrado.";
        exit;
    }

    // Si el formulario fue enviado (POST), actualizar los datos
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre = $_POST['Nombre'];
        $razonSocial = $_POST['RazonSocial'];
        $domicilio = $_POST['Domicilio'];
        $localidad = $_POST['Localidad'];
        $municipio = $_POST['Municipio'];
        $estado = $_POST['Estado'];

        // Validar que no estén vacíos los campos
        if (empty($nombre) || empty($razonSocial) || empty($domicilio) || empty($localidad) || empty($municipio) || empty($estado)) {
            echo "Todos los campos son obligatorios.";
        } else {
            // Actualizar los datos en la base de datos
            $update_sql = "UPDATE Ganaderos SET Nombre = ?, RazonSocial = ?, Domicilio = ?, Localidad = ?, Municipio = ?, Estado = ? WHERE idGanadero = ?";
            $stmt = $conexion->prepare($update_sql);
            $stmt->bind_param("ssssssi", $nombre, $razonSocial, $domicilio, $localidad, $municipio, $estado, $idGanadero);

            if ($stmt->execute()) {
                echo "Datos actualizados con éxito.";
                header("Location: Ganaderos.php"); // Redirige a la lista de ganaderos después de la actualización
                exit;
            } else {
                echo "Error al actualizar los datos: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    echo "No se especificó el id del ganadero.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Datos del Ganadero</title>
    <link rel="stylesheet" type="text/css" href="Registro.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <main>
        <section class="izquierda"></section>
        <section class="centro">
            <br><br>
            <div class="log">
                <div class="login">
                    <!-- Formulario de actualización con los datos prellenados -->
                    <form method="POST" action="">
                        <div class="titulo">
                            <h2>Actualizar Datos de Ganadero</h2>
                            <a href="Ganaderos.php">
                                <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>

                        <!-- Campos del formulario con los valores precargados -->
                        <label>Nombre</label>
                        <input type="text" name="Nombre" value="<?php echo htmlspecialchars($ganadero['Nombre']); ?>" required>

                        <label>Razón Social</label>
                        <input type="text" name="RazonSocial" value="<?php echo htmlspecialchars($ganadero['RazonSocial']); ?>" required>

                        <label>Domicilio</label>
                        <input type="text" name="Domicilio" value="<?php echo htmlspecialchars($ganadero['Domicilio']); ?>" required>

                        <label>Localidad</label>
                        <input type="text" name="Localidad" value="<?php echo htmlspecialchars($ganadero['Localidad']); ?>" required>

                        <label>Municipio</label>
                        <input type="text" name="Municipio" value="<?php echo htmlspecialchars($ganadero['Municipio']); ?>" required>

                        <label>Estado</label>
                        <input type="text" name="Estado" value="<?php echo htmlspecialchars($ganadero['Estado']); ?>" required>

                        <button type="submit" name="Boton">Actualizar</button>
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