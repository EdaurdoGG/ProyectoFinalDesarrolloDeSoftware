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
    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Obtener datos del formulario
    $n_Reemo = $_POST['N_Reemo'];
    $numeroArete = $_POST['NumeroArete'];
    $sexo = $_POST['Sexo'];
    $meses = $_POST['Meses'];
    $fierro = $_POST['Fierro'];
    $peso = $_POST['Peso'];
    $precioCompra = $_POST['PrecioCompra'];
    $motivo = $_POST['Motivo'];
    $fecha = $_POST['Fecha'];
    $razonSocial = $_POST['Razon-Social'];  // Razón Social seleccionada

    // Validar que la Razón Social exista en la tabla ganaderos y obtener su id
    $consultaGanadero = $conexion->prepare("SELECT idGanadero FROM ganaderos WHERE RazonSocial = ?");
    $consultaGanadero->bind_param("s", $razonSocial);
    $consultaGanadero->execute();
    $resultadoGanadero = $consultaGanadero->get_result();

    if ($resultadoGanadero->num_rows > 0) {
        // Obtener el idGanadero
        $ganadero = $resultadoGanadero->fetch_assoc();
        $idGanadero = $ganadero['idGanadero'];

        // Registrar en la tabla 'compraganado'
        $stmt2 = $conexion->prepare("INSERT INTO compraganado (N_Reemo, Motivo, Fecha) VALUES (?, ?, ?)");
        if ($stmt2) {
            $stmt2->bind_param("iss", $n_Reemo, $motivo, $fecha);  // Corregido para que los parámetros coincidan con la consulta

            // Ejecutar la consulta
            if ($stmt2->execute()) {
                // Obtener el id de la compra realizada
                $idCompraGanado = mysqli_insert_id($conexion);

                // Registrar en la tabla 'Animales' con el idGanadero
                $stmt1 = $conexion->prepare("INSERT INTO Animales (idCompraGanado, idGanadero, NumeroArete, Sexo, Meses, Fierro, Peso, PrecioCompra) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt1) {
                    // Vincular parámetros
                    $stmt1->bind_param("iiissisi", $idCompraGanado, $idGanadero, $numeroArete, $sexo, $meses, $fierro, $peso, $precioCompra);

                    // Ejecutar la consulta
                    if ($stmt1->execute()) {
                        // Obtener el ID del nuevo animal
                        $idAnimal = mysqli_insert_id($conexion);

                        // Registrar también en la tabla 'corral'
                        $stmtCorral = $conexion->prepare("INSERT INTO corral (idCompraGanado, idGanadero, NumeroArete, Sexo, Meses, Fierro, Peso, PrecioCompra) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        if ($stmtCorral) {
                            // Vincular parámetros
                            $stmtCorral->bind_param("iiissisi", $idCompraGanado, $idGanadero, $numeroArete, $sexo, $meses, $fierro, $peso, $precioCompra);

                            // Ejecutar la consulta en la tabla 'corral'
                            if ($stmtCorral->execute()) {
                                echo '<div class="Mensaje">Registro completo.</div>';
                            } else {
                                echo "Error al registrar en Corral: " . $stmtCorral->error;
                            }
                            $stmtCorral->close();
                        } else {
                            echo "Error al preparar la consulta de Corral: " . $conexion->error;
                        }

                        // Actualizar clasificación de los animales
                        clasificarAnimales($conexion);
                        
                        // Actualizar clasificación de los animales en Corral
                        clasificarAnimalesCorral($conexion);
                        
                        // Calcular y actualizar el precio total para el animal recién insertado
                        calcularPrecioTotal($conexion, $idAnimal);

                    } else {
                        echo "Error al registrar en Animales:". $stmt1->error;
                    }
                    $stmt1->close();
                } else {
                    echo "Error al preparar la consulta de Animales:". $conexion->error;
                }
            } else {
                echo "Error al registrar en compraganado:". $stmt2->error;
            }
            $stmt2->close();
        } else {
            echo "Error al preparar la consulta de compraganado:". $conexion->error;
        }
    } else {
        echo '<div class = "Mensaje">La Razón Social no está registrada en la base de datos.</div>';
    }

    // Cerrar la conexión
    $conexion->close();
}

