<?php
  function __autoload($class_name) {
    require_once '../classes/' . $class_name . '.php';
  }

  $t = new t_loader;

  $valid = false;
  if (isset($_GET['amount']) && isset($_GET['currency']) &&
      isset($_GET['user']) && isset($_GET['id_entry']) &&
      isset($_GET['when'])) {
    if ($t->add_pending(htmlspecialchars($_GET['amount']),
                        htmlspecialchars($_GET['currency']),
                        htmlspecialchars($_GET['user']),
                        htmlspecialchars($_GET['id_entry']),
                        htmlspecialchars($_GET['when'])))
      $valid = true;
  }
  
  if ($valid)
    echo "1";
  else
    echo "0";
?>