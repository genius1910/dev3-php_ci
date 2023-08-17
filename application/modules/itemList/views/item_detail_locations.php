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

<div class="row flex gap-1">
  <div style="flex:5">
    <table class="table">
      <thead>
        <tr style="background-color: #80808094;">
          <th></th>
          <th></th>
          <th>Current</th>
          <th></th>
          <th></th>
          <th>Company</th>
          <th></th>
          <th></th>
        </tr>
        <tr>
          <th></th>
          <th>Location</th>
          <th>Physical</th>
          <th>Res/Alloc/BO</th>
          <th>Free</th>
          <th>Physical</th>
          <th>Res/Alloc/BO</th>
          <th>Free</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $total6 = 0;
          $total5 = 0;
          $total4 = 0;
          $total3 = 0;
          $total2 = 0;
          $total1 = 0;
          $data = [];
          for($i = 0; $i < count($locations_info); $i++){
            $data[$i]['code'] = $locations_info[$i]['code'];
            $data[$i]['description'] = $locations_info[$i]['description'];
            $data[$i]['current_physical'] = 0;
            $data[$i]['current_res'] = 0;
            $data[$i]['current_free'] = 0;
            $data[$i]['company_physical'] = 0;
            $data[$i]['company_res'] = 0;
            $data[$i]['company_free'] = 0;
            for($j = 0 ; $j < count($locations_item_info) ; $j++){
                if($locations_item_info[$j]['code'] == $locations_info[$i]['code'] && $locations_item_info[$j]['description'] == $locations_info[$i]['description']){
                    $data[$i]['current_physical'] = $locations_item_info[$j]['physicalqty'];
                    $data[$i]['current_res'] = $locations_item_info[$j]['resallocboqty'];
                    $data[$i]['current_free'] = $locations_item_info[$j]['freeqty'];
                    $total6 = $total6 + $locations_item_info[$j]['physicalqty'];
                    $total5 = $total5 + $locations_item_info[$j]['resallocboqty'];
                    $total4 = $total4 + $locations_item_info[$j]['freeqty'];
                }
            }
            for($j = 0 ; $j < count($locations_all_info) ; $j++){
                if($locations_all_info[$j]['code'] == $locations_info[$i]['code'] && $locations_all_info[$j]['description'] == $locations_info[$i]['description']){
                    $data[$i]['company_physical'] = $locations_all_info[$j]['physicalqty'];
                    $data[$i]['company_res'] = $locations_all_info[$j]['resallocboqty'];
                    $data[$i]['company_free'] = $locations_all_info[$j]['freeqty'];
                    $total3 = $total3 + $locations_all_info[$j]['physicalqty'];
                    $total2 = $total2 + $locations_all_info[$j]['resallocboqty'];
                    $total1 = $total1 + $locations_all_info[$j]['freeqty'];
                }
            }
          }
        ?>

        <?php foreach($data as $item): ?>
        <tr>
          <td><?= $item['code'] ?></td>
          <td><?= $item['description'] ?></td>
          <td><?= changeNum($item['current_physical'],"!",2) ?></td>
          <td><?= changeNum($item['current_res'],"!",2) ?></td>
          <td><?= changeNum($item['current_free'],"!",2) ?></td>
          <td><?= changeNum($item['company_physical'],"!",2) ?></td>
          <td><?= changeNum($item['company_res'],"!",2) ?></td>
          <td><?= changeNum($item['company_free'],"!",2) ?></td>
        </tr>
        <?php endforeach ?>
        <tr>
          <th></th>
          <th>Total(s)</th>
          <td><?= $total6 ?></td>
          <td><?= $total5 ?></td>
          <td><?= $total4 ?></td>
          <td><?= $total3 ?></td>
          <td><?= $total2 ?></td>
          <td><?= $total1 ?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div style="flex:7; overflow: overlay;">
    <table class="table">
      <thead>
        <tr style="background-color: #80808094;">
          <?php
            foreach ($branch_info as $item):
          ?>
          <th><?= $item['branch'] ?></th>
          <th></th>
          <th></th>
          <?php
            endforeach;
          ?>
        </tr>
        <tr>
          <?php
            foreach ($branch_info as $item):
          ?>
          <th>Physical</th>
          <th>Res/Alloc/BO</th>
          <th>Free</th>
          <?php
            endforeach;
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
          $data = [];
          $total = [];
          for($i = 0; $i < count($locations_info); $i++){
            for($k = 0; $k < count($branch_info); $k++){
                $data[$i][$k]["physicalqty"] = 0;
                $data[$i][$k]["resallocboqty"] = 0;
                $data[$i][$k]["freeqty"] = 0;
                $total[$k]["physicalqty"] = 0;
                $total[$k]["resallocboqty"] = 0;
                $total[$k]["freeqty"] = 0;
                for($j = 0 ; $j < count($locations_branch_all_info) ; $j++){
                    if($locations_branch_all_info[$j]['code'] == $locations_info[$i]['code'] && $locations_branch_all_info[$j]['description'] == $locations_info[$i]['description']){
                        if($locations_branch_all_info[$j]['branch'] == $branch_info[$k]['branch']){
                            $data[$i][$k]["physicalqty"] = $locations_branch_all_info[$j]["physicalqty"];
                            $data[$i][$k]["resallocboqty"] = $locations_branch_all_info[$j]["resallocboqty"];
                            $data[$i][$k]["freeqty"] = $locations_branch_all_info[$j]["freeqty"];
                            $total[$k]["physicalqty"] = $total[$k]["physicalqty"] + $locations_branch_all_info[$j]["physicalqty"];
                            $total[$k]["resallocboqty"] = $total[$k]["resallocboqty"] + $locations_branch_all_info[$j]["resallocboqty"];
                            $total[$k]["freeqty"] = $total[$k]["freeqty"] + $locations_branch_all_info[$j]["freeqty"];
                            break;
                        }
                    }
                }
            }
          }
        ?>

        <?php foreach($data as $items): ?>
            <tr>
            <?php foreach($items as $item): ?>
                <td><?= changeNum($item['physicalqty'], "!", 2) ?></td>
                <td><?= changeNum($item['resallocboqty'], "!", 2) ?></td>
                <td><?= changeNum($item['freeqty'], "!", 2) ?></td>
            <?php endforeach ?>
            </tr>
        <?php endforeach ?>
        <tr>
            <?php foreach($total as $item): ?>
                <td><?= changeNum($item['physicalqty'], "!", 2) ?></td>
                <td><?= changeNum($item['resallocboqty'], "!", 2) ?></td>
                <td><?= changeNum($item['freeqty'], "!", 2) ?></td>
            <?php endforeach ?>
            </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
</script>