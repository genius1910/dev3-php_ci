<script>
var base_url = '<?php echo $this->config->item('base_folder'); ?>';
</script>
<header class="main-header top-menu">

  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">

    <div class="navbar-header">
      <a href="<?=base_url()?>dashboard" class="navbar-brand"><b>MI-DAS</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
      <ul class="nav navbar-nav">
        <?php
          $licencetype = $this->session->userdata('licencetype');
          $defaultmodule = $this->session->userdata('defaultmodule');
        ?>
        <?php
          if(($licencetype == 'G' || $licencetype == 'S') && $defaultmodule == 'S'):
        ?>
        <li class="dropdown <?php echo ($this->router->fetch_class()=='sales') ? 'active' : '' ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sales <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li <?php echo ($this->router->fetch_class()=='site')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>dashboard"><span>Dashboard</span></a>
            </li>

            <li <?php echo ($this->router->fetch_class()=='customer')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>customer"><span>Customers</span></a>
            </li>

            <li <?php echo ($this->router->fetch_class()=='products')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>products"><span>Products</span></a>
            </li>

            <li <?php echo ($this->router->fetch_class()=='quotation')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>quotation"><span>Quotations</span></a>
            </li>
          </ul>
        </li>
        <?php endif ?>

        <?php
          if(($licencetype == 'G' || $licencetype == 'S') && $defaultmodule == 'I'):
        ?>
        <li class="dropdown <?php echo ($this->router->fetch_class()=='inventory') ? 'active' : '' ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Inventory <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li <?php echo ($this->router->fetch_class()=='inventory')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>inventory"><span>Dashboard</span></a>
            </li>

            <li <?php echo ($this->router->fetch_class()=='itemList')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>itemList"><span>Item List</span></a>
            </li>
          </ul>
        </li>
        <?php endif ?>

        <li <?php echo ($this->router->fetch_class()=='tasks')?'class="active"':''; ?>>
          <a href="<?php echo base_url(); ?>tasks"><span>Tasks</span></a>
        </li>
        <li class="dropdown <?php echo ($this->router->fetch_class()=='settings') ? 'active' : '' ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li <?php echo ($this->router->fetch_class()=='users')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>users"><span>Users</span></a>
            </li>
            <?php if(isAdmin()): ?>
            <li <?php echo ($this->router->fetch_class()=='branches')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>branches"><span>Branches</span></a>
            </li>
            <li <?php echo ($this->router->fetch_class()=='company')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>company/details"><span>Company</span></a>
            </li>
            <li <?php echo ($this->router->fetch_class()=='logs')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>logs"><span>System log</span></a>
            </li>
            <?php if(($licencetype == 'G' || $licencetype == 'S') && $defaultmodule == 'I'): ?>
            <li <?php echo ($this->router->fetch_class()=='logs')?'class="active"':''; ?>>
              <a href="<?php echo base_url(); ?>inventory/configuration"><span>Inventory Config</span></a>
            </li>
            <?php endif ?>
            <?php endif ?>
          </ul>
        </li>
      </ul>
    </div>

    <div class="navbar-custom-menu pull-right">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo generate_profile_image_url(); ?>" class="user-image" alt="User Image">
            <span class=" hidden-xs"><?php echo $this->session->userdata('username'); ?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- Menu Footer-->
            <li><a href="<?php echo base_url(); ?>site/logout"><i class="fa fa-sign-out m-r-xs"></i> Sign out</a> </li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="navbar-custom-menu pull-right">
      <ul class="nav navbar-nav">


        <!-- Drop down for the branches. Loading through Ajax -->
        <li class="dropdown onlyforall"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
            role="button" aria-haspopup="true" aria-expanded="false"><span id="selected_branch">Loading...</span><span
              class="caret"></span></a>
          <ul class="dropdown-menu" id="branch_menu"></ul>
        </li>

        <!-- Drop down for the users. Loading through Ajax -->
        <li class="dropdown onlyforall"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
            role="button" aria-haspopup="true" aria-expanded="false"><span id="selected_user">Loading...</span><span
              class="caret"></span></a>
          <ul class="dropdown-menu" id="user_menu" style="height:269px;overflow-y:scroll;"></ul>
        </li>
      </ul>
    </div>
  </nav>
</header>