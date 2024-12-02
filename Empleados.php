<?php
// Configuración de la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "GANADERIA2";

// Crear conexión
$conexion = mysqli_connect($server, $user, $pass, $db);

// Verificar conexión
if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Probar la consulta
$consulta = "SELECT * FROM Empleados";
$guardar = $conexion->query($consulta);

if (!$guardar) {
    die("Error en la consulta: " . $conexion->error);
}

// No cerrar la conexión aquí para usar los datos en la página HTML
?>

<!DOCTYPE html> 
<html>
<head>
    <title>Empleados</title> 
    <link rel="stylesheet" type="text/css" href="Empleados.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
        <div class = "titulo">
            <h1>Ganaderia el Rosario</h1>
            <a href="Admi.html">
            <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
            </a>
        </div> 
        <main>
            <section class = "container">
                <h3>Lista de Trabajadores</h3>
                <div class = "tabla">
                    <div class="tres"></div>
                    <div class="uno">
                        <table border="1" class="table">
                            <thead class="headtable">
                                <tr>
                                    <td>Id de Empleado </td>
                                    <td>Nombre</td>
                                    <td>ApellidoP</td>
                                    <td>ApellidoM</td>
                                    <td>Telefono</td>
                                    <td>Puesto</td>
                                    <td>Salario</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while ($row = $guardar->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['idEmpleado']; ?></td>
                                <td><?php echo $row['Nombres']; ?></td>
                                <td><?php echo $row['ApellidoP']; ?></td>
                                <td><?php echo $row['ApellidoM']; ?></td>
                                <td><?php echo $row['Telefono']; ?></td>
                                <td><?php echo $row['Puesto']; ?></td>
                                <td><?php echo $row['Salario']; ?></td>
                                <td>
                                <div class = "Acciones">
                                    <a href="actualizaEmpleado.php?idEmpleado=<?php echo $row['idEmpleado']; ?>">
                                        <img src="Imagenes/Editar.png" alt="Editar" class="mi-imagen">
                                    </a>
                                    <a href="BorrarEmpleado.php?idEmpleado=<?php echo $row['idEmpleado']; ?>">
                                    <img src="Imagenes/Borrar.png" alt="Administrativos" class="mi-imagen">
                                    </a>
                                </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="dos">
                    </div>
                </div>
            </section>
        </main>
    <footer>
		<p>&copy; 2024 Ganaderia el Rosario. Todos los derechos reservados.</p>
	</footer>
</body>
</html>