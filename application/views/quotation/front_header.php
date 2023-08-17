<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MI-DAS | Management Information Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>public/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet"
    href="<?= $this->config->item('base_folder'); ?>public/css/font-awesome-4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet"
    href="<?= $this->config->item('base_folder'); ?>public/css/ionicons-2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet"
    href="<?php echo $this->config->item('base_folder'); ?>public/plugins/datatables/datatables.min.css">
  <!-- Theme style -->
  <link rel="stylesheet"
    href="<?= $this->config->item('base_folder'); ?>public/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>public/css/main.min.css">
  <link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>public/css/bootstrap-switch.css">
  <link rel="stylesheet" href="<?= $this->config->item('base_folder'); ?>public/css/skins/_all-skins.min.css">
  <link href="<?= $this->config->item('base_folder'); ?>public/plugins/pace-master/themes/blue/pace-theme-flash.css"
    rel="stylesheet" />
  <link
    href="<?= $this->config->item('base_folder'); ?>public/plugins/x-editable/bootstrap3-editable/css/bootstrap-editable.css"
    rel="stylesheet" type="text/css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <!-- jQuery 2.1.4 -->

  <script src="<?= $this->config->item('base_folder'); ?>public/plugins/jQuery/jQuery-2.1.4.min.js"></script>

  <style>
  .dataTables_wrapper {
    position: relative;
    clear: both;
    height: 100%;
    width: 100%;
    overflow: auto;
    -webkit-overflow-scrolling: touch;
    overflow-y: hidden;
  }

  div#quotation_list_table_filter.dataTables_filter,
  div#quotation_detail_table_filter.dataTables_filter {
    margin-left: 0px;
  }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini layout-top-nav">
  <div class="wrapper">