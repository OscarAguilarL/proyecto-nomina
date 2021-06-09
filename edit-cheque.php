<?php
include './includes/header.php';

if (isset($_POST['edit-cheque'])) {
  $idCheque = $_POST['idCheque'];
  $total_hrs = $_POST['horas'];
  $limite_horas = get_configuracion($db)['limite_hrs_normales'];
  if($total_hrs>$limite_horas){
      $hrs_normales = $limite_horas;
      $hrs_extra = $total_hrs-$limite_horas;
  } else {
      $hrs_normales = $total_hrs;
      $hrs_extra = 0;
  }
  $sueldo_base = calc_sueldoBase($db, $hrs_normales, $hrs_extra, $_POST['trabajador']);
  $descuento_isr = calc_descuentoISR($db, $sueldo_base);
  $descuento_retiro = calc_descuentoRetiro($db, $sueldo_base);
  $descuento_vivienda = calc_descuentoVivienda($db, $sueldo_base);
  $descuento_seguro = calc_descuentoSeguro($db, $sueldo_base);
  /*var_dump($descuento_isr);
  var_dump($descuento_retiro);
  var_dump($descuento_vivienda);
  var_dump($descuento_seguro);*/
  $sueldo_neto = $sueldo_base - ($descuento_isr + $descuento_retiro + $descuento_vivienda + $descuento_seguro);
  
  $query = "UPDATE cheque 
            SET hrs_normales_trabajadas = $hrs_normales,
                hrs_extra_trabajadas = $hrs_extra,
                sueldo_base = $sueldo_base,
                descuento_isr = $descuento_isr,
                descuento_retiro = $descuento_retiro,
                descuento_vivienda = $descuento_vivienda,
                descuento_seguro = $descuento_seguro,
                sueldo_neto = $sueldo_neto
            WHERE idCheque = $idCheque";
  $result = mysqli_query($db, $query);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción. ";
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Cheque actualizado satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: cheque-index.php');
}

if (isset($_GET['id'])) :
  $id = $_GET['id'];
  $cheque = get_cheque($db, $id);
?>

  <div class="form-edit-container">
    <div class="form-container">
      <h2>Editando horas de cheque</h2>
      <form action="edit-cheque.php" method="post" class="form">
        <label for="idCheque" style="display: none;">
          <input type="number" name="idCheque" id="idCheque" value="<?= $cheque['idCheque'] ?>">
        </label>
        <label for="nombre" class="form-label">
          <span>Nombre del trabajador:</span>
          <select required name="trabajador" id="trabajador">
            <option value="<?= $cheque['idTrabajador'] ?>"><?= $cheque['apellido_paterno']." ".$cheque['apellido_materno'].", ".$cheque['nombre'] ?></option>
          </select>
        </label>
        <label for="horas" class="form-label">
          <span>Horas trabajadas:</span>
          <input value="<?= $cheque['hrs_normales_trabajadas']+$cheque['hrs_extra_trabajadas'] ?>" 
                 type="number" min="1" max="80" name="horas" id="horas" required="">
        </label>
        <button type="submit" class="btn is-primary" name="edit-cheque">Actualizar</button>
      </form>
    </div>
  </div>

<?php
endif;
include './includes/footer.php'
?>