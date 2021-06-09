<?php
$host = "localhost";
$db_name = "nomina";
$username = "root";
$password = "";

// Create connection
$db = mysqli_connect($host, $username, $password, $db_name);

if (!$db) {
  die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION)) {
  session_start();
}
