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

    // Verificar si se presionó el botón
    if (isset($_POST["Boton"])) {
        // Validar que los campos no estén vacíos
        if (empty($_POST["Nombre"]) || empty($_POST["Clave"])) {
            echo '<div class="Mensaje">Los campos están vacíos</div>';
        } else {
            $usuario = $_POST["Nombre"];
            $clave = $_POST["Clave"];

            // Consulta para verificar el usuario, contraseña y puesto
            $sql = $conexion->prepare("SELECT * FROM empleados WHERE Nombres = ? AND Clave = ? AND Puesto = 'Almacenes'");
            $sql->bind_param("ss", $usuario, $clave);
            $sql->execute();
            $result = $sql->get_result();

            // Verificar si se encontró un usuario con el puesto de Almacenes
            if ($result->num_rows > 0) {
                // Redirigir a la página deseada
                header("Location: Admi.html");
                exit();
            } else {
                // Mensaje para usuario no autorizado
                echo '<div class="Mensaje">El nombre, contraseña o puesto son incorrectos</div>';
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
    <link rel="stylesheet" type="text/css" href="Login.css">
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
                            <h2>Bienvenido</h2>
                            <a href="index.html">
                                <img src="Imagenes/casa.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>
                        <label>Nombre</label>
                        <br>
                        <input type="text" name="Nombre" required>
                        <label>Contraseña</label>
                        <br>
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