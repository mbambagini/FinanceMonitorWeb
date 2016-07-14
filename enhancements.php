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

  //new todo
  if (isset($_POST['new_todo_description']) && isset($_POST['location']) && !empty($_POST['new_todo_description'])) {
    if ($t->add_todo(htmlspecialchars($_POST['new_todo_description']), $_POST['location']))
      $str_notification = "New task added successfully, thanks!";
    else
      $str_error = "New task not saved";
  }
?>

<?php
  require_once("header_box.php");
?>

<br/>

<div id="interaction_box">
  Require a new feature or notify a bug (it cannot be removed):
  <p>
    <form action="enhancements.php" method="post">
      <select name="location">
        <option value="app">app</option>
        <option value="web">web</option>
        <option value="common">common</option>
      </select>
      <input type="text" name="new_todo_description" maxlength="250" size="100" />
      <input type="submit" value="insert" />
    </form>
  </p>
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
               "<td id=\"movements_cell\">".$elem['username']."</td>";
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
         "<th id=\"movements_cell\">Username</th>";
    echo "</tr>";
    echo $html;
    echo "</table>\n";
  }
?>
  </p>
</div>

<br />

<div id="interaction_box">
  List of completed/rejected tasks (no more than 40)

  <p>
<?php
  $data = $t->get_done();

  $counter = 0;
  $html = "";
  if (isset($data)) {
    foreach ($data as $elem) {
      $row  = "<tr>";
      $row .= "<td id=\"movements_cell\">";
      if ($elem['state'] == 'reje')
         $row .= "<strike>";
      $row .= '('.$elem['loc'].') '.$elem['description'];
      if ($elem['state'] == 'reje')
         $row .= "</strike>";
      $row .= "</td>";
      $row .= "<td id=\"movements_cell\">".$elem['whenStored']."</td>".
              "<td id=\"movements_cell\">".$elem['username']."</td>";
      $row .= "</tr>\n";
      $html .= $row;
      $counter++;
    }
  }

  if ($counter == 0)
    echo "No stored activity";
  else {
    echo "<table id=\"movements_table\">\n";
    echo "<tr>";
    echo "<th id=\"movements_cell\">Description</th>".
         "<th id=\"movements_cell\">Completion date</th>".
         "<th id=\"movements_cell\">Submitter</th>";
    echo "</tr>";
    echo $html;
    echo "</table>\n";
  }
?>
  </p>
</div>

</body>
</html>

