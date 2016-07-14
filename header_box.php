<?php
  //show notifications: errors, warnings and confirmations
  if (isset($str_error) && $str_error != NULL)
    echo "<div id=\"error_box\">$str_error</div><br/>";
  if (isset($str_warning) && $str_warning != NULL)
    echo "<div id=\"warning_box\">$str_warning</div><br/>";
  if (isset($str_notification) && $str_notification != NULL)
    echo "<div id=\"notification_box\">$str_notification</div><br/>";
?>

<div id="interaction_box">
  <h2>Welcome
<?php echo ucfirst($_SESSION['username']); ?>!</h2>

<p>
  [<a href="movements.php">movements</a>]
  [<a href="analysis.php">analysis</a>]
<?php
  if ($_SESSION['isadmin'])
    echo '[<a href="admin.php">admin</a>]';
?>
  [<a href="profile.php">profile</a>]
<?php
  $r = false;
  if (isset($t)) {
    $pending_number = $t->get_count_pending($_SESSION['username']);
    $r = $pending_number > 0;
  }

  if ($r)
    echo '[<a href="pending.php"><b>pending ('.$pending_number.')</b></a>]';
  else
    echo '[<a href="pending.php">pending</a>]';
?>
  [<a href="enhancements.php">enhancements</a>]
  [<a href="https://play.google.com/store/apps/details?id=org.local.financemonitor" target="blanck">app</a>]
  [<a href="index.php">logout</a>]<br/>
</p>

<p>
  Global wealth:
<?php
  echo money_format('%.2n', $t->get_total('euro'));
?>&#8364;,
<?php
  echo money_format('%.2n', $t->get_total('pound'));
?>&#163;,
<?php
  echo money_format('%.2n', $t->get_total('dollar'));
?>$
</p>

<p>
Total:
<?php
  $total_euro = $t->get_total('euro') + 
                $t->get_total('pound') * 1.2530 +
                $t->get_total('dollar') * 0.8785;
  echo money_format('%.2n', $total_euro);
?>&#8364;
</p>

</div>

