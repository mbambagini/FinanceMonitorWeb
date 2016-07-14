<?php

/* \brief this class is the connection between the HTML interface and the PHP
 *        engine/DAO
 */
class t_loader {

  private $id_user = 0;
  private $db = 0;

  function __construct() {
    $this->db = new t_db();
    $this->db->connect();
  }

  public function __destruct(){
    $this->db->disconnect();
  }

  public function set_user ($id) {
    $this->id_user = $id;
  }

  public function add_entry ($name, $id_cat) {
    if($this->db->is_not_connected())
      return false;
    $query = "INSERT INTO entry (name, id_category) VALUES ('".
             $name."', ".$id_cat.")";
    return $this->db->insert_query($query);
  }

  public function add_category ($name) {
    if($this->db->is_not_connected())
      return false;
    $query = "INSERT INTO category (name) VALUES ('".$name."')";
    return $this->db->insert_query($query);
  }

  public function add_pending ($amount, $currency, $user, $id_entry, $when) {
    if($this->db->is_not_connected())
      return false;
    $query = 'SELECT username FROM user WHERE username = "'.$user.'"';
    if ($this->db->select_query($query) != 1)
      return false;
    $query = "INSERT INTO app_input (id_entry, amount, currency, user, date_move) ".
             " VALUES (".$id_entry.", ".$amount.", ".$currency.", '".$user."', FROM_UNIXTIME(".$when."/1000))";
    return $this->db->insert_query($query);
  }

  public function save_pending ($id) {
    if($this->db->is_not_connected())
      return false;

    $query = 'SELECT amount, currency, id_entry, date_move '.
             'FROM app_input '.
             'WHERE id = '.$id;
    $res = $this->db->select_query($query);
    if ($res < 1)
      return false;
    $this->db->element($dati);
    switch ($dati['currency']) {
      case 1: $currency = 'dollar'; break;
      case 2: $currency = 'pound'; break;
      case 0:
      default: $currency = 'euro'; break;
    };

    $res = $this->add_movement($dati['amount'], $currency, $dati['date_move'], $dati['id_entry']);
    if (!$res)
       return false;

    $this->delete_pending($id);
    return true;
  }

  public function add_movement ($amount, $currency, $when, $id_entry) {
    if($this->db->is_not_connected())
      return false;
    $query = "INSERT INTO move (amount, currency, execution_time, ".
             "id_entry, id_user) VALUES (".$amount.", '".
             $currency."', '".$when."', ".$id_entry.", ".
             $this->id_user.")";
    return $this->db->insert_query($query);
  }

  public function get_total_until ($currency, $month, $year) {
    if($this->db->is_not_connected())
      return false;
    $data = $year."-".$month."-1";
    $query = 'SELECT sum(amount) as total FROM move WHERE currency=\''.
             $currency.'\' AND execution_time < "'.$data.
             '" AND id_user = '.$this->id_user;
    if ($this->db->select_query($query) != 1)
      return false;
    $this->db->element($dati);

    return $dati['total'];
  }

  public function get_total ($currency) {
    if($this->db->is_not_connected())
      return false;
    $query = "SELECT sum(amount) as total FROM move WHERE currency='".
             $currency."' AND id_user = ".$this->id_user;
    if ($this->db->select_query($query) != 1)
      return false;
    $this->db->element($dati);

    return $dati['total'];
  }

  public function get_categories () {
    if($this->db->is_not_connected())
      return false;

    $query = 'SELECT id, name FROM category';
    $res = $this->db->select_query($query);
    if($res == -1 || $res == 0)
      return $res;

    $arr = array();
    $i = 0;
    while($this->db->element($dati)) {
      $arr[$i]['id'] = $dati['id'];
      $arr[$i]['name'] = $dati['name'];
      $i++;
    }
    return $arr;
  }

  public function get_entries () {
    if($this->db->is_not_connected())
      return false;

    $query = 'SELECT entry.id, entry.name as e_name, category.name as c_name '.
             'FROM entry, category '.
             'WHERE category.id = entry.id_category '.
             'ORDER BY c_name, e_name';
    if ($this->db->select_query($query) == -1)
      return false;

    $arr = array();
    $i = 0;
    while ($this->db->element($dati)) {
      $arr[$i]['id'] = $dati['id'];
      $arr[$i]['entry'] = $dati['e_name'];
      $arr[$i]['category'] = $dati['c_name'];
      $i++;
    }
    return $arr;
  }

  public function get_movements ($month_1, $year_1, $month_2, $year_2) {
    if($this->db->is_not_connected())
      return false;

    $date_1 = $year_1."-".$month_1."-1";
    $date_2 = $year_2."-".$month_2."-31";
    if (($year_1+($month_1/100)) > ($year_2+($month_2/100)))
      return -2;

    $query = 'SELECT move.id as ref_id, amount, currency, execution_time, '.
             'entry.name as e_name, category.name as c_name '.
             'FROM move, entry, category '.
             'WHERE move.id_entry = entry.id AND '.
                   'entry.id_category = category.id AND '.
                   'execution_time >= "'.$date_1.'" AND '.
                   'execution_time <= "'.$date_2.'" AND '.
                   'move.id_user = '.$this->id_user.' '.
             'ORDER BY execution_time DESC';
    $res = $this->db->select_query($query);
    if($res == -1 || $res == 0)
      return $res;

    $arr = array();
    $i = 0;
    while ($this->db->element($dati)) {
      $arr[$i]['id'] = $dati['ref_id'];
      $arr[$i]['amount'] = $dati['amount'];
      $arr[$i]['currency'] = $dati['currency'];
      $arr[$i]['execution_time'] = $dati['execution_time'];
      $arr[$i]['entry'] = $dati['e_name'];
      $arr[$i]['category'] = $dati['c_name'];
      $i++;
    }
    return $arr;
  }

