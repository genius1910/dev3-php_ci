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
    <table class="table">
      <thead>
        <tr>
          <th>Period</th>
          <th><?= $current_year - 3 ?></th>
          <th><?= $current_year - 2 ?></th>
          <th><?= $current_year - 1 ?></th>
          <th><?= $current_year ?></th>
          <th>Average</th>
          <th>Dev. From Avg.</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $total4 = 0;
          $total3 = 0;
          $total2 = 0;
          $total1 = 0;
          $total = 0;
          $diffAvg = 0;
          $data = array();
          for($i = 0; $i < 12; $i++){
            $data[$i]['period'] = $i + 1;
            $data[$i]['year4'] = getItemInfo($current_year - 3, $i + 1);
            $total4 += $data[$i]['year4'];
            $data[$i]['year3'] = getItemInfo($current_year - 2, $i + 1);
            $total3 += $data[$i]['year3'];
            $data[$i]['year2'] = getItemInfo($current_year - 1, $i + 1);
            $total2 += $data[$i]['year2'];
            $data[$i]['year1'] = getItemInfo($current_year, $i + 1);
            $total1 += $data[$i]['year1'];
            $data[$i]['average'] = ($data[$i]['year4'] + $data[$i]['year3'] + $data[$i]['year2'] + $data[$i]['year1']) / 4;
          }
          $total = $total4 + $total3 + $total2 + $total1;

          for($i = 0; $i < 12; $i++) {
            $data[$i]['devFromAvg'] = abs($data[$i]['average'] - $total / 4);
            $diffAvg += $data['devFromAvg'];
          }
          $diffAvg = $diffAvg / 12;
        ?>

        <?php foreach($data as $item): ?>
        <tr>
          <td><?= $item['period'] ?></td>
          <td><?= $item['year4'] ?></td>
          <td><?= $item['year3'] ?></td>
          <td><?= $item['year2'] ?></td>
          <td><?= $item['year1'] ?></td>
          <td><?= changepointlocation($item['average']) ?></td>
          <td><?= changepointlocation($item['devFromAvg']) ?></td>
        </tr>
        <?php endforeach ?>
        <tr>
          <th>Total</th>
          <td><?= $total4 ?></td>
          <td><?= $total3 ?></td>
          <td><?= $total2 ?></td>
          <td><?= $total1 ?></td>
          <td><?= $total / 4 ?></td>
          <td>M.A.D</td>
        </tr>
        <tr>
          <th>Average</th>
          <td><?= changepointlocation($total4 / 12) ?></td>
          <td><?= changepointlocation($total3 / 12) ?></td>
          <td><?= changepointlocation($total2 / 12) ?></td>
          <td><?= changepointlocation($total1 / 12) ?></td>
          <td><?= changepointlocation($total / 48) ?></td>
          <td><?= changepointlocation($diffAvg) ?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-12" id="demandvsaverage_chart">

  </div>
</div>

<script>
$(document).ready(function() {
  projectSalesYearChart();
});

function projectSalesYearChart() {
  demandvsaverage_chart = `
          <div class="row">
            <div class="col-md-12">
              <div class="chart">
                <canvas id="DemandVsAverageChart" style="height: 300px"></canvas>
              </div>
            </div>
          </div>`;
  $("#demandvsaverage_chart").html(demandvsaverage_chart);

  if ($("#DemandVsAverageChart").length) {
    // Get context with jQuery - using jQuery's .get() method.
    var DemandVsAverageChartCanvas = $("#DemandVsAverageChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var DemandVsAverageChart = new Chart(DemandVsAverageChartCanvas);
    let labels = ["<?= $current_year - 3 ?>", "<?= $current_year - 2 ?>", "<?= $current_year - 1 ?>",
      "<?= $current_year ?>", "Average"
    ];

    var DemandVsAverageChartData = {
      labels: labels,
      datasets: [{
        label: "Actual",
        fillColor: "#d2d6de", // Gray
        strokeColor: "#d2d6de",
        pointColor: "#d2d6de",
        pointStrokeColor: "#d2d6de",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [
          <?= changepointlocation($total4 / 12) ?>,
          <?= changepointlocation($total3 / 12) ?>,
          <?= changepointlocation($total2 / 12) ?>,
          <?= changepointlocation($total1 / 12) ?>,
          <?= changepointlocation($total / 48) ?>
        ]
      }]
    };

    var DemandVsAverageChartOptions = {
      //Boolean - If we should show the scale at all
      showScale: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: false,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - Whether the line is curved between points
      bezierCurve: true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension: 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot: false,
      //Number - Radius of each point dot in pixels
      pointDotRadius: 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth: 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius: 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke: true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth: 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill: false,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true
    };

    //Create the line chart
    DemandVsAverageChart.Line(DemandVsAverageChartData, DemandVsAverageChartOptions);
  }
}
</script>