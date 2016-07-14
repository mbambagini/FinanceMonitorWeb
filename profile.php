<?php
  require_once("check_security.php");
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
  </head>
<body>

<?php
  $str_error = NULL;
  $str_warning = NULL;
  $str_notification = NULL;

  $t = new t_loader;
  $t->set_user($_SESSION['id']);

  //change password
  if (!empty($_POST['actual']) && !empty($_POST['new_one']) && !empty($_POST['repeated'])) {
    if (strcmp($_POST['new_one'], $_POST['repeated']) != 0)
      $str_error = "The passwords are different";
    else {
      if ($t->change_password($_POST['actual'], $_POST['new_one']))
        $str_notification = "Password changed";
      else
        $str_error = "Password not changed";
    }
  }
?>

<?php
  require_once("header_box.php");
?>

<br/>

<div id="interaction_box">
  <form action="profile.php" method="post">
    Actual password <input type="password" name="actual" />
    New password <input type="password" name="new_one" />
    Repeat password <input type="password" name="repeated" />
    <input type="submit" value="passwd" />
  </form>
</div>

</body>
</html>

