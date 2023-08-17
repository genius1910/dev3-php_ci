<?php 

  class ItemList_model extends Model {
    
    public function __construct() {
      parent::__construct();
    }

    public function getItemCount($search_key = "", $specific_search = array()) {
      $query = $this->baseItemsQuery($search_key, $specific_search, true);
      return $this->db->get()->row();
    }

    public function getItemList($start, $length, $search_key, $specific_search, $specific_order) {
      $result = $this->getItemListDataTable($start, $length, $search_key, $specific_search, $specific_order, false);
      return $result;
    }

    public function baseItemsQuery($search_key, $specific_search, $count = true)
    {
      if (!$count)
        $this->db->select("s.branch, s.prodcode, p.description, s.attention, s.abcclass, s.K8status, s.stocksuppcode, sl.name, s.safetystock, s.totalqty, s.purchaseqty, s.overdueqty, s.qtytoorder, s.valuetoorder, s.surplusqty, s.belowsafety, s.stockage, s.avgcost, s.stockvalue, s.datelastreceived, s.stockturn, s.stockcover");
      else
        $this->db->select('COUNT(s.branch) as totalrows');
      $this->db->from('stock s');
      $this->db->join('product p', 's.prodcode = p.code', 'left');
      $this->db->join('supplier sl', 's.stocksuppcode = sl.account', 'left');

        
      $skip_columns_in_search_key = array();
  
      $specific_like = array();
  
      foreach ($specific_search as $key => $specific)
      {
        if (isset($specific) && "" != trim($specific))
        {
          $specific_like[] = "LOWER(".$key.") LIKE LOWER('%".$specific."%')";
          $skip_columns_in_search_key[] = $key;
        }
      }
  
      if (!empty($specific_like))
      {
        $specificLikePart = "(".implode(" AND ", $specific_like).")";
        $this->db->where($specificLikePart);
      }
  
      $like = array();
  
      if (isset($search_key) && "" != trim($search_key))
      {
        if (!in_array('p.description', $skip_columns_in_search_key))
        {
          $like[] = "LOWER(p.description) LIKE LOWER('%".$search_key."%')";
        }
  
        if (!in_array('sl.name', $skip_columns_in_search_key))
        {
          $like[] = "LOWER(sl.name) LIKE LOWER('%".$search_key."%')";
        }
  
        if (!in_array('s.prodcode', $skip_columns_in_search_key))
        {
          $like[] = "LOWER(s.prodcode) LIKE LOWER('%".$search_key."%')";
        }
  
        $likePart = "(".implode(" OR ", $like).")";
  
        $this->db->where($likePart);
      }
    }

    public function getItemListDataTable($offset, $limit, $search_key = "", $specific_search = "",  $specific_order = false, $with_keys = false) {
      $this->baseItemsQuery($search_key, $specific_search, false);
      $this->db->limit($limit, $offset);
      if ($specific_order)
      {
        $this->db->order_by($specific_order['by'], $specific_order['dir']);
      }

      $query = $this->db->get();

      if (!$with_keys)
      {
        return $this->numericalResult($query->result_array());
      }
      return $query->result_array();
    }

    public function numericalResult($result_array) {
      $numerical_result = array();
      foreach($result_array as $key => $item) {
        $numerical_result[$key][] = $item['branch'];
        $numerical_result[$key][] = "<a href='".site_url("itemList/detailedItem/".($item['prodcode']))."'>".$item['prodcode']."</a>";
        $numerical_result[$key][] = $item['description'];
        $numerical_result[$key][] = $item['attention'];
        $numerical_result[$key][] = $item['abcclass'];
        $numerical_result[$key][] = $item['k8status'];
        $numerical_result[$key][] = $item['stocksuppcode'];
        $numerical_result[$key][] = $item['name'];
        $numerical_result[$key][] = $item['safetystock'];
        $numerical_result[$key][] = $item['totalqty'];
        $numerical_result[$key][] = $item['purchaseqty'];
        $numerical_result[$key][] = $item['overdueqty'];
        $numerical_result[$key][] = $item['qtytoorder'];
        $numerical_result[$key][] = $item['valuetoorder'];
        $numerical_result[$key][] = $item['surplusqty'];
        $numerical_result[$key][] = $item['belowsafety'];
        $numerical_result[$key][] = $item['stockage'];
        $numerical_result[$key][] = $item['avgcost'];
        $numerical_result[$key][] = $item['stockvalue'];
        $numerical_result[$key][] = $item['datelastreceived'];
        $numerical_result[$key][] = $item['stockturn'];
        $numerical_result[$key][] = $item['stockcover'];
      }
      return $numerical_result;
    }
    
    public function getItemInfo($itemID) {
      $this->db->select("s.*, 
                        p.description, b.name,
                        p.totalqty as p_totalqty,
                        p.totalval as p_totalval,
                        p.idealqty as p_idealqty,
                        p.idealval as p_idealval,
                        p.surplusqty as p_surplusqty,
                        p.safetystock as p_safetystock,
                        p.backorderqty as p_backorderqty,
                        p.allocatedqty as p_allocatedqty,
                        p.reservedqty as p_reservedqty,
                        p.freeqty as p_freeqty,
                        p.forwardsoqty as p_forwardsoqty,
                        p.surplusval as p_surplusval,
                        p.purchaseqty as p_purchaseqty,
                        p.purchaseval as p_purchaseval,
                        p.overdueqty as p_overdueqty,
                        p.backtobackqty as p_backtobackqty,
                        p.forwardpoqty as p_forwardpoqty,
                        p.qtytoorder as p_qtytoorder,
                        p.valuetoorder as p_valuetoorder,
                        p.totalval as p_totalval,
                        p.provisionval as p_provisionval,
                        p.abcclass as p_abcclass,
                        p.abcrank as p_abcrank,
                        p.stockcover as p_stockcover,
                        p.idealstockcover as p_idealstockcover,
                        p.actualonorderstockcover as p_actualonorderstockcover,
                        p.stockturn as p_stockturn,
                        p.idealstockturn as p_idealstockturn
                      ");
      $this->db->from('stock as s');
      $this->db->join('product as p', 's.prodcode = p.code', 'left');
      $this->db->join('branch as b', 'b.branch = s.branch', 'left');
      $this->db->where('s.prodcode', $itemID);
      return $this->db->get()->row_array();
    }

    public function getPurchaseOutstandingInfo($itemID) {
      $this->db->select("p.*, s.name as s_name");
      $this->db->from('purchaseorders p');
      $this->db->join('supplier s', 's.account = p.suppliercode', 'left');
      $this->db->where('p.status', 'P');
      $this->db->where('p.prodcode', $itemID);
      $this->db->order_by('dateexpected', 'asc');
      return $this->db->get()->result_array();
    }

    public function getLastPurchaseInfo($itemID) {
      $this->db->select("p.*, s.name as s_name");
      $this->db->from('purchaseorders p');
      $this->db->join('supplier s', 's.account = p.suppliercode', 'left');
      $this->db->where('p.status', 'X');
      $this->db->order_by('datereceived', 'desc');
      $this->db->limit(5);
      return $this->db->get()->result_array();
    }

    public function getSupplierPricingInfo($itemID) {
      $this->db->select('p.*, s.name as s_name');
      $this->db->from('productsupplier p');
      $this->db->join('supplier s', 'p.suppliercode = s.account', 'left');
      $this->db->where('p.productcode', $itemID);
      return $this->db->get()->result_array();
    }

    public function getSupplierPricingInfoOne($itemID) {
      $this->db->select("p.*, s.name as s_name");
      $this->db->from("product p");
      $this->db->join("supplier s", "p.suppliercode1 = s.account", "left");
      $this->db->where('p.code', $itemID);
      return $this->db->get()->row_array();
    }

    public function getdemandVsAverage($itemID) {
      $this->db->where('productcode', $itemID);
      return $this->db->get('demand')->result_array();
    }

    public function getLocations() {
      $this->db->select('code, description');
      $this->db->from('stocklocation');
      return $this->db->get()->result_array();
    }

    public function getBranch() {
      $this->db->select('branch');
      $this->db->from('stocklocstock');
      $this->db->group_by('branch');
      return $this->db->get()->result_array();
    }

    public function getLocationsItem($itemID) {
      $this->db->select('sl.code, sl.description, sum(ss.physicalqty) physicalqty, sum(ss.resallocboqty) resallocboqty, sum(ss.freeqty) freeqty');
      $this->db->from('stocklocation sl');
      $this->db->join('stocklocstock ss', 'sl.code=ss.location', 'left');
      $this->db->where('ss.branch', $itemID);
      $this->db->group_by('sl.description');
      return $this->db->get()->result_array();
    }

    public function getLocationsAll() {
      $this->db->select('sl.code, sl.description, sum(ss.physicalqty) physicalqty, sum(ss.resallocboqty) resallocboqty, sum(ss.freeqty) freeqty');
      $this->db->from('stocklocation sl');
      $this->db->join('stocklocstock ss', 'sl.code=ss.location', 'left');
      $this->db->group_by('sl.description');
      return $this->db->get()->result_array();
    }

    public function getLocationsBranchAll() {
      $this->db->select('ss.branch, sl.code, sl.description, sum(ss.physicalqty) physicalqty, sum(ss.resallocboqty) resallocboqty, sum(ss.freeqty) freeqty');
      $this->db->from('stocklocation sl');
      $this->db->join('stocklocstock ss', 'sl.code=ss.location', 'left');
      $this->db->group_by('sl.description, ss.branch');
      return $this->db->get()->result_array();
    }

    public function getItemDetailDemandAnalysis($itemID) {
      $this->db->select('overridedemandqty, forecastdemandqty, demandqty, date');
      $this->db->from("demand");
      $this->db->where("date = LAST_DAY(date)");
      $this->db->where('identifier', 'STOCKVALUE');
      $this->db->where('period', 3);
      $this->db->where('analysiscode', '');
      $this->db->where('branch', $itemID);
      $this->db->group_by('date', 'asc');
      return $this->db->get()->result_array();
    }

    public function getImSystemInfo() {
      return $this->db->get('imsystem')->row_array();
    }

    public function getBasedInfo($level, $itemID) {
      $basedInfo['level'] = $level;

      $this->db->select("pac".$level."code");
      $this->db->from('product');
      $this->db->where('code', $itemID);
      $product_info = $this->db->get()->row_array();

      $basedInfo['product_code'] = $product_info["pac".$level."code"];
      
      $this->db->select("description");
      $this->db->from("pac".$level);
      $this->db->where('code', $basedInfo['product_code']);
      $pac_info = $this->db->get()->row_array();

      $basedInfo['description'] = $pac_info['description'];

      $this->db->select("branch");
      $this->db->from("stock");
      $this->db->where("prodcode", $itemID);
      $stock_info = $this->db->get()->row_array();

      $this->db->select("period, percentage, baseseries");
      $this->db->from("pac".$level."seasonality");
      $this->db->where("pac".$level."code", $basedInfo['product_code']);
      $this->db->where('branch', $stock_info['branch']);
      $table_data = $this->db->get()->result_array();

      $basedInfo['table_data'] = $table_data;

      return $basedInfo;
    }
  }
?>