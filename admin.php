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

  //category added
  if (isset($_POST['new_category']) && !empty($_POST['new_category'])) {
    if ($t->add_category($_POST['new_category']))
      $str_notification = "Category added successfully";
    else
      $str_error = "Category not added";
  }

  //subcategory added
  if (isset($_POST['category']) && !empty($_POST['category']) &&
      isset($_POST['subcat']) && !empty($_POST['subcat'])) {
    if ($t->add_entry($_POST['subcat'], $_POST['category']) )
      $str_notification = "Sub category added successfully";
    else
      $str_error = "Sub category not added";
  }

  //activity completed
  if (isset($_POST['task_done']) && !empty($_POST['task_done'])) {
    if ($t->activity_done($_POST['task_done']))
      $str_notification = "Enhancement set as completed";
    else
      $str_error = "Enhancement not updated";
  }

  //activity rejected
  if (isset($_POST['task_reje']) && !empty($_POST['task_reje'])) {
    if ($t->activity_reject($_POST['task_reje']))
      $str_notification = "Enhancement set as rejected";
    else
      $str_error = "Enhancement not updated";
  }

  //load categories
  $category_list = $t->get_categories();
?>

<?php
  require_once("header_box.php");
?>

<br/>

<div id="interaction_box">
  <form action="admin.php" method="post">
    New category:
    <input type="text" name="new_category" maxlength="20" />
    <input type="submit" value="insert" />
  </form>
</div>

<br/>

<div id="interaction_box">
    List of users: 
<?php
    $res = $t->get_users();
    foreach ($res as $u)
      echo "$u ";
?>
</div>

<br />

<div id="interaction_box">
  <form action="admin.php" method="post">
    New sub-category: 
    <input type="text" name="subcat" maxlength="20" />
    <select name="category">
<?php
    foreach ($category_list as $cat)
      echo "<option value=\"".$cat['id']."\">".$cat['name']."</option>";
?>
    </select>
    <input type="submit" value="insert" />
  </form>
</div>

<br />

<div id="interaction_box">
  List of pending tasks

  <p>
<?php
  $data = $t->get_todo();

  $counter = 0;
  if (isset($data)) {
    foreach ($data as $elem) {
      $html .= "<tr>";
      $html .= "<td id=\"movements_cell\">(".$elem['loc'].") ".$elem['description']."</td>".
               "<td id=\"movements_cell\">".$elem['whenStored']."</td>".
               "<td id=\"movements_cell\">".$elem['username']."</td>".
               "<td id=\"movements_cell\">".
                   "<form \"admin.php\" method=\"post\">".
                     "<input type=\"hidden\" name=\"task_done\" value=\"".$elem['id']."\">".
                     "<input type=\"submit\" value=\"done\">".
                   "</form>".
                   "<form \"admin.php\" method=\"post\">".
                     "<input type=\"hidden\" name=\"task_reje\" value=\"".$elem['id']."\">".
                     "<input type=\"submit\" value=\"reject\">".
                   "</form>".
               "</td>";
      $html .= "</tr>\n";
      $counter++;
    }
  }

  if ($counter == 0)
    echo "No pending task to show";
  else {
    echo "<table id=\"movements_table\">\n";
    echo "<tr>";
    echo "<th id=\"movements_cell\">Description</th>".
         "<th id=\"movements_cell\">Registration date</th>".
         "<th id=\"movements_cell\">Username</th>".
         "<th id=\"movements_cell\">Action</th>";
    echo "</tr>";
    echo $html;
    echo "</table>\n";
  }
?>
  </p>
</div>

</body>
</html>


