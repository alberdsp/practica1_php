


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Práctica 1.4 php</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <h1 class="descrip">Práctica 1.4 DI</h1>
  <h2 class="descrip">Listado de alumnos</h2>


  <form class="form" action="index.php" method="post">
    <label for="dni">Dni:</label>
    <input type="text" id="dni" name="dni">

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre">

    <label for="localidad">Localidad:</label>
    <input type="text" id="localidad" name="localidad">

    <label for="fechanacimiento">F. Nacimiento:</label>
    <input type="date" id="fechanacimiento" name="fechanacimiento">

    <input type="submit" value="Buscar">
  </form>

  <table class="table">
    <thead>
      <tr>
        <th>DNI</th>
        <th>Nombre</th>
        <th>Apellido 1</th>
        <th>Apellido 2</th>
        <th>Dirección</th>
        <th>Localidad</th>
        <th>Provincia</th>
        <th>Fecha de Nacimiento</th>
      </tr>
    </thead>
    <tbody>
      <?php

// Inicializamos las variables
$dni = '';
$nombre = '';
$localidad = '';
$fechanacimiento = '';
$sqlfiltro= '';
$params = [];



      // Conexión a la bd
      $servername = "localhost:3307";
      $username = "root";
      $password = "sauber";
      $dbname = "universidad";

      try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlfiltro = 'SELECT DNI, APELLIDO_1, APELLIDO_2, NOMBRE, DIRECCION, LOCALIDAD, PROVINCIA, FECHA_NACIMIENTO FROM alumno WHERE 1 = 1';
        $params = [];
        // comenzamos a cargar los parámetros de la consulta
        if (!empty($_POST['dni'])) {
          $dni=$_POST['dni'];
          $sqlfiltro .= ' AND dni = ?';
          $params[] = $dni;
        }

        if (!empty($_POST['nombre'])) {
          $nombre = $_POST['nombre'];
          $sqlfiltro .= ' AND nombre LIKE ?';
          $params[] = '%' . $nombre . '%';
        }

        if (!empty($_POST['localidad'])) {
          $localidad = $_POST['localidad'];
          $sqlfiltro .= ' AND localidad LIKE ?';
          $params[] = '%' . $localidad . '%';
        }

        if (!empty($_POST['fechanacimiento'])) {
          $fechanacimiento = $_POST['fechanacimiento'];
          $sqlfiltro .= ' AND fecha_nacimiento = ?';
          $params[] = $fechanacimiento;
        }

        $stmt = $con->prepare($sqlfiltro);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultados) {
          foreach ($resultados as $lista) {
            echo "<tr class='celda'>";
            echo "<td class='celda'>" . htmlspecialchars($lista['DNI']) . "</td>";
            echo "<td class='celda'>" . htmlspecialchars($lista['NOMBRE']) . "</td>";
            echo "<td class='celda'>" . htmlspecialchars($lista['APELLIDO_1']) . "</td>";
            echo "<td class='celda'>" . htmlspecialchars($lista['APELLIDO_2']) . "</td>";
            echo "<td class='celda'>" . htmlspecialchars($lista['DIRECCION']) . "</td>";
            echo "<td class='celda'>" . htmlspecialchars($lista['LOCALIDAD']) . "</td>";
            echo "<td class='celda'>" . htmlspecialchars($lista['PROVINCIA']) . "</td>";
            echo "<td class='celda'>" . htmlspecialchars($lista['FECHA_NACIMIENTO']) . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='8'>No records found.</td></tr>";
        }
      } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
      ?>
    </tbody>
  </table>

</body>

</html>