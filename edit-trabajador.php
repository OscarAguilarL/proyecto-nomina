<?php

include './includes/header.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $trabajador = get_trabajador($db, $id);
}

if (isset($_POST['edit_trabajador'])) {
  $idTrabajador = $_GET['id'];
  $nombre = escape_string($db, $_POST['nombre']);
  $apellido_paterno = escape_string($db, $_POST['apellido_paterno']);
  $apellido_materno = escape_string($db, $_POST['apellido_materno']);
  $rfc = escape_string($db, $_POST['RFC']);
  $curp = escape_string($db, $_POST['CURP']);
  $domicilio = escape_string($db, $_POST['domicilio']);
  $celular = escape_string($db, $_POST['celular']);
  $idPuesto = escape_string($db, $_POST['idPuesto']);

  $query = "
    UPDATE trabajador SET
      nombre = '$nombre',
      apellido_paterno = '$apellido_paterno',
      apellido_materno = '$apellido_materno',
      RFC = '$rfc',
      CURP = '$curp',
      domicilio = '$domicilio',
      celular = '$celular',
      Puesto_idPuesto = '$idPuesto'
    WHERE idTrabajador = $idTrabajador
  ";

  $result = mysqli_query($db, $query);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción, ";
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Trabajador actualizado satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: trabajador-index.php');
}
?>

<div class="form-edit-container">
  <div class="form-container">
    <h2>Editanto trabajador</h2>
    <form action="edit-trabajador.php?id=<?= $id ?>" method="post" class="form">
      <label for="nombre" class="form-label">
        <span>Nombre:</span>
        <input required type="text" name="nombre" id="nombre" value="<?= $trabajador['nombre'] ?>">
      </label>
      <label for="apellido_paterno" class="form-label">
        <span>Apellido Paterno:</span>
        <input required type="text" name="apellido_paterno" id="apellido_paterno" value="<?= $trabajador['apellido_paterno'] ?>">
      </label>
      <label for="apellido_materno" class="form-label">
        <span>Apellido Materno:</span>
        <input required type="text" name="apellido_materno" id="apellido_materno" value="<?= $trabajador['apellido_materno'] ?>">
      </label>
      <label for="RFC" class="form-label">
        <span>RFC:</span>
        <input required type="text" name="RFC" minlength="12" maxlength="12" id="RFC" value="<?= $trabajador['RFC'] ?>">
      </label>
      <label for="CURP" class="form-label">
        <span>CURP:</span>
        <input required type="text" name="CURP" minlength="18" maxlength="18" id="CURP" value="<?= $trabajador['CURP'] ?>">
      </label>
      <label for="domicilio">
        <span>Domicilio:</span>
        <input required type="text" name="domicilio" id="domicilio" value="<?= $trabajador['domicilio'] ?>">
      </label>
      <label for="celular">
        <span>Celular:</span>
        <input required type="tel" name="celular" minlength="10" id="celular" maxlength="10" value="<?= $trabajador['celular'] ?>">
      </label>
      <label for="idPuesto">
        <span>Puesto:</span>
        <select name="idPuesto" id="idPuesto" required>
          <?php
          $puestos = get_puestos($db);
          while ($row = mysqli_fetch_array($puestos)) :
          ?>
            <option <?= $trabajador['Puesto_idPuesto'] === $row['idPuesto'] ? 'selected' : ''  ?> value="<?= $row['idPuesto'] ?>"><?= $row['nombre'] ?></option>
          <?php endwhile; ?>
        </select>
      </label>
      <button type="submit" class="btn is-primary" name="edit_trabajador">Guardar</button>
    </form>
  </div>
</div>

<?php include './includes/footer.php' ?>