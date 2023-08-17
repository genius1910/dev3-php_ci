<?php
  $GLOBALS['pageData'] = $based_info['table_data'];
  function changepointlocation($num) {
    return floor($num * 100) / 100;
  }
  function getItemInfo($period, $type) {
    foreach($GLOBALS['pageData'] as $key => $item) {
      if($item['period'] == $period){
        return $item[$type];
      }
    }
    return 0;
  }
?>
<div class="row">
  <div class="col-md-12">
    <h3>Seasonality Profile Based On <?= $based_info['level'] ?> - <?= $based_info['product_code'] ?> -
      <?= $based_info['description'] ?> </h3>
  </div>
  <div class="col-md-12">
    <table class="table">
      <thead>
        <tr>
          <th></th>
          <th>Jan</th>
          <th>Feb</th>
          <th>Mar</th>
          <th>Apr</th>
          <th>May</th>
          <th>Jun</th>
          <th>Jul</th>
          <th>Aug</th>
          <th>Sep</th>
          <th>Oct</th>
          <th>Nov</th>
          <th>Dec</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Percentage</td>
          <?php for($i = 0; $i < 12; $i++): ?>
          <td><?= changepointlocation(getItemInfo($i + 1, 'percentage')) ?></td>
          <?php endfor ?>
        </tr>
        <tr>
          <td>Base Series</td>
          <?php for($i = 0; $i < 12; $i++): ?>
          <td><?= changepointlocation(getItemInfo($i + 1, 'baseseries')) ?></td>
          <?php endfor ?>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-12" id="seasonalityProfile_chart">

  </div>
</div>

<script>
$(document).ready(function() {
  projectSalesYearChart();
});

function projectSalesYearChart() {
  seasonalityProfile_chart = `
          <div class="row">
            <div class="col-md-12">
              <div class="chart">
                <canvas id="SeasonalityProfileChart" style="height: 300px"></canvas>
              </div>
            </div>
          </div>`;
  $("#seasonalityProfile_chart").html(seasonalityProfile_chart);

  if ($("#SeasonalityProfileChart").length) {
    // Get context with jQuery - using jQuery's .get() method.
    var DemandVsAverageChartCanvas = $("#SeasonalityProfileChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var DemandVsAverageChart = new Chart(DemandVsAverageChartCanvas);
    let labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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
          <?php for($i = 0; $i < 12; $i++) { echo '"'.changepointlocation(getItemInfo($i + 1, 'percentage')).'",'; } ?>
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