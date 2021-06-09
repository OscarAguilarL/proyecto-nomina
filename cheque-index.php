<?php
include './includes/header.php';

$trabajadores = get_trabajadores($db);
$cheques = get_cheques($db);
?>

<div class="container-fluid">
  <div class="form-container">
    <h2>Generar nuevo Cheque</h2>
    <form action="generate-cheque.php" method="post" class="form">
      <label for="nombre" class="form-label">
        <span>Nombre del trabajador:</span>
        <select required name="trabajador" id="trabajador">
          <?php
          while ($row = mysqli_fetch_array($trabajadores)) :
            $name = $row['apellido_paterno'] . ' ' . $row['apellido_materno'] . ', ' . $row['nombre'];
          ?>
            <option value="<?= $row['idTrabajador'] ?>"><?= $name ?></option>
          <?php endwhile ?>
        </select>
      </label>
      <label for="horas" class="form-label">
        <span>Horas trabajadas</span>
        <input required type="number" value="0" min="1" max="80" name="horas" id="horas">
      </label>
      <button type="submit" class="btn is-primary" name="generate-cheque">Generar</button>
    </form>
  </div>
  <div class="table-container">
    <div class="container-nominas">
      <h2 class="center">Cheques</h2>
      <a href="./cheques-web.php" target="blank" class="btn is-primary">Descargar todos los cheques</a>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th class="idNomina">No. de Cheque</th>
          <th>Trabajador</th>
          <th>Horas trabajadas</th>
          <th>Sueldo Base</th>
          <th>Descuento I.S.R</th>
          <th>Descuento retiro</th>
          <th class="idNomina">Descuento vivienda</th>
          <th>Descuento seguro</th>
          <th>Sueldo Neto</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_array($cheques)) : ?>
          <tr>
            <td><?= $row['idCheque'] ?></td>
            <td class="table-ellipsis"><?= $row['apellido_paterno'] . " " . $row['apellido_materno'] . ", " . $row['nombre'] ?></td>
            <td><?= $row['hrs_normales_trabajadas'] + $row['hrs_extra_trabajadas'] ?></td>
            <td><?= $row['sueldo_base'] ?></td>
            <td><?= $row['descuento_isr'] ?></td>
            <td><?= $row['descuento_retiro'] ?></td>
            <td><?= $row['descuento_vivienda'] ?></td>
            <td><?= $row['descuento_seguro'] ?></td>
            <td><?= $row['sueldo_neto'] ?></td>
            <td>
              <a href="edit-cheque.php?id=<?= $row['idCheque'] ?>">Editar</a>
              <a href="./cheque-web.php?id=<?= $row['idCheque'] ?>" target="blank">Descargar</a>
            </td>
          </tr>
        <?php endwhile ?>
      </tbody>
    </table>
  </div>
</div>

<?php include './includes/footer.php' ?>