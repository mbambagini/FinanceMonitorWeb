<?php

/* \brief show all movements in the interval in a table
 */
class t_html_pending {

  public function html_results ($data) {
    $html = '<table id="movements_table">';
    $html .= '<tr>';
    $html .= '<th id="movements_cell">Amount</th>';
    $html .= '<th id="movements_cell">When</th>';
    $html .= '<th id="movements_cell">Category</th>';
    $html .= '<th id="movements_cell" colspan="2">ACTION</th>';
    $html .= '<tr>';
    foreach ($data as $elem) {
      $html .= "<tr>";
      $curr = "";
      switch($elem['currency']) {
        case 0: $curr = "&#8364;"; break;
        case 1: $curr = "$"; break;
        case 2: $curr = "&#163;"; break;
      }
      $html .= "<td id=\"movements_cell\">";
      if ($elem['amount'] < 0.0)
        $html .= "<font color=\"red\">";
      else
        $html .= "<font color=\"green\">";
      $html .= $elem['amount']." ".$curr."</font></td>";

      $html .= "<td id=\"movements_cell\">".$elem['date_move']."</td>";

      $html .= "<td id=\"movements_cell\">".$elem['category']." - ".$elem['entry']."</td>";

      $html .= "<td id=\"movements_sub_cell\">";
      $html .= "<form method=\"post\" action=\"pending.php\">";
      $html .= "<input type=\"hidden\" name=\"id_save\" value=\"".$elem['id']."\">";
      $html .= "<input type=\"submit\" value=\"save\">";
      $html .= "</form>";
      $html .= "</td>";
      $html .= "<td id=\"movements_sub_cell\">";
      $html .= "<form method=\"post\" action=\"pending.php\">";
      $html .= "<input type=\"hidden\" name=\"id_delete\" value=\"".$elem['id']."\">";
      $html .= "<input type=\"submit\" value=\"delete\">";
      $html .= "</form>";
      $html .= "</td>";
      $html .= "</tr>";
    }
    $html .= '</table>';
    return $html;
  }

}

?>

