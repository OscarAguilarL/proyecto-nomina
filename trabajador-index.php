<?php include './includes/header.php' ?>

<div class="container-fluid">
  <div class="form-container">
    <h2>Registrar nuevo trabajador</h2>
    <form action="save-trabajador.php" method="post" class="form">
      <label for="nombre" class="form-label">
        <span>Nombre:</span>
        <input required type="text" name="nombre" id="nombre" placeholder="Juan">
      </label>
      <label for="apellido_paterno" class="form-label">
        <span>Apellido Paterno:</span>
        <input required type="text" name="apellido_paterno" id="apellido_paterno" placeholder="Alcachofa">
      </label>
      <label for="apellido_materno" class="form-label">
        <span>Apellido Materno:</span>
        <input required type="text" name="apellido_materno" id="apellido_materno" placeholder="Bananas">
      </label>
      <label for="RFC" class="form-label">
        <span>RFC:</span>
        <input required type="text" name="RFC" id="RFC" minlength="12" maxlength="12" placeholder="AABJ020513PD9">
      </label>
      <label for="CURP" class="form-label">
        <span>CURP:</span>
        <input required type="text" name="CURP" id="CURP" minlength="18" maxlength="18" placeholder="JIMM770826HHGMNR52">
      </label>
      <label for="domicilio">
        <span>Domicilio:</span>
        <input required type="text" name="domicilio" id="domicilio" placeholder="Pachuca, Hgo">
      </label>
      <label for="celular">
        <span>Celular:</span>
        <input required type="tel" name="celular" id="celular" minlength="10" placeholder="772123456">
      </label>
      <label for="idPuesto">
        <span>Puesto:</span>
        <select name="idPuesto" id="idPuesto" required>
          <?php
          $puestos = get_puestos($db);
          while ($row = mysqli_fetch_array($puestos)) :
          ?>
            <option value="<?= $row['idPuesto'] ?>"><?= $row['nombre'] ?></option>
          <?php endwhile; ?>
        </select>
      </label>
      <button type="submit" class="btn is-primary" name="save_trabajador">Guardar</button>
    </form>
  </div>
  <div class="table-container">
    <h2 class="center">Trabajadores</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Domicilio</th>
          <th>Celular</th>
          <th>RFC</th>
          <th>CURP</th>
          <th>Puesto</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $trabajador_data = get_trabajadores($db);
        while ($row = mysqli_fetch_array($trabajador_data)) :
          $name = $row['apellido_paterno'] . ' ' . $row['apellido_materno'] . ', ' . $row['nombre'];
          $puesto = get_puesto($db, $row['Puesto_idPuesto']);
        ?>
          <tr>
            <td class="table-ellipsis"><?= $name ?></td>
            <td><?= $row['domicilio'] ?></td>
            <td><?= $row['celular'] ?></td>
            <td><?= $row['RFC'] ?></td>
            <td class="table-ellipsis"><?= $row['CURP'] ?></td>
            <td><?= $puesto['nombre'] ?></td>
            <td>
              <a href="edit-trabajador.php?id=<?= $row['idTrabajador'] ?>">Editar</a>
              <a href="delete-trabajador.php?id=<?= $row['idTrabajador'] ?>" class="delete">Eliminar</a>
            </td>
          </tr>
        <?php endwhile ?>
      </tbody>
    </table>
  </div>
</div>

<?php include './includes/footer.php' ?>