<!DOCTYPE html>
<html>

<?php

// ABF 2023 

// incluimos el fichero aparte de conexión a la BD

require_once('config.php');

// si se ha presionado limpiar, limpiamos formulario primero de todo

if (isset($_POST['clear'])) {
  header("Location: index.php");
  exit;
}



// Inicializamos las variables


$dni = '';
$nombre = '';
$localidad = '';
$fechanacimiento = '';
$sqlfiltro = '';
$params = [];
$lineas_pagina = "15";
$primera = "";
$anterior = "";
$total_paginas = 1;
$valor_inicial_limit = 0;
$valor_final_limit = 15;
$pagina_seleccionada = 1;
$pagina_actual = 1;


//  realizamos la conexión con el try y ejecutamos los filtros y la paginación si existen



try {
  $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  $sqlfiltro = 'SELECT DNI, APELLIDO_1, APELLIDO_2, NOMBRE, DIRECCION, LOCALIDAD, PROVINCIA, FECHA_NACIMIENTO  FROM alumno WHERE 1 = 1';
  $sqlcount = ' SELECT COUNT(*) as total FROM alumno WHERE 1 = 1';


  // comenzamos a cargar los parámetros de la consulta
  if (!empty($_POST['dni'])) {
    $dni = $_POST['dni'];
    $sqlfiltro .= ' AND dni = ?';
    $sqlcount .= ' AND dni = ?';
    $params[] = $dni;
  }

  if (!empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $sqlfiltro .= ' AND nombre LIKE ?';
    $sqlcount .= ' AND nombre LIKE ?';
    $params[] = '%' . $nombre . '%';
  }

  if (!empty($_POST['localidad'])) {
    $localidad = $_POST['localidad'];
    $sqlfiltro .= ' AND localidad LIKE ?';
    $sqlcount .= ' AND localidad LIKE ?';
    $params[] = '%' . $localidad . '%';
  }

  if (!empty($_POST['fechanacimiento'])) {
    $fechanacimiento = $_POST['fechanacimiento'];
    $sqlfiltro .= ' AND fecha_nacimiento = ?';
    $sqlcount .= ' AND fecha_nacimiento = ?';
    $params[] = $fechanacimiento;
  }


  if (!empty($_POST['lineas_pagina'])) {
    $lineas_pagina = $_POST['lineas_pagina'];
  }

  //camputamos valor_inicial_limit si no viene, seria 0

  if (isset($_POST['valor_inicial_limit'])) {
    $valor_inicial_limit = $_POST['valor_inicial_limit'];
  } else {
    $valor_inicial_limit = 0;
  }


  // Calcular el número total
  $stmt = $con->prepare($sqlcount);
  $stmt->execute($params);
  $total_registros = $stmt->fetchColumn();

  // Calcular el número de páginas 
  $total_paginas = ceil($total_registros / $lineas_pagina);


  if (isset($_POST['pagina_seleccionada'])) {
    $pagina_actual = $_POST['pagina_seleccionada'];
    $pagina_seleccionada = $_POST['pagina_seleccionada'];
    $valor_inicial_limit =  $lineas_pagina * ($pagina_seleccionada - 1);
    $valor_final_limit = $lineas_pagina;
  }

  //nos situamos en la primera
  if (isset($_POST['primera'])) {
    $valor_inicial_limit = 0;
    $pagina_actual = 1;
    $pagina_seleccionada = 1;
    // retrocedemos una página

  } elseif (isset($_POST['anterior'])) {
    $valor_inicial_limit = max(0, $valor_inicial_limit - $lineas_pagina);
    $pagina_actual--;
    $pagina_seleccionada = $pagina_actual;

    // comprobamos si estamos al final de la última página

  } elseif (isset($_POST['siguiente'])) {
    $pagina_actual++;
    $pagina_seleccionada++;

    if ($pagina_actual < $total_paginas) {


      $valor_inicial_limit = min($total_registros - $lineas_pagina, $valor_inicial_limit + $lineas_pagina);
    } else {

      $pagina_actual = $total_paginas;
      $pagina_seleccionada = $total_paginas;
      $valor_inicial_limit =  $lineas_pagina * ($total_paginas - 1);
      $valor_final_limit = $total_registros - $valor_inicial_limit;

      $pagina_actual = $total_paginas;
      $pagina_seleccionada = $pagina_actual;
    }

    //nos situamos en la última página

  } elseif (isset($_POST['ultima'])) {
    $valor_inicial_limit =  $lineas_pagina * ($total_paginas - 1);
    $valor_final_limit = $total_registros - $valor_inicial_limit;

    $pagina_actual = $total_paginas;
    $pagina_seleccionada = $pagina_actual;
  }

  // controlamos que la paginación no se salga de los límites 

  if ($pagina_seleccionada > $total_paginas) {
    $pagina_seleccionada = $total_paginas;
  }

  if ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
  }

  if ($pagina_seleccionada < 1) {
    $pagina_seleccionada = 1;
  }

  if ($pagina_actual < 1) {
    $pagina_actual = 1;
  }

  // establecemos limites y orden al filtro
  $sqlfiltro .= " order by nombre ASC LIMIT $valor_inicial_limit , $lineas_pagina";

  $stmt = $con->prepare($sqlfiltro);
  $stmt->execute($params);
  $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

