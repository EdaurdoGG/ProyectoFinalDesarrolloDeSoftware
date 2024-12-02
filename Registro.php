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
    $nombre = mysqli_real_escape_string($conexion, $_POST['Nombres']);
    $apellidop = mysqli_real_escape_string($conexion, $_POST['ApellidoP']);
    $apellidom = mysqli_real_escape_string($conexion, $_POST['ApellidoM']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['Telefono']);
    $puesto = mysqli_real_escape_string($conexion, $_POST['Puesto']);
    $salario = (float)$_POST['Salario']; // Asegúrate de que sea un número
    $clave = mysqli_real_escape_string($conexion, $_POST['Clave']);
    $hashedClave = password_hash($clave, PASSWORD_DEFAULT); // Almacenar contraseña de forma segura

    // Preparar la consulta SQL usando declaraciones preparadas
    $stmt = $conexion->prepare("INSERT INTO Empleados (Nombres, ApellidoP, ApellidoM, Telefono, Puesto, Salario, Clave) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        // Vincular parámetros
        $stmt->bind_param("sssssis", $nombre, $apellidop, $apellidom, $telefono, $puesto, $salario, $hashedClave);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo '<div class = "Mensaje">Registro completo.</div>';
        } else {
            echo '<div class = "Mensaje">Error al registrar:</div>'. $stmt->error;
        }
        
        // Cerrar la declaración
        $stmt->close();
    } else {
        echo '<div class = "Mensaje">Error al preparar la consulta:</div>'. $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html> 
<html>
<head>
    <title>Registro Trabajadores</title> 
    <link rel="stylesheet" type="text/css" href="Registro.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <main>
        <section class="izquierda"></section>
        <section class="centro">
            <br>
            <div class="log">
                <div class="login">
                    <form method="POST" action="Registro.php">
                        <div class="titulo">
                            <h2>Bienvenido</h2>
                            <a href="index.html">
                                <img src="Imagenes/casa.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>
                        <label>Nombre/s</label>
                        <input type="text" name="Nombres" required>
                        <label>Apellido Paterno</label>
                        <input type="text" name="ApellidoP" required>
                        <label>Apellido Materno</label>
                        <input type="text" name="ApellidoM" required>
                        <label>Número Telefónico</label>
                        <input type="text" name="Telefono" required>
                        <label>Puesto</label>
                        <select class="Opcion" name="Puesto">
                            <option disabled selected>Selecciona una Opcion</option>
                            <option>Corrales</option>
                            <option>Almacenes</option>
                        </select>
                        <br>
                        <label>Salario</label>
                        <input type="number" name="Salario" required>
                        <label>Contraseña</label>
                        <input type="password" name="Clave" required>
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
