<?php
include './includes/header.php';

if (isset($_POST['cant_trabajadores'])) {
  $cantidad = $_POST['cant_trabajadores'];
}
if (isset($_POST['reporte-web'])) {
  header("Location: reporte-nomina-web.php?cant=$cantidad");
}
if (isset($_POST['reporte-pdf'])) {
  header("Location: ./reportes/nomina.php?cant=$cantidad");
}
?>

<div class="container-fluid">
  <div class="form-edit-container">
    <div class="form-container">
      <h2>Generar reporte de Nómina</h2>
      <form action="nomina-index.php" method="post" class="form">
        <label for="cant" class="form-label">
          <span>Trabajadores por página:</span>
          <input required type="number" value="2" min="1" max="10" name="cant_trabajadores" id="cant">
        </label>
        <button type="submit" class="btn is-primary" name="reporte-web">Generar Reporte Web</button>
        <button type="submit" class="btn is-primary" name="reporte-pdf">Generar Reporte PDF</button>
      </form>
    </div>
  </div>
</div>

<?php include './includes/footer.php' ?>