<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$baseDeDatos = "GANADERIA2";

// Crear conexión
$conexion = mysqli_connect($servidor, $usuario, $contraseña, $baseDeDatos);

// Verificar conexión
if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Probar la consulta
$consulta = "SELECT * FROM medicamento";
$guardar = $conexion->query($consulta);

if (!$guardar) {
    die("Error en la consulta: " . $conexion->error);
}

// No cerrar la conexión aquí para usar los datos en la página HTML
// Cerrar la conexión al final
$conexion->close();
?>

<!DOCTYPE html> 
<html>
<head>
    <title>Inventario Medicamento</title> 
    <link rel="stylesheet" type="text/css" href="InventarioMedicamento.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <div class="titulo">
        <h1>Ganadería el Rosario</h1>
        <a href="Admi.html">
            <img src="Imagenes/atras.png" alt="Botón Atras" class="boton-atras">
        </a>
    </div> 
    <main>
        <section class="container">
            <h3>Almacén de Medicamento</h3>
            <div class="tabla">
                <div class="uno">
                    <table border="1" class="table">
                        <thead class="headtable">
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Unidad de Medida</th>
                                <th>Precio Unidad</th>
                                <th>Precio Total</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($guardar->num_rows > 0): ?>
                                <?php while ($row = $guardar->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['idMedicamento']; ?></td>
                                    <td><?php echo $row['Nombre']; ?></td>
                                    <td><?php echo $row['Cantidad']; ?></td>
                                    <td><?php echo $row['UnidadMedida']; ?></td>
                                    <td><?php echo $row['PrecioUnidad']; ?></td>
                                    <td><?php echo $row['PrecioTotal']; ?></td>
                                    <td><?php echo $row['Fecha']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">No se encontraron registros.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="dos">
                    <a href="AgregarMedicamento.php">
                        <img src="Imagenes/agregar.png" alt="Agregar medicamento" class="agregar">
                    </a>
                </div>
            </div>
        </section>
    </main>
    <footer>
		<p>&copy; 2024 Ganaderia el Rosario. Todos los derechos reservados.</p>
	</footer>
</body>
</html>