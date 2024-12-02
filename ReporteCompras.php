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

// Consultar datos combinando las tablas
$consulta = "
        SELECT 
        c.N_Reemo,
        c.Motivo, 
        a.NumeroArete, 
        a.Clasificacion, 
        a.Peso, 
        a.PrecioCompra, 
        g.RazonSocial 
    FROM 
        compraganado c
    JOIN 
        corral a ON c.idCompraGanado = a.idCompraGanado
    JOIN 
        ganaderos g ON a.idGanadero = g.idGanadero
  
"; 

// Ejecutar la consulta
$resultado = $conexion->query($consulta);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

// Verifica si hay resultados
if ($resultado->num_rows === 0) {
    $mensaje = "No se encontraron resultados.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras</title> 
    <link rel="stylesheet" type="text/css" href="ReporteCompras.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <div class="titulo">
        <h1>Ganadería el Rosario</h1>
        <a href="Reportes.php">
            <img src="Imagenes/atras.png" alt="Botón Atras" class="boton-atras">
        </a>
    </div> 
    <main>
        <section class="container">
            <h3>Animales Comprados</h3>
            <?php if (isset($mensaje)): ?>
                <p><?php echo $mensaje; ?></p>
            <?php else: ?>
                <table border="1" class="table">
                    <thead class="headtable">
                        <tr>
                            <th>Número de Reemo</th>
                            <th>Motivo</th>
                            <th>Número de Arete</th>
                            <th>Clasificación</th>
                            <th>Peso de Compra</th>
                            <th>Precio de Compra</th>
                            <th>Razón Social</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['N_Reemo']); ?></td>
                            <td><?php echo htmlspecialchars($row['Motivo']); ?></td>
                            <td><?php echo htmlspecialchars($row['NumeroArete']); ?></td>
                            <td><?php echo htmlspecialchars($row['Clasificacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['Peso']); ?></td>
                            <td><?php echo htmlspecialchars($row['PrecioCompra']); ?></td>
                            <td><?php echo htmlspecialchars($row['RazonSocial']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
    <footer>
		<p>&copy; 2024 Ganaderia el Rosario. Todos los derechos reservados.</p>
	</footer>
</body>
</html>