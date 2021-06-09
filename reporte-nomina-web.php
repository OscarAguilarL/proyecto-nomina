<?php
include './includes/header.php';

if (isset($_GET['cant'])) :
  $fechaNomina = getdate()['mday'] . '-' . getdate()['mon'] . '-' . getdate()['year'];
  $noTrabByPage = $_GET['cant'];
  $cheques = get_cheques($db);

  $query = "SELECT COUNT(idCheque) AS n FROM cheque";
  $result = mysqli_query($db, $query);
  $cant_cheques = mysqli_fetch_array($result)['n'];

  $j = 1;
  $folio = getdate()['mday'] . getdate()['mon'] . getdate()['year'] . '001';

  $suma = array(
    's_base' => 0,
    'isr' => 0,
    'retiro' => 0,
    'vivienda' => 0,
    'seguro' => 0,
    's_neto' => 0,
  );
?>
  <div class="container-fluid">
    <div class="table-container">
      <div class="container-nominas">
        <h2 class="center">NOMINA</h2>
      </div>

      <?php while ($j <= $cant_cheques) : ?>
        <h3>Pago de Nomina para esta semana</h3>
        <h4>
          Fecha: <?= $fechaNomina ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          Folio: <?= $folio ?>
        </h4>
        <table border="1">
          <thead>
            <tr>
              <th>No. Registro</th>
              <th>No. Trabajador</th>
              <th>Nombre Trabajador</th>
              <th>Horas Trabajadas</th>
              <th>Sueldo Base</th>
              <th>Descuento ISR</th>
              <th>Cuenta Retiro</th>
              <th>Cuenta Vivienda</th>
              <th>Cuenta Seguro Social</th>
              <th>Sueldo Neto</th>
              <th>Numero de Cheque</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            while ($i <= $noTrabByPage && $j <= $cant_cheques) :
              $row = mysqli_fetch_array($cheques);
            ?>
              <tr>
                <td><?= $j ?></td>
                <td><?= $row['idTrabajador'] ?></td>
                <td><?= $row['apellido_paterno'] . " " . $row['apellido_materno'] . ", " . $row['nombre'] ?></td>
                <td><?= $row['hrs_normales_trabajadas'] + $row['hrs_extra_trabajadas'] ?></td>
                <td><?= $row['sueldo_base'] ?></td>
                <td><?= $row['descuento_isr'] ?></td>
                <td><?= $row['descuento_retiro'] ?></td>
                <td><?= $row['descuento_vivienda'] ?></td>
                <td><?= $row['descuento_seguro'] ?></td>
                <td><?= $row['sueldo_neto'] ?></td>
                <td><?= $row['idCheque'] ?></td>
              </tr>
            <?php
              $suma['s_base'] += $row['sueldo_base'];
              $suma['isr'] += $row['descuento_isr'];
              $suma['retiro'] += $row['descuento_retiro'];
              $suma['vivienda'] += $row['descuento_vivienda'];
              $suma['seguro'] += $row['descuento_seguro'];
              $suma['s_neto'] += $row['sueldo_neto'];
              $i++;
              $j++;
            endwhile;
            ?>
          </tbody>
        </table>
        <br>
        <?php if ($j > $cant_cheques) : ?>
          <center>
            <p><b>Cantidad de Cheques Expedidos en la Nomina: </b><?= $cant_cheques ?></p>
            <p><b>Suma Total de Sueldos Base: </b><?= $suma['s_base'] ?></p>
            <p><b>Suma Total de Descuentos por ISR: </b><?= $suma['isr'] ?></p>
            <p><b>Suma Total depositos a cuentas de Ahorro para el Retiro: </b><?= $suma['retiro'] ?></p>
            <p><b>Suma Total depositos a cuentas de Vivienda: </b><?= $suma['vivienda'] ?></p>
            <p><b>Suma Total depositos a cuentas de Seguro Social: </b><?= $suma['seguro'] ?></p>
            <p><b>Suma Total de Sueldos Netos: </b><?= $suma['s_neto'] ?></p>
          </center>
        <?php endif; ?>

        <h4>It is good to be with us. </h4>
        <h4>Una vez pagado no hay cambios</h4>
        <h5>Enjoy your salary</h5>
        <center>
          <p>- - - - - - - - - - - - - - - - - - - - corte de hoja - - - - - - - - - - - - - - - - - - - -</p>
        </center>
      <?php $folio++ - 1;
      endwhile; ?>
    </div>
  </div>

<?php
endif;
include './includes/footer.php' ?>