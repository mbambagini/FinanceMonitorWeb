<?php

/* \brief this class creates a pie graph showing the outgoing for each
 *        category/subcategory in the inteval
 */
class t_html_pie { //implements i_html {

  private function parse_data ($data) {
    $arr = array();

    foreach ($data as $elem) {
      $key = $elem['category']." - ".$elem['entry'];
      if (!array_key_exists($key, $arr)) {
        $arr[$key] = new t_element;
        $arr[$key]->key = $key;
      }
      if ($elem['amount'] < 0.0) {
        if (strcmp($elem['currency'], "euro") == 0)
          $arr[$key]->out_euro -= $elem['amount'];
        if (strcmp($elem['currency'], "pound") == 0)
          $arr[$key]->out_pound -= $elem['amount'];
        if (strcmp($elem['currency'], "dollar") == 0)
          $arr[$key]->out_dollar -= $elem['amount'];
       }
    }
    return $arr;
  }

  private function print_chart_function ($data, $currency, $element) {
    $html  = "function drawChart_$currency() {";
    $html .= "  var data = google.visualization.arrayToDataTable([";
    $html .= "    ['Category', 'Outcoming'],\n";
    $var = 0;
    if (strcmp($currency, "euro") == 0) $var = 1;
    if (strcmp($currency, "dollar") == 0) $var = 2;
    if (strcmp($currency, "pound") == 0) $var = 3;
    foreach ($data as $elem) {
      if ($var == 1 && $elem->out_euro > 0.0)
        $html .= "    ['".$elem->key."', ".$elem->out_euro."],\n";
      if ($var == 2 && $elem->out_dollar > 0.0)
        $html .= "    ['".$elem->key."', ".$elem->out_dollar."],\n";
      if ($var == 3 && $elem->out_pound > 0.0)
        $html .= "    ['".$elem->key."', ".$elem->out_pound."],\n";
    }
    $html .= '  ]);';
    $html .= "  var options = {title: 'Outcoming in $currency', is3D: true,};";
    $html .= "  var chart = new google.visualization.PieChart(document.getElementById('$element'));";
    $html .= '  chart.draw(data, options);';
    $html .= '}';
    return $html;
  }

  private function print_chart($data) {
    $html  = "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>\n";
    $html .= "<script type=\"text/javascript\">\n";
    $html .= 'google.load("visualization", "1", {packages:["corechart"]});';
    $html .= 'google.setOnLoadCallback(drawChart);';
    $html .= $this->print_chart_function($data, "euro", "piechart_3d_euro");
    $html .= $this->print_chart_function($data, "dollar", "piechart_3d_dollar");
    $html .= $this->print_chart_function($data, "pound", "piechart_3d_pound");
    $html .= 'function drawChart() {drawChart_euro(); drawChart_dollar(); drawChart_pound();}';
    $html .= '</script>';
    $html .= '<table><tr>';
    $html .= "<td><div id=\"piechart_3d_euro\" style=\"width: 440px; height: 300px;\"></div></td>";
    $html .= "<td><div id=\"piechart_3d_pound\" style=\"width: 440px; height: 300px;\"></div></td>";
    $html .= "<td><div id=\"piechart_3d_dollar\" style=\"width: 440px; height: 300px;\"></div></td>";
    $html .= '</tr></table>';

    return $html;
  }

  public function html_results ($data) {
    $arr = $this->parse_data($data);
    return $this->print_chart($arr);
  }

}

?>

