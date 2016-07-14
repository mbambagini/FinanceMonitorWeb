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

  //delete
  if (isset($_POST['id_delete'])) {
    if ($t->delete_pending($_POST['id_delete']))
      $str_notification = "Pending movement deleted successfully";
    else
      $str_error = "Pending movement not deleted";
  }

  //save
  if (isset($_POST['id_save'])) {
    if ($t->save_pending($_POST['id_save']))
      $str_notification = "Pending movement saved successfully";
    else
      $str_error = "Pending movement not saved";
  }

  //display pending movements
  $data = $t->get_pending($_SESSION['username']);

  //load categories and sub categories
  $category_list = $t->get_categories();
  $sub_category_list = $t->get_entries();
?>

<?php
  require_once("header_box.php");
?>

<br/>

<div id="interaction_box">
<?php
  //show date if required
  if (isset($data)) {
    $s = new t_html_pending;
    echo $s->html_results($data);
  }
?>
</div>

</body>
</html>

