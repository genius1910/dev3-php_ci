<?php 

  class itemList extends Controller {
    
    public function __construct() {
      parent::__construct();
      $this->load->model('inventory/inventory_model');
      $this->load->model('branches/branches_model');
      $this->load->model('itemList_model');
      $this->load->model('site/site_model');
    }

    public function index() {

        $data['branch_list'] = $this->branches_model->getBranchesList();
        $data['supplier_list'] = $this->inventory_model->getSupplierList();
        $this->load->helper('cookie');
        $data['activeBranchName'] = get_cookie('inventoryBranchName') ? get_cookie('inventoryBranchName') : '0';
        $data['activeDistriBranchName'] = get_cookie('inventoryDistriBranchName') ? get_cookie('inventoryDistriBranchName') : '0';
        $data['activeSupplierName'] = get_cookie('inventorySupplierName') ? get_cookie('inventorySupplierName') : '000100';   
        
        $data['lastupdated'] = $this->inventory_model->getLastUpdated();

        $data['main_content'] = 'item_list';
        $this->load->view('inventory/front_template', $data);
    }

    public function fetchItemList() {
      $this->urlFn = "item_list";
      header("Content-Type: application/json");
      $draw = 1;
      $count = 0;
      $data = array();
  
      $return_array = array
      (
        'draw' => $draw,
        'recordsTotal' => $count,
        'recordsFiltered' => $count,
        'data' => $data,
        'with' => array('columns' => $_POST['columns']),
      );
  
      if ($this->site_model->is_logged_in() == false) {
        echo json_encode($return_array);
        exit;
      }

      $start = isset($_POST['start']) ? $_POST['start'] : 0;
      $length = isset($_POST['length']) ? $_POST['length'] : $limit;
      $search_key = isset($_POST['search']['value']) ? $_POST['search']['value'] : "";
      $draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
          
      $specific_search = $this->findPostedSpecificSearchAndMakec();
      $specific_order = $this->findPostedOrder();

      $totalCount = $this->itemList_model->getItemCount();
      $recordsTotal = $totalCount->totalrows;

      $filteredCount = $this->itemList_model->getItemCount($search_key, $specific_search);
      $recordsFiltered = $filteredCount->totalrows;

      $result = $this->itemList_model->getItemList($start, $length, $search_key, $specific_search, $specific_order);

      $return_array = array
      (
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $result,
        'with' => array('columns' => $_POST['columns']),
      );

      echo json_encode($return_array);
      exit;
    }
    
    public function findPostedSpecificSearchAndMakec()
    {
      $posted_columns = $_POST['columns'];
      $search_keys = $this->getSpecificSearchKeys();
      $search = array();
  
      foreach ($posted_columns as $key => $col) {
        $search[$search_keys[$key]] = $col['search']['value'];
      }
  
      return $search;
    }

    public function findPostedOrder()
    {
      $posted_order = $_POST['order'];
      $column_index = -1;
      $order = array(
        'by' => $search_keys[0],
        'dir' => 'asc'
      );

      if (isset($posted_order[0]['column']) && isset($posted_order[0]['dir'])) {
        $column_index = $posted_order[0]['column'];
      }

      $search_keys = $this->getSpecificSearchKeys();
      if ($column_index >= 0) {
        $order = array(
          'by' => $search_keys[$column_index],
          'dir' => $posted_order[0]['dir']
        );
      } else {
        $order = array(
          'by' => $search_keys[0],
          'dir' => 'asc'
        );
      }

      return $order;
    }

    public function detailedItem($itemID) {
      $this->load->helper('cookie');
      $data['activeDetailedItemTabName'] = get_cookie('detailedItemTabName') ? get_cookie('detailedItemTabName') : 'Detail';
      $data['itemInfo'] = $this->itemList_model->getItemInfo($itemID);
      $data['lastupdated'] = $this->inventory_model->getLastUpdated();
      $data['itemID'] = $itemID;
      $data['main_content'] = 'item_detail';
      
      $this->load->helper('cookie');
      $data['activeTab'] = get_cookie('inventoryItemDetailTab') ? get_cookie('inventoryItemDetailTab') : 'detail';

      $this->load->view('inventory/front_template', $data);
    }

    public function getSpecificSearchKeys()
    {
      $search_keys = array('s.branch', 's.prodcode', 'p.description', 's.attention', 's.abcclass', 's.K8status', 's.stocksuppcode', 'sl.name', 's.safetystock', 's.totalqty', 's.purchaseqty', 's.overdueqty', 's.qtytoorder', 's.valuetoorder', 's.surplusqty', 's.belowsafety', 's.stockage', 's.avgcost', 's.stockvalue', 's.datelastreceived', 's.stockturn', 's.stockcover');
      return $search_keys;
    }

    public function getItemDetail_detail() {
      $itemID = $this->input->post('itemID');
      $data['stock_info'] = $this->itemList_model->getItemInfo($itemID);
      $this->load->view('itemList/item_detail_detail', $data);
    }

    public function getItemDetail_purchaseOrders() {
      $itemID = $this->input->post('itemID');
      $data['outstanding_info'] = $this->itemList_model->getPurchaseOutstandingInfo($itemID);
      $data['lastpurchase_info'] = $this->itemList_model->getLastPurchaseInfo($itemID);
      $data['supplierPricing_info'] = $this->itemList_model->getSupplierPricingInfo($itemID);
      $data['supplierPricingOne_info'] = $this->itemList_model->getSupplierPricingInfoOne($itemID);
      $this->load->view('itemList/item_detail_purchase', $data);
    }

    public function getItemDetail_demandVsAverage() {
      $itemID = $this->input->post('itemID');
      $data['demandVsAverage_info'] = $this->itemList_model->getdemandVsAverage($itemID);
      $this->load->view('itemList/item_detail_demandVsAverage', $data);
    }

    public function getItemDetail_locations() {
      $itemID = $this->input->post('itemID');
      $data['locations_info'] = $this->itemList_model->getLocations();
      $data['branch_info'] = $this->itemList_model->getBranch();
      $data['locations_item_info'] = $this->itemList_model->getLocationsItem($itemID);
      $data['locations_all_info'] = $this->itemList_model->getLocationsAll();
      $data['locations_branch_all_info'] = $this->itemList_model->getLocationsBranchAll();
      $this->load->view('itemList/item_detail_locations', $data);
    }

    public function getItemDetail_demandAnalysis() {
      $itemID = $this->input->post('itemID');
      $data['demandAnalysis'] = $this->itemList_model->getItemDetailDemandAnalysis($itemID);
      $this->load->view('itemList/item_detail_demandAnalysis', $data);
    }

    public function getItemDetail_seasonalityProfile() {
      $itemID = $this->input->post('itemID');
      $imSystemInfo = $this->itemList_model->getImSystemInfo();
      $data['based_info'] = $this->itemList_model->getBasedInfo($imSystemInfo['seasonalityprofilelevel'], $itemID);
      $this->load->view('itemList/item_detail_seasonalityProfile', $data);
    }
  }
?>