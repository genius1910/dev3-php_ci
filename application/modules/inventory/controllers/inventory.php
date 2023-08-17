<?php

class Inventory extends Controller
{

  public function __construct() {
    parent::__construct();
    $this->load->model('site/site_model');
    $this->load->model('inventory/inventory_model');
    $this->load->model('branches/branches_model');
    $this->load->library('session');
  }

  public function index() {
      if ($this->site_model->is_logged_in() == false) {
          redirect('/');
      }
      $this->load->helper('cookie');
      $data['branch_list'] = $this->branches_model->getBranchesList();
      $data['supplier_list'] = $this->inventory_model->getSupplierList();

      $systemInfo = $this->inventory_model->getSystemInfo();
      $data['system_curyearmonth'] = substr($systemInfo['curyearmonth'], 0, 4)."-".substr($systemInfo['curyearmonth'], 4, 2);
      
      $data['activeInventoryOverviewSection'] = get_cookie('activeInventoryOverviewSection') ? get_cookie('activeInventoryOverviewSection') : 'Y';
      $data['activeInventoryAllOverSection'] = get_cookie('activeInventoryAllOverSection') ? get_cookie('activeInventoryAllOverSection') : 'Y';
      $data['activeInventoryAClass'] = get_cookie('activeInventoryAClass') ? get_cookie('activeInventoryAClass') : 'Y';
      $data['activeInventoryBClass'] = get_cookie('activeInventoryBClass') ? get_cookie('activeInventoryBClass') : 'Y';
      $data['activeInventoryCClass'] = get_cookie('activeInventoryCClass') ? get_cookie('activeInventoryCClass') : 'Y';
      $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
      $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
      $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
      $data['stockValuationAnalysisChart'] = get_cookie('stockValuationAnalysisChart') ? get_cookie('stockValuationAnalysisChart') : 'Y';
      $data['stockCoverAnalysisChart'] = get_cookie('stockCoverAnalysisChart') ? get_cookie('stockCoverAnalysisChart') : 'Y';
      $data['stockTurnAnalysisChart'] = get_cookie('stockTurnAnalysisChart') ? get_cookie('stockTurnAnalysisChart') : 'Y';
      $data['stockValuationByLocationChart'] = get_cookie('stockValuationByLocationChart') ? get_cookie('stockValuationByLocationChart') : 'Y';
      $data['stockValuationByAgeChart'] = get_cookie('stockValuationByAgeChart') ? get_cookie('stockValuationByAgeChart') : 'Y';

      $data['stockValuationAnalysis'] = $this->inventory_model->getStockValuationAnalysis($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
      $data['stockCoverAnalysis'] = $this->inventory_model->getStockCoverAnalysis($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
      $data['stockTurnAnalysis'] = $this->inventory_model->getStockTurnAnalysis($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
      $data['stockValuationByLocation'] = $this->inventory_model->getStockValuationByLocation($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
      $data['stockvaluationByAge'] = $this->inventory_model->getStockValuationByAge($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
      $data['inventoryAllOverSection'] = $this->inventory_model->getInventoryAllOverSection($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'])[0];
      $data['month'] = $this->inventory_model->getMonths()[0]["months"];
      $data['inventoryAClass'] = $this->inventory_model->getInventoryClass($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], 'A')[0];
      $data['inventoryBClass'] = $this->inventory_model->getInventoryClass($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], 'B')[0];
      $data['inventoryCClass'] = $this->inventory_model->getInventoryClass($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], 'C')[0];
      $data['inventoryBelowSafety'] = $this->inventory_model->getInventoryBelowSafety($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);

      // Get last updated date
      $data['lastupdated'] = $this->inventory_model->getLastUpdated();

      //All overview section
      $data['alloverviewheaderdata'] = $this->inventory_model->getAllOverViewHeaderData();

      $data['main_content'] = 'dashboard';
      $this->load->view('inventory/front_template', $data);
  }
  
  public function configuration() {
    $data['main_content'] = 'configuration';
    $data['imsystem_info'] = $this->inventory_model->getImSystemInfo();
    $data['acsf_list'] = $this->inventory_model->getClassList($data['imsystem_info']['apc']);
    $data['bcsf_list'] = $this->inventory_model->getClassList($data['imsystem_info']['bpc']);
    $data['ccsf_list'] = $this->inventory_model->getClassList($data['imsystem_info']['cpc']);
    $this->load->view('inventory/front_template', $data);
  }

  public function getStockValuationAnalysisChart() {
    $this->load->helper('cookie');
    $systemInfo = $this->inventory_model->getSystemInfo();
    $system_curyearmonth = substr($systemInfo['curyearmonth'], 0, 4)."-".substr($systemInfo['curyearmonth'], 4, 2);
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $stockValuationAnalysis = $this->inventory_model->getStockValuationAnalysis($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
    $stockvaluation = [];
    for($i = 0; $i < 12; $i++) {
      array_push($stockvaluation, date('M-y', strtotime($system_curyearmonth." - ".(12 - $i)."month")));
    }
    array_push($stockvaluation, date('M-y', strtotime($system_curyearmonth)));
    for($i = 0; $i < 12; $i++) {
      array_push($stockvaluation, date('M-y', strtotime($system_curyearmonth." + ".($i + 1)." month")));
    }
    $valuation = "[";
    $ideal = "[";
    $actual = "[";
    $percent = "[";
    for ($i = 0; $i < count($stockvaluation); $i++){
      $valuation .= $stockvaluation[$i];
      if(count($stockValuationAnalysis) == 0){
        $valuation .= "0";
        $ideal .= "0";
        $actual .= "0";
        $percent .= "0";
      }
      foreach($stockValuationAnalysis as $item):
        $ideal .= strtotime($item['date']) == $stockvaluation[$i] ? $item['actualvalue1'] : '0';
      endforeach;
      foreach($stockValuationAnalysis as $item):
        $actual .= strtotime($item['date']) == $stockvaluation[$i] ? $item['actualvalue2'] : '0';
      endforeach;
      foreach($stockValuationAnalysis as $item):
        $percent .= strtotime($item['date']) == $stockvaluation[$i] ? $item['actualvalue3'] : '0';
      endforeach;
      if($i != count($stockvaluation)-1){
        $valuation .= ",";
        $ideal .= ",";
        $actual .= ",";
        $percent .= ",";
      }
    }
    $valuation .= "]";
    $ideal .= "]";
    $actual .= "]";
    $percent .= "]";
    $chart_data=[];
    $chart_data['valuation'] = $stockvaluation;
    $chart_data['ideal'] = $ideal;
    $chart_data['actual'] = $actual;
    $chart_data['percent'] = $percent;
    echo json_encode($chart_data);
  }
  
  public function getStockCoverAnalysisChart() {
    $this->load->helper('cookie');
    $systemInfo = $this->inventory_model->getSystemInfo();
    $system_curyearmonth = substr($systemInfo['curyearmonth'], 0, 4)."-".substr($systemInfo['curyearmonth'], 4, 2);
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $stockCoverAnalysis = $this->inventory_model->getStockCoverAnalysis($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
    $stockcover = [];
    for($i = 0; $i < 12; $i++) {
    array_push($stockcover, date('M-y', strtotime($system_curyearmonth." - ".(12 - $i)."month")));
    }
    array_push($stockcover, date('M-y', strtotime($system_curyearmonth)));
    $valuation = "[";
    $ideal = "[";
    $actual = "[";
    $percent = "[";
    for ($i = 0; $i < count($stockcover); $i++){
      $valuation .= $stockcover[$i];
      if(count($stockCoverAnalysis) == 0){
        $valuation .= "0";
        $ideal .= "0";
        $actual .= "0";
        $percent .= "0";
      }
      foreach($stockCoverAnalysis as $item):
        $ideal .= strtotime($item['date']) == $stockcover[$i] ? $item['actualvalue1'] : '0';
      endforeach;
      foreach($stockCoverAnalysis as $item):
        $actual .= strtotime($item['date']) == $stockcover[$i] ? $item['actualvalue2'] : '0';
      endforeach;
      foreach($stockCoverAnalysis as $item):
        $percent .= strtotime($item['date']) == $stockcover[$i] ? $item['actualvalue3'] : '0';
      endforeach;
      if($i != count($stockcover)-1){
        $valuation .= ",";
        $ideal .= ",";
        $actual .= ",";
        $percent .= ",";
      }
    }
    $valuation .= "]";
    $ideal .= "]";
    $actual .= "]";
    $percent .= "]";
    $chart_data=[];
    $chart_data['valuation'] = $stockcover;
    $chart_data['ideal'] = $ideal;
    $chart_data['actual'] = $actual;
    $chart_data['percent'] = $percent;
    echo json_encode($chart_data);
  }
  
  public function getStockTurnAnalysisChart() {
    $this->load->helper('cookie');
    $systemInfo = $this->inventory_model->getSystemInfo();
    $system_curyearmonth = substr($systemInfo['curyearmonth'], 0, 4)."-".substr($systemInfo['curyearmonth'], 4, 2);
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $stockTurnAnalysis = $this->inventory_model->getStockTurnAnalysis($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
    $stockturn = [];
    for($i = 0; $i < 12; $i++) {
      array_push($stockturn, date('M-y', strtotime($system_curyearmonth." - ".(12 - $i)."month")));
    }
    array_push($stockturn, date('M-y', strtotime($system_curyearmonth)));
    $valuation = "[";
    $ideal = "[";
    $actual = "[";
    $percent = "[";
    for ($i = 0; $i < count($stockturn); $i++){
      $valuation .= $stockturn[$i];
      if(count($stockTurnAnalysis) == 0){
        $valuation .= "0";
        $ideal .= "0";
        $actual .= "0";
        $percent .= "0";
      }
      foreach($stockTurnAnalysis as $item):
        $ideal .= strtotime($item['date']) == $stockturn[$i] ? $item['actualvalue1'] : '0';
      endforeach;
      foreach($stockTurnAnalysis as $item):
        $actual .= strtotime($item['date']) == $stockturn[$i] ? $item['actualvalue2'] : '0';
      endforeach;
      foreach($stockTurnAnalysis as $item):
        $percent .= strtotime($item['date']) == $stockturn[$i] ? $item['actualvalue3'] : '0';
      endforeach;
      if($i != count($stockturn)-1){
        $valuation .= ",";
        $ideal .= ",";
        $actual .= ",";
        $percent .= ",";
      }
    }
    $valuation .= "]";
    $ideal .= "]";
    $actual .= "]";
    $percent .= "]";
    $chart_data=[];
    $chart_data['valuation'] = $stockturn;
    $chart_data['ideal'] = $ideal;
    $chart_data['actual'] = $actual;
    $chart_data['percent'] = $percent;
    echo json_encode($chart_data);
  }

  public function getStockLocationChart() {
    $this->load->helper('cookie');
    $systemInfo = $this->inventory_model->getSystemInfo();
    $system_curyearmonth = substr($systemInfo['curyearmonth'], 0, 4)."-".substr($systemInfo['curyearmonth'], 4, 2);
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $stockValuationByLocation = $this->inventory_model->getStockValuationByLocation($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
    $color = ['#f012be','#00a65a','#39cccc','#3d9970','#d2d6de','#01ff70','#ff851b','#605ca8','#001f3f','#3c8dbc','#dd4b39','#f39c12','#00c0ef','#d81b60'];
    $chart_data['data'] = "[";
    for ($i = 0 ; $i < count($stockValuationByLocation) ; $i++) {
      $value = $stockValuationByLocation[$i]['actualvalue1'];
      $ordstatus = $stockValuationByLocation[$i]['description'];
      $colorValue = $color[$i % 14];
      $chart_data['data'] .= "{value:$value,color:'$colorValue',highlight:'$colorValue',label:'$ordstatus'}";
      
      if($i != count($stockValuationByLocation)-1){
        $chart_data['data'] .= ",";
      }
    }
    $chart_data['data'] .= "]";
    echo json_encode($chart_data);
  }

  public function getStockAgeChart() {
    $this->load->helper('cookie');
    $systemInfo = $this->inventory_model->getSystemInfo();
    $system_curyearmonth = substr($systemInfo['curyearmonth'], 0, 4)."-".substr($systemInfo['curyearmonth'], 4, 2);
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $stockvaluationByAge = $this->inventory_model->getStockValuationByAge($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName']);
    $color = ['#f012be','#00a65a','#39cccc','#3d9970','#d2d6de','#01ff70','#ff851b','#605ca8','#001f3f','#3c8dbc','#dd4b39','#f39c12','#00c0ef','#d81b60'];
    $chart_data['data'] = "[";
    for ($i = 0 ; $i < count($stockvaluationByAge) ; $i++) {
      $value = $stockvaluationByAge[$i]['actualvalue1'];
      $ordstatus = $stockvaluationByAge[$i]['description'];
      $colorValue = $color[$i % 14];
      $chart_data['data'] .= "{value:$value,color:'$colorValue',highlight:'$colorValue',label:'$ordstatus'}";
      
      if($i != count($stockvaluationByAge)-1){
        $chart_data['data'] .= ",";
      }
    }
    $chart_data['data'] .= "]";
    echo json_encode($chart_data);
  }

  public function getInventoryBelowSafety() {
    $this->load->helper('cookie');
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $inventoryBelowSafety = $this->inventory_model->getInventoryBelowSafety($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], $_POST["data"]);    
    echo json_encode($inventoryBelowSafety);
  }

  public function getInventoryNeedToOrder() {
    $this->load->helper('cookie');
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $inventoryNeedToOrder = $this->inventory_model->getInventoryNeedToOrder($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], $_POST["data"]);    
    echo json_encode($inventoryNeedToOrder);
  }

  public function getInventoryStockCover() {
    $this->load->helper('cookie');
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $inventoryNeedToOrder = $this->inventory_model->getInventoryStockCover($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], $_POST["data"]);    
    echo json_encode($inventoryNeedToOrder);
  }

  public function getInventoryOverduePOLines() {
    $this->load->helper('cookie');
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $inventoryNeedToOrder = $this->inventory_model->getInventoryOverduePOLines($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], $_POST["data"]);    
    echo json_encode($inventoryNeedToOrder);
  }

  public function getInventoryStockTurn() {
    $this->load->helper('cookie');
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $inventoryNeedToOrder = $this->inventory_model->getInventoryStockTurn($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], $_POST["data"]);    
    echo json_encode($inventoryNeedToOrder);
  }

  public function getInventorySurplusStock() {
    $this->load->helper('cookie');
    $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
    $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
    $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';
    $inventoryNeedToOrder = $this->inventory_model->getInventorySurplusStock($data['activeBranchName'], $data['activeDistriBranchName'], $data['activeSupplierName'], $_POST["data"]);    
    echo json_encode($inventoryNeedToOrder);
  }
}