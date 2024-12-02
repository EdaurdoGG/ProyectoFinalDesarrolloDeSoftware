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
$consulta = "SELECT * FROM ganaderos";
$guardar = $conexion->query($consulta);

if (!$guardar) {
    die("Error en la consulta: " . $conexion->error);
}

// No cerrar la conexión aquí para usar los datos en la página HTML
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
                <h3>Lista de Ganaderos</h3>
                <div class = "tabla">
                    <div class="tres"></div>
                    <div class="uno">
                        <table border="1" class="table">
                            <thead class="headtable">
                                <tr>
                                    <td>PSG (id de ganadero)</td>
                                    <td>Nombre</td>
                                    <td>Razon Social</td>
                                    <td>Domicilio</td>
                                    <td>Localidad</td>
                                    <td>Municipio</td>
                                    <td>Estado</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while ($row = $guardar->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['idGanadero']; ?></td>
                                <td><?php echo $row['Nombre']; ?></td>
                                <td><?php echo $row['RazonSocial']; ?></td>
                                <td><?php echo $row['Domicilio']; ?></td>
                                <td><?php echo $row['Localidad']; ?></td>
                                <td><?php echo $row['Municipio']; ?></td>
                                <td><?php echo $row['Estado']; ?></td>
                                <td>
                                <div class = "Acciones">
                                    <a href="ActualizaGanadero.php?idGanadero=<?php echo $row['idGanadero']; ?>">
                                        <img src="Imagenes/Editar.png" alt="Editar" class="mi-imagen">
                                    </a>
                                    <a href="BorrarGanadero.php?idGanadero=<?php echo $row['idGanadero']; ?>">
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
                        <a href="AgregarGanadero.php">
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