  public function get_pending ($user) {
    if($this->db->is_not_connected())
      return false;

    $query = 'SELECT app_input.id, app_input.amount, app_input.currency, '.
                    'app_input.date_move, entry.name as e_name, '.
                    'category.name as c_name '.
             'FROM app_input, entry, category '.
             'WHERE app_input.id_entry = entry.id AND '.
                   'entry.id_category = category.id AND '.
                   'app_input.user = "'.$user.'" ';
    $res = $this->db->select_query($query);
    if($res == -1 || $res == 0)
      return $res;

    $arr = array();
    $i = 0;
    while ($this->db->element($dati)) {
      $arr[$i]['id'] = $dati['id'];
      $arr[$i]['amount'] = $dati['amount'];
      $arr[$i]['currency'] = $dati['currency'];
      $arr[$i]['entry'] = $dati['e_name'];
      $arr[$i]['category'] = $dati['c_name'];
      $arr[$i]['date_move'] = $dati['date_move'];
      $i++;
    }
    return $arr;
  }

  public function get_count_pending($user) {
    if($this->db->is_not_connected())
      return false;

    $query = 'SELECT count(app_input.id) as num_pending '.
             'FROM app_input '.
             'WHERE app_input.user = "'.$user.'" ';
    if ($this->db->select_query($query) != 1)
      return false;
    $this->db->element($dati);

    return $dati['num_pending'];
  }

  public function delete_movement ($id) {
    if($this->db->is_not_connected())
      return false;
    $query = "DELETE FROM move WHERE id=".$id." AND id_user=".$this->id_user;
    return $this->db->delete_query($query);
  }

  public function delete_pending ($id) {
    if($this->db->is_not_connected())
      return false;
    $query = "DELETE FROM app_input WHERE id=".$id;
    return $this->db->delete_query($query);
  }

  public function login ($username, $password, &$id, &$isadmin){
    if($this->db->is_not_connected())
      return false;
    $query = 'SELECT id, username, isadmin FROM user '.
             'WHERE username = "'.$username.'" AND '.
             'pass = password("'.$password.'")';
    if ($this->db->select_query($query) != 1)
      return false;
    $this->db->element($dati);
    $id = $dati['id'];
    $username = $dati['username'];
    $isadmin = $dati['isadmin'];

    return true;
  }

  public function change_password ($old_password, $new_password){
    if($this->db->is_not_connected())
      return false;
    $query = 'UPDATE user '.
             'SET pass=password("'.$new_password.'") '.
             'WHERE id='.$this->id_user.' AND '.
             'pass=password("'.$old_password.'")';
    return $this->db->update_query($query);
  }

  public function get_todo () {
    if($this->db->is_not_connected())
      return false;

    $query = 'SELECT activity.id, description, whenStored, user.username as username, loc '.
             'FROM activity, user '.
             'WHERE activity.idUser = user.id AND state = \'todo\' '.
             'ORDER BY whenStored DESC';

    $res = $this->db->select_query($query);
    if($res == -1 || $res == 0)
      return $res;

    $arr = array();
    $i = 0;
    while($this->db->element($dati)) {
      $arr[$i++] = $dati;
    }

    return $arr;
  }

  public function get_done () {
    if($this->db->is_not_connected())
      return false;

    $query = 'SELECT activity.id, description, whenStored, user.username as username, state, loc '.
             'FROM activity, user '.
             'WHERE activity.idUser = user.id AND state <> \'todo\' '.
             'ORDER BY whenStored DESC '.
             'LIMIT 40';

    $res = $this->db->select_query($query);
    if($res == -1 || $res == 0)
      return $res;

    $arr = array();
    $i = 0;
    while($this->db->element($dati)) {
      $arr[$i++] = $dati;
    }

    return $arr;
  }

  public function add_todo ($description, $location) {
    if($this->db->is_not_connected())
      return false;

    $query = "INSERT INTO activity (state, description, whenStored, idUser, loc) ".
             "VALUES ('todo', '".$description."', CURDATE(), ".$this->id_user.", '".$location."')";
    return $this->db->insert_query($query);
  }

  public function activity_done ($id) {
    if($this->db->is_not_connected())
      return false;
    $query = 'UPDATE activity SET state=\'done\' WHERE id='.$id;
    return $this->db->update_query($query);
  }

  public function activity_reject ($id) {
    if($this->db->is_not_connected())
      return false;
    $query = 'UPDATE activity SET state=\'reje\' WHERE id='.$id;
    return $this->db->update_query($query);
  }

  public function get_users () {
    if($this->db->is_not_connected())
      return false;
    $query = "SELECT username FROM user";
    $res = $this->db->select_query($query);
    if($res == -1 || $res == 0)
      return $res;
    $arr = array();
    $i = 0;
    while($this->db->element($dati))
      $arr[$i++] = $dati['username'];
    return $arr;

  }

}

?>

