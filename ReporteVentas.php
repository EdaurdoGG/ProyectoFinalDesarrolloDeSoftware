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
$consulta = "SELECT * FROM ventaganado";

$consulta = "
    SELECT 
        v.idVenta, 
        v.N_Reemo, 
        v.Destino, 
        v.TipoVenta, 
        v.PesoVenta, 
        v.PrecioVenta, 
        v.FechaVenta,
        v.GananciaTotal
    FROM 
        ventaganado v
";

$guardar = $conexion->query($consulta);

if (!$guardar) {
    die("Error en la consulta: " . $conexion->error);
}

// No cerrar la conexión aquí para usar los datos en la página HTML
?>

<!DOCTYPE html> 
<html>
<head>
    <title>Reporte de Ventas</title> 
    <link rel="stylesheet" type="text/css" href="ReporteVentas.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
        <div class = "titulo">
            <h1>Ganaderia el Rosario</h1>
            <a href="Reportes.php">
            <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
            </a>
        </div> 
        <main>
            <section class = "container">
                <h3>Animales Vendidos</h3>
                <div class="table-responsive table-hover">
                <table border="1" class="table">
                    <thead class="headtable">
                    <tr>
                        <td>Numero de Venta</td>
                        <td>Numero de Reemo</td>
                        <td>Destino</td>
                        <td>Finalidad</td>
                        <td>Peso de Venta</td>
                        <td>Precio de Venta</td>
                        <td>Fecha de Venta</td>
                        <td>Ganancia</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $guardar->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['idVenta']; ?></td>
                                <td><?php echo $row['N_Reemo']; ?></td>
                                <td><?php echo $row['Destino']; ?></td>
                                <td><?php echo $row['TipoVenta']; ?></td>
                                <td><?php echo $row['PesoVenta']; ?></td>
                                <td><?php echo $row['PrecioVenta']; ?></td>
                                <td><?php echo $row['FechaVenta']; ?></td>
                                <td><?php echo $row['GananciaTotal']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                </div>   
            </section>
        </main>
    <footer>
		<p>&copy; 2024 Ganaderia el Rosario. Todos los derechos reservados.</p>
	</footer>
</body>
</html>