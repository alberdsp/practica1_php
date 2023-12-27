<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Mantenimiento Alumnos</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    // Cargamos fichero de configuración
    require_once('config.php');

    // Inicializar variables
    $dni = '';
    $nombre = '';
    $apellido1 = '';
    $apellido2 = '';
    $direccion = '';
    $localidad = '';
    $provincia = '';
    $fecha_nacimiento = '';
    $editando = false;

    try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // comprobamos si venimos con parametros de edición
        if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['dni'])) {
            $accion = $_GET['accion'];
            $dni = $_GET['dni'];

            $sql = "SELECT DNI, APELLIDO_1, APELLIDO_2, NOMBRE, DIRECCION, LOCALIDAD, PROVINCIA, FECHA_NACIMIENTO FROM alumno WHERE dni = :dni";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':dni', $dni);
            $stmt->execute();

            $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($alumno) {
                $dni = $alumno['DNI'];
                $nombre = $alumno['NOMBRE'];
                $apellido1 = $alumno['APELLIDO_1'];
                $apellido2 = $alumno['APELLIDO_2'];
                $direccion = $alumno['DIRECCION'];
                $localidad = $alumno['LOCALIDAD'];
                $provincia = $alumno['PROVINCIA'];
                $fecha_nacimiento = $alumno['FECHA_NACIMIENTO'];
                $editando = true;
            }
        }

        // comprobamos si hemos realizado acción de actualizar registro o de insertar
        if (!empty($_POST['accion'])) {
            $accion = $_POST['accion'];
            $params = [];

            if ($accion === 'insertar') {
                $sql = "INSERT INTO alumno (DNI, APELLIDO_1, APELLIDO_2, NOMBRE, DIRECCION, LOCALIDAD, PROVINCIA, FECHA_NACIMIENTO) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $params = [$_POST['dni'], $_POST['apellido1'], $_POST['apellido2'], $_POST['nombre'], $_POST['direccion'], $_POST['localidad'], $_POST['provincia'], $_POST['fecha_nacimiento']];
            } elseif ($accion === 'actualizar') {
                $sql = "UPDATE alumno SET APELLIDO_1 = ?, APELLIDO_2 = ?, NOMBRE = ?, DIRECCION = ?, LOCALIDAD = ?, PROVINCIA = ?, FECHA_NACIMIENTO = ? WHERE DNI = ?";
                $params = [$_POST['apellido1'], $_POST['apellido2'], $_POST['nombre'], $_POST['direccion'], $_POST['localidad'], $_POST['provincia'], $_POST['fecha_nacimiento'], $_POST['dni']];
            }

            $stmt = $con->prepare($sql);
            $stmt->execute($params);



            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>


    <!-- Mostramos mensaje según la acción -->
    <div class="divbotonera">
        <?php
        if (isset($_GET['accion'])) {
            $action = $_GET['accion'];
            if ($action === 'añadir') {
                echo '<h2>Nuevo alumno</h2>';
            } elseif ($action === 'editar') {
                echo '<h2>Editando Alumno</h2>';
            }
        }
        ?>
    </div>

    <div class="divbotonera">
        <h2>Información del Alumno</h2>
    </div>

    <!-- Formulario dinámico, si estamos en editar o insertar cambia los botones -->
    <div>
        <form id="alumnoInfoForm" method="post" action="form.php">
            <?php if ($editando) : ?>
                <input type="hidden" id="accion" name="accion" value="actualizar">
            <?php else : ?>
                <input type="hidden" id="accion" name="accion" value="insertar">
            <?php endif; ?>

            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" value="<?php echo htmlspecialchars($dni); ?>" placeholder="DNI" required>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" placeholder="Nombre" required>
            <label for="apellido1">Apellido 1:</label>
            <input type="text" id="apellido1" name="apellido1" value="<?php echo htmlspecialchars($apellido1); ?>" placeholder="Apellido 1" required>
            <label for="apellido2">Apellido 2:</label>
            <input type="text" id="apellido2" name="apellido2" value="<?php echo htmlspecialchars($apellido2); ?>" placeholder="Apellido 2" required>
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion); ?>" placeholder="Direccion" required>
            <label for="localidad">Localidad:</label>
            <input type="text" id="localidad" name="localidad" value="<?php echo htmlspecialchars($localidad); ?>" placeholder="Localidad" required>
            <label for="provincia">Provincia:</label>
            <input type="text" id="provincia" name="provincia" value="<?php echo htmlspecialchars($provincia); ?>" placeholder="Provincia" required>
            <label for="fecha_nacimiento">Fecha Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" placeholder="Fecha Nacimiento" required>

            <button class="botonbuscar" type="submit">
                <?php echo $editando ? 'Actualizar' : 'Añadir'; ?>
            </button>
        </form>
    </div>

    <div class="divbotonera">
        <button class="botonvolver" onclick="window.location.href='index.php'">Volver al listado</button>
    </div>
</body>

</html>