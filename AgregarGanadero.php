<?php
session_start(); // Iniciar la sesión

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Boton'])) {
    // Configuración de la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "GANADERIA2";

    // Crear conexión
    $conexion = mysqli_connect($server, $user, $pass, $db);

    // Verificar conexión
    if ($conexion === false) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Obtener datos del formulario y sanitizar
    $nombre = $_POST['Nombre'];
    $razonSocial= $_POST['RazonSocial'];
    $domicilio = $_POST['Domicilio'];
    $localidad = $_POST['Localidad'];
    $municipio = $_POST['Municipio'];
    $estado = $_POST['Estado'];
    // Preparar la consulta SQL usando declaraciones preparadas
    $stmt = $conexion->prepare("INSERT INTO ganaderos (Nombre, RazonSocial, Domicilio, Localidad, Municipio, Estado) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        // Vincular parámetros
        $stmt->bind_param("ssssss", $nombre, $razonSocial, $domicilio, $localidad, $municipio, $estado);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo '<div class = "Mensaje">Ganadero Registrado Correctamente</div>';
        } else {
            echo "Error al registrar: " . $stmt->error;
        }
        
        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html> 
<html>
<head>
    <title>Registro Ganaderos</title> 
    <link rel="stylesheet" type="text/css" href="AgregarGanadero.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <main>
        <section class="izquierda"></section>
        <section class="centro">
            <br>
            <div class="log">
                <div class="login">
                    <form method="POST" action="">
                        <div class="titulo">
                            <h2>Registro Ganadero</h2>
                            <a href="Ganaderos.php">
                                <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>
                        <label>Nombre/s</label>
                        <input type="text" name="Nombre" required>
                        <label>Razon Social</label>
                        <input type="text" name="RazonSocial" required>
                        <label>Domicilio</label>
                        <input type="text" name="Domicilio" required>
                        <label>Localidad</label>
                        <input type="text" name="Localidad" required>
                        <label>Municipio</label>
                        <input type="text" name="Municipio" required>
                        <label>Estado</label>
                        <input type="text" name="Estado" required>
                        <button type="submit" name="Boton">Agregarme</button>
                    </form>
                </div>
            </div>
            <br>
        </section>
        <section class="derecha"></section>
    </main>
    <footer>
		<p>&copy; 2024 Ganaderia el Rosario. Todos los derechos reservados.</p>
	</footer>
</body>
</html>