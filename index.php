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

  $columnas = 5;
  $filas = 7;
  ?>


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