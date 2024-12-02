<?php
// Verifica si se ha pasado un idGanadero en la URL
if (isset($_GET['idGanadero'])) {
    $idGanadero = $_GET['idGanadero']; // Usamos la variable correcta

    // Configuración de la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "GANADERIA2";

    // Crear conexión
    $conexion = mysqli_connect($server, $user, $pass, $db);

    // Verificar la conexión
    if ($conexion === false) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Eliminar al ganadero de la base de datos
    $delete_sql = "DELETE FROM Ganaderos WHERE idGanadero = ?";
    $stmt = $conexion->prepare($delete_sql);
    $stmt->bind_param("i", $idGanadero); // Pasar el idGanadero correctamente

    if ($stmt->execute()) {
        // Redirigir a la lista de ganaderos después de la eliminación
        header("Location: Ganaderos.php?mensaje=Ganadero eliminado con éxito");
        exit;
    } else {
        echo "Error al eliminar el ganadero: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "No se especificó el id del ganadero.";
    exit;
}
?>;