?>

<head>
  <meta charset="UTF-8">
  <title>Práctica 1.6 php</title>
  <link rel="stylesheet" href="css/style.css">
  <script>
    function clearForm() {
      document.getElementById("filtroForm").reset();
    }
  </script>
</head>

<body>

  <h1 class="descrip">Práctica 1.6 DI</h1>
  <h2 class="descrip">Listado de alumnos</h2>


  <form id='filtroForm' class="form" action="index.php" method="post">
    <label for="dni">Dni:</label>
    <input type="text" id="dni" name="dni" value="<?php echo $dni ?>">

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre ?>">

    <label for="localidad">Localidad:</label>
    <input type="text" id="localidad" name="localidad" value="<?php echo $localidad ?>">

    <label for="fechanacimiento">F. Nacimiento:</label>
    <input type="date" id="fechanacimiento" name="fechanacimiento" value="<?php echo $fechanacimiento ?>">
    <input type="hidden" name="lineas_pagina" value="<?php echo htmlspecialchars($lineas_pagina); ?>" />
    <input class="botonbuscar" type="submit" value="Buscar">
    <input class="botonlimpiar" type="submit" name="clear" value="Limpiar">



  </form>
  <!-- Añadimos el boton de añadir -->

  <button class="botonmas" onclick="window.location.href='form.php?accion=añadir'">Añadir alumno</button>

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
          echo "<td class='celda'><button class='botoneditar'onclick='editarAlumno(" . $lista['DNI'] . ")'>Editar</button> <button class='botonborrar' onclick='eliminarAlumno(" . $lista['DNI'] . ")'>Eliminar</button></td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='8'>No se han encontrado registros.</td></tr>";
      }

      ?>
    </tbody>
  </table>


  <br>


  <form class="paginacion" method="post" action="index.php">

    <!-- Campos Ocultos para Mantener los Filtros -->
    <input type="hidden" name="dni" value="<?php echo htmlspecialchars($dni); ?>">
    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
    <input type="hidden" name="localidad" value="<?php echo htmlspecialchars($localidad); ?>">
    <input type="hidden" name="fechanacimiento" value="<?php echo htmlspecialchars($fechanacimiento); ?>">
    <input type="hidden" name="valor_inicial_limit" value="<?php echo htmlspecialchars($valor_inicial_limit); ?>" />
    <input type="hidden" name="pagina_actual" value="<?php echo htmlspecialchars($pagina_actual); ?>" />
    <input type="hidden" name="lineas_pagina" value="<?php echo htmlspecialchars($lineas_pagina); ?>" />
    <input class="botonflecha" type="submit" name="primera" value=" << " />
    <input class="botonflecha" type="submit" name="anterior" value=" < " />

    <!-- Selector de Página -->
    <label for="pagina_seleccionada">Ir a la página:</label>

    <input type="text" name="pagina_seleccionada" value="<?php echo $pagina_seleccionada; ?>" />
    <input class="boton" type="submit" value="ir a página">
    <input class="botonflecha" type="submit" name="siguiente" value=" > " />
    <input class="botonflecha" type="submit" name="ultima" value=" >> " />

    <label for="lineas_pagina">Registros por página:</label>
    <select name="lineas_pagina" onchange="this.form.submit()">
      <option value="5" <?php if ($lineas_pagina == '5') echo 'selected'; ?>>5</option>
      <option value="10" <?php if ($lineas_pagina == '10') echo 'selected'; ?>>10</option>
      <option value="15" <?php if ($lineas_pagina == '15') echo 'selected'; ?>>15</option>
      <option value="20" <?php if ($lineas_pagina == '20') echo 'selected'; ?>>20</option>
      <option value="50" <?php if ($lineas_pagina == '50') echo 'selected'; ?>>50</option>
    </select>


    <!-- Mostrar el Número Total de Registros Mostrar Número de Página Actual y Total de Páginas -->
    <?php

    echo "<p>Total de registros: $total_registros  -  Página $pagina_actual  de $total_paginas </p>";
    ?>
    <input type="hidden" name="valor_inicial_limit" value="<?php echo $valor_inicial_limit; ?>" />
  </form>

  </br>

  <!-- Lógica para eliminar y borrar -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const alumnoForm = document.getElementById("alumnoForm");
      const veralumnoFormButton = document.getElementById("showalumnoForm");

      // mostrar formulario añadir 
      veralumnoFormButton.addEventListener("click", function() {
        alumnoForm.style.display = "block";
      });
    });

    // función editar 
    function editarAlumno(dni) {

      window.location.href = '  form.php?accion=editar&dni=' + dni;
    }

    // función borrar
    function deleteStudent(studentID) {

      window.location.href = 'delete_student.php?studentID=' + studentID;
    }
  </script>
</body>

</html>