<?php
include './includes/header.php';

$row = get_configuracion($db);

?>

<div class="container-fluid">
  <div class="table-container">
    <div class="container-nominas">
      <h2 class="center">Configuración de nómina</h2>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Ajuste</th>
          <th>Valor</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Límite de Horas Normales</td>
          <td><?= $row['limite_hrs_normales'] ?></td>
        </tr>
        <tr>
          <td>Valor Límite ISR</td>
          <td><?= $row['limite_isr'] ?></td>
        </tr>
        <tr>
          <td>Porcentaje ISR menor al límite</td>
          <td><?= $row['isr_min'] ?>%</td>
        </tr>
        <tr>
          <td>Porcentaje ISR mayor al límite</td>
          <td><?= $row['isr_max'] ?>%</td>
        </tr>
        <tr>
          <td>Porcentaje Ahorro para retiro</td>
          <td><?= $row['ahorro_retiro'] ?>%</td>
        </tr>
        <tr>
          <td>Porcentaje Ahorro para Vivienda</td>
          <td><?= $row['vivienda'] ?>%</td>
        </tr>
        <tr>
          <td>Porcentaje Seguro Social</td>
          <td><?= $row['seguro_social'] ?>%</td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center">
              <a href="edit-configuracion.php" class="btn is-primary">Editar valores</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<?php include './includes/footer.php' ?>