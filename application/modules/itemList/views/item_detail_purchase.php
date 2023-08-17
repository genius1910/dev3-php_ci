<div class="row">
  <div class="col-md-12">
    <h3>Outstanding Purchase Orders</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Branch</th>
          <th>Order No.</th>
          <th>Supplier</th>
          <th>Supplier Name</th>
          <th>Order Qty</th>
          <th>Price</th>
          <th>Cost</th>
          <th>Date Ordered</th>
          <th>Date Expected</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($outstanding_info as $item): ?>
        <tr>
          <td><?= $item['branch'] ?></td>
          <td><?= $item['orderno'] ?></td>
          <td><?= $item['suppliercode'] ?></td>
          <td><?= $item['s_name'] ?></td>
          <td><?= $item['orderquantity'] ?></td>
          <td><?= $item['price'] ?></td>
          <td><?= $item['cost'] ?></td>
          <td><?= $item['dateactivated'] ?></td>
          <td><?= $item['dateexpected'] ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>

    <h3>Last 5 Purchase Orders</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Branch</th>
          <th>Order No.</th>
          <th>Supplier</th>
          <th>Supplier Name</th>
          <th>Order Qty</th>
          <th>Price</th>
          <th>Cost</th>
          <th>Date Ordered</th>
          <th>Date Expected</th>
          <th>Date Received</th>
          <th>Qty Received</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($lastpurchase_info as $item): ?>
        <tr>
          <td><?= $item['branch'] ?></td>
          <td><?= $item['orderno'] ?></td>
          <td><?= $item['suppliercode'] ?></td>
          <td><?= $item['s_name'] ?></td>
          <td><?= $item['orderquantity'] ?></td>
          <td><?= $item['price'] ?></td>
          <td><?= $item['cost'] ?></td>
          <td><?= $item['dateactivated'] ?></td>
          <td><?= $item['dateexpected'] ?></td>
          <td><?= $item['datereceived'] ?></td>
          <td><?= $item['receivedquantity'] ?></td>
        </tr>
        <?php endforeach ?>
        <?php ?>
      </tbody>
    </table>

    <h3>Supplier(s) and Pricing</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Effective From</th>
          <th>Supplier</th>
          <th>Supplier Name</th>
          <th>Buy Unit</th>
          <th>Pack</th>
          <th>MOQ</th>
          <th>Cost Unit</th>
          <th>Base Cost</th>
          <th>Discount 1</th>
          <th>Discount 2</th>
          <th>Discount 3</th>
          <th>Net Cost</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= $supplierPricingOne_info['costeffectivefrom'] ?></td>
          <td><?= $supplierPricingOne_info['suppliercode1'] ?></td>
          <td><?= $supplierPricingOne_info['s_name'] ?></td>
          <td><?= $supplierPricingOne_info['buyingunit'] ?></td>
          <td><?= $supplierPricingOne_info['packsize'] ?></td>
          <td><?= $supplierPricingOne_info['minimumorderqty'] ?></td>
          <td><?= $supplierPricingOne_info['costunit'] ?></td>
          <td><?= $supplierPricingOne_info['basecostprice'] ?></td>
          <td><?= $supplierPricingOne_info['rebatediscount1'] ?></td>
          <td><?= $supplierPricingOne_info['rebatediscount2'] ?></td>
          <td><?= $supplierPricingOne_info['rebatediscount3'] ?></td>
          <td><?= $supplierPricingOne_info['netcostprice1'] ?></td>
        </tr>
        <?php foreach($supplierPricing_info as $row): ?>
        <tr>
          <td><?= $supplierPricingOne_info['costeffectivefrom'] ?></td>
          <td><?= $supplierPricingOne_info['suppliercode'] ?></td>
          <td><?= $supplierPricingOne_info['s_name'] ?></td>
          <td><?= $supplierPricingOne_info['buyingunit'] ?></td>
          <td><?= $supplierPricingOne_info['packsize'] ?></td>
          <td><?= $supplierPricingOne_info['minimumorderqty'] ?></td>
          <td><?= $supplierPricingOne_info['costunit'] ?></td>
          <td><?= $supplierPricingOne_info['basecostprice'] ?></td>
          <td><?= $supplierPricingOne_info['rebatediscount1'] ?></td>
          <td><?= $supplierPricingOne_info['rebatediscount2'] ?></td>
          <td><?= $supplierPricingOne_info['rebatediscount3'] ?></td>
          <td><?= $supplierPricingOne_info['netcostprice1'] ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>