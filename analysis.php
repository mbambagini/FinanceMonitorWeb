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
  if (!isset($_POST['view']))
    $w = 1;
  else
    $w = $_POST['view'];

  //load categories and sub categories
  $category_list = $t->get_categories();
  $sub_category_list = $t->get_entries();
?>

<?php
  require_once("header_box.php");
?>

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
  <form action="analysis.php" method="post">
    Transactions from 
    <input type="hidden" name="operation" value="show" />
    <select name="month_1">
<?php
  print_month_list($m1);
?>
    </select>
    <input type="number" name="year_1" min="2000" max="2016" value="<?php
      echo $y1;
?>" />
    to 
    <select name="month_2">
<?php
  print_month_list($m2);
?>
    </select>
    <input type="number" name="year_2" min="2000" max="2016" value="<?php
      echo $y2;
?>" />
    <select name="view">
      <option value="1" <?php 
    if ($w  == 1)
       echo "selected";
?>>In/Out</option>
      <option value="2" <?php 
    if ($w == 2)
       echo "selected";
?>>Trend</option>
      <option value="3" <?php 
    if ($w == 3)
       echo "selected";
?>>Outcoming</option>
    </select>
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
    switch ($w) {
      case 1:
        $s = new t_html_in_out;
        echo $s->html_results($data);
        break;
      case 2:
        $s = new t_html_graph;
        $s->set_initial_budget($t->get_total_until("euro", $m1, $y1),
                               $t->get_total_until("pound", $m1, $y1),
                               $t->get_total_until("dollar", $m1, $y1));
        $s->set_range($m1, $y1, $m2, $y2);
        echo $s->html_results($data);
        break;
      case 3:
        $s = new t_html_pie;
        echo $s->html_results($data);
        break;
    }
  }
?>
</div>

</body>
</html>

