<?php

include './includes/connection.php';

if (isset($_GET['id'])) {

  $id = $_GET['id'];
  $query = "DELETE FROM trabajador WHERE idTrabajador = $id";

  $result = mysqli_query($db, $query);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción, ";
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Trabajador eliminado satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: trabajador-index.php');
}
