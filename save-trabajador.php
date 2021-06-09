<?php

include './includes/connection.php';
include './includes/helpers.php';

if (isset($_POST['save_trabajador'])) {
  $nombre = escape_string($db, $_POST['nombre']);
  $apellido_paterno = escape_string($db, $_POST['apellido_paterno']);
  $apellido_materno = escape_string($db, $_POST['apellido_materno']);
  $rfc = strtoupper(escape_string($db, $_POST['RFC']));
  $curp = strtoupper(escape_string($db, $_POST['CURP']));
  $domicilio = escape_string($db, $_POST['domicilio']);
  $celular = escape_string($db, $_POST['celular']);
  $idPuesto = escape_string($db, $_POST['idPuesto']);

  $query = "
    INSERT INTO trabajador
      (nombre, apellido_paterno, apellido_materno,
      RFC, CURP, domicilio,
      celular, Puesto_idPuesto)
    VALUES (
      '$nombre', '$apellido_paterno','$apellido_materno',
      '$rfc', '$curp', '$domicilio',
      '$celular', $idPuesto);
  ";

  $result = mysqli_query($db, $query);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción, ";
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Trabajador guardado satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: trabajador-index.php');
}
