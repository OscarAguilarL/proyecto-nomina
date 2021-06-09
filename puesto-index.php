<?php include './includes/header.php' ?>

<div class="container-fluid">
  <div class="form-container">
    <h2>Registrar nuevo puesto</h2>
    <form action="save-puesto.php" method="post" class="form">
      <label for="nombre" class="form-label">
        <span>Nombre:</span>
        <input type="text" name="nombre" id="nombre" placeholder="Frontend" required>
      </label>
      <label for="desc" class="form-label">
        <span>Descripcion:</span>
        <textarea name="desc" id="desc" cols="30" rows="10" required></textarea>
      </label>
      <label for="base" class="form-label">
        <span>Salario por hora normal $:</span>
        <input type="number" name="base" id="salario" min="1" placeholder="100" required>
        </label>
      <label for="extra" class="form-label">
        <span>Salario por hora extra $:</span>
        <input type="number" name="extra" id="salario" min="1" placeholder="200" required>
      </label>
      <button type="submit" class="btn is-primary" name="save-puesto">Guardar</button>
    </form>
  </div>
  <div class="table-container">
    <h2 class="center">Puestos disponibles</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Descripcion</th>
          <th>Salario X hora normal</th>
          <th>Salario X hora extra</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $puestos = get_puestos($db);
        while ($row = mysqli_fetch_array($puestos)) :
        ?>
          <tr>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['descripcion'] ?></td>
            <td><?= $row['salario_hora_base'] ?></td>
            <td><?= $row['salario_hora_extra'] ?></td>
            <td>
              <a href="edit-puesto.php?id=<?= $row['idPuesto'] ?>">Editar</a>
              <a href="delete-puesto.php?id=<?= $row['idPuesto'] ?>" onclick="confirm('Desea eliminar?')">Eliminar</a>
            </td>
          </tr>
        <?php endwhile ?>
      </tbody>
    </table>
  </div>
</div>

<?php include './includes/footer.php' ?>