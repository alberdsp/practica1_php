<html>

<head>
  <meta charset="UTF-8">
  <title>Práctica 1 php</title>
  <link rel="stylesheet" href="css/style.css">
  <meta charset="UTF-8">
</head>

<body>

  <?php
  /**
   * ABF 2023 declaramos las variables
   */

  $columnas = 2;
  $filas = 2;


  // capturamos del array post y establecemos mímino 2x2

  if (isset($_POST['filas'])) {
    $filas = $_POST['filas'];
    if ($filas < 2) {
      $filas = 2;
    }
  }

  if (isset($_POST['columnas'])) {
    $columnas = $_POST['columnas'];
    if ($columnas < 2) {
      $columnas = 2;
    }
  }

  ?>


  <form class="form" action="index.php" method="post">

    <label for="Filas">Filas:</label>
    <input type="number" id="filas" name="filas" value="<?php echo $filas; ?>">
    <label for="Columnas">Columnas:</label>
    <input type="number" id="columnas" name="columnas" value="<?php echo $columnas; ?>">

    <input type="submit" value="  Enviar  ">
  </form>


  <table class="table">

    <thead>

      <tr>
        <?php
        /**
         * creamos bucle para crear la cabecera
         */
        for ($i = 0; $i < $columnas; $i++) {
          echo "<th> Campo " . ($i + 1) . "</th>";
        }

        ?>
      </tr>


    </thead>

    <tbody>

      <?php
      /**
       * creamos bucle para crear columnas y las filas
       */
      for ($i = 0; $i < $filas; $i++) {
        // creamos las  filas  
        echo "<tr>";
        // creamos las  columnas por cada fila
        for ($e = 0; $e < $columnas; $e++) {

          echo '<td class="celda"> Fila ' . ($i + 1) . " Columna " . ($e + 1) . " </td>";
        }


        echo "</tr>";
      }

      ?>

    </tbody>


  </table>



</body>

</html>