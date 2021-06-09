<?php

include './includes/connection.php';
include './includes/helpers.php';

if(isset($_POST['save-puesto'])) {
  $nombre = escape_string($db, $_POST['nombre']);
  $desc = escape_string($db, $_POST['desc']);
  $salariobase = escape_string($db, $_POST['base']);
  $salarioextra = escape_string($db, $_POST['extra']);
  
  $query = "
    INSERT INTO puesto (
      nombre, descripcion, salario_hora_base, salario_hora_extra
    ) VALUES (
      '$nombre', '$desc', '$salariobase', '$salarioextra'
    )
  ";

  $result = mysqli_query($db, $query);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción, ";
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Puesto guardado satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: puesto-index.php');
}