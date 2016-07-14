<?php
  session_start();
  $_SESSION = array();
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
              $params["path"], $params["domain"],
              $params["secure"], $params["httponly"]);
  }
  session_destroy();
?>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <title>Login</title>
  </head>
  <body>
    <div  id="interaction_box" align="center">
      <form action="login.php" method="post">
        <input type="hidden" name="operation" value="login" />
        Username: <input type="text" name="username" /><br/>
        Password: <input type="password" name="password" /><br/>
        <input type="submit" name="login" value="login" />
        <input type="reset" value="clear" />
      </form>
    </div>
  </body>
</html>

