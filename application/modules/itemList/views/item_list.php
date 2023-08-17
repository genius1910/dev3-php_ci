<!-- Content Header (Page header) -->
<link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>application/modules/inventory/css/style.css" />
<?php
$canSeeProjectedSales = canSeeProjectedSales();
$canSeeProjectedSalesYear = canSeeProjectedSalesYear();
$canSeeOrderFulfillment = canSeeOrderFulfillment();

$currency_symbol = $this->config->item("currency_symbol"); ?>
<section class="content-header">
    <h1> Item List </h1>
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
        <div class=" col-lg-4 col-md-6 col-xs-12 flex gap-2 margin-bottom">
            <lavel>Search:</lavel>
            <input type="search"></input>
            <button type="submit">Download</button>
            <button>Export To K8 PO</button>
            <select>
                <option></option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table class="table table-bordered table-striped" id="item_list_table">
                        <thead>
                            <th>Branch</th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Attention</th>
                            <th>ABC</th>
                            <th>Status</th>
                            <th>Supplier</th>
                            <th>Supplier Name</th>
                            <th>Safety Qty</th>
                            <th>On Hand Qty</th>
                            <th>On Order Qty</th>
                            <th>Overdue Qty</th>
                            <th>To Order Qty</th>
                            <th>Order Value</th>
                            <th>Surplus Qty</th>
                            <th>Below Safety</th>
                            <th>Age</th>
                            <th>Avg.Cost</th>
                            <th>Stock Value</th>
                            <th>Last Recv</th>
                            <th>Turn</th>
                            <th>Cover</th>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th>Branch</th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Attention</th>
                            <th>ABC</th>
                            <th>Status</th>
                            <th>Supplier</th>
                            <th>Supplier Name</th>
                            <th>Safety Qty</th>
                            <th>On Hand Qty</th>
                            <th>On Order Qty</th>
                            <th>Overdue Qty</th>
                            <th>To Order Qty</th>
                            <th>Order Value</th>
                            <th>Surplus Qty</th>
                            <th>Below Safety</th>
                            <th>Age</th>
                            <th>Avg.Cost</th>
                            <th>Stock Value</th>
                            <th>Last Recv</th>
                            <th>Turn</th>
                            <th>Cover</th>
                        </tfoot>
                    </table>
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
<script src="<?= $this->config->item('base_folder'); ?>application/modules/itemList/js/itemlist.js"></script>
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
</script>