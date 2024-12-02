<?php
// Verifica si se ha pasado un idEmpleado en la URL
if (isset($_GET['idEmpleado'])) {
    $idEmpleado = $_GET['idEmpleado'];

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

    // Eliminar al empleado de la base de datos
    $delete_sql = "DELETE FROM Empleados WHERE idEmpleado = ?";
    $stmt = $conexion->prepare($delete_sql);
    $stmt->bind_param("i", $idEmpleado); // Pasar el idEmpleado

    if ($stmt->execute()) {
        // Redirigir a la lista de empleados después de la eliminación
        header("Location: Empleados.php?mensaje=Empleado eliminado con éxito");
        exit;
    } else {
        echo "Error al eliminar el empleado: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "No se especificó el id del empleado.";
    exit;
}
?>;
