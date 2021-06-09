<?php

include './includes/connection.php';

if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = "DELETE FROM puesto WHERE idPuesto = $id";

  $result = mysqli_query($db, $query);

  if (!$result) {
    $_SESSION['msg'] = "Fallo al ejecutar la acción, ";
    $_SESSION['msg_type'] = "danger";
  } else {
    $_SESSION['msg'] = "Puesto eliminado satisfactoriamente.";
    $_SESSION['msg_type'] = "success";
  }

  header('Location: puesto-index.php');
}