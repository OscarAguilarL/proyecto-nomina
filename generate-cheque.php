<?php
include './includes/connection.php';
include './includes/helpers.php';

if(isset($_POST['generate-cheque'])) {
  $idTrabajador = $_POST['trabajador'];
  $total_hrs = $_POST['horas'];
  $limite_horas = get_configuracion($db)['limite_hrs_normales'];
  if($total_hrs>$limite_horas){
      $hrs_normales = $limite_horas;
      $hrs_extra = $total_hrs-$limite_horas;
  } else {
      $hrs_normales = $total_hrs;
      $hrs_extra = 0;
  }
  $sueldo_base = calc_sueldoBase($db, $hrs_normales, $hrs_extra, $idTrabajador);
  $descuento_isr = calc_descuentoISR($db, $sueldo_base);
  $descuento_retiro = calc_descuentoRetiro($db, $sueldo_base);
  $descuento_vivienda = calc_descuentoVivienda($db, $sueldo_base);
  $descuento_seguro = calc_descuentoSeguro($db, $sueldo_base);
  /*var_dump($descuento_isr);
  var_dump($descuento_retiro);
  var_dump($descuento_vivienda);
  var_dump($descuento_seguro);*/
  $sueldo_neto = $sueldo_base - ($descuento_isr + $descuento_retiro + $descuento_vivienda + $descuento_seguro);
  
  $query = "
    INSERT INTO cheque
    VALUES (
        DEFAULT,$idTrabajador,
        $hrs_normales,$hrs_extra,$sueldo_base,
        $descuento_isr, $descuento_retiro,
        $descuento_vivienda, $descuento_seguro,
        $sueldo_neto
    )
  ";

  $result = mysqli_query($db, $query);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción, ";
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Cheque generado satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: cheque-index.php');
}