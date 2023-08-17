<?php
$GLOBALS['pageData'] = $demandVsAverage_info;
function changepointlocation($num) {
  return floor($num * 100) / 100;
}
function getItemInfo($year, $period) {
  $value = 0;
  foreach($GLOBALS['pageData'] as $key => $item) {
    if($item['year'] == $year && $item['period'] == $period){
      return $item['demandqty'];
    }
  }
  return 0;
}

$current_year = date('Y');

?>

<div class="row">
  <div class="col-md-12">
    <div class="box-footer text-black">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
            <li class="<?php if($stockValuationAnalysisChart == 'Y') echo 'active'; ?>"
            onclick="manage_cookie('stockValuationAnalysisChart', 'Y')">
            <a href="#stockvaluationcharts" data-toggle="tab">
                <i class="fa fa-pie-chart"></i>
            </a>
            </li>
            <li class="<?php if($stockValuationAnalysisChart == 'N') echo 'active'; ?>"
            onclick="manage_cookie('stockValuationAnalysisChart', 'N')">
            <a href="#stockvaluationtables" data-toggle="tab" aria-expanded="true">
                <i class="fa fa-table"></i>
            </a>
            </li>
            <li class="pull-left header">Demand Analysis</li>
        </ul>
        <div class="tab-content no-padding">
            <div class="tab-pane <?php if($stockValuationAnalysisChart == 'Y') echo 'active'; ?>"
            id="stockvaluationcharts" style="position: relative;">
            <div class="row">
                <div class="col-md-12">
                This is chart.
                </div>
                <div class="col-md-12">
                <div class="box-body" id="project_sales_year_chart" style="min-height:250px">
                    <div class="overlay h2 m-0 loading_spinner" style="margin: 0px;display: block;position: absolute;">
                    <i class="fa fa-spinner fa-spin fa-fw"></i>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <div class="tab-pane <?php if($stockValuationAnalysisChart == 'N') echo 'active'; ?>"
            id="stockvaluationtables" style="position: relative;">
            <div class="row">
                <div class="col-md-12">
                <div class="table-scroll">
                    <?php
                    $stockvaluation = [];
                    for($i = 0; $i < 12; $i++) {
                        array_push($stockvaluation, date('M-y', strtotime($system_curyearmonth." - ".(12 - $i)."month")));
                    }
                    array_push($stockvaluation, date('M-y', strtotime($system_curyearmonth)));
                    for($i = 0; $i < 12; $i++) {
                        array_push($stockvaluation, date('M-y', strtotime($system_curyearmonth." + ".($i + 1)." month")));
                    }
                    ?>
                    <input type="hidden" id="stockValuationLables" value="<?php echo join($stockvaluation, '&') ?>" />
                    <input type="hidden" id="stockValuationData" value="<?php var_dump($stockValuationAnalysis) ?>" />
                    <table class="table table-striped" id="stockvalutionanalysis">
                    <tr>
                        <th>Valuation</th>
                        <?php for($i = 0; $i < count($stockvaluation); $i++): ?>
                        <th><?=$stockvaluation[$i]?></th>
                        <?php endfor ?>
                    </tr>
                    <tr>
                        <td>Override</td>
                        <?php for($i = 0; $i < count($stockvaluation); $i++): ?>
                        <td>
                        <?php if(count($stockValuationAnalysis) == 0) echo "0" ?>
                        <?php foreach($stockValuationAnalysis as $item): ?>
                        <?php echo date('M-y', strtotime($item['date'])) == $stockvaluation[$i] ? $item['actualvalue1'] : '0'; break; ?>
                        <?php endforeach ?>
                        </td>
                        <?php endfor ?>
                    </tr>
                    <tr>
                        <td>Ideal</td>
                        <?php for($i = 0; $i < count($stockvaluation); $i++): ?>
                        <td>
                        <?php if(count($stockValuationAnalysis) == 0) echo "0" ?>
                        <?php foreach($stockValuationAnalysis as $item): ?>
                        <?php echo date('M-y', strtotime($item['date'])) == $stockvaluation[$i] ? $item['actualvalue2'] : '0'; break; ?>
                        <?php endforeach ?>
                        </td>
                        <?php endfor ?>
                    </tr>
                    <tr>
                        <td>Actual</td>
                        <?php for($i = 0; $i < count($stockvaluation); $i++): ?>
                        <td>
                        <?php if(count($stockValuationAnalysis) == 0) echo "0" ?>
                        <?php foreach($stockValuationAnalysis as $item): ?>
                        <?php echo date('M-y', strtotime($item['date'])) == $stockvaluation[$i] ? $item['actualvalue3'] : '0'; break; ?>
                        <?php endforeach ?>
                        </td>
                        <?php endfor ?>
                    </tr>
                    </table>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
      </div>
    </div>
</div>
</div>