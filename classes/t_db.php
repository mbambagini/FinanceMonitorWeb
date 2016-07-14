<?php

class t_db {
  private static $host = 'localhost';
  private static $user = 'root';
  private static $pass = '';
  private static $db = 'my_marioapp';
  private $connection = 0;
  private $data = 0;

  /*! \brief return if is is NOT connected
   */
  public function is_not_connected () {
    if ($this->connection == 0)
      return true;
    return false;
  }

  /*! \brief set up the connection with the DB
   */
  public function connect () {
    if ($this->connection != 0) return true;
    @$con = mysql_connect(t_db::$host,t_db::$user,t_db::$pass);
    if (!$con) return false;
    $sel = mysql_select_db(t_db::$db,$con);
    if (!$sel) return false;
    $this->connection = $con;
    return true;
  }

  /*! \brief close connection with the DB
   */
  public function disconnect () {
    if($this->connection == 0) return false;
    mysql_close($this->connection);
    $this->connection = 0;
  }

  /*! \brief execute insert query
   *  \return if the operation succedded
   */
  public function insert_query ($stringa_query) {
    if($this->connection == 0) return -1;
    @ $res = mysql_query($stringa_query);
    if ($res)
      return true;
    return false;
  }

  /*! \brief execute deletion query
   *  \return if the operation succedded
   */
  public function delete_query ($stringa_query) {
    if($this->connection == 0) return -1;
    @ $res = mysql_query($stringa_query);
    if ($res)
      return true;
    return false;
  }

  /*! \brief execute selection query
   *  \return the number of results
   */
  public function select_query ($stringa_query) {
    if($this->connection == 0) return -1;
    @ $res = mysql_query($stringa_query);
    if ($res) {
      $this->dati = $res;
      return mysql_num_rows($res);
    }
    return -1;
  }

  /*! \brief execute deletion query
   *  \return if the operation succedded
   */
  public function update_query ($stringa_query) {
    if($this->connection == 0) return -1;
    return (mysql_query($stringa_query) == TRUE);
  }

  /*! \brief return an element each time from the select result
   */
  public function element (&$r_dati) {
    if($this->connection == 0) return false;
    if($r_dati = mysql_fetch_array($this->dati,MYSQL_ASSOC))
      return true;
    return false;
  }

  /*! \brief return the connection state
   */
  public function state () {
    return (($this->connection != 0) ? true : false);
  }

}
?>

