<?php
// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    //Tienen que poner el nombre que le dieron a su boton donde esta de color naranja y dice boton
    if (isset($_POST["Boton"])) {
        // Validar que los campos no estén vacíos
        if (empty($_POST["Nombre"]) || empty($_POST["Clave"])) {
            echo '<div class = "Mensaje">Los campos están vacíos</div>';
        } else {
            $usuario = $_POST["Nombre"];
            $clave = $_POST["Clave"];

            // Verificar si el usuario y la clave coinciden con los valores específicos
            if ($usuario === "Admi" && $clave === "Admi001") {
                // Si el usuario y la clave son correctos, redirigir a la página de reportes
                header("Location: Reportes.php");
                exit();
            } else {
                // Mensaje para cuando no es un usuario registrado o la contraseña es incorrecta
                echo '<div class = "Mensaje">El nombre o contraseña son incorrectos</div>';
            }
        }
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="LoginPatron.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <main>
        <section class="izquierda"></section>
        <section class="centro">
            <br>
            <br>
            <div class="log">
                <div class="login">
                    <form method="POST" action="">
                        <div class="titulo">
                            <h2>Bienvenido Señor</h2>
                            <a href="index.html">
                                <img src="Imagenes/casa.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>
                        <label>Nombre</label>
                        <input type="text" name="Nombre" required>
                        <label>Contraseña</label>
                        <input type="password" name="Clave" required>
                        <button type="submit" name="Boton">Acceder</button>
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