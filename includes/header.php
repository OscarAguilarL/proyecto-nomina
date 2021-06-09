<?php require_once 'connection.php'; ?>
<?php include_once 'includes/helpers.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./static/css/styles.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <!-- JQUERY para confirmar la acción de eliminar -->
  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script language="JavaScript" type="text/javascript">
    $(document).ready(() => {
      $("a.delete").click(e => {
        if (!confirm('Are you sure?')) {
          e.preventDefault();
          return false;
        }
        return true;
      });
    });
  </script>
  <title>Gestion de nómina</title>
</head>

<body>

  <header class="header">
    <h1><a class="header-title" href="index.php">Gestión de nómina</a></h1>
    <nav class="navigation">
      <ul>
        <li><a class="link" href="trabajador-index.php">Trabajadores</a></li>
        <li><a class="link" href="cheque-index.php">Cheques</a></li>
        <li><a class="link" href="puesto-index.php">Puestos</a></li>
        <li><a class="link" href="nomina-index.php">Reporte Nomina</a></li>
        <li><a class="link" href="configuracion-index.php">Configuración</a></li>
      </ul>
    </nav>
  </header>

  <div class="wrapper">

    <?php if (isset($_SESSION['msg'])) : ?>
      <!-- Alert if is a message from session -->
      <div class="alert is-<?= $_SESSION['msg_type'] ?>" id="alert">
        <p><?= $_SESSION['msg'] ?></p>
        <span class="close">&times;</span>
      </div>
    <?php
      session_unset();
    endif;
    ?>