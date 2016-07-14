<?php
  function __autoload($class_name) {
    require_once './classes/' . $class_name . '.php';
  }

  $t = new t_loader;

  $id = -1;
  $isadmin = 0;
  $username = htmlspecialchars($_POST['username']);
  if ($t->login($username, htmlspecialchars($_POST['password']), $id, $isadmin)) {
    session_start();
    session_regenerate_id(true);
    $_SESSION = array();
    $_SESSION['id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['isadmin'] = $isadmin;
    $t->set_user($id);
    header("Location: movements.php");
  } else {
    header("Location: index.php");
  }
?>

