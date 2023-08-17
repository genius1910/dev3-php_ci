<!-- Content Header (Page header) -->
<?php
$canSeeProjectedSales = canSeeProjectedSales();
$canSeeProjectedSalesYear = canSeeProjectedSalesYear();
$canSeeOrderFulfillment = canSeeOrderFulfillment();

$currency_symbol = $this->config->item("currency_symbol"); ?>
<section class="content-header">
    <h1> Dashboard </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard </li>
    </ol>
</section>
<!-- Main content -->
<section class="content content-dashboard">
    <!-- Info boxes -->
    <div class="row">
        <div class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-6 col-xs-12<?php } else { ?>col-md-6<?php } ?>">
            <?php
			if ($dailysalespc < $G_kpithreshold1)
				$class = "bg-red";
			if ($dailysalespc >= $G_kpithreshold1 and $dailysalespc < $G_kpithreshold2)
				$class = "bg-yellow";
			if ($dailysalespc >= $G_kpithreshold2)
				$class = "bg-green";
			if (empty($G_DailySalesTarget))
				$class = "bg-green";
			?>
            <div class="info-box <?= $class ?>" id="sales-previous-daydrill-report">
                <div
                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                    <i class="fa fa-spinner fa-spin fa-fw" style="
						color: #000;
						scale: 2;
						position: absolute;
						top: 44%;
					"></i>
                </div>
                <a style="color: white;text-decoration: none;" href="<?= base_url() . 'site/daydrillreport'; ?>">
                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-title">Sales for</span>
                        <span class="info-box-number">
                            &pound;0
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 0% !important;"></div>
                        </div>
                        <span class="progress-description">
                            0% of target (&pound;0)
                        </span>
                    </div>
                </a>
            </div>
        </div>
        <!-- /.info-box -->
        <?php
		if ($dailymarginpc < $G_MarginOk)
			$class = "bg-red";
		if ($dailymarginpc >= $G_MarginOk and $dailymarginpc < $G_MarginGood)
			$class = "bg-yellow";
		if ($dailymarginpc >= $G_MarginGood)
			$class = "bg-green";
		if (empty($G_MarginOk) && empty($G_MarginGood)) {
			$class = "bg-green";
		}
		?>
        <?php if (!!$canSeeMargins) { ?>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box <?= $class ?>" id="dashboard-top-card-margin-01">
                <div
                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                    <i class="fa fa-spinner fa-spin fa-fw" style="
							color: #000;
							scale: 2;
							position: absolute;
							top: 44%;
						"></i>
                </div>
                <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-title">Sales for</span>
                    <span class="info-box-number">
                        &pound;0
                    </span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 0% !important;"></div>
                    </div>
                    <span class="progress-description">
                        0% of target (&pound;0)
                    </span>
                </div>
            </div>
        </div>
        <!-- /.col -->
        <?php } ?>
        <!-- fix for small devices only -->
        <div class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-6 col-xs-12<?php } else { ?>col-md-6<?php } ?>">
            <?php
			if ($monthlysalespc < $G_kpithreshold1)
				$class = "bg-red";
			if ($monthlysalespc >= $G_kpithreshold1 and $monthlysalespc < $G_kpithreshold2)
				$class = "bg-yellow";
			if ($monthlysalespc >= $G_kpithreshold2)
				$class = "bg-green";
			if (empty($G_MonthlySalesTarget))
				$class = "bg-green";
			?>
            <div class="info-box <?= $class ?>" id="dashboard-top-card-02">
                <div
                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                    <i class="fa fa-spinner fa-spin fa-fw" style="
						color: #000;
						scale: 2;
						position: absolute;
						top: 44%;
					"></i>
                </div>
                <a style="color: white;text-decoration: none;" href="<?= base_url() . 'site/salesmtdreport'; ?>">
                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-title">Sales MTD</span>
                        <span class="info-box-number">
                            &pound;0
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 0%"></div>
                        </div>
                        <span class="progress-description">
                            0% of target (&pound;0)
                        </span>
                    </div>
                </a>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
        <?php if (!!$canSeeMargins) { ?>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- Colour code the graphic based on margin ok and good values -->
            <?php
				if ($monthlymarginpc < $G_MarginOk)
					$class = "bg-red";
				if ($monthlymarginpc >= $G_MarginOk and $monthlymarginpc < $G_MarginGood)
					$class = "bg-yellow";
				if ($monthlymarginpc >= $G_MarginGood)
					$class = "bg-green";
				if (empty($G_MarginOk) && empty($G_MarginGood)) {
					$class = "bg-green";
				}
				?>
            <div class="info-box <?= $class ?>" id="dashboard-top-card-margin-02">
                <div
                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                    <i class="fa fa-spinner fa-spin fa-fw" style="
							color: #000;
							scale: 2;
							position: absolute;
							top: 44%;
						"></i>
                </div>
                <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-title">Margin MTD</span>
                    <span class="info-box-number">
                        &pound;0
                    </span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 0%"></div>
                    </div>
                    <span class="progress-description">
                        0%
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <?php } ?>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="<?php if (!!$canSeeOMR) { ?>col-md-9<?php } else { ?> col-md-12<?php } ?>">
            <!-- Main left hand side of dashboard -->
            <div class="row">
                <!-- Today's and outstanding orders row -->
                <!------------------------------------------------------------------------------------------------------------------>
                <!-- TODAYS ORDERS - BY TYPE & BY STATUS -->
                <!------------------------------------------------------------------------------------------------------------------>
                <div class="col-md-8">
                    <div class="nav-tabs-custom">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs pull-right">
                            <li class="active" onclick="manage_cookie('salestodaydonutcharts','N')"><a
                                    href="#salestodaydonutcharts" id="goto_salestodaydonutcharts" data-toggle="tab"><i
                                        class="fa fa-pie-chart"></i></a></li>
                            <li onclick="manage_cookie('salestodaydonutcharts','Y')" id="salestodaytables_nav"><a
                                    href="#salestodaytables" data-toggle="tab" aria-expanded="true"><i
                                        class="fa fa-table"></i></a></li>
                            <li class="pull-left header"><i class="fa fa-inbox"></i>Today's Orders</li>
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="tab-pane active" id="salestodaydonutcharts" style="position: relative;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="text-center">
                                            <strong>By Type</strong>
                                        </p>
                                        <div class="chart">
                                            <div class="loading_spinner"
                                                style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                                <i class="fa fa-spinner fa-spin fa-fw" style="
												 	color: #000;
													scale: 2;
													position: absolute;
													top: 44%;
												"></i>
                                            </div>
                                            <canvas id="TodaysOrdersByType"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="chart-legend clearfix" id="todaysordersbytypelegend">
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-center">
                                            <strong>By Status</strong>
                                        </p>
                                        <div class="chart">
                                            <div class="loading_spinner"
                                                style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                                <i class="fa fa-spinner fa-spin fa-fw" style="
													color: #000;
													scale: 2;
													position: absolute;
													top: 44%;
												"></i>
                                            </div>
                                            <canvas id="TodaysOrdersByStatus"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="chart-legend clearfix" id="todaysordersbystatuslegend">
                                        </ul>
                                    </div>
                                </div> <!-- row -->
                            </div>
                            <div class="tab-pane" id="salestodaytables" style="position: relative;">
                                <div class="row">
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            <div class="loading_spinner"
                                                style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                                <i class="fa fa-spinner fa-spin fa-fw" style="
													color: #000;
													scale: 2;
													position: absolute;
													top: 44%;
												"></i>
                                            </div>
                                            <table class="table table-striped" id="todaysordersbytypetable">
                                                <tr>
                                                    <th>By Type</th>
                                                    <th>Description</th>
                                                    <th style="text-align: right">Value</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th style="text-align: right"></th>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="loading_spinner"
                                                style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                                <i class="fa fa-spinner fa-spin fa-fw" style="
													color: #000;
													scale: 2;
													position: absolute;
													top: 44%;
												"></i>
                                            </div>
                                            <table class="table table-striped" id="todaysordersbystatustable">
                                                <tr>
                                                    <th>By Status</th>
                                                    <th>Description</th>
                                                    <th style="text-align: right">Value</th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div> <!-- class="box-body" -->
                                </div> <!-- class="row" -->
                            </div> <!-- class="tab-pane" -->
                        </div> <!-- class="tab-content no-padding" -->
                    </div> <!-- class="nav-tabs-custom" -->
                </div> <!-- col-md-8 -->
                <!------------------------------------------------------------------------------------------------------------------>
                <!-- OUTSTANDING ORDERS BY STATUS DONUT CHART -->
                <!------------------------------------------------------------------------------------------------------------------>
                <div class="col-md-4">
                    <div class="nav-tabs-custom">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs pull-right">
                            <li class="active"><a href="#outstandingordersdonutchart" data-toggle="tab"
                                    id="goto_outstandingordersdonutchart"
                                    onclick="manage_cookie('outstandingordersdonutchart','N')"><i
                                        class="fa fa-pie-chart"></i></a></li>
                            <li onclick="manage_cookie('outstandingordersdonutchart','Y')"
                                id="outstandingorderstable_nav"> <a href="#outstandingorderstable" data-toggle="tab"><i
                                        class="fa fa-table"></i></a></li>
                            <li class="pull-left header" style="padding:0 4px;"><i
                                    class="fa fa-hourglass-end"></i>Outstanding Orders
                            </li>
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="tab-pane active" id="outstandingordersdonutchart" style="position: relative;">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-center">
                                            <strong>By Status</strong>
                                        </p>
                                        <div class="chart">
                                            <div class="loading_spinner"
                                                style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                                <i class="fa fa-spinner fa-spin fa-fw" style="
													color: #000;
													scale: 2;
													position: absolute;
													top: 44%;
												"></i>
                                            </div>
                                            <canvas id="OutstandingOrdersByStatus"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="chart-legend clearfix" id="outstandingordersbystatuslegend">
                                        </ul>
                                    </div>
                                </div>
                            </div> <!-- class= "tab-pane" -->
                            <div class="tab-pane" id="outstandingorderstable" style="position: relative;">
                                <div class="box-body">
                                    <div class="loading_spinner"
                                        style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 20px);height: calc(100% - 15px);text-align: center;">
                                        <i class="fa fa-spinner fa-spin fa-fw" style="
											color: #000;
											scale: 2;
											position: absolute;
											top: 44%;
										"></i>
                                    </div>
                                    <table class="table table-striped" id="outstandingordersbystatustable">
                                        <tr>
                                            <th>By Status</th>
                                            <th>Description</th>
                                            <th style="text-align: right">Value</th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </div> <!-- class="box-body" -->
                            </div> <!-- class="tab-pane" -->
                        </div> <!-- tab-content -->
                    </div> <!-- nav-tabs-custom -->
                </div> <!-- col-md-4 -->
            </div> <!-- Today's and outstanding orders row -->
            <!------------------------------------------------------------------------------------------------------------------>
            <!-- 3 YEAR SALES CHART -->
            <!------------------------------------------------------------------------------------------------------------------>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="active" onclick="manage_cookie('threeyearsaleschart','N')" id="threeyearsaleschart_nav">
                        <a href="#threeyearsaleschart" id="goto_threeyearsaleschart" data-toggle="tab"><i
                                class="fa fa-line-chart"></i></a>
                    </li>
                    <li onclick="manage_cookie('threeyearsaleschart','Y')" id="threeyearsalestable_nav"><a
                            href="#threeyearsalestable" data-toggle="tab"><i class="fa fa-table"></i></a></li>
                    <li class="pull-left header"><i class="fa fa-shopping-cart"></i>Sales</li>
                </ul>
                <div class="tab-content no-padding">
                    <div class="tab-pane active" id="threeyearsaleschart" style="position: relative;">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="chart" style="min-height: 180px;">
                                    <div class="loading_spinner"
                                        style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                        <i class="fa fa-spinner fa-spin fa-fw" style="
                                          color: #000;
                                          scale: 2;
                                          position: absolute;
                                          top: 44%;
                                        "></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <ul class="chart-legend clearfix">
                                    <li id="this-year-legend"><i class="fa fa-circle-o text-navy"></i>
                                        <?= $year0; ?>
                                    </li>
                                    <li id="this-year-cml-legend" style="display:none;"><i
                                            class="fa fa-circle-o text-navy"></i>
                                        <?= $year0; ?> Cml.
                                    </li>
                                    <li id="last-year-legend" style="display:none;"><i
                                            class="fa fa-circle-o text-light-blue"></i>
                                        <?= $year1; ?>
                                    </li>
                                    <li id="last-year-cml-legend" style="display:none;"><i
                                            class="fa fa-circle-o text-light-blue"></i>
                                        <?= $year1; ?> Cml.
                                    </li>
                                    <li id="before-year-legend" style="display:none;"><i
                                            class="fa fa-circle-o text-gray"></i>
                                        <?= $year2; ?>
                                    </li>
                                    <li id="before-year-cml-legend" style="display:none;"><i
                                            class="fa fa-circle-o text-gray"></i>
                                        <?= $year2; ?> Cml.
                                    </li>
                                    <li id="target-legend"><i class="fa fa-circle-o text-light-blue"></i>Target</li>
                                    <li id="target-cml-legend" style="display:none;"><i
                                            class="fa fa-circle-o text-light-blue"></i>Target
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
                                                <option value="this-year-cml-vs-last-year-cml-option">Cumulative
                                                </option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- class="tab-pane" -->
                    <div class="tab-pane" id="threeyearsalestable" style="position: relative;overflow: hidden;">
                        <?php //print_r($salesTargetForLastThreeYear); ?>
                        <div class="loading_spinner"
                            style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;text-align: center;">
                            <i class="fa fa-spinner fa-spin fa-fw" style="
								color: #000;
								scale: 2;
								position: absolute;
								top: 44%;
							"></i>
                        </div>
                        <table class="table table-striped" id="dashboardthreeyearsalestable">
                            <?php
							$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
							for ($i = 1; $i < $yearstartmonth; $i++) {
								$tmp = array_shift($months);
								array_push($months, $tmp);
							}
							?>

                            <tr class="border-header">
                                <th>Year</th>
                                <?php foreach ($months as $month) { ?>
                                <th>
                                    <?php echo $month; ?>
                                </th>
                                <?php } ?>
                                <th>Total</th>
                            </tr>
                            <tr></tr>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <?php // if ($_SERVER['REMOTE_ADDR']=='115.112.129.194'){?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">PAC Sales vs Target</h3>
                        </div>
                        <div class="box-body">
                            <div class="">
                                <div class="overlay h2 m-0 loading_spinner"
                                    style="margin: 0px;display: block;position: absolute;">
                                    <i class="fa fa-spinner fa-spin fa-fw"></i>
                                </div>
                                <table class="table table-striped" id="pac_sale_vs_target">
                                    <thead>
                                        <tr>
                                            <th>PAC</th>
                                            <th>Description</th>
                                            <th>Sales MTD</th>
                                            <th>Target</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <a href="<?= base_url(); ?>/products/pacsalestargetdata" class="btn btn-info">See All</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php // } ?>

            <div class="row">
                <?php
				/* ------------------------------------------------------------------------------------------------------------------
						---- QUOTATIONS x PAC1 ----
						------------------------------------------------------------------------------------------------------------------- */
				?>
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Quotations x PAC1</h3>
                        </div>
                        <div class="box-body" style="max-height: 350px; overflow-y: auto;">
                            <div class="overlay h2 m-0 loading_spinner"
                                style="margin: 0px;display: block;position: absolute;">
                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                            </div>
                            <table class="table table-striped" id="quotations_x_pac1">
                                <thead>
                                    <tr>
                                        <th>PAC 1</th>
                                        <th>Description</th>
                                        <th>Value this Month</th>
                                        <th>Qty this Month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php
				/* ------------------------------------------------------------------------------------------------------------------
						---- SALES PIPELINE ----
						------------------------------------------------------------------------------------------------------------------- */
				?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sales Pipeline</h3>
                        </div>
                        <div class="box-body" style="max-height: 350px; overflow-y: auto;">
                            <div class="overlay h2 m-0 loading_spinner"
                                style="margin: 0px;display: block;position: absolute;">
                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                            </div>
                            <table class="table table-striped" id='sales_pipeline'>
                                <thead>
                                    <tr>
                                        <th>Stage</th>
                                        <th class="text-right">Value</th>
                                        <th class="text-right">%</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!------------------------------------------------------------------------------------------------------------------>
                <!-- PROJECTED SALES MONTH CHART -->
                <!------------------------------------------------------------------------------------------------------------------>
                <?php if ($canSeeProjectedSales): ?>
                <?php echo ($canSeeProjectedSalesYear) ? '<div class="col-md-6">' : '<div class="col-md-12">'; ?>
                <!-- Colour code the graphic based on kpi thresholds -->
                <?php
					$currmonth = date('n', time());
					$fcounter = $currmonth + 1;
					if ($projmonthsalespc < $G_kpithreshold1)
						$class = "box-danger";
					if ($projmonthsalespc >= $G_kpithreshold1 and $projmonthsalespc < $G_kpithreshold2)
						$class = "box-warning";
					if ($projmonthsalespc >= $G_kpithreshold2)
						$class = "box-success";
					if (empty($projmonthsalespc)) {
						$class = "box-success";
					}
					?>
                <div class="box <?= $class ?>">
                    <!-- Colour code the graphic based on kpi thresholds -->
                    <?php
						if ($projmonthsalespc < $G_kpithreshold1)
							$class = "bg-red";
						if ($projmonthsalespc >= $G_kpithreshold1 and $projmonthsalespc < $G_kpithreshold2)
							$class = "bg-yellow";
						if ($projmonthsalespc >= $G_kpithreshold2)
							$class = "bg-green";
						if (empty($projmonthsalespc)) {
							$class = "bg-green";
						}
						?>
                    <div class="box-header with-border <?= $class ?>">
                        <i class="fa fa-line-chart"></i>
                        <h3 class="box-title">Projected Sales</h3>
                        <div class="pull-right">
                            <i stat="prev" style="cursor:pointer;" id="left-month-circle"
                                class="fa fa-arrow-circle-o-left "></i>
                            <span class="box-title">(<span id="month-year-representer">
                                    <?= date('M Y', time()); ?>
                                </span>)</span>
                            <i stat="next" style="cursor:pointer;display: none;" id="right-month-circle"
                                class=" fa fa-arrow-circle-o-right"></i>
                            <input type="hidden" value="<?= date('Y-m-01', time()); ?>"
                                id="curr-datemonth-indicator2" />
                        </div>
                        <div class="box-tools pull-right">
                            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                        </div>
                    </div>
                    <div id="spinner-cust" class="overlay h2 m-0 loading_spinner"
                        style="margin: 0px;display: block;position: absolute; display:none;">
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                    </div>
                    <div class="box-body" id='project_sales_month_chart' style="min-height:250px">
                        <div class="overlay h2 m-0 loading_spinner"
                            style="margin: 0px;display: block;position: absolute;">
                            <i class="fa fa-spinner fa-spin fa-fw"></i>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <?php endif; ?>
            <!------------------------------------------------------------------------------------------------------------------>
            <!-- PROJECTED SALES YEAR CHART -->
            <!------------------------------------------------------------------------------------------------------------------>
            <?php if ($canSeeProjectedSalesYear): ?>
            <?php echo ($canSeeProjectedSales) ? '<div class="col-md-6">' : '<div class="col-md-12">'; ?>
            <!-- Colour code the graphic based on kpi thresholds -->
            <?php
				if ($projyearsalespc < $G_kpithreshold1)
					$class = "box-danger";
				if ($projyearsalespc >= $G_kpithreshold1 and $projyearsalespc < $G_kpithreshold2)
					$class = "box-warning";
				if ($projyearsalespc >= $G_kpithreshold2)
					$class = "box-success";
				if (empty($projyearsalespc)) {
					$class = "box-success";
				}
				?>
            <div class="box <?= $class ?>">
                <!-- Colour code the graphic based on kpi thresholds -->
                <?php
					if ($projyearsalespc < $G_kpithreshold1)
						$class = "bg-red";
					if ($projyearsalespc >= $G_kpithreshold1 and $projyearsalespc < $G_kpithreshold2)
						$class = "bg-yellow";
					if ($projyearsalespc >= $G_kpithreshold2)
						$class = "bg-green";
					if (empty($projyearsalespc)) {
						$class = "bg-green";
					}
					?>
                <div class="box-header with-border <?= $class ?>">
                    <i class="fa fa-line-chart"></i>
                    <h3 class="box-title">Projected Sales - Year</h3>
                    <div class="box-tools pull-right">
                        <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                    </div>
                </div>
                <div class="box-body" id="project_sales_year_chart" style="min-height:250px">
                    <div class="overlay h2 m-0 loading_spinner" style="margin: 0px;display: block;position: absolute;">
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <?php endif; ?>
    </div>
    <?php if ($canSeeOrderFulfillment): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck"></i>
                    <h3 class="box-title">Order Fulfilment - Same Day (%)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="OrderFulfillSameDay" style="height:250px"></canvas>
                        <div class="overlay h2 m-0 loading_spinner"
                            style="margin: 0px;display: block;position: absolute;">
                            <i class="fa fa-spinner fa-spin fa-fw"></i>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.box -->
    </div>
    <?php endif; ?>
    </div> <!-- Main left hand side of dashboard col-md-9 -->
    <?php if (!!$canSeeOMR) { ?>
    <div class="col-md-3">
        <!-- Right hand column of dashboard -->
        <!------------------------------------------------------------------------------------------------------------------>
        <!-- HELD IN OMR -->
        <!------------------------------------------------------------------------------------------------------------------>
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-stop"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Held In OMR</span>
                <span class="info-box-number">SL -
                    <?= $currency_symbol; ?>
                    <?= $HeldInOMRSL ?>
                </span>
                <span class="info-box-number">CR -
                    <?= $currency_symbol; ?>
                    <?= $HeldInOMRCR ?>
                </span>
            </div>
        </div>
        <!------------------------------------------------------------------------------------------------------------------>
        <!-- WAITING POSTING -->
        <!------------------------------------------------------------------------------------------------------------------>
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="ion ion-ios-pause"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Waiting Posting</span>
                <span class="info-box-number">SL -
                    <?= $currency_symbol; ?>
                    <?= $WaitingPostingSL ?>
                </span>
                <span class="info-box-number">CR -
                    <?= $currency_symbol; ?>
                    <?= $WaitingPostingCR ?>
                </span>
            </div>
        </div>
        <!------------------------------------------------------------------------------------------------------------------>
        <!-- POSTED -->
        <!------------------------------------------------------------------------------------------------------------------>
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="ion ion-ios-play"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Posted</span>
                <span class="info-box-number">SL -
                    <?= $currency_symbol; ?>
                    <?= $PostedSL ?>
                </span>
                <span class="info-box-number">CR -
                    <?= $currency_symbol; ?>
                    <?= $PostedCR ?>
                </span>
            </div>
        </div>
    </div> <!-- col-md-3 -->
    <?php } ?>
    </div> <!-- row -->
