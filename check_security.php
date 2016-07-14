<?php
  session_start();

  if (!isset($_SESSION['id'])) {
    header("Location: http://marioapp.altervista.org/finance/index.php");
  }

  function __autoload($class_name) {
    require_once 'classes/' . $class_name . '.php';
  }
?>

