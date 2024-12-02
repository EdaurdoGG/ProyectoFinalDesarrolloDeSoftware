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
$consulta = "
    SELECT 
        a.idAnimal, 
        c.N_Reemo, 
        a.NumeroArete, 
        a.Sexo, 
        a.Meses, 
        a.Clasificacion, 
        a.Fierro,
        a.Peso
    FROM 
        animales a
    JOIN 
        compraganado c ON c.idCompraGanado = a.idCompraGanado
"; // Verificar que las relaciones son correctas

$guardar = $conexion->query($consulta);

if (!$guardar) {
    die("Error en la consulta: " . $conexion->error);
}

// No cerrar la conexión aquí para usar los datos en la página HTML
?>

<!DOCTYPE html>
<html>
<head>
    <title>Animales en Corrales</title> 
    <link rel="stylesheet" type="text/css" href="Animales.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <div class="titulo">
        <h1>Ganaderia el Rosario</h1>
        <a href="Admi.html">
            <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
        </a>
    </div>
    <main>
        <section class="container">
            <h3>Animales en Corrales</h3>
            <div class="table-responsive table-hover" id="TablaConsulta">
                <table border="1" class="table">
                    <thead class="headtable">
                        <tr>
                            <td>Id Animal</td>
                            <td>Numero de Reemo</td>
                            <td>Numero de Arete</td>
                            <td>Sexo</td>
                            <td>Meses</td>
                            <td>Clasificación</td>
                            <td>Fierro</td>
                            <td>Peso en KG</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $guardar->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['idAnimal']; ?></td>
                                <td><?php echo $row['N_Reemo']; ?></td>
                                <td><?php echo $row['NumeroArete']; ?></td>
                                <td><?php echo $row['Sexo']; ?></td>
                                <td><?php echo $row['Meses']; ?></td>
                                <td><?php echo $row['Clasificacion']; ?></td>
                                <td><?php echo $row['Fierro']; ?></td>
                                <td><?php echo $row['Peso']; ?></td>
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