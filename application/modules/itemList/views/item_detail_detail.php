<div class="row">
  <div class="col-md-4 col-sm-12">
    <table class="table">
      <thead>
        <tr>
          <th></th>
          <th colspan="2">
            <center>Branch</center>
          </th>
          <th colspan="2">
            <center>Central</center>
          </th>
        </tr>
        <tr>
          <th><b>PHYSICAL STOCK</b></th>
          <th>#</th>
          <th>£</th>
          <th>#</th>
          <th>£</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Actual</td>
          <td><?php echo changeNum($stock_info['totalqty'], null, 2) ?></td>
          <td><?php echo changeNum($stock_info['totalval'], "£", 2) ?></td>
          <td><?php echo changeNum($stock_info['p_totalqty'], null, 2) ?></td>
          <td><?php echo changeNum($stock_info['p_totalval'], "£", 2) ?></td>
        </tr>
        <tr>
          <td>Ideal</td>
          <td><?php echo changeNum($stock_info['idealqty'], null, 2) ?></td>
          <td><?php echo changeNum($stock_info['idealval'], "£", 2) ?></td>
          <td><?php echo changeNum($stock_info['p_idealqty'], null, 2) ?></td>
          <td><?php echo changeNum($stock_info['p_idealval'], "£", 2) ?></td>
        </tr>
        <tr>
          <td>Difference</td>
          <td
            class="<?= ($stock_info['totalqty'] - $stock_info['idealqty']) == 0 ? 'bg-green' : (($stock_info['totalqty'] - $stock_info['idealqty']) <= $stock_info['idealqty']/10  ? 'bg-Warning' : 'bg-red') ?>">
            <?php echo changeNum($stock_info['totalqty'] - $stock_info['idealqty'], null, 2) ?>
          </td>
          <td
            class="<?= ($stock_info['totalval'] - $stock_info['idealval']) == 0 ? 'bg-green' : (($stock_info['totalval'] - $stock_info['idealval']) <= $stock_info['idealval']/10  ? 'bg-Warning' : 'bg-red') ?>">
            <?php echo changeNum($stock_info['totalval'] - $stock_info['idealval'], "£", 2) ?></td>
          <td
            class="<?= ($stock_info['p_totalqty'] - $stock_info['p_idealqty']) == 0 ? 'bg-green' : (($stock_info['p_totalqty'] - $stock_info['p_idealqty']) <= $stock_info['p_idealqty']/10  ? 'bg-Warning' : 'bg-red') ?>">
            <?php echo changeNum($stock_info['p_totalqty'] - $stock_info['p_idealqty'], null, 2) ?></td>
          <td
            class="<?= ($stock_info['p_totalval'] - $stock_info['p_idealval']) == 0 ? 'bg-green' : (($stock_info['p_totalval'] - $stock_info['p_idealval']) <= $stock_info['p_idealval']/10  ? 'bg-Warning' : 'bg-red') ?>">
            <?php echo changeNum($stock_info['p_totalval'] - $stock_info['p_idealval'], "£", 2) ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table">
      <thead>
        <th><b>SAFETY STOCK</b></th>
        <th><b>#</b></th>
        <th><b>#</b></th>
      </thead>
      <tbody>
        <tr>
          <td>Ideal</td>
          <td><?php echo changeNum($stock_info['safetystock'], null, 2) ?></td>
          <td><?php echo changeNum($stock_info['p_safetystock'], null, 2) ?></td>
        </tr>
        <tr>
          <td>Difference</td>
          <td
            class="<?= ($stock_info['totalval'] - $stock_info['safetystock']) == 0 ? 'bg-green' : (($stock_info['totalval'] - $stock_info['safetystock']) <= $stock_info['safetystock']/10  ? 'bg-Warning' : 'bg-red') ?>">
            <?php echo changeNum($stock_info['totalval'] - $stock_info['safetystock'], null, 2) ?></td>
          <td
            class="<?= ($stock_info['totalqty'] - $stock_info['p_safetystock']) == 0 ? 'bg-green' : (($stock_info['totalqty'] - $stock_info['p_safetystock']) <= $stock_info['p_safetystock']/10  ? 'bg-Warning' : 'bg-red') ?>">
            <?php echo changeNum($stock_info['totalqty'] - $stock_info['p_safetystock'], null, 2) ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table">
      <thead>
        <tr>
          <th><b>STOCK LEVELS</b></th>
          <th colspan="2"><b>#</b></th>
          <th colspan="2"><b>#</b></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Customer Backorder</td>
          <td colspan="2">
            <?php echo changeNum($stock_info['backorderqty'], null, 2) ?>
          </td>
          <td colspan="2">
            <?php echo changeNum($stock_info['p_backorderqty'], null, 2) ?>
          </td>
        </tr>
        <tr>
          <td>Allocated</td>
          <td colspan="2"><?php echo changeNum($stock_info['allocatedqty'], null, 2) ?></td>
          <td colspan="2"><?php echo changeNum($stock_info['p_allocatedqty'], null, 2) ?></td>
        </tr>
        <tr>
          <td>Reserved</td>
          <td colspan="2"><?php echo changeNum($stock_info['reservedqty'], null, 2) ?></td>
          <td colspan="2"><?php echo changeNum($stock_info['p_reservedqty'], null, 2) ?></td>
        </tr>
        <tr>
          <td>Free</td>
          <td colspan="2"><?php echo changeNum($stock_info['freeqty'], null, 2) ?></td>
          <td colspan="2"><?php echo changeNum($stock_info['p_freeqty'], null, 2) ?></td>
        </tr>
        <tr>
          <td>Forward S/Os</td>
          <td colspan="2"><?php echo changeNum($stock_info['forwardsoqty'], null, 2) ?></td>
          <td colspan="2"><?php echo changeNum($stock_info['p_forwardsoqty'], null, 2) ?></td>
        </tr>
        <tr>
          <td>Surplus Stock</td>
          <td class="<?= $stock_info['surplusqty'] > 0 ? 'bg-error' : '' ?>"><?php echo changeNum($stock_info['surplusqty'], null,2) ?></td>
          <td class="<?= $stock_info['surplusval'] > 0 ? 'bg-error' : '' ?>"><?php echo changeNum($stock_info['surplusval'],"£",2) ?></td>
          <td class="<?= $stock_info['p_surplusqty'] > 0 ? 'bg-error' : '' ?>"><?php echo changeNum($stock_info['p_surplusqty'], null,2) ?></td>
          <td class="<?= $stock_info['p_surplusval'] > 0 ? 'bg-error' : '' ?>"><?php echo changeNum($stock_info['p_surplusval'],"£",2) ?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-4 col-sm-12">
    <table class="table">
      <thead>
        <tr>
          <th></th>
          <th colspan="2">
            <center>Branch</center>
          </th>
          <th colspan="2">
            <center>Central</center>
          </th>
        </tr>
        <tr>
          <th><b>PURCHASES</b></th>
          <th>#</th>
          <th>£</th>
          <th>#</th>
          <th>£</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>On Order</td>
          <td><?php echo changeNum($stock_info['purchaseqty'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['purchaseval'],"£",2) ?></td>
          <td><?php echo changeNum($stock_info['p_purchaseqty'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_purchaseval'],"£",2) ?></td>
        </tr>
        <tr>
          <td>Overdue</td>
          <td
            class="<?= $stock_info['overdueqty'] > 0 && $stock_info['totalqty'] - $stock_info['safetystock'] < 0 ? 'bg-red' : '' ?>">
            <?php echo changeNum($stock_info['overdueqty'], null,2) ?></td>
          <td></td>
          <td
            class="<?= $stock_info['p_overdueqty'] > 0 && $stock_info['p_totalqty'] - $stock_info['p_safetystock'] < 0 ? 'bg-red' : '' ?>">
            <?php echo changeNum($stock_info['p_overdueqty'], null,2) ?></td>
          <td></td>
        </tr>
        <tr>
          <td>Back To Back</td>
          <td><?php echo changeNum($stock_info['backtobackqty'], null,2) ?></td>
          <td></td>
          <td><?php echo changeNum($stock_info['p_backtobackqty'], null,2) ?></td>
          <td></td>
        </tr>
        <tr>
          <td>Forward P/Os</td>
          <td><?php echo changeNum($stock_info['forwardpoqty'], null,2) ?></td>
          <td></td>
          <td><?php echo changeNum($stock_info['p_forwardpoqty'], null,2) ?></td>
          <td></td>
        </tr>
        <tr>
          <td>
            Need To Order
          </td>
          <td class="<?= $stock_info['qtytoorder'] > 0 ? 'bg-red' : '' ?>"><?php echo changeNum($stock_info['qtytoorder'], null,2) ?></td>
          <td class="<?= $stock_info['valuetoorder'] > 0 ? 'bg-red' : '' ?>"><?php echo changeNum($stock_info['valuetoorder'], "£",2) ?></td>
          <td class="<?= $stock_info['p_qtytoorder'] > 0 ? 'bg-red' : '' ?>"><?php echo changeNum($stock_info['p_qtytoorder'], null,2) ?></td>
          <td class="<?= $stock_info['p_valuetoorder'] > 0 ? 'bg-red' : '' ?>"><?php echo changeNum($stock_info['p_valuetoorder'], "£",2) ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table">
      <thead>
        <tr>
          <th>STOCK VALUE</th>
          <th>%</th>
          <th>£</th>
          <th></th>
          <th>£</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Value</td>
          <td></td>
          <td><?php echo changeNum($stock_info['totalval'], "£",2) ?></td>
          <td></td>
          <td><?php echo changeNum($stock_info['p_totalval'], "£",2) ?></td>
        </tr>
        <tr>
          <td>Provision</td>
          <td><?php echo changeNum($stock_info['provisionpc'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['provisionval'], "£",2) ?></td>
          <td></td>
          <td><?php echo changeNum($stock_info['p_provisionval'], "£",2) ?></td>
        </tr>
        <tr>
          <td>Net Stock</td>
          <td></td>
          <td><?php echo changeNum($stock_info['netstockval'], "£",2) ?></td>
          <td></td>
          <td><?php echo changeNum($stock_info['p_netstockval'], "£",2) ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table">
      <thead>
        <tr>
          <th>STOCK AGE</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Date Last Received</td>
          <td colspan="2"><?= $stock_info['datelastreceived'] ?></td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td>Stock Age</td>
          <td colspan="2"><?= $stock_info['stockage'] ?></td>
          <td colspan="2"></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-4 col-sm-12">
    <table class="table">
      <thead>
        <tr>
          <th>ABC</th>
          <th>Branch</th>
          <th>Central</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Classification</td>
          <td><?= $stock_info['abcclass'] ?></td>
          <td><?= $stock_info['p_abcclass'] ?></td>
        </tr>
        <tr>
          <td>Item Rank</td>
          <td><?= $stock_info['abcrank'] ?></td>
          <td><?= $stock_info['p_abcrank'] ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table">
      <thead>
        <tr>
          <th>STOCK COVER</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Actual</td>
          <td><?php echo changeNum($stock_info['stockcover'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_stockcover'], null,2) ?></td>
        </tr>
        <tr>
          <td>Ideal</td>
          <td><?php echo changeNum($stock_info['idealstockcover'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_idealstockcover'], null,2) ?></td>
        </tr>
        <tr>
          <td>Difference</td>
          <td><?php echo changeNum($stock_info['idealstockcover'] - $stock_info['stockcover'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_idealstockcover'] - $stock_info['p_stockcover'], null,2) ?></td>
        </tr>
        <tr>
          <td>Actual + On Order</td>
          <td><?php echo changeNum($stock_info['actualonorderstockcover'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_actualonorderstockcover'], null,2) ?></td>
        </tr>
        <tr>
          <td>Difference</td>
          <td><?php echo changeNum($stock_info['actualonorderstockcover'] - $stock_info['stockcover'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_actualonorderstockcover'] - $stock_info['stockcover'], null,2) ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table">
      <thead>
        <tr>
          <th>STOCK TURN</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Actual</td>
          <td><?php echo changeNum($stock_info['stockturn'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_stockturn'], null,2) ?></td>
        </tr>
        <tr>
          <td>Ideal</td>
          <td><?php echo changeNum($stock_info['idealstockturn'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_idealstockturn'], null,2) ?></td>
        </tr>
        <tr>
          <td>Difference</td>
          <td><?php echo changeNum($stock_info['idealstockturn'] - $stock_info['stockturn'], null,2) ?></td>
          <td><?php echo changeNum($stock_info['p_idealstockturn'] - $stock_info['p_stockturn'], null,2) ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>