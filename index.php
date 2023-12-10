<html>

<head>
  <meta charset="UTF-8">
  <title>Práctica 1.4 php</title>
  <link rel="stylesheet" href="css/style.css">
  <meta charset="UTF-8">
</head>

<h1 class="descrip">Práctica 1.4 DI</h1>
<h2 class="descrip">Listado de alumnos</h2>



<?php
// Inicializamos las variables
$dni = '';
$nombre = '';
$localidad = '';
$fechanacimiento = '';
$sqlfiltro= '';
?>

<form class="form" action="index.php" method="post">
    <label for="dni">Dni:</label>
    <input type="number" id="dni" name="dni" value="<?php echo $dni; ?>">

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
    <label for="localidad">Localidad:</label>
    <input type="text" id="localidad" name="localidad" value="<?php echo $localidad; ?>">

    <label for="fechanacimiento">Fecha Nacimiento:</label>
    <input type="text" id="fechanacimiento" name="fechanacimiento" value="<?php echo $fechanacimiento; ?>">

    <input type="submit" value="Enviar">
</form>











<table class="table">



  <?php
  /*
   * ABF 2023 declaramos las variables
   */

  $servername = "localhost:3307";
  $username = "root";
  $password = "sauber";
  $dbname = "universidad";

  try {
    $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // establecemos excepción  PDO  error
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión a la base de datos correcta"."<br>";
  } catch (PDOException $e) {
    echo "Conexión erronea: " . $e->getMessage() . "<br>";
  }

  // 

  $stmt = $con->prepare('SELECT   DNI,APELLIDO_1,APELLIDO_2,NOMBRE,DIRECCION,
LOCALIDAD,PROVINCIA,FECHA_NACIMIENTO FROM alumno ');

// Capturamos los valores de las variables
if (isset($_POST['dni'])) {
  $dni = $_POST['dni'];

  $sqlfiltro = '';
}

if (isset($_POST['nombre'])) {
  $nombre = $_POST['nombre'];
}

if (isset($_POST['localidad'])) {
  $localidad = $_POST['localidad'];
}

if (isset($_POST['fechanacimiento'])) {
  $fechanacimiento = $_POST['fechanacimiento']; 
}






  ?>

  <thead>

    <tr>
      <th>DNI</th>
      <th>Nombre</th>
      <th>Apellido 1</th>
      <th>Apellido 2</th>
      <th>Dirección</th>
      <th>Localidad</th>
      <th>Provincia</th>
      <th>Fecha de Nacimiento</th> <br>
    </tr>

  </thead>



  <body>

    <?php
    try {
      // Executamos el statement
      $stmt->execute();

      // Hacemos Fetch a todos los registros
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Recorremos el resultado y creamos las filas
      if ($resultado) {
        foreach ($resultado as $lista) {

          // rellenamos cada fila con los datos
          echo '<tr class= "celda">';
          echo '<td class= "celda" >' . $lista['DNI'] . "</td>";
          echo '<td class= "celda" >' . $lista['NOMBRE'] . "</td>";
          echo '<td class= "celda" >' . $lista['APELLIDO_1'] . "</td>";
          echo '<td class= "celda" >' . $lista['APELLIDO_2'] . "</td>";
          echo '<td class= "celda" >' . $lista['DIRECCION'] . "</td>";
          echo '<td class= "celda" >' . $lista['LOCALIDAD'] . "</td>";
          echo '<td class= "celda" >' . $lista['PROVINCIA'] . "</td>";
          echo '<td class= "celda" >' . $lista['FECHA_NACIMIENTO'] . "</td>";
          echo "</tr>";
        }
      } else {
        echo "No records found.";
      }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }

    ?>

</table>


</body>

</html>