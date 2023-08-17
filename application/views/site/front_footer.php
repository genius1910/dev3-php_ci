<!--<?php echo date('Y'); ?> &copy; MI-DAS by <a href="http://www.kk-cs.co.uk" target="_blank">Kieran Kelly Consultancy Services Ltd.</a> -->
</footer>
<!-- Control Sidebar -->
<!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- ChartJS 1.0.1 -->
<script src="<?php echo $this->config->item('base_folder'); ?>public/plugins/chartjs/Chart.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $this->config->item('base_folder'); ?>public/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $this->config->item('base_folder'); ?>public/js/app.min.js"></script>
<script src="<?php echo $this->config->item('base_folder'); ?>public/js/demo.js"></script>
<!-- AdminLTE for demo purposes -->
<link rel="stylesheet" href="<?php echo $this->config->item('base_folder'); ?>public/colorbox.css" />
<script src="<?php echo $this->config->item('base_folder'); ?>public/jquery.colorbox.js"></script>
<script>
$(document).ready(function() {
  //Examples of how to assign the Colorbox event to elements
  $(".iframe").colorbox({
    iframe: true,
    width: "100%",
    height: "100%"
  });
  //Example of preserving a JavaScript event for inline calls.

});
</script>
<script>
function hide_pop(url) {


  $.colorbox({
    width: "100%",
    height: "100%",
    iframe: true,
    href: url
  });
}
</script>
<!-- AdminLTE for demo purposes -->


<?php
$year0dataCml = ltrim($year0data, '[');
$year0dataCml = rtrim($year0dataCml, ']');
$year0dataCml = explode(',', $year0dataCml);
$year0dataCmlString = '[';
$runningTotal = 0;
foreach ($year0dataCml as $item) {
  $runningTotal += $item;
  $year0dataCmlString .= $runningTotal . ',';
}
$year0dataCmlString = rtrim($year0dataCmlString, ',');
$year0dataCmlString .= ']';
?>

<?php require_once(BASEPATH . '../application/views/common/line_a_vs_line_b_charts.php'); ?>

<script>
$(function() {







  //-------------
  //- BAR CHART -
  //-------------
  // var barChartCanvas = $("#barChart").get(0).getContext("2d");
  // var barChart = new Chart(barChartCanvas);
  // var barChartData = OrderFulfillSameDayData;
  // barChartData.datasets[1].fillColor = "#00a65a";
  // barChartData.datasets[1].strokeColor = "#00a65a";
  // barChartData.datasets[1].pointColor = "#00a65a";
  // var barChartOptions = {
  //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
  // scaleBeginAtZero: true,
  //Boolean - Whether grid lines are shown across the chart
  // scaleShowGridLines: true,
  //String - Colour of the grid lines
  // scaleGridLineColor: "rgba(0,0,0,.05)",
  //Number - Width of the grid lines
  // scaleGridLineWidth: 1,
  //Boolean - Whether to show horizontal lines (except X axis)
  // scaleShowHorizontalLines: true,
  //Boolean - Whether to show vertical lines (except Y axis)
  // scaleShowVerticalLines: true,
  //Boolean - If there is a stroke on each bar
  // barShowStroke: true,
  //Number - Pixel width of the bar stroke
  // barStrokeWidth: 2,
  //Number - Spacing between each of the X value sets
  // barValueSpacing: 5,
  //Number - Spacing between data sets within X values
  // barDatasetSpacing: 1,
  //String - A legend template
  // legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
  //Boolean - whether to make the chart responsive
  // responsive: true,
  // maintainAspectRatio: true
  // };

  // barChartOptions.datasetFill = false;
  // barChart.Bar(barChartData, barChartOptions);
});
</script>

<script type="text/javascript">
$(function() {
  <?php if ($salestodaydonutcharts == 'Y') { ?>
  $("#salestodaytables_nav a").click();
  <?php } ?>

  <?php if ($outstandingordersdonutchart == 'Y') { ?>
  $("#outstandingorderstable_nav a").click();
  <?php } ?>

  <?php if ($threeyearsaleschart == 'Y') { ?>
  $("#threeyearsaleschart_nav a").click();
  $("#threeyearsalestable_nav a").click();
  <?php } ?>
});
</script>
</body>

</html>