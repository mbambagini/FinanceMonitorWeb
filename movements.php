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

  //movement delete
  if (isset($_POST['id'])) {
    if ($t->delete_movement($_POST['id']))
      $str_notification = "Movement deleted successfully";
    else
      $str_error = "Movement not deleted";
  }

  //add movement
  if (isset($_POST['amount']) && !empty($_POST['amount']) &&
      isset($_POST['type_currency']) &&
      isset($_POST['when']) &&
      isset($_POST['subcat'])) {
    if ($t->add_movement ($_POST['amount'], $_POST['type_currency'],
        $_POST['when'], $_POST['subcat'], $_SESSION['id']))
      $str_notification = "Movement added successfully";
    else
      $str_error = "Movement not added";
  }

  //display movements within a range
  $show_msg = true;
  if (!isset($_POST['month_1']) || !isset($_POST['year_1']) || 
      !isset($_POST['month_2']) || !isset($_POST['year_2'])) {
    $m1 = date("m");
    $m2 = $m1;
    $y1 = date("Y");
    $y2 = $y1;
    $show_msg = false;
  } else {
    $m1 = $_POST['month_1'];
    $m2 = $_POST['month_2'];
    $y1 = $_POST['year_1'];
    $y2 = $_POST['year_2'];
  }
  $data = $t->get_movements($m1, $y1, $m2, $y2);
  if ($show_msg) {
    switch($data) {
      case 0:
        $str_warning = "No movements to show";
        break;
      case -1:
        $str_error = "Impossible to load movements";
        break;
      case -2:
        $str_error = "Wrong dates";
        break;
    }
  }

  //load categories and sub categories
  $category_list = $t->get_categories();
  $sub_category_list = $t->get_entries();
?>

<?php
  require_once("header_box.php");
?>

<br/>

<div id="interaction_box">
  <form action="movements.php" method="post">
    New movement:
    <input type="number" name="amount" min="-25000" max="25000" step="0.01" value="9.99">
    <select name="type_currency">
      <option value="euro">Euro</option>
      <option value="pound">Pound</option>
      <option value="dollar">Dollar</option>
    </select>
    <select name="subcat">
<?php
    foreach ($sub_category_list as $sub_category) {
      echo "<option value=\"".$sub_category['id']."\">";
      echo $sub_category['category']." - ".$sub_category['entry'];
      echo "</option>";
    }
?>
    </select>
    <input type="date" name="when" value="<?php echo date("Y-m-d"); ?>" />
    <input type="submit" value="insert" />
  </form>

</div>

<br/>

<?php
function print_month_list ($m) {
  $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec");
  for ($i = 1; $i <= 12; $i++) {
    echo "<option value=\"".$i."\"";
    if ($i == $m)
      echo " selected";
    echo ">".$months[$i-1]."</option>";
  }
}
?>

<div id="interaction_box">
  <form action="movements.php" method="post">
    Transactions from 
    <select name="month_1">
<?php
  if (isset($_POST['month_1']))
    print_month_list($_POST['month_1']);
  else
    print_month_list(date("m"));
?>
    </select>
    <input type="number" name="year_1" min="2000" max="2020" value="<?php
    if (isset($_POST['year_1']))
       echo $_POST['year_1'];
    else
      echo date("Y"); 
?>" />
    to 
    <select name="month_2">
<?php
  if (isset($_POST['month_2']))
    print_month_list($_POST['month_2']);
  else
    print_month_list(date("m"));
?>
    </select>
    <input type="number" name="year_2" min="2000" max="2016" value="<?php
    if (isset($_POST['year_2']))
       echo $_POST['year_2'];
    else
      echo date("Y"); 
?>" />
    <input type="submit" value="show" />
  </form>
  <br/>
<?php
  $sum_euro = 0.0;
  $sum_pound = 0.0;
  $sum_dollar = 0.0;
  if (isset($data) && $data != 0 && $data != -1 && $data != -2) {
    foreach ($data as $elem) {
      if (strcmp($elem['currency'], "euro") == 0)
        $sum_euro += $elem['amount'];
      if (strcmp($elem['currency'], "pound") == 0)
        $sum_pound += $elem['amount'];
      if (strcmp($elem['currency'], "dollar") == 0)
        $sum_dollar += $elem['amount'];
    }
?>
  Overall money in the selected interval:
<?php
    echo money_format('%.2n', $sum_euro)."&#8364, "; 
    echo money_format('%.2n', $sum_pound)."&#163;, ";
    echo money_format('%.2n', $sum_dollar)."$";
  }
?>
  <br/>
<?php
  //show date if required
  if (isset($data) && $data != 0 && $data != -1 && $data != -2) {
    $s = new t_html_moves;
    echo $s->html_results($data);
  }
?>
</div>

</body>
</html>

