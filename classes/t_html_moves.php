<?php

/* \brief show all movements in the interval in a table
 */
class t_html_moves {

  public function html_results ($data) {
    $html = '<table id="movements_table">';
    $html .= '<tr>';
    $html .= '<th id="movements_cell">Amount</th>';
    $html .= '<th id="movements_cell">When</th>';
    $html .= '<th id="movements_cell">Category</th>';
    $html .= '<th id="movements_cell">Delete</th>';
    $html .= '<tr>';
    foreach ($data as $elem) {
      $html .= "<tr>";
      if (strcmp($elem['currency'], "euro") == 0)
        $curr = "&#8364;";
      if (strcmp($elem['currency'], "pound") == 0)
        $curr = "&#163;";
      if (strcmp($elem['currency'], "dollar") == 0)
        $curr = "$";
      $html .= "<td id=\"movements_cell\">";
      if ($elem['amount'] < 0.0)
        $html .= "<font color=\"red\">";
      else
        $html .= "<font color=\"green\">";
      $html .= $elem['amount']." ".$curr."</font></td>";
      $html .= "<td id=\"movements_cell\">".$elem['execution_time']."</td>";
      $html .= "<td id=\"movements_cell\">".$elem['category']." - ".$elem['entry']."</td>";
      $html .= "<td id=\"movements_cell\">";
      $html .= "<form method=\"post\" action=\"movements.php\">";
      $html .= "<input type=\"hidden\" name=\"operation\" value=\"delete\">";
      $html .= "<input type=\"hidden\" name=\"id\" value=\"".$elem['id']."\">";
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