// Función para clasificar animales
function clasificarAnimales($conexion) {
    $sql = "
        UPDATE Animales
        SET Clasificacion = CASE
            WHEN Meses BETWEEN 1 AND 15 THEN 'Becerro/Becerra'
            WHEN Meses BETWEEN 16 AND 24 THEN 'Torete/Vacona'
            WHEN Meses >= 25 THEN 'Toro/Vaca'
            ELSE 'No clasificado'
        END
    ";

    // Ejecutar la consulta
    if (!mysqli_query($conexion, $sql)) {
        echo "Error al actualizar clasificaciones en Animales: " . mysqli_error($conexion);
    }
}

// Función para clasificar animales en Corral
function clasificarAnimalesCorral($conexion) {
    $sql = "
        UPDATE corral
        SET Clasificacion = CASE
            WHEN Meses BETWEEN 1 AND 15 THEN 'Becerro/Becerra'
            WHEN Meses BETWEEN 16 AND 24 THEN 'Torete/Vacona'
            WHEN Meses >= 25 THEN 'Toro/Vaca'
            ELSE 'No clasificado'
        END
    ";

    // Ejecutar la consulta
    if (!mysqli_query($conexion, $sql)) {
        echo "Error al actualizar clasificaciones en Corral: " . mysqli_error($conexion);
    }
}

// Función para calcular y actualizar el precio total
function calcularPrecioTotal($conexion, $idAnimal) {
    $sql = "
        UPDATE Animales
        SET PrecioTotal = Peso * PrecioCompra,
        Ganancia = Peso * PrecioCompra
        WHERE idAnimal = ? 
    ";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        // Vincular parámetros
        $stmt->bind_param("i", $idAnimal);

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            echo "Error al actualizar PrecioTotal y Ganancia en Animales: " . mysqli_error($conexion);
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta para PrecioTotal y Ganancia en Animales: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Ganado</title>
    <link rel="stylesheet" type="text/css" href="AgregarGanado.css">
    <link rel="icon" type="image" href="Imagenes/Logo.jpg">
</head>
<body>
    <main>
        <section class="izquierda">
            <img src="Imagenes/Fierros.png" alt="Fierros" class="mi-foto">
        </section>
        <section class="centro">
            <br><br>
            <div class="log">
                <div class="login">
                    <form method="POST" action="">
                        <div class="titulo">
                            <h2>Agregar Ganado</h2>
                            <a href="Tabajadores.php">
                                <img src="Imagenes/atras.png" alt="Boton Atras" class="logo">
                            </a>
                        </div>
                        <label>Numero de Reemo</label>
                        <input type="number" name="N_Reemo" required>
                        <label>Numero de Arete</label>
                        <input type="number" name="NumeroArete" required>
                        <label>Razon Social</label>
                        <select class="Opcion" name="Razon-Social" required>
                            <option disabled selected>Selecciona una Opcion</option>
                            <?php
                            // Configuración de la base de datos
                            $conexion = mysqli_connect("localhost", "root", "", "GANADERIA2");

                            // Consultar los nombres de los ganaderos
                            $sql = "SELECT RazonSocial FROM ganaderos";
                            $result = mysqli_query($conexion, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . htmlspecialchars($row['RazonSocial']) . '">' . htmlspecialchars($row['RazonSocial']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay Razones Sociales Registrados</option>';
                            }

                            // Cerrar la conexión
                            mysqli_close($conexion);
                            ?>
                        </select>
                        <label>Sexo</label>
                        <select type="text" class="Opcion" name="Sexo" required>
                            <option disabled selected>Selecciona una Opcion</option>
                            <option>Macho</option>
                            <option>Hembra</option>
                        </select>
                        <label>Meses</label>
                        <input type="number" name="Meses" required>
                        <label>Numero de Marcas</label>
                        <input type="number" name="Fierro" required>
                        <label>Peso de Compra (en KG)</label>
                        <input type="number" name="Peso" required>
                        <label>Precio del Kg</label>
                        <input type="number" name="PrecioCompra" required>
                        <label>Motivo</label>
                        <select class="Opcion" name="Motivo" required>
                            <option disabled selected>Selecciona una Opcion</option>
                            <option>Cria</option>
                            <option>Engorda</option>
                            <option>Sacrificio</option>
                        </select>
                        <label>Fecha de Compra</label>
                        <input type="date" name="Fecha" required>
                        <button type="submit">Registrar</button> 
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>