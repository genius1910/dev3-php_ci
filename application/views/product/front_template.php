<?php $this->load->view('product/front_header'); ?>
<?php $this->load->view('site/front_menubar'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <?php $this->load->view($main_content, $template); ?>
</div>
<!-- /.content-wrapper -->
<?php $this->load->view('product/front_footer'); ?>