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
$consulta = "SELECT * FROM ingredientes";
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
    <title>Inventario Alimento</title> 
    <link rel="stylesheet" type="text/css" href="InventarioComida.css">
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
                <h3>Almacen de Alimento</h3>
                <div class = "tabla">
                    <div class="tres"></div>
                    <div class="uno">
                        <table border="1" class="table">
                            <thead class="headtable">
                                <tr>
                                    <td>Id</td>
                                    <td>Nombre</td>
                                    <td>Cantidad</td>
                                    <td>Unidad de Medida</td>
                                    <td>Precio Unidad</td>
                                    <td>Precio Total</td>
                                    <td>Fecha</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $guardar->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['idIngrediente']; ?></td>
                                    <td><?php echo $row['NombreIngrediente']; ?></td>
                                    <td><?php echo $row['Cantidad']; ?></td>
                                    <td><?php echo $row['UnidadMedida']; ?></td>
                                    <td><?php echo $row['PrecioUnidad']; ?></td>
                                    <td><?php echo $row['PrecioTotal']; ?></td>
                                    <td><?php echo $row['Fecha']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="dos">
                        <a href="AgregarComida.php">
                            <img src="Imagenes/agregar.png" alt="agregar" class="agregar">
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