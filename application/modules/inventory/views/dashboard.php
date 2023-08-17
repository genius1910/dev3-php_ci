<!-- Content Header (Page header) -->
<link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>application/modules/inventory/css/style.css" />
<?php
$canSeeProjectedSales = canSeeProjectedSales();
$canSeeProjectedSalesYear = canSeeProjectedSalesYear();
$canSeeOrderFulfillment = canSeeOrderFulfillment();

$currency_symbol = $this->config->item("currency_symbol"); ?>
<section class="content-header">
    <h1> Inventory Dashboard </h1>
    <ol class="breadcrumb">
        <li class="active">Data last updated: <?php echo date('m/d/Y H:i', strtotime($lastupdated)) ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content content-dashboard">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm 8 col-xs-12 flex gap-2 margin-bottom">
            <select name="branch" id="branch" class="form-control cursor-pointer w-auto">
                <option value="0" <?php if($activeBranchName == "0") echo "selected" ?>>Branch(All)</option>
                <?php foreach($branch_list as $branch_item): ?>
                <option value="<?php echo $branch_item['branch']?>"
                    <?php if($branch_item['branch'] == $activeBranchName) echo "selected" ?>>
                    <?php echo $branch_item['name']?></option>
                <?php endforeach ?>
            </select>
            <select name="d_branch" id="d_branch" class="form-control cursor-pointer w-auto">
                <option value="0" <?php if($activeDistriBranchName == "0") echo "selected" ?>>Distribution Branch(All)
                </option>
                <?php foreach($branch_list as $branch_item): ?>
                <option value="<?php echo $branch_item['branch']?>"
                    <?php if($branch_item['branch'] == $activeDistriBranchName) echo "selected" ?>>
                    <?php echo $branch_item['name']?></option>
                <?php endforeach ?>
            </select>
            <select name="s_branch" id="s_branch" class="form-control w-auto">
                <?php foreach($supplier_list as $supplier_item): ?>
                <option value="<?php echo $supplier_item['account'] ?>"
                    <?php if($supplier_item['account'] == $activeSupplierName) echo "selected" ?>>
                    <?php echo $supplier_item['name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div
                class="box box-solid <?php echo $activeInventoryOverviewSection == 'Y' ? 'bg-red' : 'bg-blue' ?> inventory_box">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Overview</b></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool text-white" data-type="activeInventoryOverviewSection">
                            <i
                                class="fa <?php echo $activeInventoryOverviewSection == 'Y' ? 'fa-compress' : 'fa-expand' ?>"></i>
                        </button>
                    </div>
                </div>
                <div class="box-footer text-black"
                    style="display:<?php echo $activeInventoryOverviewSection == 'Y' ? 'block' : 'none' ?>">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li class="<?php if($stockValuationAnalysisChart == 'Y') echo 'active'; ?>"
                                onclick="manage_cookie('stockValuationAnalysisChart', 'Y')">
                                <a href="#stockvaluationcharts" data-toggle="tab">
                                    <i class="fa fa-line-chart"></i>
                                </a>
                            </li>
                            <li class="<?php if($stockValuationAnalysisChart == 'N') echo 'active'; ?>"
                                onclick="manage_cookie('stockValuationAnalysisChart', 'N')">
                                <a href="#stockvaluationtables" data-toggle="tab" aria-expanded="true">
                                    <i class="fa fa-table"></i>
                                </a>
                            </li>
                            <li class="pull-left header">Stock Valuation Analysis</li>
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="tab-pane <?php if($stockValuationAnalysisChart == 'Y') echo 'active'; ?>"
                                id="stockvaluationcharts" style="position: relative;">
                                <div class="row">
                                    <div class="col-md-12">
                                        This is chart.
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box-body" id="inventory_dashboard_stock_valuation_analysis"
                                            style="min-height:250px">
                                            <div class="overlay h2 m-0 loading_spinner"
                                                style="margin: 0px;display: block;position: absolute;">
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
                                            <input type="hidden" id="stockValuationLables"
                                                value="<?php echo join($stockvaluation, '&') ?>" />
                                            <input type="hidden" id="stockValuationData"
                                                value="<?php var_dump($stockValuationAnalysis) ?>" />
                                            <table class="table table-striped" id="stockvalutionanalysis">
                                                <tr>
                                                    <th>Valuation</th>
                                                    <?php for($i = 0; $i < count($stockvaluation); $i++): ?>
                                                    <th><?=$stockvaluation[$i]?></th>
                                                    <?php endfor ?>
                                                </tr>
                                                <tr>
                                                    <td>Ideal</td>
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
                                                    <td>Actual</td>
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
                                                    <td>%</td>
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
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li class="<?php if($stockCoverAnalysisChart == "Y") echo 'active' ?>"
                                onClick="manage_cookie('stockCoverAnalysisChart', 'Y')">
                                <a href="#stockcovercharts" data-toggle="tab">
                                    <i class="fa fa-line-chart"></i>
                                </a>
                            </li>
                            <li class="<?php if($stockCoverAnalysisChart == "N") echo 'active' ?>"
                                onClick="manage_cookie('stockCoverAnalysisChart', 'N')">
                                <a href="#stockcovertables" data-toggle="tab" aria-expanded="true">
                                    <i class="fa fa-table"></i>
                                </a>
                            </li>
                            <li class="pull-left header">Stock Cover Analysis</li>
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="tab-pane <?php if($stockCoverAnalysisChart == "Y") echo 'active' ?>"
                                id="stockcovercharts" style="position: relative;">
                                <div class="row">
                                    <div class="col-md-12">
                                        This is chart.
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box-body" id="inventory_dashboard_stock_cover_analysis"
                                            style="min-height:250px">
                                            <div class="overlay h2 m-0 loading_spinner"
                                                style="margin: 0px;display: block;position: absolute;">
                                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane <?php if($stockCoverAnalysisChart == "N") echo 'active' ?>"
                                id="stockcovertables" style="position: relative;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-scroll">
                                            <?php
                                                $stockcover = [];
                                                for($i = 0; $i < 12; $i++) {
                                                array_push($stockcover, date('M-y', strtotime($system_curyearmonth." - ".(12 - $i)."month")));
                                                }
                                                array_push($stockcover, date('M-y', strtotime($system_curyearmonth)));
                                            ?>
                                            <table class="table table-striped" id="stockvalutionanalysis">
                                                <tr>
                                                    <th>Cover</th>
                                                    <?php for($i = 0; $i < count($stockcover); $i++): ?>
                                                    <th><?=$stockcover[$i]?></th>
                                                    <?php endfor ?>
                                                </tr>
                                                <tr>
                                                    <td>Ideal</td>
                                                    <?php for($i = 0; $i < count($stockcover); $i++): ?>
                                                    <td>
                                                        <?php if(count($stockCoverAnalysis) == 0) echo "0" ?>
                                                        <?php foreach($stockCoverAnalysis as $item): ?>
                                                        <?php echo date('M-y', strtotime($item['date'])) == $stockcover[$i] ? $item['actualvalue1'] : '0'; break; ?>
                                                        <?php endforeach ?>
                                                    </td>
                                                    <?php endfor ?>
                                                </tr>
                                                <tr>
                                                    <td>Actual</td>
                                                    <?php for($i = 0; $i < count($stockcover); $i++): ?>
                                                    <td>
                                                        <?php if(count($stockCoverAnalysis) == 0) echo "0" ?>
                                                        <?php foreach($stockCoverAnalysis as $item): ?>
                                                        <?php echo date('M-y', strtotime($item['date'])) == $stockcover[$i] ? $item['actualvalue2'] : '0'; break; ?>
                                                        <?php endforeach ?>
                                                    </td>
                                                    <?php endfor ?>
                                                </tr>
                                                <tr>
                                                    <td>%</td>
                                                    <?php for($i = 0; $i < count($stockcover); $i++): ?>
                                                    <td>
                                                        <?php if(count($stockCoverAnalysis) == 0) echo "0" ?>
                                                        <?php foreach($stockCoverAnalysis as $item): ?>
                                                        <?php echo date('M-y', strtotime($item['date'])) == $stockcover[$i] ? $item['actualvalue3'] : '0'; break; ?>
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
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li class="<?php if($stockTurnAnalysisChart == "Y") echo 'active' ?>"
                                onClick="manage_cookie('stockTurnAnalysisChart', 'Y')">
                                <a href="#stockturncharts" data-toggle="tab">
                                    <i class="fa fa-line-chart"></i>
                                </a>
                            </li>
                            <li class="<?php if($stockTurnAnalysisChart == "N") echo 'active' ?>"
                                onClick="manage_cookie('stockTurnAnalysisChart', 'N')">
                                <a href="#stockturntables" data-toggle="tab" aria-expanded="true"><i
                                        class="fa fa-table"></i></a>
                            </li>
                            <li class="pull-left header">Stock Turn Analysis</li>
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="tab-pane <?php if($stockTurnAnalysisChart == "Y") echo "active" ?>"
                                id="stockturncharts" style="position: relative;">
                                <div class="row">
                                    <div class="col-md-12">
                                        This is chart.
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box-body" id="inventory_dashboard_stock_turn_analysis"
                                            style="min-height:250px">
                                            <div class="overlay h2 m-0 loading_spinner"
                                                style="margin: 0px;display: block;position: absolute;">
                                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane <?php if($stockTurnAnalysisChart == "N") echo "active" ?>"
                                id="stockturntables" style="position: relative;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-scroll">
                                            <?php
                                                $stockturn = [];
                                                for($i = 0; $i < 12; $i++) {
                                                array_push($stockturn, date('M-y', strtotime($system_curyearmonth." - ".(12 - $i)."month")));
                                                }
                                                array_push($stockturn, date('M-y', strtotime($system_curyearmonth)));
                                            ?>
                                            <table class="table table-striped" id="stockvalutionanalysis">
                                                <tr>
                                                    <th>Cover</th>
                                                    <?php for($i = 0; $i < count($stockturn); $i++): ?>
                                                    <th><?=$stockturn[$i]?></th>
                                                    <?php endfor ?>
                                                </tr>
                                                <tr>
                                                    <td>Ideal</td>
                                                    <?php for($i = 0; $i < count($stockturn); $i++): ?>
                                                    <td>
                                                        <?php if(count($stockTurnAnalysis) == 0) echo "0" ?>
                                                        <?php foreach($stockTurnAnalysis as $item): ?>
                                                        <?php echo date('M-y', strtotime($item['date'])) == $stockturn[$i] ? $item['actualvalue1'] : '0'; break; ?>
                                                        <?php endforeach ?>
                                                    </td>
                                                    <?php endfor ?>
                                                </tr>
                                                <tr>
                                                    <td>Actual</td>
                                                    <?php for($i = 0; $i < count($stockturn); $i++): ?>
                                                    <td>
                                                        <?php if(count($stockTurnAnalysis) == 0) echo "0" ?>
                                                        <?php foreach($stockTurnAnalysis as $item): ?>
                                                        <?php echo date('M-y', strtotime($item['date'])) == $stockturn[$i] ? $item['actualvalue2'] : '0'; break; ?>
                                                        <?php endforeach ?>
                                                    </td>
                                                    <?php endfor ?>
                                                </tr>
                                                <tr>
                                                    <td>%</td>
                                                    <?php for($i = 0; $i < count($stockturn); $i++): ?>
                                                    <td>
                                                        <?php if(count($stockTurnAnalysis) == 0) echo "0" ?>
                                                        <?php foreach($stockTurnAnalysis as $item): ?>
                                                        <?php echo date('M-y', strtotime($item['date'])) == $stockturn[$i] ? $item['actualvalue3'] : '0'; break; ?>
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs pull-right">
                                    <li class="<?php if($stockValuationByLocationChart == "Y") echo 'active' ?>"
                                        onClick="manage_cookie('stockValuationByLocationChart', 'Y')">
                                        <a href="#stockvaluationbylocationcharts" data-toggle="tab">
                                            <i class="fa fa-pie-chart"></i>
                                        </a>
                                    </li>
                                    <li class="<?php if($stockValuationByLocationChart == "N") echo 'active' ?>"
                                        onClick="manage_cookie('stockValuationByLocationChart', 'N')">
                                        <a href="#stockvaluationbylocationtables" data-toggle="tab"
                                            aria-expanded="true">
                                            <i class="fa fa-table"></i>
                                        </a>
                                    </li>
                                    <li class="pull-left header">Stock Valuation By Location</li>
                                </ul>
                                <div class="tab-content no-padding">
                                    <div class="tab-pane <?php if($stockValuationByLocationChart == "Y") echo 'active' ?>"
                                        id="stockvaluationbylocationcharts" style="position: relative;">
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
                                                    <canvas id="StockvaluationbylocationchartsCanvas"></canvas>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <ul class="chart-legend clearfix"
                                                    id="stockvaluationbylocationchartsCanvaslegend">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane <?php if($stockValuationByLocationChart == "N") echo 'active' ?>"
                                        id="stockvaluationbylocationtables" style="position: relative;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scroll">
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th>Location</th>
                                                            <th>Value £</th>
                                                            <th>#Item(s)</th>
                                                            <th>Provision £</th>
                                                            <th>Net Stock £</th>
                                                        </tr>
                                                        <?php
                                                            $totalValue1 = 0;
                                                            $totalValue2 = 0;
                                                            $totalValue3 = 0;
                                                            $totalValue4 = 0;
                                                        ?>
                                                        <?php foreach($stockValuationByLocation as $item): ?>
                                                        <tr>
                                                            <td><?= $item['description'] ?></td>
                                                            <td><?= $item['actualvalue1'] ?></td>
                                                            <td><?= $item['actualvalue2'] ?></td>
                                                            <td><?= $item['actualvalue3'] ?></td>
                                                            <td><?= $item['actualvalue4'] ?></td>
                                                        </tr>
                                                        <?php
                                                            $totalValue1 += $item['actualvalue1'];
                                                            $totalValue2 += $item['actualvalue2'];
                                                            $totalValue3 += $item['actualvalue3'];
                                                            $totalValue4 += $item['actualvalue4'];
                                                        ?>
                                                        <?php endforeach ?>
                                                        <tr>
                                                            <td>Total</td>
                                                            <td><?= $totalValue1 ?></td>
                                                            <td><?= $totalValue2 ?></td>
                                                            <td><?= $totalValue3 ?></td>
                                                            <td><?= $totalValue4 ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs pull-right">
                                    <li class="<?php if($stockValuationByAgeChart == "Y") echo 'active' ?>"
                                        onClick="manage_cookie('stockValuationByAgeChart', 'Y')">
                                        <a href="#stockvalutationbyagecharts" data-toggle="tab">
                                            <i class="fa fa-pie-chart"></i>
                                        </a>
                                    </li>
                                    <li class="<?php if($stockValuationByAgeChart == "N") echo 'active' ?>"
                                        onClick="manage_cookie('stockValuationByAgeChart', 'N')">
                                        <a href="#stockvalutationbyagetables" data-toggle="tab" aria-expanded="true">
                                            <i class="fa fa-table"></i>
                                        </a>
                                    </li>
                                    <li class="pull-left header">Stock Valuation By Age</li>
                                </ul>
                                <div class="tab-content no-padding">
                                    <div class="tab-pane <?php if($stockValuationByAgeChart == "Y") echo 'active' ?>"
                                        id="stockvalutationbyagecharts" style="position: relative;">
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
                                                    <canvas id="StockvaluationbyagechartsCanvas"></canvas>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <ul class="chart-legend clearfix"
                                                    id="stockvaluationbyagechartsCanvaslegend">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane <?php if($stockValuationByAgeChart == "N") echo 'active' ?>"
                                        id="stockvalutationbyagetables" style="position: relative;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scroll">
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th>Age</th>
                                                            <th>Value £</th>
                                                            <th>#Item(s)</th>
                                                            <th>Provision £</th>
                                                            <th>Net Stock £</th>
                                                        </tr>
                                                        <?php
                                                            $totalValue1 = 0;
                                                            $totalValue2 = 0;
                                                            $totalValue3 = 0;
                                                            $totalValue4 = 0;
                                                        ?>
                                                        <?php foreach($stockValuationByAge as $item): ?>
                                                        <tr>
                                                            <td><?= $item['description'] ?></td>
                                                            <td><?= $item['actualvalue1'] ?></td>
                                                            <td><?= $item['actualvalue2'] ?></td>
                                                            <td><?= $item['actualvalue3'] ?></td>
                                                            <td><?= $item['actualvalue4'] ?></td>
                                                        </tr>
                                                        <?php
                                                            $totalValue1 += $item['actualvalue1'];
                                                            $totalValue2 += $item['actualvalue2'];
                                                            $totalValue3 += $item['actualvalue3'];
                                                            $totalValue4 += $item['actualvalue4'];
                                                        ?>
                                                        <?php endforeach ?>
                                                        <tr>
                                                            <td>Total</td>
                                                            <td><?= $totalValue1 ?></td>
                                                            <td><?= $totalValue2 ?></td>
                                                            <td><?= $totalValue3 ?></td>
                                                            <td><?= $totalValue4 ?></td>
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div
                class="box box-solid <?php echo $activeInventoryAllOverSection == 'Y' ? 'bg-red' : 'bg-blue' ?> inventory_box">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>All -
                            <?php echo $inventoryAllOverSection["actualvalue1"] ? $inventoryAllOverSection["actualvalue1"] : 0 ?>
                            Turnover in the past <?php echo $month ?> months with
                            <?php echo $inventoryAllOverSection["actualvalue2"] ? $inventoryAllOverSection["actualvalue2"] : 0 ?>
                            Stock Items</b></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool text-white" data-type="activeInventoryAllOverSection">
                            <i
                                class="fa <?php echo $activeInventoryAllOverSection == 'Y' ? 'fa-compress' : 'fa-expand' ?>"></i>
                        </button>
                    </div>
                </div>
                <div class="box-footer text-black"
                    style="display:<?php echo $activeInventoryAllOverSection == 'Y' ? 'block' : 'none' ?>">
                    <div class="row">
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                                $class = "bg-red";
                            ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-below-safety">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                                        color: #000;
                                        scale: 2;
                                        position: absolute;
                                        top: 44%;
                                    "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-thumbs-down"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Below Safety</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                                $class = "bg-green";
                            ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-need-to-order">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Need To Order</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-blue";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-stock-cover">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-recycle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Stock Cover</span>
                                        <span class="info-box-number">
                                            0.0 (months)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-red";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-overdue-po-lines">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Overdue PO Lines</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = " bg-yellow";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-surplus-stock">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-bell"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Surplus Stock</span>
                                        <span class="info-box-number">
                                            0 Item(s) (0)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-blue";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-stock-turn">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-recycle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Stock Turn</span>
                                        <span class="info-box-number">
                                            0.0 (months)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid <?php echo $activeInventoryAClass== 'Y' ? 'bg-red' : 'bg-blue' ?> inventory_box">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>A Class
                            <?php echo $inventoryAClass["actualvalue1"] ? $inventoryAClass["actualvalue1"] : 0 ?>
                            Turnover
                            <?php echo $inventoryAClass["actualvalue2"] ? $inventoryAClass["actualvalue2"] : 0 ?> % Of
                            Total <?php echo $month ?> Stock Items
                            <?php echo $inventoryAClass["actualvalue2"] ? $inventoryAClass["actualvalue2"] : 0 ?> Of
                            Total</b></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool text-white" data-type="activeInventoryAClass">
                            <i class="fa <?php echo $activeInventoryAClass == 'Y' ? 'fa-compress' : 'fa-expand' ?>"></i>
                        </button>
                    </div>
                </div>
                <div class="box-footer text-black"
                    style="display:<?php echo $activeInventoryAClass == 'Y' ? 'block' : 'none' ?>">
                    <div class="row">
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-red";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-below-safety-a">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-thumbs-down"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Below Safety</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-green";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-need-to-order-a">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Need To Order</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-blue";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-stock-cover-a">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-recycle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Stock Cover</span>
                                        <span class="info-box-number">
                                            0.0 (months)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-red";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-overdue-po-lines-a">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Overdue PO Lines</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = " bg-yellow";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-surplus-stock-a">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-bell"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Surplus Stock</span>
                                        <span class="info-box-number">
                                            0 Item(s) (0)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-blue";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-stock-turn-a">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-recycle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Stock Turn</span>
                                        <span class="info-box-number">
                                            0.0 (months)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid <?php echo $activeInventoryBClass == 'Y' ? 'bg-red' : 'bg-blue' ?> inventory_box">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>B Class
                            <?php echo $inventoryBClass["actualvalue1"] ? $inventoryBClass["actualvalue1"] : 0 ?>
                            Turnover
                            <?php echo $inventoryBClass["actualvalue2"] ? $inventoryBClass["actualvalue2"] : 0 ?> % Of
                            Total <?php echo $month ?> Stock Items
                            <?php echo $inventoryBClass["actualvalue2"] ? $inventoryBClass["actualvalue2"] : 0 ?> Of
                            Total</b></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool text-white" data-type="activeInventoryBClass">
                            <i class="fa <?php echo $activeInventoryBClass == 'Y' ? 'fa-compress' : 'fa-expand' ?>"></i>
                        </button>
                    </div>
                </div>
                <div class="box-footer text-black"
                    style="display:<?php echo $activeInventoryBClass == 'Y' ? 'block' : 'none' ?>">
                    <div class="row">
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-red";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-below-safety-b">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-thumbs-down"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Below Safety</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-green";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-need-to-order-b">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Need To Order</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-blue";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-stock-cover-b">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-recycle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Stock Cover</span>
                                        <span class="info-box-number">
                                            0.0 (months)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-red";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-overdue-po-lines-b">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Overdue PO Lines</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = " bg-yellow";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-surplus-stock-b">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-bell"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Surplus Stock</span>
                                        <span class="info-box-number">
                                            0 Item(s) (0)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-blue";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-stock-turn-b">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-recycle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Stock Turn</span>
                                        <span class="info-box-number">
                                            0.0 (months)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid <?php echo $activeInventoryCClass == 'Y' ? 'bg-red' : 'bg-blue' ?> inventory_box">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>C Class
                            <?php echo $inventoryCClass["actualvalue1"] ? $inventoryCClass["actualvalue1"] : 0 ?>
                            Turnover
                            <?php echo $inventoryCClass["actualvalue2"] ? $inventoryCClass["actualvalue2"] : 0 ?> % Of
                            Total <?php echo $month ?> Stock Items
                            <?php echo $inventoryCClass["actualvalue2"] ? $inventoryCClass["actualvalue2"] : 0 ?> Of
                            Total</b></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool text-white" data-type="activeInventoryCClass">
                            <i class="fa <?php echo $activeInventoryCClass == 'Y' ? 'fa-compress' : 'fa-expand' ?>"></i>
                        </button>
                    </div>
                </div>
                <div class="box-footer text-black"
                    style="display:<?php echo $activeInventoryCClass == 'Y' ? 'block' : 'none' ?>">
                    <div class="row">
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-red";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-below-safety-c">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-thumbs-down"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Below Safety</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-green";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-need-to-order-c">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Need To Order</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-blue";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-stock-cover-c">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-recycle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Stock Cover</span>
                                        <span class="info-box-number">
                                            0.0 (months)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-red";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-overdue-po-lines-c">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Overdue PO Lines</span>
                                        <span class="info-box-number">
                                            0 Item(s)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = " bg-yellow";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-surplus-stock-c">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-bell"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Surplus Stock</span>
                                        <span class="info-box-number">
                                            0 Item(s) (0)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div
                            class="<?php if (!!$canSeeMargins) { ?>col-md-3 col-sm-4 col-xs-12<?php } else { ?>col-md-4<?php } ?>">
                            <?php
                $class = "bg-blue";
              ?>
                            <div class="info-box <?= $class ?>" id="inventory-turnover-stock-turn-c">
                                <div
                                    style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 30px);height: calc(100% - 15px);text-align: center;">
                                    <i class="fa fa-spinner fa-spin fa-fw" style="
                    color: #000;
                    scale: 2;
                    position: absolute;
                    top: 44%;
                  "></i>
                                </div>
                                <a style="color: black;text-decoration: none;"
                                    href="<?= base_url() . 'site/daydrillreport'; ?>">
                                    <span class="info-box-icon"><i class="fa fas fa-recycle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-title">Stock Turn</span>
                                        <span class="info-box-number">
                                            0.0 (months)
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 50% !important;"></div>
                                        </div>
                                        <span class="progress-description">
                                            0.00% Equal To in 30 days
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Main row -->
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
            cookie_value: cookie_value,
        },
        success: function(data) {
            location.reload('');
        },
    });
}
$(document).ready(function() {
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventorySurplusStock',
        data: {
            data: ''
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCardStock("inventory-turnover-surplus-stock", "Surplus Stock",
                "fa fas fa-bell", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryBelowSafety',
        data: {
            data: ''
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-below-safety", "Below Safety",
                "fa fas fa-thumbs-down", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryNeedToOrder',
        data: {
            data: ''
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-need-to-order", "Need To Order",
                "fa fa-shopping-cart", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryStockCover',
        data: {
            data: ''
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-stock-cover", "Stock Cover", "fa fas fa-recycle",
                "(months)", data, 1);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryOverduePOLines',
        data: {
            data: ''
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-overdue-po-lines", "Overdue PO Lines",
                "fa fa-clock-o", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryStockTurn',
        data: {
            data: ''
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-stock-turn", "Stock Turn", "fa fas fa-recycle",
                "(months)", data, 1);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventorySurplusStock',
        data: {
            data: 'A'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCardStock("inventory-turnover-surplus-stock-a", "Surplus Stock",
                "fa fas fa-bell", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryBelowSafety',
        data: {
            data: 'A'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-below-safety-a", "Below Safety",
                "fa fas fa-thumbs-down", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryNeedToOrder',
        data: {
            data: 'A'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-need-to-order-a", "Need To Order",
                "fa fa-shopping-cart", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryStockCover',
        data: {
            data: 'A'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-stock-cover-a", "Stock Cover", "fa fas fa-recycle",
                "(months)", data, 1);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryOverduePOLines',
        data: {
            data: 'A'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-overdue-po-lines-a", "Overdue PO Lines",
                "fa fa-clock-o", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryStockTurn',
        data: {
            data: 'A'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-stock-turn-a", "Stock Turn", "fa fas fa-recycle",
                "(months)", data, 1);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventorySurplusStock',
        data: {
            data: 'B'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCardStock("inventory-turnover-surplus-stock-b", "Surplus Stock",
                "fa fas fa-bell", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryBelowSafety',
        data: {
            data: 'B'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-below-safety-b", "Below Safety",
                "fa fas fa-thumbs-down", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryNeedToOrder',
        data: {
            data: 'B'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-need-to-order-b", "Need To Order",
                "fa fa-shopping-cart", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryStockCover',
        data: {
            data: 'B'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-stock-cover-b", "Stock Cover", "fa fas fa-recycle",
                "(months)", data, 1);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryOverduePOLines',
        data: {
            data: 'B'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-overdue-po-lines-b", "Overdue PO Lines",
                "fa fa-clock-o", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryStockTurn',
        data: {
            data: 'B'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-stock-turn-b", "Stock Turn", "fa fas fa-recycle",
                "(months)", data, 1);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventorySurplusStock',
        data: {
            data: 'C'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCardStock("inventory-turnover-surplus-stock-c", "Surplus Stock",
                "fa fas fa-bell", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryBelowSafety',
        data: {
            data: 'C'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-below-safety-c", "Below Safety",
                "fa fas fa-thumbs-down", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryNeedToOrder',
        data: {
            data: 'C'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-need-to-order-c", "Need To Order",
                "fa fa-shopping-cart", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryStockCover',
        data: {
            data: 'C'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-stock-cover-c", "Stock Cover", "fa fas fa-recycle",
                "(months)", data, 1);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryOverduePOLines',
        data: {
            data: 'C'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-overdue-po-lines-c", "Overdue PO Lines",
                "fa fa-clock-o", "Item(s)", data, 0);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getInventoryStockTurn',
        data: {
            data: 'C'
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            getInventoryCard("inventory-turnover-stock-turn-c", "Stock Turn", "fa fas fa-recycle",
                "(months)", data, 1);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>inventory/getStockValuationAnalysisChart',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            inventoryStockValuationAnalysis(data);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getStockCoverAnalysisChart',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            inventoryStockCoverAnalysis(data);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getStockTurnAnalysisChart',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            inventoryStockTurnAnalysis(data);
        }
    });

    $.ajax({
        url: '<?= base_url() ?>inventory/getStockLocationChart',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            inventoryStockLocation(data);
        }
    });
    $.ajax({
        url: '<?= base_url() ?>inventory/getStockAgeChart',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            inventoryStockAge(data);
        }
    });

    $(".inventory_box > .box-header > .box-tools > button").click(function() {
        let state = '';
        if ($(this).find("i").hasClass("fa-expand")) {
            state = 'Y';
            $(this).find("i").removeClass("fa-expand");
            $(this).find("i").addClass("fa-compress");
        } else {
            state = 'N';
            $(this).find("i").addClass("fa-expand");
            $(this).find("i").removeClass("fa-compress");
        }
        $(this).parent().parent().next().slideToggle();
        setTimeout(() => {
            manage_cookie($(this).data('type'), state);
        }, 500);
    });

    $("#branch").change(function(e) {
        manage_cookie("inventoryBranchName", $(this).val());
    });

    $("#d_branch").change(function(e) {
        manage_cookie('inventoryDistriBranchName', $(this).val());
    })

    $("#s_branch").change(function(e) {
        manage_cookie('inventorySupplierName', $(this).val());
    })


});
//
function getInventoryCard(id, title, icon, text, data, dp) {
    if (data[0])
        $("#" + id + "").html('\
      <a style="color: black;text-decoration: none;" href="' + base_url + 'site/daydrillreport">\
        <span class="info-box-icon"><i class="' + icon + '"></i></span>\
        <div class="info-box-content">\
          <span class="info-box-title">' + title + '\
          </span>\
          <span class="info-box-number">\
            ' + number_format(data[0]["actualvalue1"], dp) + ' ' + text + '\
          </span>\
          <div class="progress">\
            <div class="progress-bar" style="width: ' + (50 + data[0]["actualvalue2"]) + '% !important;"></div>\
          </div>\
          <span class="progress-description">\
            ' + number_format(data[0]["actualvalue2"], 2) + '% ' + (data[0]["actualvalue2"] == 0 ? "Equal To" : (data[
            0]["actualvalue2"] > 0 ? "Increase On" : "Decrease On")) + ' in 30 days\
          </span>\
        </div>\
      </a>\
    ');
    else
        $("#" + id + "").html('\
      <a style="color: black;text-decoration: none;" href="' + base_url + 'site/daydrillreport">\
        <span class="info-box-icon"><i class="' + icon + '"></i></span>\
        <div class="info-box-content">\
          <span class="info-box-title">' + title + '\
          </span>\
          <span class="info-box-number">\
            ' + number_format(0, dp) + ' ' + text + '\
          </span>\
          <div class="progress">\
            <div class="progress-bar" style="width: 50% !important;"></div>\
          </div>\
          <span class="progress-description">\
            0.00% Equal To in 30 days\
          </span>\
        </div>\
      </a>\
    ');
}

function getInventoryCardStock(id, title, icon, text, data, dp) {
    if (data[0])
        $("#" + id + "").html('\
      <a style="color: black;text-decoration: none;" href="' + base_url + 'site/daydrillreport">\
        <span class="info-box-icon"><i class="' + icon + '"></i></span>\
        <div class="info-box-content">\
          <span class="info-box-title">' + title + '\
          </span>\
          <span class="info-box-number">\
            ' + number_format(data[0]["actualvalue3"], dp) + ' ' + text + '(' + data[0]["actualvalue1"] + ')\
          </span>\
          <div class="progress">\
            <div class="progress-bar" style="width: ' + (50 + data[0]["actualvalue2"]) + '% !important;"></div>\
          </div>\
          <span class="progress-description">\
            ' + number_format(data[0]["actualvalue2"], 2) + '% ' + (data[0]["actualvalue1"] == 0 ? "Equal To" : (data[
            0]["actualvalue1"] > 0 ? "Increase On" : "Decrease On")) + ' in 30 days\
          </span>\
        </div>\
      </a>\
    ');
    else
        $("#" + id + "").html('\
      <a style="color: black;text-decoration: none;" href="' + base_url + 'site/daydrillreport">\
        <span class="info-box-icon"><i class="' + icon + '"></i></span>\
        <div class="info-box-content">\
          <span class="info-box-title">' + title + '\
          </span>\
          <span class="info-box-number">\
            ' + number_format(0, dp) + ' ' + text + '(0)\
          </span>\
          <div class="progress">\
            <div class="progress-bar" style="width: 50% !important;"></div>\
          </div>\
          <span class="progress-description">\
            0.00% Equal To in 30 days\
          </span>\
        </div>\
      </a>\
    ');
}
// chat view
function inventoryStockValuationAnalysis(data) {
    inventory_dashboard_stock_valuation_analysis = `
    <div class="row">
      <div class="col-md-10">
        <div class="chart">
          <canvas id="StockValuationChart" style="min-height:150px; max-height:250px"></canvas>
        </div>
      </div>
      <div class="col-md-2">
        <ul class="chart-legend clearfix">
          <li><i class="fa fa-circle-o text-green"></i> Ideal</li>
          <li><i class="fa fa-circle-o text-red"></i> Actual</li>
          <li><i class="fa fa-circle-o text-black"></i> %</li>
        </ul>
      </div>
    </div>`;
    $("#inventory_dashboard_stock_valuation_analysis").html(inventory_dashboard_stock_valuation_analysis);

    if ($("#StockValuationChart").length) {
        // Get context with jQuery - using jQuery's .get() method.
        var ChartCanvas = $("#StockValuationChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var StockValuationChart = new Chart(ChartCanvas);
        let labels = [];
        for (let i = 0; i < data['valuation'].length; i++) {
            labels.push(data['valuation'][i]);
        }

        var StockValuationChartData = {
            labels: labels,
            datasets: [{
                    label: "Actual",
                    fillColor: "#00a65a", // Gray
                    strokeColor: "#00a65a",
                    pointColor: "#00a65a",
                    pointStrokeColor: "#00a65a",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: JSON.parse(
                        data['ideal']
                    ) // [1154047, 2364833, 3663974, 4954779, 6186292, 7488464, 7968665, 0, 0, 0, 0, 0 ]
                },
                {
                    label: "Target",
                    fillColor: "#dd4b39",
                    strokeColor: "#dd4b39",
                    pointColor: "#dd4b39",
                    pointStrokeColor: "#dd4b39",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: JSON.parse(
                        data['actual']
                    ) // [1250000, 2500000, 3750000, 5000000, 6250000, 7500000, 8750000, 10000000, 11250000, 12500000, 13750000, 15000000 ]
                },
                {
                    label: "Projected",
                    fillColor: "#111",
                    strokeColor: "#111",
                    pointColor: "#111",
                    pointStrokeColor: "#111",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: JSON.parse(
                        data['percent'])
                }


            ]
        };

        var StockValuationChartOptions = {
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
        StockValuationChart.Line(StockValuationChartData, StockValuationChartOptions);
    }
}

function inventoryStockCoverAnalysis(data) {
    inventory_dashboard_stock_cover_analysis = `
    <div class="row">
      <div class="col-md-10">
        <div class="chart">
          <canvas id="StockCoverChart" style="min-height:150px; max-height:250px"></canvas>
        </div>
      </div>
      <div class="col-md-2">
        <ul class="chart-legend clearfix">
          <li><i class="fa fa-circle-o text-green"></i> Ideal</li>
          <li><i class="fa fa-circle-o text-red"></i> Actual</li>
          <li><i class="fa fa-circle-o text-black"></i> %</li>
        </ul>
      </div>
    </div>`;
    $("#inventory_dashboard_stock_cover_analysis").html(inventory_dashboard_stock_cover_analysis);

    if ($("#StockCoverChart").length) {
        // Get context with jQuery - using jQuery's .get() method.
        var ChartCanvas = $("#StockCoverChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var StockCoverChart = new Chart(ChartCanvas);
        let labels = [];
        for (let i = 0; i < data['valuation'].length; i++) {
            labels.push(data['valuation'][i]);
        }

        var StockCoverChartData = {
            labels: labels,
            datasets: [{
                    label: "Actual",
                    fillColor: "#00a65a", // Gray
                    strokeColor: "#00a65a",
                    pointColor: "#00a65a",
                    pointStrokeColor: "#00a65a",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: JSON.parse(
                        data['ideal']
                    ) // [1154047, 2364833, 3663974, 4954779, 6186292, 7488464, 7968665, 0, 0, 0, 0, 0 ]
                },
                {
                    label: "Target",
                    fillColor: "#dd4b39",
                    strokeColor: "#dd4b39",
                    pointColor: "#dd4b39",
                    pointStrokeColor: "#dd4b39",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: JSON.parse(
                        data['actual']
                    ) // [1250000, 2500000, 3750000, 5000000, 6250000, 7500000, 8750000, 10000000, 11250000, 12500000, 13750000, 15000000 ]
                },
                {
                    label: "Projected",
                    fillColor: "#111",
                    strokeColor: "#111",
                    pointColor: "#111",
                    pointStrokeColor: "#111",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: JSON.parse(
                        data['percent'])
                }


            ]
        };

        var StockCoverChartOptions = {
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
        StockCoverChart.Line(StockCoverChartData, StockCoverChartOptions);
    }
}

function inventoryStockTurnAnalysis(data) {
    inventory_dashboard_stock_turn_analysis = `
    <div class="row">
      <div class="col-md-10">
        <div class="chart">
          <canvas id="StockTurnChart" style="min-height:150px; max-height:250px"></canvas>
        </div>
      </div>
      <div class="col-md-2">
        <ul class="chart-legend clearfix">
          <li><i class="fa fa-circle-o text-green"></i> Ideal</li>
          <li><i class="fa fa-circle-o text-red"></i> Actual</li>
          <li><i class="fa fa-circle-o text-black"></i> %</li>
        </ul>
      </div>
    </div>`;
    $("#inventory_dashboard_stock_turn_analysis").html(inventory_dashboard_stock_turn_analysis);

    if ($("#StockTurnChart").length) {
        // Get context with jQuery - using jQuery's .get() method.
        var ChartCanvas = $("#StockTurnChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var StockTurnChart = new Chart(ChartCanvas);
        let labels = [];
        for (let i = 0; i < data['valuation'].length; i++) {
            labels.push(data['valuation'][i]);
        }

        var StockTurnChartData = {
            labels: labels,
            datasets: [{
                    label: "Actual",
                    fillColor: "#00a65a", // Gray
                    strokeColor: "#00a65a",
                    pointColor: "#00a65a",
                    pointStrokeColor: "#00a65a",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: JSON.parse(
                        data['ideal']
                    ) // [1154047, 2364833, 3663974, 4954779, 6186292, 7488464, 7968665, 0, 0, 0, 0, 0 ]
                },
                {
                    label: "Target",
                    fillColor: "#dd4b39",
                    strokeColor: "#dd4b39",
                    pointColor: "#dd4b39",
                    pointStrokeColor: "#dd4b39",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: JSON.parse(
                        data['actual']
                    ) // [1250000, 2500000, 3750000, 5000000, 6250000, 7500000, 8750000, 10000000, 11250000, 12500000, 13750000, 15000000 ]
                },
                {
                    label: "Projected",
                    fillColor: "#111",
                    strokeColor: "#111",
                    pointColor: "#111",
                    pointStrokeColor: "#111",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: JSON.parse(
                        data['percent'])
                }


            ]
        };

        var StockTurnChartOptions = {
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
        StockTurnChart.Line(StockTurnChartData, StockTurnChartOptions);
    }
}

function inventoryStockLocation(data) {
    var StockvaluationbylocationCanvas = $("#StockvaluationbylocationchartsCanvas").get(0).getContext("2d");
    var Stockvaluationbylocation = new Chart(StockvaluationbylocationCanvas);
    var PieData = eval(data['data']);
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
    Stockvaluationbylocation.Doughnut(PieData, pieOptions);
    $("#StockvaluationbylocationchartsCanvas").prev(".loading_spinner").remove();
}

function inventoryStockAge(data) {
    var StockvaluationbyageCanvas = $("#StockvaluationbyagechartsCanvas").get(0).getContext("2d");
    var Stockvaluationbyage = new Chart(StockvaluationbyageCanvas);
    var PieData = eval(data['data']);
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
    Stockvaluationbyage.Doughnut(PieData, pieOptions);
    $("#StockvaluationbyagechartsCanvas").prev(".loading_spinner").remove();
}
</script>