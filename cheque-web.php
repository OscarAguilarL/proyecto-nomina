<?php

// include '../includes/connection.php';
// include '../includes/helpers.php';
include './includes/header.php';


if (isset($_GET['id'])) :
  $cheque = get_vistaCheque($db, $_GET['id']);
  $retenciones = $cheque['descuento_isr'] + $cheque['descuento_retiro'] + $cheque['descuento_vivienda'] + $cheque['descuento_seguro'];
  $percepciones = $cheque['sueldo_base'];
  $pago_total = $percepciones - $retenciones;
  $str = get_cantidadLetra($pago_total);
  $firma = get_firmaDigital();
  $firma = get_firmaDigital();
?>

  <br>
  <a href="reportes/cheque.php?id=<?= $cheque['idCheque'] ?>" target="_blank" class="btn is-primary">Descargar en PDF</a>

  <div class="cheque-container">
    <center>
      <h2>Recibo de cheque</h2>
    </center>
    <p style="text-align: end;">Numero de cheque: <b><?= $cheque['idCheque'] ?></b></p>
    <h2>Datos del empleado</h2>
    <p>
      <b>Nombre:</b>
      <?= $cheque['Nombre'] ?>
    </p>
    <p>
      <b>Puesto:</b>
      <?= $cheque['Puesto'] ?>
    </p>
    <p>
      <b>RFC:</b>
      <?= $cheque['RFC'] ?>
    </p>
    <p>
      <b>CURP:</b>
      <?= $cheque['CURP'] ?>
    </p>
    <br>
    <hr>
    <h2>Percepciones</h2>
    <p>
      <b>Sueldo base:</b>
      <?= $cheque['sueldo_base'] ?>
    </p>
    <br>
    <hr>
    <h2>Retenciones</h2>
    <p>
      <b>I.S.R:</b>
      <?= $cheque['descuento_isr'] ?>
    </p>
    <p>
      <b>Ahorro para el retiro:</b>
      <?= $cheque['descuento_retiro'] ?>
    </p>
    <p>
      <b>Ahorro para la vivienda:</b>
      <?= $cheque['descuento_vivienda'] ?>
    </p>
    <p>
      <b>Ahorro seguro social:</b>
      <?= $cheque['descuento_seguro'] ?>
    </p>
    <br>
    <hr>
    <p>
      <b>Total de percepciones:</b>
      <?= $cheque['sueldo_base'] ?>
    </p>
    <p>
      <b>Total de retenciones:</b>
      <?= $retenciones ?>
    </p>
    <hr>
    <h3>
      Pago:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <?= $pago_total ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <?= $str ?>
    </h3>
    <br>
    <h3>
      Firma digital del emisor:
    </h3>
    <h3><?= $firma ?></h3>
  </div>

<?php
endif;
include './includes/footer.php';

?>