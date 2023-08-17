<!-- Content Header (Page header) -->
<link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>application/modules/inventory/css/style.css" />
<section class="content-header">
  <h1> <span><?= $itemInfo['prodcode'] ?></span> - <span><?= $itemInfo['description'] ?></span> </h1>
  <h1> <span><?= $itemInfo['branch'] ?></span> - <span><?= $itemInfo['name'] ?></span> </h1>
  <ol class="breadcrumb">
    <li class="active">Data last updated: <?php echo date('m/d/Y H:i', strtotime($lastupdated)) ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active">
        <a href="#detail" id="detailTab" role="tab" data-toggle="tab" aria-expanded="false"
          data-params='{"action" : "detail"}'>
          Detail
        </a>
      </li>
      <li role="presentation">
        <a href="#purchaseOrders" id="purchaseOrdersTab" role="tab" data-toggle="tab" aria-expanded="false"
          data-params='{"action" : "purchaseOrders"}'>
          Purchase Orders
        </a>
      </li>
      <li role="presentation">
        <a href="#demandAnalysis" id="demandAnalysisTab" role="tab" data-toggle="tab" aria-expanded="false"
          data-params='{"action" : "demandAnalysis"}'>
          Demand Analysis
        </a>
      </li>
      <li role="presentation">
        <a href="#demandVsAverage" id="demandVsAverageTab" role="tab" data-toggle="tab" aria-expanded="false"
          data-params='{"action" : "demandVsAverage"}'>
          Demand vs Average
        </a>
      </li>
      <li role="presentation">
        <a href="#locations" id="locationsTab" role="tab" data-toggle="tab" aria-expanded="false"
          data-params='{"action" : "locations"}'>
          Locations
        </a>
      </li>
      <li role="presentation">
        <a href="#seasonalityProfile" id="seasonalityProfileTab" role="tab" data-toggle="tab" aria-expanded="false"
          data-params='{"action" : "seasonalityProfile"}'>
          Seasonality Profile
        </a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="detail"></div>
      <div class="tab-pane" id="purchaseOrders"></div>
      <div class="tab-pane" id="demandAnalysis"></div>
      <div class="tab-pane" id="demandVsAverage"></div>
      <div class="tab-pane" id="locations"></div>
      <div class="tab-pane" id="seasonalityProfile"></div>
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
$(document).ready(function() {
  getDetailInfo('<?= $activeTab ?>');
  $("ul.nav-tabs li").removeClass('active');
  $("#<?= $activeTab ?>Tab").parent().addClass('active');
  $(".tab-content > div").removeClass('active');
  $("#<?= $activeTab ?>").addClass('active');
  $("ul.nav-tabs li > a").click(function(e) {
    var data = $(this).data('params');
    getDetailInfo(data.action);
  })
})

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

    },
  });
}

function getDetailInfo(url) {
  manage_cookie('inventoryItemDetailTab', url);
  $.post("<?=site_url()?>/itemList/getItemDetail_" + url, {
    itemID: "<?= $itemID ?>"
  }, function(res) {
    $("#" + url).html(res);
  })
}
</script>