<?php
// Configuración de la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "GANADERIA2";

// Crear conexión
$conexion = mysqli_connect($server, $user, $pass, $db);
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Inicializar las variables
$ganancia = 0;
$precioTotal = 0;
$numeroArete = 0; // Iniciar la variable para mantener el valor de N_Reemo
$n_Reemo = '';

// Si se selecciona un NumeroArete, cargamos el precio y el N_Reemo
if (isset($_POST['NumeroArete']) && !empty($_POST['NumeroArete'])) {
    $numeroArete = $_POST['NumeroArete'];

    // Consultar el valor de 'ganancia', 'PrecioTotal' y 'N_Reemo' de la tabla 'animales' usando JOIN con 'compraganado'
    $sql = "
        SELECT a.ganancia, a.PrecioTotal, c.N_Reemo
        FROM animales a
        JOIN compraganado c ON c.idCompraGanado = a.idCompraGanado
        WHERE a.NumeroArete = ? 
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $numeroArete); // Usar 's' para tipo string (NumeroArete es un campo de texto)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $animal = $result->fetch_assoc();
        $ganancia = $animal['ganancia'];  // Cargar el valor de 'ganancia' como 'PrecioSugerido'
        $precioTotal = $animal['PrecioTotal'];
        $n_Reemo = $animal['N_Reemo'];  // Obtener el valor de N_Reemo desde 'compraganado'
    } else {
        $ganancia = 0;
        $precioTotal = 0;
        $n_Reemo = '';  // En caso de no encontrar el valor
    }

    $stmt->close();
}

// Cerrar la conexión
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta de Ganado</title>
    <link rel="stylesheet" type="text/css" href="VenderGanado.css">
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
                        <br>
                        <div class="titulo">
                            <h2>Venta de Ganado</h2>
                            <a href="Tabajadores.php">
                                <img src="Imagenes/atras.png" alt="Boton Atras" class="boton-atras">
                            </a>
                        </div>
                        <br>
                        <label>Numero de Arete</label>
                        <select class="Opcion" name="NumeroArete" id="NumeroArete" onchange="this.form.submit()">
                            <option disabled selected>Selecciona el Numero de Arete</option>
                            <?php
                            // Conexión a la base de datos
                            $conexion = mysqli_connect($server, $user, $pass, $db);
                            if (!$conexion) {
                                die("Conexión fallida: " . mysqli_connect_error());
                            }

                            // Consultar los NumeroArete disponibles
                            $sql = "SELECT NumeroArete FROM animales";
                            $result = mysqli_query($conexion, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Verificar si el NumeroArete está seleccionado
                                    $selected = '';
                                    if (isset($numeroArete) && $row['NumeroArete'] == $numeroArete) {
                                        $selected = 'selected';
                                    }
                                    echo '<option value="' . htmlspecialchars($row['NumeroArete']) . '" ' . $selected . '>' . htmlspecialchars($row['NumeroArete']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay NumeroAretes disponibles</option>';
                            }

                            // Cerrar la conexión
                            mysqli_close($conexion);
                            ?>
                        </select>

                        <label>Destino del Animal</label>
                        <input type="text" name="Destino" required>

                        <label>Finalidad de la Venta</label>
                        <select class="Opcion" name="TipoVenta">
                            <option disabled selected>Selecciona una opción</option>
                            <option value="Cria">Cría</option>
                            <option value="Engorda">Engorda</option>
                            <option value="Sacrificio">Sacrificio</option>
                        </select>

                        <label>Peso del Animal (en KG)</label>
                        <input type="number" name="PesoVenta" required>

                        <label>Precio Sugerido</label>
                        <input type="number" name="PrecioVenta" value="<?php echo htmlspecialchars($ganancia); ?>" required>

                        <label>Fecha de Venta</label>
                        <input type="date" name="FechaVenta" required>

                        <label>Nombre del Patron</label>
                        <input type="text" name="Nombres" required>

                        <label>Clave de Trabajador</label>
                        <input type="password" name="Clave" required>

                        <button type="submit">Vender</button>
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

<?php
// Procesamiento del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración de la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "GANADERIA2";

    // Crear conexión
    $conexion = new mysqli($server, $user, $pass, $db);

    // Verificar conexión
    if ($conexion->connect_errno) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Obtener datos del formulario con validación
    $n_Reemo = $_POST['NumeroArete'] ?? '';
    $destino = $_POST['Destino'] ?? '';
    $tipoVenta = $_POST['TipoVenta'] ?? '';
    $pesoVenta = $_POST['PesoVenta'] ?? 0;
    $precioVenta = $_POST['PrecioVenta'] ?? 0;
    $fechaVenta = $_POST['FechaVenta'] ?? '';
    $nombresTrabajador = $_POST['Nombres'] ?? '';
    $claveTrabajador = $_POST['Clave'] ?? '';

    // Consultar el PrecioTotal y N_Reemo de la tabla animales y compraganado
    $sql = "
        SELECT a.PrecioTotal, c.N_Reemo, a.idCompraGanado 
        FROM animales a
        JOIN compraganado c ON c.idCompraGanado = a.idCompraGanado
        WHERE a.NumeroArete = ? 
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $n_Reemo); // Usar 's' para cadena (string)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $precioTotal = floatval($row['PrecioTotal']); // Convertir a número flotante
        $n_Reemo = $row['N_Reemo'];  // Obtener N_Reemo desde compraganado
        $idCompraGanado = $row['idCompraGanado'];
    } else {
        exit("No se encontró el animal con el número de arete proporcionado.");
    }

    // Asegúrate de que precioVenta también es un número
    $precioVenta = floatval($precioVenta); // Convertir a número flotante

    // Calcular la ganancia (PrecioVenta - PrecioTotal)
    $gananciaTotal = $precioVenta - $precioTotal;

    // Validar el trabajador
    if ($nombresTrabajador === 'Admi' && $claveTrabajador === 'Admi001') {
        // Primero insertar el registro en la tabla ventaganado
        $stmt = $conexion->prepare("INSERT INTO ventaganado (idCompraGanado, N_Reemo, Destino, TipoVenta, PesoVenta, PrecioVenta, FechaVenta, GananciaTotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssddsd", $idCompraGanado, $n_Reemo, $destino, $tipoVenta, $pesoVenta, $precioVenta, $fechaVenta, $gananciaTotal);

        if ($stmt->execute()) {
            // Ahora eliminamos el animal de la tabla animales
            $deleteAnimalStmt = $conexion->prepare("DELETE FROM animales WHERE idCompraGanado = ?");
            $deleteAnimalStmt->bind_param("i", $idCompraGanado);
            if ($deleteAnimalStmt->execute()) {
                echo '<div class = "Mensaje">Venta Exitosa</div>';
            } else {
                echo "Error al eliminar el animal de la tabla animales.";
            }
        } else {
            echo "Error al insertar en la tabla ventaganado.";
        }
        $stmt->close();
    } else {
        echo "Credenciales incorrectas para el trabajador.";
    }
    $conexion->close();
}
?>