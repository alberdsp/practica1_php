<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
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
$localidad = '';
$provincia = ''; 
$fecha_nacimiento = '';
$editando = false;

try {
    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['accion']) && isset($_GET['dni'])) {
        $accion = $_GET['accion'];
        $dni = $_GET['dni'];

        if ($accion === 'editar') {
            // Actualizar consulta para usar una declaración preparada
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
                $localidad = $alumno['LOCALIDAD'];
                $provincia = $alumno['PROVINCIA']; 
                $fecha_nacimiento = $alumno['FECHA_NACIMIENTO'];
                $editando = true; // Establecer editando en verdadero
            }
        } else {
            echo "Acción desconocida";
        }
    } else {
        echo "Faltan parámetros";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>



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

<div>
   
    <form id="alumnoInfoForm" method="post" action="form.php">
        <?php if ($editando) : ?>
            <input type="hidden" name="edit" value="<?php echo $_GET['dni']; ?>">
        <?php endif; ?>
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" value="<?php echo $dni; ?>" placeholder="DNI" required>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" placeholder="Nombre" required>
        <label for="apellido1">Apellido 1:</label>
        <input type="text" id="apellido1" name="apellido1" value="<?php echo $apellido1; ?>" placeholder="Apellido 1" required>
        <label for="apellido2">Apellido 2:</label>
        <input type="text" id="apellido2" name="apellido2" value="<?php echo $apellido2; ?>" placeholder="Apellido 2" required>
        <label for="localidad">Localidad:</label>
        <input type="text" id="localidad" name="localidad" value="<?php echo $localidad; ?>" placeholder="Localidad" required>
        <label for="provincia">Provincia:</label>
        <input type="text" id="provincia" name="provincia" value="<?php echo $provincia; ?>" placeholder="Provincia" required>
        <label for="fecha_nacimiento">Fecha Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fecha_nacimiento; ?>" placeholder="Fecha Nacimiento" required>
        <!-- Botón para crear o actualizar -->
        <button class= "botonbuscar" type="submit" name="<?php echo $editando ? 'update' : 'create'; ?>">
            <?php echo $editando ? 'Actualizar' : 'Añadir'; ?>
        </button>
    </form>
</div>

<div class="divbotonera">
<button  class ="botonvolver" onclick="window.location.href='index.php'">Volver al listado</button>
</div>

</body>
</html>
