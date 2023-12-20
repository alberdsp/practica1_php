<!DOCTYPE html>
<html>

<?php


// ABF 2023 


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



if (isset($_POST['dni'])) {
  $dni = $_POST['dni'];
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

if (isset($_POST['lineas_pagina'])) {
  $lineas_pagina = $_POST['lineas_pagina'];
}
//camputamos valor_inicial_limit si no viene, seria 0
if (isset($_POST['valor_inicial_limit'])) {
  $valor_inicial_limit = $_POST['valor_inicial_limit'];
} else {
  $valor_inicial_limit = 0;
}

// Conexión a la bd
$servername = "localhost:3307";
$username = "root";
$password = "sauber";
$dbname = "universidad";

?>

<head>
  <meta charset="UTF-8">
  <title>Práctica 1.5 php</title>
  <link rel="stylesheet" href="css/style.css">
  <script>
    function clearForm() {
      document.getElementById("filtroForm").reset();
    }
  </script>
</head>

<body>

  <h1 class="descrip">Práctica 1.5 DI</h1>
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

    <input type="submit" value="Buscar">
    <input type="button" value="Limpiar" onclick="clearForm()">

    <input type="hidden" name="lineas_pagina" value="<?php echo htmlspecialchars($lineas_pagina); ?>" />
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



        // Calcular el número total
        $stmt = $con->prepare($sqlcount);
        $stmt->execute($params);
        $total_registros = $stmt->fetchColumn();

        $total_paginas = ceil($total_registros / $lineas_pagina);

        // Calcular el número de páginas 
        $total_paginas = ceil($total_registros / $lineas_pagina);

        if (isset($_POST['primera'])) {
          $valor_inicial_limit = 0;
          $pagina_actual = 1;
        } elseif (isset($_POST['anterior'])) {
          $valor_inicial_limit = max(0, $valor_inicial_limit - $lineas_pagina);
          $pagina_actual--;
          $pagina_seleccionada = $pagina_actual;
        } elseif (isset($_POST['siguiente'])) {
          $pagina_actual++;
          if ($pagina_actual != $total_paginas) {
            $valor_inicial_limit = min($total_registros - $lineas_pagina, $valor_inicial_limit + $lineas_pagina);
          } else {
            $valor_inicial_limit =  $lineas_pagina * ($total_paginas - 1);
            $lineas_pagina = $total_registros - $valor_inicial_limit;
          }
          $pagina_seleccionada = $pagina_actual;
          echo "valor del limite incial " . $valor_inicial_limit;
          echo "valor del limite final " . $lineas_pagina;
        } elseif (isset($_POST['ultima'])) {
          $valor_inicial_limit =  $lineas_pagina * ($total_paginas - 1);
          $lineas_pagina = $total_registros - $valor_inicial_limit;
          echo "valor del limite incial " . $valor_inicial_limit;
          echo "valor del limite final " . $lineas_pagina;
          echo "valor numero de páginas " . $total_paginas;
          $pagina_actual = $total_paginas;
          $pagina_seleccionada = $pagina_actual;
          // TODO  ARREGLAR EL SELECTOR DE PÁGINA

        } elseif (isset($_POST['pagina_seleccionada'])) {
          $pagina_actual = $_POST['pagina_seleccionada'];
          $pagina_seleccionada = $_POST['pagina_seleccionada'];
          $valor_inicial_limit =  $lineas_pagina * $pagina_actual;
          $valor_final_limit = $lineas_pagina;
        }



        $sqlfiltro .= " order by nombre ASC LIMIT $valor_inicial_limit , $lineas_pagina";

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
          echo "<tr><td colspan='8'>No se han encontrado registros.</td></tr>";
        }
      } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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



    <input type="submit" name="primera" value=" << " />
    <input type="submit" name="anterior" value=" < " />

    <!-- Selector de Página -->
    <label for="pagina_seleccionada">Ir a la página:</label>

    <input type="text" name="pagina_seleccionada" value="<?php echo $pagina_seleccionada; ?>" />
    <input type="submit" value="ir a página">





    <input type="submit" name="siguiente" value=" > " />
    <input type="submit" name="ultima" value=" >> " />

    <label for="lineas_pagina">Registros por página:</label>
    <select name="lineas_pagina" onchange="this.form.submit()">
      <option value="5" <?php if ($lineas_pagina == '5') echo 'selected'; ?>>5</option>
      <option value="10" <?php if ($lineas_pagina == '10') echo 'selected'; ?>>10</option>
      <option value="15" <?php if ($lineas_pagina == '15') echo 'selected'; ?>>15</option>
      <option value="20" <?php if ($lineas_pagina == '20') echo 'selected'; ?>>20</option>
      <option value="50" <?php if ($lineas_pagina == '50') echo 'selected'; ?>>50</option>
    </select>


    <!-- Mostrar el Número Total de Registros -->
    <?php

    echo "<p>Total de registros: $total_registros</p>";
    ?>

    <!-- Mostrar Número de Página Actual y Total de Páginas -->
    <p>Página <?php echo $pagina_actual ?> de <?php echo $total_paginas ?></p>

    <input type="hidden" name="valor_inicial_limit" value="<?php echo $valor_inicial_limit; ?>" />
  </form>

  </br>

</body>

</html>