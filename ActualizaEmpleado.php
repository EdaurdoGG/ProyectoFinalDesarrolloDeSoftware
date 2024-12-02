<?php
// Verifica si se ha pasado un idEmpleado en la URL
if (isset($_GET['idEmpleado'])) {
    $idEmpleado = $_GET['idEmpleado'];

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

    // Obtener los datos del empleado
    $sql = "SELECT * FROM Empleados WHERE idEmpleado = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idEmpleado); // Pasar el idEmpleado
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $empleado = $resultado->fetch_assoc(); // Obtener los datos del empleado
    } else {
        echo "Empleado no encontrado.";
        exit;
    }

    // Si el formulario fue enviado (POST), actualizar los datos
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombres = $_POST['Nombres'];
        $apellidoP = $_POST['ApellidoP'];
        $apellidoM = $_POST['ApellidoM'];
        $telefono = $_POST['Telefono'];
        $puesto = $_POST['Puesto'];
        $salario = $_POST['Salario'];

        // Validar que no estén vacíos los campos
        if (empty($nombres) || empty($apellidoP) || empty($apellidoM) || empty($telefono) || empty($puesto) || empty($salario)) {
            echo "Todos los campos son obligatorios.";
        } else {
            // Actualizar los datos en la base de datos
            $update_sql = "UPDATE Empleados SET Nombres = ?, ApellidoP = ?, ApellidoM = ?, Telefono = ?, Puesto = ?, Salario = ? WHERE idEmpleado = ?";
            $stmt = $conexion->prepare($update_sql);
            $stmt->bind_param("ssssssi", $nombres, $apellidoP, $apellidoM, $telefono, $puesto, $salario, $idEmpleado);

            if ($stmt->execute()) {
                echo "Datos actualizados con éxito.";
                header("Location: Empleados.php"); // Redirige a la lista de empleados después de la actualización
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
    echo "No se especificó el id del empleado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Datos del Trabajador</title>
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
                            <h2>Actualizar Datos de <?php echo htmlspecialchars($empleado['Nombres']); ?></h2>
                            <a href="Empleados.php">
                                <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>
                        <label>Nombre/s</label>
                        <input type="text" name="Nombres" value="<?php echo htmlspecialchars($empleado['Nombres']); ?>" required>
                        <label>Apellido Paterno</label>
                        <input type="text" name="ApellidoP" value="<?php echo htmlspecialchars($empleado['ApellidoP']); ?>" required>
                        <label>Apellido Materno</label>
                        <input type="text" name="ApellidoM" value="<?php echo htmlspecialchars($empleado['ApellidoM']); ?>" required>
                        <label>Número Telefónico</label>
                        <input type="text" name="Telefono" value="<?php echo htmlspecialchars($empleado['Telefono']); ?>" required>
                        <label>Puesto</label>
                        <select class="Opcion" name="Puesto" required>
                            <option value="Corrales" <?php echo ($empleado['Puesto'] == 'Corrales') ? 'selected' : ''; ?>>Corrales</option>
                            <option value="Almacenes" <?php echo ($empleado['Puesto'] == 'Almacenes') ? 'selected' : ''; ?>>Almacenes</option>
                        </select>
                        <br>
                        <label>Salario</label>
                        <input type="number" name="Salario" value="<?php echo htmlspecialchars($empleado['Salario']); ?>" required>
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