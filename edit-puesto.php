<?php

include './includes/header.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $query = "SELECT * FROM puesto WHERE idPuesto = $id";

  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);
    $id = $row['idPuesto'];
    $nombre = $row['nombre'];
    $desc = $row['descripcion'];
    $sueldobase = $row['salario_hora_base'];
    $sueldoextra = $row['salario_hora_extra'];
  }
}

if (isset($_POST['edit-puesto'])) {
  $id = $_GET['id'];
  $nombre = $_POST['nombre'];
  $desc = $_POST['desc'];
  $sueldobase = $_POST['base'];
  $sueldoextra = $_POST['extra'];

  $query = "UPDATE puesto
            SET nombre = '$nombre',
                descripcion = '$desc',
                salario_hora_base = $sueldobase,
                salario_hora_extra = $sueldoextra
            WHERE idPuesto = $id";

  $result = mysqli_query($db, $query);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción, ";
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Puesto actualizado satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: puesto-index.php');
}

?>

<div class="form-edit-container">
  <div class="form-container">
    <h2>Editando puesto</h2>
    <form action="edit-puesto.php?id=<?= $id ?>" method="post" class="form">
      <label for="nombre" class="form-label">
        <span>Nombre:</span>
        <input type="text" required name="nombre" id="nombre" placeholder="Frontend" value="<?= $nombre ?>">
      </label>
      <label for="desc" class="form-label">
        <span>Descripcion:</span>
        <textarea name="desc" required id="desc" cols="30" rows="10"><?= $desc ?></textarea>
      </label>
      <label for="base" class="form-label">
        <span>Salario por hora base $:</span>
        <input type="number" required name="base" id="base" min="1" value="<?= $sueldobase ?>">
      </label>
      <label for="extra" class="form-label">
        <span>Salario por hora extra $:</span>
        <input type="number" required name="extra" id="extra" min="1" value="<?= $sueldoextra ?>">
      </label>
      <button type="submit" class="btn is-primary" name="edit-puesto">Guardar</button>
    </form>
  </div>
</div>

<?php include './includes/footer.php' ?>