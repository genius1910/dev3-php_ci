<?php $currency_symbol = $this->config->item("currency_symbol"); ?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>MI-DAS | Management Information Dashboard</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>public/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="./AdminLTE-2.3.3/dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
			 folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>public/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="<?php echo $this->config->item('base_folder'); ?>public/css/twoyearsaleschart.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>


	<![endif]-->
	<style>
		.tab-content {
			padding-top: 40px;
		}

		.text-navy {
			color: #001f3f !important
		}

		.text-light-blue {
			color: #3c8dbc !important
		}

		.text-gray {
			color: #d2d6de !important
		}
	</style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper" style="background-color:white!important">

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->


			<!-- Main content -->
			<section class="content">


				<!-- /.row -->

				<div class="row">
					<div class="col-md-12"> <!-- Main left hand side of dashboard -->

						<!------------------------------------------------------------------------------------------------------------------>
						<!-- 3 YEAR SALES CHART -->
						<!------------------------------------------------------------------------------------------------------------------>

						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs pull-right">
								<li class="active"><a href="#threeyearsaleschart" data-toggle="tab"
										onclick="manage_cookie('threeyearsalesanalysispacchart','N')"><i class="fa fa-line-chart"></i></a>
								</li>
								<li onclick="manage_cookie('threeyearsalesanalysispacchart','Y')"
									id="threeyearsalesanalysispactable_nav"><a href="#threeyearsalestable" data-toggle="tab"
										class="threeyearsalestable-link"><span class="threeyearsalestable-sales">
											<?= $currency_symbol; ?>
										</span> / <span class="threeyearsalestable-quantities">Qty&nbsp;&nbsp;</span><i
											class="fa fa-table"></i></a></li>
							</ul>
							<div class="tab-content no-padding">
								<div class="tab-pane active" id="threeyearsaleschart" style="position: relative;">
									<div class="row">
										<div class="col-md-9">
											<div class="chart">
												<div class="loading_spinner" style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
													<i class="fa fa-spinner fa-spin fa-fw" style="
														color: #000;
														scale: 2;
														position: absolute;
														top: 44%;
													"></i>
												</div>
												<canvas id="this-year-vs-target" style="height: 250px;"></canvas>
												<canvas id="this-year-cml-vs-target-cml" style="height: 250px;"></canvas>
												<canvas id="this-year-vs-last-year" style="height: 250px;"></canvas>
												<canvas id="this-year-cml-vs-last-year-cml" style="height: 250px;"></canvas>
												<canvas id="quantity-this-year-vs-last-year" style="height: 250px;"></canvas>
												<canvas id="quantity-this-year-cml-vs-target-cml" style="height: 250px;"></canvas>
											</div>
										</div>
										<div class="col-md-1">
											<ul class="chart-legend clearfix">
												<li id="this-year-legend"><i class="fa fa-circle-o text-navy"></i>
													<?= $year0; ?>
												</li>
												<li id="this-year-cml-legend" style="display:none;"><i class="fa fa-circle-o text-navy"></i>
													<?= $year0; ?> Cml.
												</li>
												<li id="last-year-legend" style="display:none;"><i class="fa fa-circle-o text-light-blue"></i>
													<?= $year1; ?>
												</li>
												<li id="last-year-cml-legend" style="display:none;"><i class="fa fa-circle-o text-light-blue"></i>
													<?= $year1; ?> Cml.
												</li>
												<li id="before-year-legend" style="display:none;"><i class="fa fa-circle-o text-gray"></i>
													<?= $year2; ?>
												</li>
												<li id="before-year-cml-legend" style="display:none;"><i class="fa fa-circle-o text-gray"></i>
													<?= $year2; ?> Cml.
												</li>
												<li id="target-legend"><i class="fa fa-circle-o text-light-blue"></i>Target</li>
												<li id="target-cml-legend" style="display:none;"><i class="fa fa-circle-o text-light-blue"></i>Target
													Cml.</li>
											</ul>
										</div>
										<div class="col-md-2">
											<form>
												<div class="form-group">
													<label for="chose-graph">Choose graph</label>
													<select class="form-control" id="choose-graph">
														<optgroup label="Sales vs Target">
															<option value="this-year-vs-target-option">
																<?= $year0; ?> vs Target
															</option>
															<option value="this-year-cml-vs-target-cml-option">
																<?= $year0; ?> cml. vs Target cml.
															</option>
														</optgroup>
														<optgroup label="Sales Year Comparison">
															<option value="this-year-vs-last-year-option">Monthly</option>
															<option value="this-year-cml-vs-last-year-cml-option">Cumulative</option>
														</optgroup>
														<optgroup label="Quantity Year Comparison">
															<option value="quantity-this-year-vs-last-year-option">Monthly</option>
															<option value="quantity-this-year-cml-vs-target-cml-option">Cumulative</option>
														</optgroup>
													</select>
												</div>
											</form>
										</div>
									</div>
								</div> <!-- class="tab-pane" -->
								<div class="tab-pane" id="threeyearsalestable" style="position: relative;">
									<div class="loading_spinner"
										style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;text-align: center;">
										<i class="fa fa-spinner fa-spin fa-fw" style="
								color: #000;
								scale: 2;
								position: absolute;
								top: 44%;
							"></i>
									</div>
									<table class="table table-striped sales-toggle hide" id="dialogthreeyearsalestable">
										<?php
										$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
										for ($i = 1; $i < $yearstartmonth; $i++) {
											$tmp = array_shift($months);
											array_push($months, $tmp);
										}
										?>
										<tr class="border-header">
											<th>Year1</th>
											<?php foreach ($months as $month) { ?>
												<th>
													<?php echo $month; ?> (
													<?= $currency_symbol; ?>)
												</th>
											<?php } ?>
											<th>Total (
												<?= $currency_symbol; ?>)
											</th>
										</tr>
									</table>
									<table class="table table-striped quantities-toggle">
										<?php
										$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
										for ($i = 1; $i < $yearstartmonth; $i++) {
											$tmp = array_shift($months);
											array_push($months, $tmp);
										}
										?>
										<tr class="border-header">
											<th>Year2</th>
											<?php foreach ($months as $month) { ?>
												<th>
													<?php echo $month; ?> (Qty)
												</th>
											<?php } ?>
											<th>Total (Qty)</th>
										</tr>
										<?php require_once(BASEPATH . '../application/views/common/quantitytwoyearsvstarget.php'); ?>
									</table>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div> <!-- Main left hand side of dashboard col-md-9 -->

				</div> <!-- row -->
			</section>
			<!-- /.content -->

			<!-- ./wrapper -->
			<script src="<?= $this->config->item('base_folder'); ?>public/plugins/jQuery/jQuery-2.2.0.min.js"></script>
			<!-- Bootstrap 3.3.6 -->
			<script src="<?= $this->config->item('base_folder'); ?>public/bootstrap/js/bootstrap.min.js"></script>
			<!-- ChartJS 1.0.1 -->
			<script src="<?= $this->config->item('base_folder'); ?>public/plugins/chartjs/Chart.min.js"></script>
			<!-- FastClick -->
			<script src="<?= $this->config->item('base_folder'); ?>public/plugins/fastclick/fastclick.js"></script>
			<!-- AdminLTE App -->
			<script src="<?= $this->config->item('base_folder'); ?>public/js/app.min.js"></script>
			<!-- AdminLTE for demo purposes -->
			<script src="<?= $this->config->item('base_folder'); ?>public/js/demo.js"></script>

			<?php require_once(BASEPATH . '../application/views/common/line_a_vs_line_b_charts.php'); ?>
			<script src="<?= $this->config->item('base_folder'); ?>public/js/common.js"></script>

			<script type="text/javascript">
				$(function () {
					$(".threeyearsalestable-link").on("click", function () {
						if ($(".quantities-toggle").hasClass("hide")) {
							$(".quantities-toggle").removeClass("hide");
							$(".sales-toggle").addClass("hide");
							$(".threeyearsalestable-quantities").css("color", "red");
							$(".threeyearsalestable-sales").css("color", "black");
						}
						else if ($(".sales-toggle").hasClass("hide")) {
							$(".sales-toggle").removeClass("hide");
							$(".quantities-toggle").addClass("hide");
							$(".threeyearsalestable-sales").css("color", "red");
							$(".threeyearsalestable-quantities").css("color", "black");
						}
					})
				});
			</script>

			<script>
				function manage_cookie(cookie_name, cookie_value) {
					$.ajax({
						type: "POST",
						dataType: "html",
						url: "<?= base_url(); ?>/customer/manage_cookie",
						data: { cookie_name: cookie_name, cookie_value: cookie_value },
						success: function (data) {
						}
					});
				}
			</script>

			<script type="text/javascript">
				$(function () {
					$.ajax({
						url: '<?= base_url() ?>customer/getCustomergraphtable',
						data: {
							account: '<?= $account; ?>',
							level: '<?= $level; ?>',
							code: '<?= $code; ?>'
						},
						method: 'post',
						dataType: 'json',
						success: function (data) {
							//Two Years Target Table
							const resp = twoYearsTargetTable({
								year0,
								year1,
								year2,
								year0table,
								year1table,
								year2table,
								yearstartmonth,
								salesTargetForLastThreeYear,
								G_MarginOk,
								G_MarginGood,
								G_kpithreshold1,
								G_kpithreshold2,
								year0total,
								year1total,
								year2total,
								year0data,
								year1data,
								year2data,
							} = data);
							$('#dialogthreeyearsalestable').append(resp);
							$('#dialogthreeyearsalestable ').prev(".loading_spinner").remove();
						}
					});
					let count = 0;
					if (count == 0) {
						$.ajax({
							url: '<?= base_url() ?>customer/getCustomergraphchart',
							data: {
								account: '<?= $account; ?>',
								level: '<?= $level; ?>',
								code: '<?= $code; ?>'
							},
							method: 'post',
							dataType: 'json',
							success: function (data) {
								//This Year Vs Target Chart
								ThreeYearChartCanvas({
									targetDataForCurrentYear,
									yearstartmonth,
									cumulativeTargetDataForCurrentYear,
									cumulativeYear0ChartValues,
									cumulativeYear1ChartValues,
									cumulativeYear2ChartValues,
									cumulativeQuantityYear0ChartValues,
									cumulativeQuantityYear1ChartValues,
									cumulativeQuantityYear2ChartValues,
									quantityYear0ChartValues,
									quantityYear1ChartValues,
									quantityYear2ChartValues,
									year0ChartValues,
									year1ChartValues,
									year2ChartValues,
									year0,
									year1,
									year2
								} = data);
								count++;
							}
						});
						$("#choose-graph").val("this-year-vs-target-option");

						//Hide all the charts except the this-year-vs-target chart
						$("#quantity-this-year-vs-last-year").hide();
						$("#quantity-this-year-cml-vs-target-cml").hide();

						$("#choose-graph").on('change', function () {
							//Hide all graphs and legends.
							$("#threeyearsaleschart canvas").hide();
							$("#LineAvsLineBChart .chart-legend li").hide();

							//Unhide the selected graph and legend.
							if ($(this).val() == "this-year-vs-target-option") {
								$("#this-year-vs-target").show();
								$("#this-year-legend").show();
								$("#target-legend").show();
							}
							else if ($(this).val() == "this-year-cml-vs-target-cml-option") {
								$("#this-year-cml-vs-target-cml").show();
								$("#this-year-cml-legend").show();
								$("#target-cml-legend").show();
							}
							else if ($(this).val() == "this-year-vs-last-year-option") {
								$("#this-year-vs-last-year").show();
								$("#this-year-legend").show();
								$("#last-year-legend").show();
								$("#before-year-legend").show();
							}
							else if ($(this).val() == "this-year-cml-vs-last-year-cml-option") {
								$("#this-year-cml-vs-last-year-cml").show();
								$("#this-year-cml-legend").show();
								$("#last-year-cml-legend").show();
								$("#before-year-cml-legend").show();
							}
							else if ($(this).val() == "quantity-this-year-vs-last-year-option") {
								$("#quantity-this-year-vs-last-year").show();
								$("#this-year-legend").show();
								$("#last-year-legend").show();
								$("#before-year-legend").show();
							}
							else if ($(this).val() == "quantity-this-year-cml-vs-target-cml-option") {
								$("#quantity-this-year-cml-vs-target-cml").show();
								$("#this-year-cml-legend").show();
								$("#last-year-cml-legend").show();
								$("#before-year-cml-legend").show();
							}
						});
					}
				});
				async function ThreeYearChartCanvas(data) {
					let pram = {
						targetDataForCurrentYear,
						yearstartmonth,
						cumulativeTargetDataForCurrentYear,
						cumulativeYear0ChartValues,
						cumulativeYear1ChartValues,
						cumulativeYear2ChartValues,
						cumulativeQuantityYear0ChartValues,
						cumulativeQuantityYear1ChartValues,
						cumulativeQuantityYear2ChartValues,
						quantityYear0ChartValues,
						quantityYear1ChartValues,
						quantityYear2ChartValues,
						year0ChartValues,
						year1ChartValues,
						year2ChartValues,
						year0,
						year1,
						year2
					} = data;
					$("#threeyearsaleschart").addClass("active");
					$("#threeyearsaleschart .chart").html(`
							<canvas id="this-year-vs-target" style="height: 250px;"></canvas>
							<canvas id="this-year-cml-vs-target-cml" style="height: 250px;"></canvas>
							<canvas id="this-year-vs-last-year" style="height: 250px;"></canvas>
							<canvas id="this-year-cml-vs-last-year-cml" style="height: 250px;"></canvas>
							<canvas id="quantity-this-year-vs-last-year" style="height: 250px;"></canvas>
							<canvas id="quantity-this-year-cml-vs-target-cml" style="height: 250px;"></canvas>`);
					await loadingThreeYearsChart(pram);

					$("#this-year-cml-vs-target-cml").hide();
					$("#this-year-vs-last-year").hide();
					$("#this-year-cml-vs-last-year-cml").hide();
					$("#quantity-this-year-vs-last-year").hide();
					$("#quantity-this-year-cml-vs-target-cml").hide();
					$("#this-year-vs-target").parent().find(".loading_spinner").hide();
					if ($("#threeyearsalestable").hasClass("active")) {
						$("#threeyearsaleschart").removeClass("active");
					}
				};
			</script>

			<?php if ($threeyearsalesanalysispacchart == 'Y') { ?>
				<script>
					$(function () {
						$("#threeyearsalesanalysispactable_nav a").click();
					});
				</script>
			<?php } ?>

</body>

</html>