</section>
<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <div class="col-md-8">
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
<script src="<?= $this->config->item('base_folder'); ?>public/js/common.js"></script>
<script>
function manage_cookie(cookie_name, cookie_value) {
    $.ajax({
        type: "POST",
        dataType: "html",
        url: "<?= base_url(); ?>/site/manage_cookie",
        data: {
            cookie_name: cookie_name,
            cookie_value: cookie_value
        },
        success: function(data) {
            //alert(data);
        }
    });
}
</script>

<script type="text/javascript">
$(document).ready(function() {
    $.ajax({
        url: '<?= base_url() ?>site/getSalesDailyReport',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            // Display Dashboard Top card 01
            salesPreviousDaydrillReport({
                dailysalespc,
                G_kpithreshold1,
                G_kpithreshold2,
                lastsalesdate,
                currency_symbol,
                dailysales,
                G_DailySalesTarget
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getSalesDailyMargin',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            // Display Dashboard Top card 01
            dashboardTopCardMargin01({
                lastsalesdate,
                currency_symbol,
                dailymargin,
                dailymarginpc,
                G_MarginOk,
                G_MarginGood
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getSalesMontlyReport',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            // Display Dashboard Top card 02
            dashboardTopCard02({
                monthlysalespc,
                G_kpithreshold1,
                G_kpithreshold2,
                G_MonthlySalesTarget
            } = data)
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getSalesMontlyMargin',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            // Display Dashboard Top card margin 02
            dashboardTopCardMargin02({
                monthlymarginpc,
                G_MarginOk,
                G_MarginGood,
                currency_symbol,
                monthlymargin
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getTodayOrdersType',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //Today Orders By Type Table
            todayOrdersByTypeTable({
                currency_symbol,
                todayOrdersType
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getOrdersByStatusTable',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //Today Orders By Status Table 
            todayOrdersByStatusTable({
                todayOrdersStatus,
                currency_symbol
            } = data)
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getOutByStatus',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //Outstanding Orders By Status 
            OutstandingOrdersByStatus({
                outOrders,
                currency_symbol
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getTwoYearsTargetTable',
        method: 'post',
        dataType: 'json',
        success: function(data) {
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
            $('#dashboardthreeyearsalestable').append(resp);
            $('#dashboardthreeyearsalestable ').prev(".loading_spinner").remove();
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getPacSaleVsTarget',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //PAC SALES VS TARGET 
            pacSaleVsTarget({
                pac1salestarget,
                getSalesTotalMonthWise
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getQuotationsXPac1',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //QUOTATIONS X PAC1
            quotationsXPac1({
                currentMonthPac1QuoteConversions
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getSalesPipeline',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //SALES PIPELINE
            salesPipeline({
                salesPipelineStages
            } = data);
        }
    });

    let monthChart = 0;
    $.ajax({
        url: '<?= base_url() ?>site/getSalesMonthChart',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //PROJECT SALES MONTH CHART 
            monthChart = projectSalesMonthChart({
                projmonthsalespc,
                ProjectedSalesMonthGraphLabel,
                ProjectedSalesMonthGraphActual,
                ProjectedSalesMonthGraphTarget,
                ProjectedSalesMonthGraphProjected
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/getSalesYearChart',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //PROJECT SALES YEAR CHART
            projectSalesYearChart({
                yearstartmonth,
                projyearsalespc,
                ProjectedSalesYearGraphActual,
                ProjectedSalesYearGraphTarget,
                ProjectedSalesYearGraphProjected
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/OrderFulfillSameDay',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            // ORDER FULFILMENT - ORDERS ENTERED TODAY AND AT WDL OR COM
            OrderFulfillSameDay({
                OrdersFulfilledGraphLabel,
                OrdersFulfilledGraph,
                todaysordersbytypedata
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/OutStatusCanvas',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //- OUTSTANDING ORDERS BY STATUS DONUT CHART
            console.log("!2312312#", data);
            OutstandingOrdersByStatusCanvas({
                outstandingordersbystatusdata
            } = data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>site/TodaysOrdersCanvas',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            //Todays Orders ByType Canvas
            TodaysOrdersCanvas({
                todaysordersbytypedata,
                todaysordersbystatusdata
            } = data);
        }
    });

    let count = 0;
    if (count == 0) {
        $.ajax({
            url: '<?= base_url() ?>site/ThreeYearChartCanvas',
            method: 'post',
            dataType: 'json',
            success: function(data) {
                //This Year Vs Target Chart
                ThreeYearChartCanvas({
                    targetDataForCurrentYear,
                    yearstartmonth,
                    cumulativeYear0ChartValues,
                    cumulativeTargetDataForCurrentYear,
                    cumulativeYear1ChartValues,
                    cumulativeYear2ChartValues,
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
    }

    var acounter = 0;
    $('#left-month-circle,#right-month-circle').click(function() {
        var stat = $(this).attr('stat');
        var currDatemonthIndicator = $('#curr-datemonth-indicator2').val();
        $.ajax({
            url: base_url + 'site/getprojectedmonthdata/' + stat + '/' + currDatemonthIndicator,
            type: "POST",
            dataType: 'json',
            success: function(response) {
                $('#spinner-cust').hide();
                if (response.ProjectedSalesMonthGraphActual) {
                    monthChart.clear().destroy();
                    var ProjectedSalesForMonthChartCanvas2 = $(
                        "#ProjectedSalesForMonthChart").get(0).getContext(
                        "2d");
                    var ProjectedSalesForMonthChart2 = new Chart(
                        ProjectedSalesForMonthChartCanvas2);
                    var ProjectedSalesForMonthChartData2 = {
                        labels: response.ProjectedSalesMonthGraphLabel.split(','),
                        datasets: [{
                                label: "Actual",
                                fillColor: "#d2d6de", // Gray
                                strokeColor: "#d2d6de",
                                pointColor: "#d2d6de",
                                pointStrokeColor: "#d2d6de",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                                data: response.ProjectedSalesMonthGraphActual.split(
                                    ',')
                            },
                            {
                                label: "Target",
                                fillColor: "#000000",
                                strokeColor: "#000000",
                                pointColor: "#000000",
                                pointStrokeColor: "#000000",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(60,141,188,1)",
                                data: response.ProjectedSalesMonthGraphTarget.split(
                                    ',')
                            },
                            {
                                label: "Projected",
                                fillColor: response.fillColor,
                                strokeColor: response.strokeColor,
                                pointColor: response.pointColor,
                                pointStrokeColor: response.pointStrokeColor,
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(60,141,188,1)",
                                data: response.ProjectedSalesMonthGraphProjected
                                    .split(',')
                            }

                        ]
                    };
                    var ProjectedSalesForMonthChartOptions2 = {
                        showScale: true,
                        scaleShowGridLines: false,
                        scaleGridLineColor: "rgba(0,0,0,.05)",
                        scaleGridLineWidth: 1,
                        scaleShowHorizontalLines: true,
                        scaleShowVerticalLines: true,
                        bezierCurve: true,
                        bezierCurveTension: 0.3,
                        pointDot: false,
                        pointDotRadius: 4,
                        pointDotStrokeWidth: 1,
                        pointHitDetectionRadius: 20,
                        datasetStroke: true,
                        datasetStrokeWidth: 2,
                        datasetFill: false,
                        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                        maintainAspectRatio: true,
                        responsive: true
                    };
                    if (acounter > 0) {
                        upChart.clear().destroy();
                    }
                    upChart = ProjectedSalesForMonthChart2.Line(
                        ProjectedSalesForMonthChartData2,
                        ProjectedSalesForMonthChartOptions2);
                    $('#month-year-representer').html(response.monthyearindicator);
                    $('#curr-datemonth-indicator2').val(response
                        .currdatemonthindicatorCust);
                    // upChart.update();
                    if (response.disablenext == 1) {
                        $('#right-month-circle').hide();
                    } else {
                        $('#right-month-circle').show();
                    }
                    $('#month-projected-color').removeClass().addClass('fa fa-circle-o ' +
                        response.projColor);
                    acounter++;
                }
            },
            beforeSend: function() {
                $('#spinner-cust').show();
            }
        });
    });

});

function salesPreviousDaydrillReport(data) {
    const {
        dailysalespc,
        G_kpithreshold1,
        G_kpithreshold2,
        lastsalesdate,
        currency_symbol,
        dailysales,
        G_DailySalesTarget
    } = data;
    var cls = "bg-red";
    if (dailysalespc < G_kpithreshold1)
        cls = "bg-red";
    if (dailysalespc >= G_kpithreshold1 && dailysalespc < G_kpithreshold2)
        cls = "bg-yellow";
    if (dailysalespc >= G_kpithreshold2)
        cls = "bg-green";
    if (!G_kpithreshold1)
        cls = "bg-green";
    $("#sales-previous-daydrill-report").html('\
			<a style="color: white;text-decoration: none;" href="' + base_url + 'site/daydrillreport">\
				<span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>\
				<div class="info-box-content">\
					<span class="info-box-title">Sales for\
						' + lastsalesdate + '\
					</span>\
					<span class="info-box-number">\
						' + currency_symbol + number_format(dailysales) + '\
					</span>\
					<div class="progress">\
						<div class="progress-bar" style="width: ' + Math.round(Number(dailysalespc)) + '% !important;"></div>\
					</div>\
					<span class="progress-description">\
						' + number_format(dailysalespc, 2) + '% of target (' + currency_symbol + number_format(G_DailySalesTarget) + ')\
					</span>\
				</div>\
			</a>\
		');
}

function dashboardTopCardMargin01(data) {
    const {
        lastsalesdate,
        currency_symbol,
        dailymargin,
        dailymarginpc,
        G_MarginOk,
        G_MarginGood
    } = data;
    if (dailymarginpc < G_MarginOk)
        cls = "bg-red";
    if (dailymarginpc >= G_MarginOk && dailymarginpc < G_MarginGood)
        cls = "bg-yellow";
    if (dailymarginpc >= G_MarginGood)
        cls = "bg-green";
    if (!G_MarginOk && !G_MarginGood)
        cls = "bg-green";
    $("#dashboard-top-card-margin-01").html('\
			<div class="info-box ' + cls + '" id="dashboard-top-card-margin-01">\
				<span class="info-box-icon"><i class="fa fa-line-chart"></i></span>\
				<div class="info-box-content">\
					<span class="info-box-title">Margin for\
						' + lastsalesdate + '\
					</span>\
					<span class="info-box-number">\
						' + currency_symbol + '\
						' + number_format(dailymargin) + '\
					</span>\
					<div class="progress">\
						<div class="progress-bar" style="width: ' + Math.round(Number(dailymarginpc)) + '%"></div>\
					</div>\
					<span class="progress-description">\
					' + number_format(dailymarginpc) + '%\
					</span>\
				</div>\
			</div>\
		');
}

function dashboardTopCard02(data) {
    const {
        monthlysalespc,
        G_kpithreshold1,
        G_kpithreshold2,
        G_MonthlySalesTarget,
        currency_symbol,
        monthlysales,

    } = data
    if (monthlysalespc < G_kpithreshold1)
        cls = "bg-red";
    if (monthlysalespc >= G_kpithreshold1 && monthlysalespc < G_kpithreshold2)
        cls = "bg-yellow";
    if (monthlysalespc >= G_kpithreshold2)
        cls = "bg-green";
    if (!G_MonthlySalesTarget)
        cls = "bg-green";
    $("#dashboard-top-card-02").html('\
			<div class="info-box ' + cls + '" id="dashboard-top-card-02">\
				<a style="color: white;text-decoration: none;" href="' + base_url + 'site/salesmtdreport">\
					<span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>\
					<div class="info-box-content">\
						<span class="info-box-title">Sales MTD</span>\
						<span class="info-box-number">\
							' + currency_symbol + number_format(monthlysales) + '\
						</span>\
						<div class="progress">\
							<div class="progress-bar" style="width: ' + Math.round(Number(monthlysalespc)) + '% !important;"></div>\
						</div>\
						<span class="progress-description">\
							' + number_format(monthlysalespc, 2) + '% of target (' + currency_symbol + number_format(G_MonthlySalesTarget) + ')\
						</span>\
					</div>\
				</a>\
			</div>\
		');
}

function dashboardTopCardMargin02(data) {
    const {
        monthlymarginpc,
        G_MarginOk,
        G_MarginGood,
        currency_symbol,
        monthlymargin
    } = data;
    if (monthlymarginpc < G_MarginOk)
        cls = "bg-red";
    if (monthlymarginpc >= G_MarginOk && monthlymarginpc < G_MarginGood)
        cls = "bg-yellow";
    if (monthlymarginpc >= G_MarginGood)
        cls = "bg-green";
    if (!G_MarginOk && !G_MarginGood)
        cls = "bg-green";

    $("#dashboard-top-card-margin-02").html('\
			<div id="dashboard-top-card-margin-02" class="info-box ' + cls + '">\
				<span class="info-box-icon"><i class="fa fa-line-chart"></i></span>\
				<div class="info-box-content">\
					<span class="info-box-title">Margin MTD</span>\
					<span class="info-box-number">\
						' + currency_symbol + '\
						' + number_format(monthlymargin) + '\
					</span>\
					<div class="progress">\
						<div class="progress-bar" style="width: ' + Math.round(Number(monthlymarginpc)) + '%"></div>\
					</div>\
					<span class="progress-description">\
					' + number_format(monthlymarginpc) + '%\
					</span>\
				</div>\
			</div>\
		');
}

function todayOrdersByTypeTable(data) {
    const {
        currency_symbol,
        todayOrdersType
    } = data;

    const BIColour = "#3c8dbc"; // Book Ins  		Light Blue
    const BOColour = "#f39c12"; // Book Outs  		Yellow
    const BTColour = "#001f3f"; // Branch Transfers	Navy
    const CRColour = "#dd4b39"; // Credits  		Red
    const DNColour = "#39cccc"; // Debit Notes  	Teal
    const QTColour = "#00c0ef"; // Quotations  		Aqua
    const SLColour = "#00a65a"; // Orders  			Green
    const WOColour = "#d2d6de"; // Works Orders  	Gray
    const RWColour = "#f44295";
    const TCColour = "#7a1919";
    const THColour = "#4f5960";

    // Assign legend colours to order types

    const BITextColour = "text-light-blue"; // Book Ins  		Light Blue
    const BOTextColour = "text-yellow"; // Book Outs  		Yellow
    const BTTextColour = "text-navy"; // Branch Transfers	Navy
    const CRTextColour = "text-red"; // Credits  		Red
    const DNTextColour = "text-teal"; // Debit Notes  	Teal
    const QTTextColour = "text-aqua"; // Quotations  		Aqua
    const SLTextColour = "text-green"; // Orders  			Green
    const WOTextColour = "text-gray"; // Works Orders  	Gray
    const RWTextColour = "text-rwcolor";
    const TCTextColour = "text-tccolor";
    const THTextColour = "text-thcolor";
    let todaysordersbytypedata = "[";

    let i = 1;
    let tmp_total = 0;
    let todaysordersbytypetable = '<tr>\
			<th>By Type</th>\
			<th>Description</th>\
			<th style="text-align: right">Value</th>\
		</tr>';
    let todaysordersbytypelegend = "";
    todayOrdersType.forEach(today => {
        identifier = today['identifier'];
        value = today['actualvalue1'];
        // The order type is the last two characters of the identifier
        ordtype = identifier.substr(10, 2)
        if (value != 0) {
            tmp_total += Number(value);

            // Set the colour, which is the order type followed by"Colour"
            switch (ordtype) {
                case "BI":
                    colour = BIColour;
                    textcolour = BITextColour;
                    description = "Book Ins";
                    break;
                case "BO":
                    colour = BOColour;
                    textcolour = BOTextColour;
                    description = "Book Outs";
                    break;
                case "BT":
                    colour = BTColour;
                    textcolour = BTTextColour;
                    description = "Branch Transfers";
                    break;
                case "CR":
                    colour = CRColour;
                    textcolour = CRTextColour;
                    description = "Credit Notes";
                    break;
                case "DN":
                    colour = DNColour;
                    textcolour = DNTextColour;
                    description = "Debit Notes";
                    break;
                case "QT":
                    colour = QTColour;
                    textcolour = QTTextColour;
                    description = "Quotations";
                    break;
                case "SL":
                    colour = SLColour;
                    textcolour = SLTextColour;
                    description = "Sales Orders";
                    break;
                case "WO":
                    colour = WOColour;
                    textcolour = WOTextColour;
                    description = "Works Orders";
                    break;
                case "RW":
                    colour = RWColour;
                    textcolour = RWTextColour;
                    description = "Repairs & Warranty";
                    break;
                case "TC":
                    colour = TCColour;
                    textcolour = TCTextColour;
                    description = "Plant Hire Credit Note";
                    break;
                case "TH":
                    colour = THColour;
                    textcolour = THTextColour;
                    description = "Plant Hire Order";
                    break;
            }

            // The comma only comes in after the first set

            if (i != 1) {
                todaysordersbytypedata += ",";
            }

            // Build the data string for the pie chart data
            todaysordersbytypedata += "{value:" + value + ",color:'" + colour + "',highlight:'" + colour +
                "',label:'" +
                ordtype + "'}";

            // Build the string for the legend
            typeLink = "<?php echo site_url("site/todaysorder/"); ?>/" + ordtype + "/type";
            todaysordersbytypelegend += "<li><a style='text-decoration:none;color:#333;' href='" + typeLink +
                "'><i class='fa fa-circle-o " + textcolour + "'></i> " + ordtype + "</a></li>";
            // Build the string for the table
            todaysordersbytypetable += "<tr><td><a style='text-decoration:none;color:#333;' href='" + typeLink +
                "'>" +
                ordtype + "</a></td><td><a style='text-decoration:none;color:#333;' href='" + typeLink + "'>" +
                description + "</a></td><td align='right'><a style='text-decoration:none;color:#333;' href='" +
                typeLink +
                "'>" + currency_symbol + number_format(value, 2) + "</a></td></tr>";

            i++;
        }

    });
    // Only interested in graphing order types that have a value
    todaysordersbytypetable += "<tr><th>&nbsp</th><th>Total</th><th style='text-align: right'>" + currency_symbol +
        number_format(tmp_total, 2) + "</th></tr>";
    todaysordersbytypedata += "]";
    $("#todaysordersbytypetable").html(todaysordersbytypetable);
    $("#todaysordersbytypelegend").html(todaysordersbytypelegend);
    $("#todaysordersbytypetable").prev(".loading_spinner").remove();
}

function todayOrdersByStatusTable(data) {
    const {
        todayOrdersStatus,
        currency_symbol
    } = data;
    const ADVColour = "#f012be"; // Waiting advice note	Fuschia
    const COMColour = "#00a65a"; // Completed line		Green
    const CUSColour = "#39cccc"; // Call customer back	Teal
    const HLDColour = "#3d9970"; // Goods on hold		Olive
    const IBTColour = "#d2d6de"; // Inter-branch transfer	Gray
    const KITColour = "#01ff70"; // Process kit list		Lime
    const MEMColour = "#ff851b"; // Memo line			Orange
    const OFFColour = "#605ca8"; // Call off later		Purple
    const PIKColour = "#001f3f"; // Pick note printed	Navy
    const PROColour = "#3c8dbc"; // Process document		Light Blue
    const PURColour = "#dd4b39"; // Purchase order		Red
    const SBOColour = "#f39c12"; // Stock backorder		Yellow
    const WDLColour = "#00c0ef"; // Waiting delivery		Aqua
    const WRKColour = "#d81b60"; // Create works order	Maroon

    // Assign legend colours to order statuses

    const ADVTextColour = "text-fuschia"; // Waiting advice note	Fuschia
    const COMTextColour = "text-green"; // Completed line		Green
    const CUSTextColour = "text-teal"; // Call customer back	Teal
    const HLDTextColour = "text-olive"; // Goods on hold		Olive
    const IBTTextColour = "text-gray"; // Inter-branch transfer	Gray
    const KITTextColour = "text-lime"; // Process kit list		Lime
    const MEMTextColour = "text-orange"; // Memo line			Orange
    const OFFTextColour = "text-purple"; // Call off later		Purple
    const PIKTextColour = "text-navy"; // Pick note printed	Navy
    const PROTextColour = "text-light-blue"; // Process document		Light Blue
    const PURTextColour = "text-red"; // Purchase order		Red
    const SBOTextColour = "text-yellow"; // Stock backorder		Yellow
    const WDLTextColour = "text-aqua"; // Waiting delivery		Aqua
    const WRKTextColour = "text-maroon"; // Cr

    let todaysordersbystatusdata = "[";

    i = 1;
    tmp_total = 0;
    todaysordersbystatustable = '<tr>\
				<th>By Status</th>\
				<th>Description</th>\
				<th style="text-align: right">Value</th>\
			</tr>';
    todaysordersbystatuslegend = "";
    todayOrdersStatus.forEach(today => {
        identifier = today['identifier'];
        value = today['actualvalue1'];

        // The order type is the last three characters of the identifier
        ordstatus = identifier.substr(10, 3);
        // Only interested in graphing order statuses that have a value

        if (value != 0) {
            tmp_total += Number(value);

            // Set the colour, which is the order status followed by"Colour"
            switch (ordstatus) {
                case "ADV":
                    colour = ADVColour;
                    textcolour = ADVTextColour;
                    description = "Waiting Advice Note";
                    break;
                case "COM":
                    colour = COMColour;
                    textcolour = COMTextColour;
                    description = "Completed Line";
                    break;
                case "CUS":
                    colour = CUSColour;
                    textcolour = CUSTextColour;
                    description = "Call Customer Back";
                    break;
                case "HLD":
                    colour = HLDColour;
                    textcolour = HLDTextColour;
                    description = "Goods On Hold";
                    break;
                case "IBT":
                    colour = IBTColour;
                    textcolour = IBTTextColour;
                    description = "Inter-Branch Transfer";
                    break;
                case "KIT":
                    colour = KITColour;
                    textcolour = KITTextColour;
                    description = "Process Kit List";
                    break;
                case "MEM":
                    colour = MEMColour;
                    textcolour = MEMTextColour;
                    description = "Memo Line (Quotations)";
                    break;
                case "OFF":
                    colour = OFFColour;
                    textcolour = OFFTextColour;
                    description = "Call Off Later";
                    break;
                case "PIK":
                    colour = PIKColour;
                    textcolour = PIKTextColour;
                    description = "Pick Note Printed";
                    break;
                case "PRO":
                    colour = PROColour;
                    textcolour = PROTextColour;
                    description = "Process Document";
                    break;
                case "PUR":
                    colour = PURColour;
                    textcolour = PURTextColour;
                    description = "Purchase Order";
                    break;
                case "SBO":
                    colour = SBOColour;
                    textcolour = SBOTextColour;
                    description = "Stock Backorder";
                    break;
                case "WDL":
                    colour = WDLColour;
                    textcolour = WDLTextColour;
                    description = "Waiting Delivery";
                    break;
                case "WRK":
                    colour = WRKColour;
                    textcolour = WRKTextColour;
                    description = "Create Works Order";
                    break;
            }

            // The comma only comes in after the first set

            if (i != 1) {
                todaysordersbystatusdata += ",";
            }

            // Build the data string for the pie chart data
            todaysordersbystatusdata += "{value:" + value + ",color:'" + colour + "',highlight:'" + colour +
                "',label:'" +
                ordstatus + "'}";
            typeLinkStatus = "<?php echo site_url("site/todaysorder/"); ?>/" + ordstatus + "/status";
            // Build the string for the legend
            todaysordersbystatuslegend += "<li><a style='text-decoration:none;color:#333;' href='" +
                typeLinkStatus +
                "'><i class='fa fa-circle-o " + textcolour + "'></i> " + ordstatus + "</a></li>";

            // Build the string for the table
            todaysordersbystatustable += "<tr><td><a style='text-decoration:none;color:#333;' href='" +
                typeLinkStatus +
                "'>" + ordstatus + "</a></td><td><a style='text-decoration:none;color:#333;' href='" +
                typeLinkStatus +
                "'>" + description +
                "</a></td><td align='right'><a style='text-decoration:none;color:#333;' href='" +
                typeLinkStatus + "'>" + currency_symbol + number_format(value, 2) + "</a></td></tr>";

            i++;
        }
    });
    todaysordersbystatustable += "<tr><th>&nbsp</th><th>Total</th><th  style='text-align: right'>" + currency_symbol +
        number_format(tmp_total, 2) + "</th></tr>";
    todaysordersbystatusdata += "]";
    $("#todaysordersbystatustable").html(todaysordersbystatustable);
    $("#todaysordersbystatuslegend").html(todaysordersbystatuslegend);
    $("#todaysordersbystatustable").prev(".loading_spinner").remove();

}

function OutstandingOrdersByStatus(data) {
    const {
        outOrders,
        currency_symbol
    } = data;
    const ADVColour = "#f012be"; // Waiting advice note	Fuschia
    const COMColour = "#00a65a"; // Completed line		Green
    const CUSColour = "#39cccc"; // Call customer back	Teal
    const HLDColour = "#3d9970"; // Goods on hold		Olive
    const IBTColour = "#d2d6de"; // Inter-branch transfer	Gray
    const KITColour = "#01ff70"; // Process kit list		Lime
    const MEMColour = "#ff851b"; // Memo line			Orange
    const OFFColour = "#605ca8"; // Call off later		Purple
    const PIKColour = "#001f3f"; // Pick note printed	Navy
    const PROColour = "#3c8dbc"; // Process document		Light Blue
    const PURColour = "#dd4b39"; // Purchase order		Red
    const SBOColour = "#f39c12"; // Stock backorder		Yellow
    const WDLColour = "#00c0ef"; // Waiting delivery		Aqua
    const WRKColour = "#d81b60"; // Create works order	Maroon

    // Assign legend colours to order statuses

    const ADVTextColour = "text-fuschia"; // Waiting advice note	Fuschia
    const COMTextColour = "text-green"; // Completed line		Green
    const CUSTextColour = "text-teal"; // Call customer back	Teal
    const HLDTextColour = "text-olive"; // Goods on hold		Olive
    const IBTTextColour = "text-gray"; // Inter-branch transfer	Gray
    const KITTextColour = "text-lime"; // Process kit list		Lime
    const MEMTextColour = "text-orange"; // Memo line			Orange
    const OFFTextColour = "text-purple"; // Call off later		Purple
    const PIKTextColour = "text-navy"; // Pick note printed	Navy
    const PROTextColour = "text-light-blue"; // Process document		Light Blue
    const PURTextColour = "text-red"; // Purchase order		Red
    const SBOTextColour = "text-yellow"; // Stock backorder		Yellow
    const WDLTextColour = "text-aqua"; // Waiting delivery		Aqua
    const WRKTextColour = "text-maroon"; // Cr
    let outstandingordersbystatustable = `<tr>
							<th>By Status</th>
							<th>Description</th>
							<th style="text-align: right">Value</th>
						</tr>`;
    let outstandingordersbystatusdata = "[";

    i = 1;
    tmp_total = 0;
    let outstandingordersbystatuslegend = "";
    outOrders.forEach(outor => {
        identifier = outor["identifier"];
        value = outor["actualvalue1"];

        // The order type is the last three characters of the identifier
        ordstatus = identifier.substr(10, 3);
        // Only interested in graphing order statuses that have a value

        if (value != 0) {
            tmp_total += Number(value);

            // Set the colour, which is the order status followed by"Colour"
            switch (ordstatus) {
                case "ADV":
                    colour = ADVColour;
                    textcolour = ADVTextColour;
                    description = "Waiting Advice Note";
                    break;
                case "COM":
                    colour = COMColour;
                    textcolour = COMTextColour;
                    description = "Completed Line";
                    break;
                case "CUS":
                    colour = CUSColour;
                    textcolour = CUSTextColour;
                    description = "Call Customer Back";
                    break;
                case "HLD":
                    colour = HLDColour;
                    textcolour = HLDTextColour;
                    description = "Goods On Hold";
                    break;
                case "IBT":
                    colour = IBTColour;
                    textcolour = IBTTextColour;
                    description = "Inter-Branch Transfer";
                    break;
                case "KIT":
                    colour = KITColour;
                    textcolour = KITTextColour;
                    description = "Process Kit List";
                    break;
                case "MEM":
                    colour = MEMColour;
                    textcolour = MEMTextColour;
                    description = "Memo Line (Quotations)";
                    break;
                case "OFF":
                    colour = OFFColour;
                    textcolour = OFFTextColour;
                    description = "Call Off Later";
                    break;
                case "PIK":
                    colour = PIKColour;
                    textcolour = PIKTextColour;
                    description = "Pick Note Printed";
                    break;
                case "PRO":
                    colour = PROColour;
                    textcolour = PROTextColour;
                    description = "Process Document";
                    break;
                case "PUR":
                    colour = PURColour;
                    textcolour = PURTextColour;
                    description = "Purchase Order";
                    break;
                case "SBO":
                    colour = SBOColour;
                    textcolour = SBOTextColour;
                    description = "Stock Backorder";
                    break;
                case "WDL":
                    colour = WDLColour;
                    textcolour = WDLTextColour;
                    description = "Waiting Delivery";
                    break;
                case "WRK":
                    colour = WRKColour;
                    textcolour = WRKTextColour;
                    description = "Create Works Order";
                    break;
            }

            // The comma only comes in after the first set

            if (i != 1) {
                outstandingordersbystatusdata += ",";
            }
            outstandingLink = "<?php echo site_url("site/outstandingorder/"); ?>/" + ordstatus + "/status";

            // Build the data string for the pie chart data
            outstandingordersbystatusdata += "{value:" + value + ",color:'" + colour + "',highlight:'" +
                colour +
                "',label:'" + ordstatus + "'}";

            // Build the string for the legend
            outstandingordersbystatuslegend += "<li><a style='text-decoration:none;color:#333;' href='" +
                outstandingLink + "'><i class='fa fa-circle-o " + textcolour + "'></i> " + ordstatus +
                "</a></li>";

            // Build the string for the table
            outstandingordersbystatustable += "<tr><td><a style='text-decoration:none;color:#333;' href='" +
                outstandingLink + "'>" + ordstatus +
                "</a></td><td><a style='text-decoration:none;color:#333;' href='" +
                outstandingLink + "'>" + description +
                "</a></td><td style='text-align: right'><a style='text-decoration:none;color:#333;' href='" +
                outstandingLink + "'>" + currency_symbol + (Intl.NumberFormat('en-US', {
                    maximumFractionDigits: 2,
                    minimumFractionDigits: 2
                }).format(value)) + "</a></td></tr>";

            i++;
        }
    });
    outstandingordersbystatustable += "<tr><th>&nbsp</th><th>Total</th><th  style='text-align: right'>" +
        currency_symbol + (Intl.NumberFormat('en-US', {
            maximumFractionDigits: 2,
            minimumFractionDigits: 2
        }).format(tmp_total)) + "</th></tr>";
    outstandingordersbystatusdata += "]";

    $("#outstandingordersbystatustable").html(outstandingordersbystatustable);
    $("#outstandingordersbystatuslegend").append(outstandingordersbystatuslegend);
    $("#outstandingordersbystatustable").prev(".loading_spinner").remove();
}

function pacSaleVsTarget(data) {
    let {
        pac1salestarget,
        getSalesTotalMonthWise
    } = data;

    let pac_sales_vs_target = `<table class="table table-striped" id="pac_sale_vs_target">
			<thead>
				<tr>
					<th>PAC</th>
					<th>Description</th>
					<th>Sales MTD</th>
					<th>Target</th>
					<th>Progress</th>
				</tr>
			</thead>
			<tbody>`;

    pac1salestarget.forEach(pac1 => {
        let pac_progress = Math.round(getSalesTotalMonthWise[pac1.paccode] * 100 / pac1.salestarget, 2);
        if (pac1.salestarget == "") {
            pac1.salestarget = 0;
        }
        if (getSalesTotalMonthWise[pac1.paccode] == "") {
            getSalesTotalMonthWise[pac1.paccode] = 0;
        }
        if (pac_progress == "") {
            pac_progress = 0;
        }

        let cls = "";
        if (pac_progress == "" || pac_progress <= 30) {
            cls = "danger";
        } else if (pac_progress <= 30) {
            cls = "danger";
        } else if (pac_progress > 30 && pac_progress <= 60) {
            cls = "warning";
        } else {
            cls = "success";
        }

        if (pac1.paccode != '') {
            pac_sales_vs_target += `
					<tr>
						<td>
							` + pac1.paccode + `
						</td>
						<td><a href="<?= base_url(); ?>products/details2/` + pac1.tabl + `/` + pac1.paccode + `">` + pac1.description + `</a></td>
						<td>
							` + getSalesTotalMonthWise[pac1.paccode] + `
						</td>
						<td>
							` + pac1.salestarget + `
						</td>
						<td>
							<div class="progress" style="height:5px;">
								<div class="progress-bar progress-bar-` + cls + `"
									style="width:` + pac_progress + `% !important;"></div>
							</div>
							<span class="progress-description">
								` + pac_progress + `%
							</span>
						</td>
					</tr>
				</tbody>
			</table>`;
        }
    });
    $("#pac_sale_vs_target").prev(".loading_spinner").remove();
    $("#pac_sale_vs_target").html(pac_sales_vs_target);
    $('#pac_sale_vs_target').DataTable({
        "PAC": [
            [3, "desc"]
        ],
        "paging": false,
        "searching": false,
        "info": false
    });
}

function quotationsXPac1(data) {
    let {
        currentMonthPac1QuoteConversions
    } = data;
    let quotations_x_pac1 = `
			<thead>
				<tr>
					<th>PAC 1</th>
					<th>Description</th>
					<th>Value this Month</th>
					<th>Qty this Month</th>
				</tr>
			</thead>
			<tbody>`;

    let rowCount = currentMonthPac1QuoteConversions.length;
    let rowNumber = 0;

    currentMonthPac1QuoteConversions.forEach(currentMonthPac1QuoteConversion => {
        if (rowCount == ++rowNumber) {
            quotations_x_pac1 += `
					<tr style="background-color: #e1e1e1; font-style: italic; font-weight: bold; color: black;"
						class="total-row">
						<td>Total</td>
						<td></td>
						<td class="text-left">
							` + number_format(currentMonthPac1QuoteConversion['value_this_month'], 2) + `
						</td>
						<td class="text-left">
						` + number_format(currentMonthPac1QuoteConversion['quantity_this_month']) + `
						</td>
					</tr>`;
        } else {
            quotations_x_pac1 += `
					<tr>
						<td>
							` + currentMonthPac1QuoteConversion['code'] + `
						</td>
						<td>
							` + currentMonthPac1QuoteConversion['description'] + `
						</td>
						<td class="text-left">
						` + number_format(currentMonthPac1QuoteConversion['value_this_month'], 2) + `
						</td>
						<td class="text-left">
						` + number_format(currentMonthPac1QuoteConversion['quantity_this_month']) + `
						</td>
					</tr>`;
        }
    });
    quotations_x_pac1 += `
				</tbody>`;
    $("#quotations_x_pac1").prev(".loading_spinner").remove();
    $("#quotations_x_pac1").html(quotations_x_pac1);
}

function salesPipeline(data) {
    let {
        salesPipelineStages
    } = data;

    let sales_pipeline = `
			<thead>
				<tr>
					<th>Stage</th>
					<th class="text-right">Value</th>
					<th class="text-right">%</th>
				</tr>
			</thead>
			<tbody>`;

    rowCount = salesPipelineStages.length;
    rowNumber = 0;

    salesPipelineStages.forEach(salesPipelineStage => {
        if (rowCount == ++rowNumber) {
            sales_pipeline += `
					<tr style="background-color: #e1e1e1; font-style: italic; font-weight: bold; color: black;"
						class="total-row">
						<td>Total</td>
						<td class="text-right">
						` + number_format(salesPipelineStage['value'], 2) + `
						</td>
						<td class="text-right"></td>
					</tr>`;
        } else {
            sales_pipeline += `
					<tr>
						<td>
								` + salesPipelineStage['description'] + `
						</td>
						<td class="text-right">
						` + number_format(salesPipelineStage['value'], 2) + `
						</td>
						<td class="text-right">
						` + number_format(salesPipelineStage['percentage'], 2) + `%
						</td>
					</tr>`;
        }
    });
    sales_pipeline += `
				</tbody>`;
    $("#sales_pipeline").prev(".loading_spinner").remove();
    $("#sales_pipeline").html(sales_pipeline);
}

function projectSalesMonthChart(data) {
    const {
        projmonthsalespc,
        ProjectedSalesMonthGraphLabel,
        ProjectedSalesMonthGraphActual,
        ProjectedSalesMonthGraphTarget,
        ProjectedSalesMonthGraphProjected
    } = data;

    G_kpithreshold1 = 0;
    G_kpithreshold2 = 0;

    project_sales_month_chart = `
			<div class="row">
				<div class="col-md-10">
					<div class="chart">
						<canvas id="ProjectedSalesForMonthChart" style="min-height:250px"></canvas>
					</div>
				</div>
				<div class="col-md-2">
					<ul class="chart-legend clearfix">
						<li><i class="fa fa-circle-o text-gray"></i> Actual</li>
						<li><i class="fa fa-circle-o text-black"></i> Target</li>`;

    if (projmonthsalespc < G_kpithreshold1) {
        cls = "text-red";
    }
    if (projmonthsalespc >= G_kpithreshold1 && projmonthsalespc < G_kpithreshold2) {
        cls = "text-yellow";
    }
    if (projmonthsalespc >= G_kpithreshold2) {
        cls = "text-green";
    }
    if (projmonthsalespc == "") {
        cls = "text-green";
    }
    project_sales_month_chart += `
						<li><i id="month-projected-color" class="fa fa-circle-o ` + cls + `"></i> Projected</li>
					</ul>
				</div>
			</div>`;
    $("#project_sales_month_chart").html(project_sales_month_chart);

    if ($("#ProjectedSalesForMonthChart").length) {
        // Get context with jQuery - using jQuery's .get() method.
        var ProjectedSalesForMonthChartCanvas = $("#ProjectedSalesForMonthChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var ProjectedSalesForMonthChart = new Chart(ProjectedSalesForMonthChartCanvas);
        var ProjectedSalesForMonthChartData = {
            labels: eval(ProjectedSalesMonthGraphLabel),
            datasets: [{
                    label: "Actual",
                    fillColor: "#d2d6de", // Gray
                    strokeColor: "#d2d6de",
                    pointColor: "#d2d6de",
                    pointStrokeColor: "#d2d6de",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: eval(
                        ProjectedSalesMonthGraphActual
                    ) // [156538, 217208, 266948, 266948, 266948, 341905, 411450, 474286, 540821, 591018, 591018, 591018, 654755, 718922, 777295, 825292, 875581, 875581, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                },
                {
                    label: "Target",
                    fillColor: "#000000",
                    strokeColor: "#000000",
                    pointColor: "#000000",
                    pointStrokeColor: "#000000",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: eval(
                        ProjectedSalesMonthGraphTarget
                    ) //[41667, 83333, 125000, 166667, 208333, 250000, 291667, 333333, 375000, 416667, 458333, 500000, 541667, 583333, 625000, 666667, 708333, 750000, 791667, 833333, 875000, 916667, 958333, 1000000, 1041667, 1083333, 1125000, 1166667, 1208333, 1250000 ]
                },
                {
                    label: "Projected",
                    fillColor: <?php if(empty($projmonthsalespc)){ echo "'#00a65a'"; }elseif(empty($projmonthsalespc)){ $re= "'#00a65a'"; }elseif ($projmonthsalespc < $G_kpithreshold1) { echo "'#dd4b39'"; } elseif ($projmonthsalespc >= $G_kpithreshold1 AND $projmonthsalespc < $G_kpithreshold2) {echo "'#f39c12'";} elseif ($projmonthsalespc > $G_kpithreshold2) {echo "'#00a65a'";}else{echo "'#00000'";}?>,
                    strokeColor: <?php if(empty($projmonthsalespc)){ echo "'#00a65a'"; }elseif ($projmonthsalespc < $G_kpithreshold1) { echo "'#dd4b39'"; } elseif ($projmonthsalespc >= $G_kpithreshold1 AND $projmonthsalespc < $G_kpithreshold2) {echo "'#f39c12'";} elseif ($projmonthsalespc > $G_kpithreshold2) {echo "'#00a65a'";}else{echo "'#00000'";} ?>,
                    pointColor: <?php if(empty($projmonthsalespc)){ echo "'#00a65a'"; }elseif ($projmonthsalespc < $G_kpithreshold1) { echo "'#dd4b39'"; } elseif ($projmonthsalespc >= $G_kpithreshold1 AND $projmonthsalespc < $G_kpithreshold2) {echo "'#f39c12'";} elseif ($projmonthsalespc > $G_kpithreshold2) {echo "'#00a65a'";}else{echo "'#00000'";}?>,
                    pointStrokeColor: <?php if(empty($projmonthsalespc)){ echo "'#00a65a'"; }elseif ($projmonthsalespc < $G_kpithreshold1) { echo "'#dd4b39'"; } elseif ($projmonthsalespc >= $G_kpithreshold1 AND $projmonthsalespc < $G_kpithreshold2) {echo "'#f39c12'";} elseif ($projmonthsalespc > $G_kpithreshold2) {echo "'#00a65a'";}else{echo "'#00000'";}?>,
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: eval(
                        ProjectedSalesMonthGraphProjected
                    ) //[51505,103010,154514,206019,257524,309029,360533,412038,463543,515048,566552,618057,669562,721067,772571,824076,875581,927086,978591,1030095,1081600,1133105,1184610,1236114,1287619,1339124,1390629,1442133,1493638,1545143]
                }

            ]
        };

        var ProjectedSalesForMonthChartOptions = {
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
        monthChart = ProjectedSalesForMonthChart.Line(ProjectedSalesForMonthChartData,
            ProjectedSalesForMonthChartOptions);
        return monthChart;
    }
}

function projectSalesYearChart(data) {
    const {
        yearstartmonth,
        projyearsalespc,
        ProjectedSalesYearGraphActual,
        ProjectedSalesYearGraphTarget,
        ProjectedSalesYearGraphProjected
    } = data;

    G_kpithreshold1 = 0;
    G_kpithreshold2 = 0;

    project_sales_year_chart = `
				<div class="row">
					<div class="col-md-10">
						<div class="chart">
							<canvas id="ProjectedSalesForYearChart" style="min-height:250px"></canvas>
						</div>
					</div>
					<div class="col-md-2">
						<ul class="chart-legend clearfix">
							<li><i class="fa fa-circle-o text-gray"></i> Actual</li>
							<li><i class="fa fa-circle-o text-black"></i> Target</li>`;
    if (projyearsalespc < G_kpithreshold1) {
        cls = "text-red";
    }
    if (projyearsalespc >= G_kpithreshold1 && projyearsalespc < G_kpithreshold2) {
        cls = "text-yellow";
    }
    if (projyearsalespc >= G_kpithreshold2) {
        cls = "text-green";
    }
    if (projyearsalespc == "") {
        cls = "text-green";
    }

    project_sales_year_chart += `
							<li><i class="fa fa-circle-o ` + cls + `"></i> Projected</li>
						</ul>
					</div>
				</div>`;
    $("#project_sales_year_chart").html(project_sales_year_chart);

    if ($("#ProjectedSalesForYearChart").length) {
        // Get context with jQuery - using jQuery's .get() method.
        var ProjectedSalesForYearChartCanvas = $("#ProjectedSalesForYearChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var ProjectedSalesForYearChart = new Chart(ProjectedSalesForYearChartCanvas);
        let labels = ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"];
        for (let i = 1; i < yearstartmonth; i++) {
            var tmp = labels.shift();
            labels.push(tmp);
        }

        var ProjectedSalesForYearChartData = {
            labels: labels,
            datasets: [{
                    label: "Actual",
                    fillColor: "#d2d6de", // Gray
                    strokeColor: "#d2d6de",
                    pointColor: "#d2d6de",
                    pointStrokeColor: "#d2d6de",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: JSON.parse(
                        ProjectedSalesYearGraphActual
                    ) // [1154047, 2364833, 3663974, 4954779, 6186292, 7488464, 7968665, 0, 0, 0, 0, 0 ]
                },
                {
                    label: "Target",
                    fillColor: "#000000",
                    strokeColor: "#000000",
                    pointColor: "#000000",
                    pointStrokeColor: "#000000",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: JSON.parse(
                        ProjectedSalesYearGraphTarget
                    ) // [1250000, 2500000, 3750000, 5000000, 6250000, 7500000, 8750000, 10000000, 11250000, 12500000, 13750000, 15000000 ]
                },
                {
                    label: "Projected",
                    fillColor: <?php if(empty($projyearsalespc)){ echo "'#00a65a'"; }elseif ($projyearsalespc < $G_kpithreshold1) { echo "'#dd4b39'"; } elseif ($projyearsalespc >= $G_kpithreshold1 AND $projyearsalespc < $G_kpithreshold2) {echo "'#f39c12'";} elseif ($projyearsalespc > $G_kpithreshold2) {echo "'#00a65a'";} else{ echo "'#fff'";}?>,
                    strokeColor: <?php if(empty($projyearsalespc)){ echo "'#00a65a'"; }elseif ($projyearsalespc < $G_kpithreshold1) { echo "'#dd4b39'"; } elseif ($projyearsalespc >= $G_kpithreshold1 AND $projyearsalespc < $G_kpithreshold2) {echo "'#f39c12'";} elseif ($projyearsalespc > $G_kpithreshold2) {echo "'#00a65a'";}else{ echo "'#fff'";}?>,
                    pointColor: <?php if(empty($projyearsalespc)){ echo "'#00a65a'"; }elseif ($projyearsalespc < $G_kpithreshold1) { echo "'#dd4b39'"; } elseif ($projyearsalespc >= $G_kpithreshold1 AND $projyearsalespc < $G_kpithreshold2) {echo "'#f39c12'";} elseif ($projyearsalespc > $G_kpithreshold2) {echo "'#00a65a'";}else{ echo "'#fff'";}?>,
                    pointStrokeColor: <?php if(empty($projyearsalespc)){ echo "'#00a65a'"; }elseif ($projyearsalespc < $G_kpithreshold1) { echo "'#dd4b39'"; } elseif ($projyearsalespc >= $G_kpithreshold1 AND $projyearsalespc < $G_kpithreshold2) {echo "'#f39c12'";} elseif ($projyearsalespc > $G_kpithreshold2) {echo "'#00a65a'";}else{ echo "'#fff'";}?>,
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: JSON.parse(ProjectedSalesYearGraphProjected)
                }


            ]
        };

        var ProjectedSalesForYearChartOptions = {
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
        ProjectedSalesForYearChart.Line(ProjectedSalesForYearChartData, ProjectedSalesForYearChartOptions);
    }
}

function OrderFulfillSameDay(data) {
    let {
        OrdersFulfilledGraphLabel,
        OrdersFulfilledGraph,
        todaysordersbytypedata
    } = data;

    if ($("#OrderFulfillSameDay").length) {
        // Get context with jQuery - using jQuery's .get() method.
        var OrderFulfillSameDayCanvas = $("#OrderFulfillSameDay").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var OrderFulfillSameDay = new Chart(OrderFulfillSameDayCanvas);

        var OrderFulfillSameDayData = {
            labels: eval(OrdersFulfilledGraphLabel),
            datasets: [{
                label: "% Fulfilled",
                fillColor: "#000000",
                strokeColor: "#000000",
                pointColor: "#000000",
                pointStrokeColor: "#000000",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: eval(OrdersFulfilledGraph)
            }, ]
        };

        var OrderFulfillSameDayOptions = {
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
            bezierCurve: false,
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
            maintainAspectRatio: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true
        };

        //Create the line chart
        OrderFulfillSameDay.Line(OrderFulfillSameDayData, OrderFulfillSameDayOptions);
        $("#OrderFulfillSameDay").next(".loading_spinner").remove();
    }


}

function TodaysOrdersCanvas(data) {
    const {
        todaysordersbytypedata,
        todaysordersbystatusdata
    } = data;
    $("#salestodaydonutcharts").addClass("active");
    var TodaysOrdersByTypeCanvas = $("#TodaysOrdersByType").get(0).getContext("2d");
    var TodaysOrdersByType = new Chart(TodaysOrdersByTypeCanvas);
    var PieData = eval(todaysordersbytypedata);
    var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: "easeOutExpo",
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    }


    var TodaysOrdersByTypeChart = TodaysOrdersByType.Doughnut(PieData, pieOptions);
    $("#TodaysOrdersByType").click(function(evt) {
        var activePoints = TodaysOrdersByTypeChart.getSegmentsAtEvent(evt);
        var ulToRed = base_url + 'site/todaysorder/' + activePoints[0].label + '/type';
        location.href = ulToRed;
    });

    $("#TodaysOrdersByType").prev(".loading_spinner").remove();


    var TodaysOrdersByStatusCanvas = $("#TodaysOrdersByStatus").get(0).getContext("2d");
    var TodaysOrdersByStatus = new Chart(TodaysOrdersByStatusCanvas);
    var PieData = eval(todaysordersbystatusdata);

    var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: "easeOutExpo",
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart

    // You can switch between pie and douhnut using the method below.
    var TodaysOrdersByStatusChart = TodaysOrdersByStatus.Doughnut(PieData, pieOptions);
    $("#TodaysOrdersByStatus").click(function(evt) {
        var activePointsStatus = TodaysOrdersByStatusChart.getSegmentsAtEvent(evt);
        var ulToRedStatus = base_url + 'site/todaysorder/' + activePointsStatus[0].label + '/status';
        location.href = ulToRedStatus;
    });
    if ($("#salestodaytables").hasClass("active")) {
        $("#salestodaydonutcharts").removeClass("active");
    }
    $("#TodaysOrdersByStatus").prev(".loading_spinner").remove();
}

function OutstandingOrdersByStatusCanvas(data) {
    $("#outstandingordersdonutchart").addClass("active");
    const {
        outstandingordersbystatusdata
    } = data;
    var OutstandingOrdersByStatusCanvas = $("#OutstandingOrdersByStatus").get(0).getContext("2d");
    var OutstandingOrdersByStatus = new Chart(OutstandingOrdersByStatusCanvas);
    var PieData = eval(outstandingordersbystatusdata);
    var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: "easeOutExpo",
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var OutstandingOrdersByStatusChart = OutstandingOrdersByStatus.Doughnut(PieData, pieOptions);
    $("#OutstandingOrdersByStatus").click(function(evt) {
        var activePointsOutstandingStatus = OutstandingOrdersByStatusChart.getSegmentsAtEvent(evt);
        var ulToRedOutstandingStatus = base_url + 'site/outstandingorder/' + activePointsOutstandingStatus[0]
            .label +
            '/status';
        location.href = ulToRedOutstandingStatus;
    });
    if ($("#outstandingorderstable").hasClass("active")) {
        $("#outstandingordersdonutchart").removeClass("active");
    }
    $("#OutstandingOrdersByStatus").prev(".loading_spinner").remove();

}

async function ThreeYearChartCanvas(data) {
    let pram = {
        targetDataForCurrentYear,
        yearstartmonth,
        cumulativeYear0ChartValues,
        cumulativeTargetDataForCurrentYear,
        cumulativeYear1ChartValues,
        cumulativeYear2ChartValues,
        year1ChartValues,
        year2ChartValues,
        year0,
        year1,
        year2
    } = data;
    $("#threeyearsaleschart .chart").html(`<div class="loading_spinner" style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
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
									<canvas id="this-year-cml-vs-last-year-cml" style="height: 250px;"></canvas>`);
    $("#threeyearsaleschart").addClass("active");
    await loadingThreeYearsChart(pram);
    if ($("#threeyearsalestable").hasClass("active")) {
        $("#threeyearsaleschart").removeClass("active");
    }
    $("#this-year-cml-vs-target-cml").hide();
    $("#this-year-vs-last-year").hide();
    $("#this-year-cml-vs-last-year-cml").hide();
    $("#this-year-vs-target").parent().find(".loading_spinner").hide();
}
</script>