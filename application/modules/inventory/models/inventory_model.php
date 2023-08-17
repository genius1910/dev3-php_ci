<?php

  class Inventory_model extends Model {

    private $system_info;

    public function __construct() {
      parent::__construct();
      $this->load->library('session');
      $system_info = $this->getSystemInfo();
    }

    public function getSystemInfo() {
      return $this->db->get('system')->row_array();
    }

    public function getSupplierList() {
      $this->db->select("account, name");
      $this->db->from('supplier');
      return $this->db->get()->result_array();
    }

    public function getStockValuationAnalysis($branch, $distBranch, $supplierCode) {
      $this->db->select('actualvalue1, actualvalue2, actualvalue3, date');
      $this->db->from("imkpidata");
      $this->db->where("date = LAST_DAY(date)");
      $this->db->where('identifier', 'STOCKVALUE');
      $this->db->where('period', 3);
      $this->db->where('analysiscode', '');
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      $this->db->group_by('date', 'asc');
      return $this->db->get()->result_array();
    }

    public function getStockCoverAnalysis($branchName, $distriBranchName, $supplierCode) {
      $this->db->select('actualvalue1, actualvalue2, actualvalue3, date');
      $this->db->from("imkpidata");
      $this->db->where("date = LAST_DAY(date)");
      $this->db->where('identifier', 'STOCKCOVER');
      $this->db->where('period', 3);
      $this->db->where('analysiscode', '');
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      $this->db->group_by('date', 'asc');
      return $this->db->get()->result_array();
    }

    public function getStockTurnAnalysis($branchName, $distriBranchName, $supplierCode) {
      $this->db->select('actualvalue1, actualvalue2, actualvalue3, date');
      $this->db->from("imkpidata");
      $this->db->where("date = LAST_DAY(date)");
      $this->db->where('identifier', 'STOCKTURN');
      $this->db->where('period', 3);
      $this->db->where('analysiscode', '');
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      $this->db->group_by('date', 'asc');
      return $this->db->get()->result_array();
    }

    public function getStockValuationByLocation($branchName, $distriBranchName, $supplierCode) {
      $this->db->select('im.actualvalue1, im.actualvalue2, im.actualvalue3, im.actualvalue4, im.date, sl.description');
      $this->db->from("imkpidata im");
      $this->db->join("stocklocation sl", 'im.analysiscode = sl.code', 'left');
      $this->db->where("im.date = LAST_DAY(date)");
      $this->db->where('im.identifier', 'STOCKVALUELOCATION');
      $this->db->where('im.period', 3);
      $this->db->where('im.branch', $branch);
      $this->db->where('im.distbranch', $distBranch);
      $this->db->where('im.suppliercode', $supplierCode);
      $this->db->group_by('im.date', 'asc');
      return $this->db->get()->result_array();
    }

    public function getStockValuationByAge($branchName, $distriBranchName, $supplierCode) {
      $this->db->select('im.actualvalue1, im.actualvalue2, im.actualvalue3, im.actualvalue4, im.date, sl.description');
      $this->db->from("imkpidata im");
      $this->db->join("stocklocation sl", 'im.analysiscode = sl.code', 'left');
      $this->db->where("im.date = LAST_DAY(date)");
      $this->db->where('im.identifier', 'STOCKVALUEAGE');
      $this->db->where('im.period', 3);
      $this->db->where('im.branch', $branch);
      $this->db->where('im.distbranch', $distBranch);
      $this->db->where('im.suppliercode', $supplierCode);
      $this->db->group_by('im.date', 'asc');
      return $this->db->get()->result_array();
    }

    public function getAllOverViewHeaderData() {
      $this->db->select("*");
      $this->db->from('imkpidata');
      $this->db->where('identifier', 'ABCANALYSIS');
      $this->db->where('period', 1);
      $this->db->where('date = CURRENT_DATE');
      $this->db->where('analysiscode', '');
      return $this->db->get()->result_array();
    }

    public function getInventoryAllOverSection($branch, $distBranch, $supplierCode) {
      $this->db->select('actualvalue1, actualvalue2');
      $this->db->from("imkpidata");
      $this->db->where("date = CURRENT_DATE()");
      $this->db->where('identifier', 'ABCANALYSIS');
      $this->db->where('period', 1);
      $this->db->where('analysiscode', '');
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      return $this->db->get()->result_array();
    }

    public function getInventoryClass($branch, $distBranch, $supplierCode, $analysiscode ) {
      $this->db->select('actualvalue1, actualvalue2');
      $this->db->from("imkpidata");
      $this->db->where("date = CURRENT_DATE()");
      $this->db->where('identifier', 'ABCANALYSIS');
      $this->db->where('period', 1);
      $this->db->where('analysiscode', $analysiscode);
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      return $this->db->get()->result_array();
    }

    public function getInventoryBelowSafety($branch, $distBranch, $supplierCode, $analysiscode) {
      $this->db->select('actualvalue1, actualvalue2');
      $this->db->from("imkpidata");
      $this->db->where("date = CURRENT_DATE()");
      $this->db->where('identifier', 'BELOWSAFETY');
      $this->db->where('period', 1);
      $this->db->where('analysiscode', $analysiscode);
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      return $this->db->get()->result_array();
    }

    public function getInventoryNeedToOrder($branch, $distBranch, $supplierCode, $analysiscode) {
      $this->db->select('actualvalue1, actualvalue2');
      $this->db->from("imkpidata");
      $this->db->where("date = CURRENT_DATE()");
      $this->db->where('identifier', 'NEEDTOORDER');
      $this->db->where('period', 1);
      $this->db->where('analysiscode', $analysiscode);
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      return $this->db->get()->result_array();
    }

    public function getInventoryStockCover($branch, $distBranch, $supplierCode, $analysiscode) {
      $this->db->select('actualvalue1, actualvalue2');
      $this->db->from("imkpidata");
      $this->db->where("date = CURRENT_DATE()");
      $this->db->where('identifier', 'STOCKCOVER');
      $this->db->where('period', 1);
      $this->db->where('analysiscode', $analysiscode);
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      return $this->db->get()->result_array();
    }

    public function getInventoryOverduePOLines($branch, $distBranch, $supplierCode, $analysiscode) {
      $this->db->select('actualvalue1, actualvalue2');
      $this->db->from("imkpidata");
      $this->db->where("date = CURRENT_DATE()");
      $this->db->where('identifier', 'OVERDUEPOLINES');
      $this->db->where('period', 1);
      $this->db->where('analysiscode', $analysiscode);
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      return $this->db->get()->result_array();
    }

    public function getInventoryStockTurn($branch, $distBranch, $supplierCode, $analysiscode) {
      $this->db->select('actualvalue1, actualvalue2');
      $this->db->from("imkpidata");
      $this->db->where("date = CURRENT_DATE()");
      $this->db->where('identifier', 'STOCKTURN');
      $this->db->where('period', 1);
      $this->db->where('analysiscode', $analysiscode);
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      return $this->db->get()->result_array();
    }

    public function getInventorySurplusStock($branch, $distBranch, $supplierCode, $analysiscode) {
      $this->db->select('actualvalue1, actualvalue2, actualvalue3');
      $this->db->from("imkpidata");
      $this->db->where("date = CURRENT_DATE()");
      $this->db->where('identifier', 'SURPLUSSTOCK');
      $this->db->where('period', 1);
      $this->db->where('analysiscode', $analysiscode);
      $this->db->where('branch', $branch);
      $this->db->where('distbranch', $distBranch);
      $this->db->where('suppliercode', $supplierCode);
      return $this->db->get()->result_array();
    }

    public function getMonths(){
      $this->db->select('months');
      $this->db->from("imsystem");
      return $this->db->get()->result_array();
    }

    public function getLastUpdated() {
      return $this->db->get('imsystem')->row_array()['kpislastupdated'];
    }

    public function getImSystemInfo() {
      return $this->db->get('imsystem')->row_array();
    }

    public function getClassList($pc) {
      return $this->db->get_where('customerservicefactors', array('servicelevelpc' => $pc))->row_array();
    }
  }
?>