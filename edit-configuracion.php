<?php
include './includes/header.php';

if (isset($_POST['edit-config'])) {
  $limHr = $_POST['limite_horas'];
  $limIsr = $_POST['limite_isr'];
  $isrmin = $_POST['isrmin'];
  $isrmax = $_POST['isrmax'];
  $retiro = $_POST['retiro'];
  $vivienda = $_POST['vivienda'];
  $seguro = $_POST['seguro'];

  $query = "
    UPDATE configuracion SET
      limite_hrs_normales = '$limHr',
      limite_isr = '$limIsr',
      isr_min = '$isrmin',
      isr_max = '$isrmax',
      ahorro_retiro = '$retiro',
      vivienda = '$vivienda',
      seguro_social = '$seguro'
    WHERE idConfiguracion = 1
  ";

  $result = mysqli_query($db, $query);

  echo mysqli_error($db);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción" . mysqli_error($db);
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Configuración actualizada satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: configuracion-index.php');
}
?>

<div class="form-edit-container">
  <div class="form-container">
    <?php $row = get_configuracion($db); ?>
    <h2>Editando configuración de nómina</h2>
    <form action="edit-configuracion.php" method="post" class="form">
      <label for="horas_lim" class="form-label">
        <span>Lìmite de Horas normales:</span>
        <input required type="number" id="horas_lim" min="1" max="80" name="limite_horas" value="<?= $row['limite_hrs_normales'] ?>">
      </label>
      <label for="ISR_limite" class="form-label">
        <span>Valor Límite ISR:</span>
        <input required type="number" id="ISR_limite" min="1" name="limite_isr" value="<?= $row['limite_isr'] ?>">
      </label>
      <label for="ISR_menor">
        <span>ISR menor al límite:</span>
        <select name="isrmin" id="ISR_menor" required>
          <?php
          $percentage = 5;
          while ($percentage <= 35) :
          ?>
            <option <?= $row['isr_min'] == $percentage ? 'selected' : ''  ?> value="<?= $percentage ?>"><?= $percentage ?>%</option>
          <?php
            $percentage += 5;
          endwhile;
          ?>
        </select>
      </label>
      <label for="ISR_mayor">
        <span>ISR mayor al límite:</span>
        <select name="isrmax" id="ISR_mayor" required>
          <?php
          $percentage = 5;
          while ($percentage <= 35) :
          ?>
            <option <?= $row['isr_max'] == $percentage ? 'selected' : ''  ?> value="<?= $percentage ?>"><?= $percentage ?>%</option>
          <?php
            $percentage += 5;
          endwhile;
          ?>
        </select>
      </label>
      <label for="p-retiro">
        <span>Ahorro para el Retiro:</span>
        <select name="retiro" id="p-retiro" required>
          <?php
          $percentage = 5;
          while ($percentage <= 20) :
          ?>
            <option <?= $row['ahorro_retiro'] == $percentage ? 'selected' : ''  ?> value="<?= $percentage ?>"><?= $percentage ?>%</option>
          <?php
            $percentage += 5;
          endwhile;
          ?>
        </select>
      </label>
      <label for="p-vivienda">
        <span>Ahorro para la Vivienda:</span>
        <select name="vivienda" id="p-vivienda" required>
          <?php
          $percentage = 5;
          while ($percentage <= 20) :
          ?>
            <option <?= $row['vivienda'] == $percentage ? 'selected' : ''  ?> value="<?= $percentage ?>"><?= $percentage ?>%</option>
          <?php
            $percentage += 5;
          endwhile;
          ?>
        </select>
      </label>
      <label for="p-seguro">
        <span>Retención Seguro Social:</span>
        <select name="seguro" id="p-seguro" required>
          <?php
          $percentage = 5;
          while ($percentage <= 20) :
          ?>
            <option <?= $row['seguro_social'] == $percentage ? 'selected' : ''  ?> value="<?= $percentage ?>"><?= $percentage ?>%</option>
          <?php
            $percentage += 5;
          endwhile;
          ?>
        </select>
      </label>

      <button type="submit" class="btn is-primary" name="edit-config">Actualizar</button>
    </form>
  </div>
</div>

<?php
include './includes/footer.php'
?>