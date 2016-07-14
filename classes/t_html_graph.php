<?php

/* \brief this class creates a XY graph showing how the amount of money has
 *        varied in the interval
 */
class t_html_graph {

  private $month_list;
  private $initial_euro = 0.0;
  private $initial_pound = 0.0;
  private $initial_dollar = 0.0;
  private $min_amount;
  private $max_amount;

  public function set_range ($month_start, $year_start, $month_stop, $year_stop) {
    //create all month between the min and max
    $i = 0;
    $this->month_list = array();
    for ($y = $year_start; $y <= $year_stop; $y++)
      for ($m = ($y==$year_start)?$month_start:1; $m <= ( ($y==$year_stop)?$month_stop:12 ); $m++) {
        $this->month_list[$i] = new t_trend;
        $this->month_list[$i]->month = $m;
        $this->month_list[$i]->year = $y;
        $i++;
      }
  }

  public function set_initial_budget ($e, $p, $d) {
    $this->initial_euro = $e;
    $this->initial_pound = $p;
    $this->initial_dollar = $d;
  }

  private function fill_data ($data) {
    $format = '%Y-%m-%d';
    //create all month between the min and max
    foreach ($data as $elem) {
      $d = strptime($elem['execution_time'], $format);
      foreach ($this->month_list as $month) {
        if ($month->year == ($d['tm_year'] + 1900) && $month->month == ($d['tm_mon'] + 1)) {
          if (strcmp($elem['currency'], "euro") == 0)
            $month->euro += $elem['amount'];
          if (strcmp($elem['currency'], "pound") == 0)
            $month->pound += $elem['amount'];
          if (strcmp($elem['currency'], "dollar") == 0)
            $month->dollar += $elem['amount'];
          break;
        }
      }
    }

    $previous_euro = $this->initial_euro;
    $previous_pound = $this->initial_pound;
    $previous_dollar = $this->initial_dollar;
    $this->min_amount = 10000.0;
    $this->max_amount = -10000.0;
    foreach ($this->month_list as $month) {
      $month->euro += $previous_euro;
      $previous_euro = $month->euro;
      $month->pound += $previous_pound;
      $previous_pound = $month->pound;
      $month->dollar += $previous_dollar;
      $previous_dollar = $month->dollar;
      if ($month->euro > $this->max_amount) $this->max_amount = $month->euro;
      if ($month->pound > $this->max_amount) $this->max_amount = $month->pound;
      if ($month->dollar > $this->max_amount) $this->max_amount = $month->dollar;
      if ($month->euro < $this->min_amount) $this->min_amount = $month->euro;
      if ($month->pound < $this->min_amount) $this->min_amount = $month->pound;
      if ($month->dollar < $this->min_amount) $this->min_amount = $month->dollar;
    }
  }

  private function print_javascript () {
    $html  = '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
    $html .= '<script type="text/javascript">'.
             "  google.charts.load('current', {'packages':['line', 'corechart']});".
             "  google.charts.setOnLoadCallback(drawChart);".
             "  function drawChart() {".
             "    var chartDiv = document.getElementById('chart_div');".
             "    var data = new google.visualization.DataTable();".
             "    data.addColumn('date', 'Month');".
             "    data.addColumn('number', 'Euro');".
             "    data.addColumn('number', 'Pound');".
             "    data.addColumn('number', 'Dollar');".
             "    data.addRows([";
    foreach ($this->month_list as $month)
       $html .= "[new Date(".$month->year.", ".
                ($month->month-1)."), ".$month->euro.", ".
                $month->pound.", ".$month->dollar."], ";
    $html .= "      ]);".
             "   var classicOptions = { ".
             "   title: 'Budget', ".
             "   width: 900,".
             "   height: 500,".
             "   series: {".
             "     0: {targetAxisIndex: 0},".
             "     0: {targetAxisIndex: 0},".
             "     0: {targetAxisIndex: 0},".
             "   },".
             "   vAxes: {".
             "     0: {title: 'Budget'},".
             "   },".
             "   hAxis: {".
             "   ticks: [";
    foreach ($this->month_list as $month)
      $html .= "new Date(".$month->year.", ".($month->month-1)."), ";
    $html .= "  ]}, vAxis: {viewWindow: {max: ".$this->max_amount.", min: ".$this->min_amount."}}};";
    $html .= "  var classicChart = new google.visualization.LineChart(chartDiv);".
             "  classicChart.draw(data, classicOptions);".
             '}</script><div id="chart_div"></div>';
    return $html;
  }

  public function html_results ($data) {
    $this->fill_data($data);
    return $this->print_javascript();
  }

}

?>

