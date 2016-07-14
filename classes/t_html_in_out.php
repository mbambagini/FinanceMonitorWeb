<?php

/* \brief this class provides a table with the overall in/out collected
 *        according to the category/subcategory
 */
class t_html_in_out {

  public function html_results ($data) {
    $arr = array();

    $html = "";

    foreach ($data as $elem) {
      $key = $elem['category']." - ".$elem['entry'];
      if (!array_key_exists($key, $arr)) {
        $arr[$key] = new t_element;
        $arr[$key]->key = $key;
      }
      if (strcmp($elem['currency'], "euro") == 0)
        if ($elem['amount'] >= 0.0)
          $arr[$key]->in_euro += $elem['amount'];
        else
          $arr[$key]->out_euro -= $elem['amount'];
      if (strcmp($elem['currency'], "pound") == 0)
        if ($elem['amount'] >= 0.0)
          $arr[$key]->in_pound += $elem['amount'];
        else
          $arr[$key]->out_pound -= $elem['amount'];
      if (strcmp($elem['currency'], "dollar") == 0)
        if ($elem['amount'] >= 0.0)
          $arr[$key]->in_dollar += $elem['amount'];
        else
          $arr[$key]->out_dollar -= $elem['amount'];
    }

    $html .= '<table id="in_out_table"><tr>'.
             '<th id="in_out_cell">ENTRY</th>'.
             '<th id="in_out_cell">INCOMING</th>'.
             '<th id="in_out_cell">OUTCOMING</th>'.
             '</tr>';

    $in_euro = 0.0;
    $in_pound = 0.0;
    $in_dollar = 0.0;
    $out_euro = 0.0;
    $out_pound = 0.0;
    $out_dollar = 0.0;

    foreach ($arr as $elem) {
      $html .= '<tr>'.
               '<td id="in_out_cell">'.$elem->key.'</td>'.
               '<td id="in_out_cell">';
      if ($elem->in_euro > 0.0) {
        $html .= $elem->in_euro.' '."&#8364;<br>";
        $in_euro += $elem->in_euro;
      }
      if ($elem->in_pound > 0.0) {
        $html .= $elem->in_pound.' '."&#163;<br>";
        $in_pound += $elem->in_pound;
      }
      if ($elem->in_dollar > 0.0) {
        $html .= $elem->in_dollar.' '."$<br>";
        $in_dollar += $elem->in_dollar;
      }
      $html .= '</td><td id="in_out_cell">';
      if ($elem->out_euro > 0.0) {
        $html .= $elem->out_euro.' '."&#8364;<br>";
        $out_euro -= $elem->out_euro;
      }
      if ($elem->out_pound > 0.0) {
        $html .= $elem->out_pound.' '."&#163;<br>";
        $out_pound -= $elem->out_pound;
      }
      if ($elem->out_dollar > 0.0) {
        $html .= $elem->out_dollar.' '."$<br>";
        $out_dollar -= $elem->out_dollar;
      }
      $html .= '</td></tr>';
    }

    $html .= '<tr>'.
             '<th id="in_out_cell">TOTAL</th>'.
             '<th id="in_out_cell"><font color="green">'.
             $in_euro." &#8364;, ".$in_pound." &#163;, ".
             $in_dollar." $</font></th>".
             '<th id="in_out_cell"><font color="red">'.
             $out_euro." &#8364;, ".$out_pound." &#163;, ".
             $out_dollar." $</font></th>".
             '</tr>';

    $html .= '</table>';

    return $html;
  }

}

?>

