<?php
  function __autoload($class_name) {
    require_once '../classes/' . $class_name . '.php';
  }

  $t = new t_loader;

  $sub_category_list = $t->get_entries();

  foreach ($sub_category_list as $sub_category) {
    echo $sub_category['id']."-".$sub_category['entry']."-".$sub_category['category']."\n\r";
  }
?>
