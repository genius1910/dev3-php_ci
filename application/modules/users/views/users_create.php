<!-- Content Header (Page header) -->
<section class="content-header">
  <h4>


    <?php if(isset($userDetail['firstname'])) {
		echo "Edit - ".$userDetail['firstname']." ".$userDetail['surname'];
	} else {
		echo "Add new user";
	}
	$disabled = "";
	if("view"!=$type) {		
	} else {
		$disabled = "disabled";
	}
	
	?>

  </h4>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo base_url(); ?>users"><i class="fa fa-users"></i> Users</a></li>
    <li class="active">
      <?php echo isset($userDetail['userid'])? $userDetail['userid'].' - '.$userDetail['firstname'].' '.$userDetail['surname']:"Add new user";?>
    </li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#details" role="tab" data-toggle="tab"
          aria-expanded="false">Details</a></li>
    </ul>
    <div class="tab-content">
      <div class="active tab-pane" id="details">
        <div class="row no-border">
          <?php if("view"==$type) { ?>
          <?php echo form_open('users/details');?>
          <div class="box-footer">
            <input type="hidden" name="userid" value="<?php echo $userDetail['userid'];?>" />
            <input type="submit" name="edit" value="Edit" class="btn btn-default" />
            <?php echo anchor('users/index', '<button type="button" class="btn btn-danger" name="cancel" value="cancel">Cancel</button>');?>
            <?php echo form_close();?>
          </div>
          <?php } ?>
        </div>
        <div class="row">
          <?php echo $this->session->flashdata('user_operation');?>
          <?php echo form_open('users/details', array("role"=>"form", "enctype"=>"multipart/form-data", "onsubmit"=>"return checkUnique();"));?>
          <div class="col-xs-12 col-md-8">
            <div class="box-header">
              <?php if("view"!=$type) { ?>
              <button type="submit" class="btn btn-default" name="submit" value="formsubmitted">
                <?php 
								$addnew = false;
								echo "Save";
								if(isset($userDetail['userid'])) {
									$addnew = false;
								} else {
									$addnew = true;
								}?>
              </button>
              <?php echo anchor('users/index', '<button type="button" class="btn btn-danger" name="cancel" value="cancel">Cancel</button>');?>

              <?php } ?>
            </div>
            <?php if(isset($userDetail['userid']) && "copy"!=$type) { ?>
            <div class="form-group">
              <label>ID</label>
              <input type="text" class="form-control" placeholder="User Id" name="userid" id="userid"
                value="<?php echo isset($userDetail['userid'])?$userDetail['userid']:"";?>" readonly
                <?php echo $disabled;?>>
            </div>
            <?php } ?>

            <div class="form-group">
              <label>First Name</label>
              <input type="text" class="form-control" placeholder="First name" name="firstname" id="firstname"
                value="<?php echo isset($userDetail['firstname'])?$userDetail['firstname']:"";?>" required
                <?php echo $disabled;?>>
              <?php echo form_error('firstname'); ?>
            </div>

            <div class="form-group">
              <label>Surname</label>
              <input type="text" class="form-control" placeholder="Surname" name="surname" id="surname"
                value="<?php echo isset($userDetail['surname'])?$userDetail['surname']:"";?>" required
                <?php echo $disabled;?>>
              <?php echo form_error('surname'); ?>
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" placeholder="Email" name="email" id="email"
                value="<?php echo isset($userDetail['email'])?$userDetail['email']:"";?>" required
                <?php echo $disabled;?>>
              <?php echo form_error('email'); ?>
            </div>

            <?php if($isAdmin) { ?>

            <div class="form-group">
              <label>User Type</label>
              <select class="form-control" name="usertype" id="usertype" required <?php echo $disabled;?>>
                <?php foreach($usertypes as $option) { 
									$current_usertype = "";
									if(isset($userDetail['usertype'])) {
										$current_usertype = $userDetail['usertype'];
									}
							?>
                <option value="<?php echo $option['value'];?>"
                  <?php if($current_usertype==$option['value']) { echo "selected";} else { echo "";}?>>
                  <?php echo $option['option'];?></option>
                <?php } ?>
              </select>
              <?php echo form_error('usertype'); ?>
            </div>

            <div class="form-group">
              <label>Licence Type</label>
              <select class="form-control" name="licencetype" id="licencetype">
                <option value="G" selected>(G)old</option>
                <option value="S">(S)ilver</option>
              </select>
            </div>

            <div class="form-group">
              <label>Default Module</label>
              <select class="form-control" name="defaultmodule" id="defaultmodule">
                <option value="S" selected>(S)ales</option>
                <option value="I">(I)nventory Management</option>
              </select>
            </div>

            <div class="form-group rep-code hidden">
              <label>K8 Rep Code(s)</label>
              <input type="text" class="form-control small-text frst" placeholder="" name="repcode" id="repcode"
                value="<?php echo isset($userDetail['repcode'])?$userDetail['repcode']:"";?>" <?php echo $disabled;?>
                required>
              <input type="text" class="form-control small-text" placeholder="" name="repcode_2" id="repcode_2"
                value="<?php echo isset($userDetail['repcode_2'])?$userDetail['repcode_2']:"";?>"
                <?php echo $disabled;?> required>
              <input type="text" class="form-control small-text" placeholder="" name="repcode_3" id="repcode_3"
                value="<?php echo isset($userDetail['repcode_3'])?$userDetail['repcode_3']:"";?>"
                <?php echo $disabled;?> required>
              <input type="text" class="form-control small-text" placeholder="" name="repcode_4" id="repcode_4"
                value="<?php echo isset($userDetail['repcode_4'])?$userDetail['repcode_4']:"";?>"
                <?php echo $disabled;?> required>
              <input type="text" class="form-control small-text lst" placeholder="" name="repcode_5" id="repcode_5"
                value="<?php echo isset($userDetail['repcode_5'])?$userDetail['repcode_5']:"";?>"
                <?php echo $disabled;?> required>
            </div>
            <div class="form-group rep-code hidden">
              <label>&nbsp;</label>
              <input type="text" class="form-control small-text frst" placeholder="" name="repcode_6" id="repcode_6"
                value="<?php echo isset($userDetail['repcode_6'])?$userDetail['repcode_6']:"";?>"
                <?php echo $disabled;?>>
              <input type="text" class="form-control small-text" placeholder="" name="repcode_7" id="repcode_7"
                value="<?php echo isset($userDetail['repcode_7'])?$userDetail['repcode_7']:"";?>"
                <?php echo $disabled;?>>
              <input type="text" class="form-control small-text" placeholder="" name="repcode_8" id="repcode_8"
                value="<?php echo isset($userDetail['repcode_8'])?$userDetail['repcode_8']:"";?>"
                <?php echo $disabled;?>>
              <input type="text" class="form-control small-text" placeholder="" name="repcode_9" id="repcode_9"
                value="<?php echo isset($userDetail['repcode_9'])?$userDetail['repcode_9']:"";?>"
                <?php echo $disabled;?>>
              <input type="text" class="form-control small-text lst" placeholder="" name="repcode_10" id="repcode_10"
                value="<?php echo isset($userDetail['repcode_10'])?$userDetail['repcode_10']:"";?>"
                <?php echo $disabled;?>>
            </div>

            <div class="form-group">
              <label>Branch</label>
              <select class="form-control" name="branch" id="branch" required <?php echo $disabled;?>>
                <option value="0"></option>
                <?php foreach($branches as $option) { 
									$current_branch = "";
									if(isset($userDetail['branch'])) {
										$current_branch = $userDetail['branch'];
									}
							?>
                <option value="<?php echo 0==$option['branch']?"":$option['branch'];?>"
                  <?php if($current_branch==$option['branch']) { echo "selected";} else { echo "";}?>>
                  <?php echo $option['name'];?></option>
                <?php }?>
              </select>
              <?php echo form_error('branch'); ?>
            </div>

            <div class="form-group">
              <label>K8 User ID</label>
              <input type="text" class="form-control" placeholder="Ex. pjones" name="k8userid" id="k8userid"
                value="<?php echo isset($userDetail['k8userid'])?$userDetail['k8userid']:"";?>" <?php echo $disabled;?>
                required>
            </div>

            <div class="row">
              <div class="form-group">
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="administrator" id="administrator"
                        <?php echo isset($userDetail['administrator']) && ($userDetail['administrator']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      Administrator
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="active" id="active"
                        <?php echo isset($userDetail['active']) && ($userDetail['active']==1)?"checked":"";?> value="1"
                        <?php echo $disabled;?> onchange="checklicense(this.checked);">
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="salesemail" id="salesemail"
                        <?php echo isset($userDetail['salesemail']) && ($userDetail['salesemail']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      Sales Email
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="seemargins" id="seemargins"
                        <?php echo isset($userDetail['seemargins']) && ($userDetail['seemargins']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      See Margins
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="seeomr" id="seeomr"
                        <?php echo isset($userDetail['seeomr']) && ($userDetail['seeomr']==1)?"checked":"";?> value="1"
                        <?php echo $disabled;?>>
                      See OMR
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="seeprojectedsales" id="seeprojectedsales"
                        <?php echo isset($userDetail['seeprojectedsales']) && ($userDetail['seeprojectedsales']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      See Projected Sales
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="seeprojectedsalesyear" id="seeprojectedsalesyear"
                        <?php echo isset($userDetail['seeprojectedsalesyear']) && ($userDetail['seeprojectedsalesyear']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      See Projected Sales Year
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="seeorderfulfillment" id="seeorderfulfillment"
                        <?php echo isset($userDetail['seeorderfulfillment']) && ($userDetail['seeorderfulfillment']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      See Order Fulfillment
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="editnotes" id="editnotes"
                        <?php echo isset($userDetail['editnotes']) && ($userDetail['editnotes']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      Edit Notes
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="editterms" id="editterms"
                        <?php echo isset($userDetail['editterms']) && ($userDetail['editterms']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      Edit Terms
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="edittargets" id="edittargets"
                        <?php echo isset($userDetail['edittargets']) && ($userDetail['edittargets']==1)?"checked":"";?>
                        value="1" <?php echo $disabled;?>>
                      Edit Targets
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
            <div class="box-footer">
              <input type="hidden" name="type" value="<?php echo $type;?>" />
              <?php if("view"!=$type) { ?>
              <button type="submit" class="btn btn-default" name="submit" value="formsubmitted">
                <?php 
								$addnew = false;
								echo "Save";
								if(isset($userDetail['userid'])) {
									$addnew = false;
								} else {
									$addnew = true;
								}?>
              </button>
              <?php echo anchor('users/index', '<button type="button" class="btn btn-danger" name="cancel" value="cancel">Cancel</button>');?>

              <?php } ?>
            </div>
          </div>
          <div class="col-xs-12 col-md-3">
            <div class="row">
              <?php $img = generate_profile_image_url($userDetail['userid'], $addnew);?>
              <div align="center"><img src="<?php echo $img;?>" alt="User profile image" class="img-thumbnail"
                  width="200"></div>
              <div style="clear:both"></div>

              <?php if(""==$disabled) { ?>
              <div id="userfile">Change Image</div>
              <?php } ?>
            </div>
            <p>
            <p><strong>Instructions for file upload</strong>
            <p>
            <p>
            <ol>
              <li>File allowed types are gif, jpg and png.</li>
              <li>Maximum filesize for upload can be <?php echo $max_size;?> kilobytes.</li>
              <li>The image should not have dimensions beyond <?php echo $max_width;?> pixels x
                <?php echo $max_height;?> pixels. <input type="hidden" name="updateimage" id="updateimage" value="" />
              </li>
            </ol>
            </p>
            </p>
          </div>
          <?php echo form_close();?>
        </div>
        <div class="row no-border">
          <?php if("view"==$type) { ?>
          <?php echo form_open('users/details');?>
          <div class="box-footer">
            <input type="hidden" name="userid" value="<?php echo $userDetail['userid'];?>" />
            <input type="submit" name="edit" value="Edit" class="btn btn-default" />
            <?php echo anchor('users/index', '<button type="button" class="btn btn-danger" name="cancel" value="cancel">Cancel</button>');?>
            <?php echo form_close();?>
          </div>
          <?php } ?>
        </div>
      </div>

      <div class="tab-pane" id="target">
        <div class="row">
          <div class="col-xs-12">
            <div class="content_box">

              <div class="row">
                <div class="col-xs-12">
                  <div class="box">
                    <div class="box-body">
                      <span id="alertmsg"><?php echo $this->session->flashdata('target_operation');?></span>
                      <div class="box-footer no-border">

                        <?php if($mainUserEdirAccess=='1'){ ?>
                        <button type="button" class="btn btn-success pull-right" onclick="openAddTargetForm();"><i
                            class="fa fa-fw fa-calendar-plus-o"></i> Add target</button>
                        <button type="button" class="btn btn-success pull-left" onclick="openAdduploadTargetForm();"><i
                            class="fa fa-fw fa-calendar-plus-o"></i> Upload
                          target(s)</button>

                        <hr>
                        <a href="<?= base_url(); ?>/images/import_user_target.csv" download>Sample File (Use Only CSV
                          with headings)</a>
                        <?php } ?>
                      </div>
                      <table class="table table-bordered table-striped users-list-table users-listing">
                        <thead>
                          <tr>
                            <th>Year/Month</th>
                            <th>Target</th>
                            <?php if($mainUserEdirAccess=='1'){ ?>
                            <th>Actions</th>
                            <?php } ?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach($userTargets as $row) { ?>
                          <tr id="row_<?php echo $row['id'];?>">
                            <td><?php //echo $row['yearmonth'];?>



                              <?php if($mainUserEdirAccess=='1'){ ?>
                              <div class="ulink" id="ulink_<?php echo $row['id'];?>" for="<?php echo $row['id'];?>">
                                <?php echo $row['yearmonth'];?></div>

                              <div class="hidden" id="hidden_<?php echo $row['id'];?>">
                                <input type="number" min="<?php echo date("Y");?>" name="year"
                                  id="year_<?php echo $row['id'];?>" value="<?php echo substr($row['yearmonth'],0,4);?>"
                                  for="<?php echo $row['id'];?>" class="width-50" />/<input type="number" min="1"
                                  max="12" name="month" id="month_<?php echo $row['id'];?>"
                                  value="<?php echo substr($row['yearmonth'],4,2);?>" for="<?php echo $row['id'];?>"
                                  class="width-50" />


                                <button type="button" class="btn btn-primary btn-sm editable-submit"
                                  onclick="updateyearmonth('<?php echo $row['id'];?>');"><i
                                    class="glyphicon glyphicon-ok"></i></button>

                                <button type="button" class="btn btn-default btn-sm editable-cancel"
                                  onclick="closeediting('<?php echo $row['id'];?>', 'yearmonth', false);"><i
                                    class="glyphicon glyphicon-remove"></i></button>
                              </div>

                              <?php }else{ ?>
                              <?php echo $row['yearmonth'];?>
                              <?php } ?>






                            </td>
                            <td><?php //echo $row['salestarget'];?>

                              <?php if($mainUserEdirAccess=='1'){ ?>
                              <div class="flink" id="flink_<?php echo $row['id'];?>" for="<?php echo $row['id'];?>">
                                <?php echo $row['salestarget'];?></div>
                              <div class="hidden" id="fhidden_<?php echo $row['id'];?>">
                                <input type="number" min="0" name="salestarget"
                                  id="salestarget_<?php echo $row['id'];?>" value="<?php echo $row['salestarget'];?>"
                                  for="<?php echo $row['id'];?>" class="height-29" />

                                <button type="button" class="btn btn-primary btn-sm editable-submit"
                                  onclick="updatesalestarget('<?php echo $row['id'];?>');"><i
                                    class="glyphicon glyphicon-ok"></i></button>

                                <button type="button" class="btn btn-default btn-sm editable-cancel"
                                  onclick="closeediting('<?php echo $row['id'];?>', 'target', false);"><i
                                    class="glyphicon glyphicon-remove"></i></button>
                              </div>
                              <?php }else{ ?>
                              <?php echo $row['salestarget'];?>
                              <?php } ?>




                            </td>
                            <?php if($mainUserEdirAccess=='1'){ ?>
                            <td>

                              <span id="dlink_<?php echo $row['id'];?>"><a
                                  onclick="deletetarget('<?php echo $row['id'];?>', '<?php echo $userDetail['userid'];?>');"
                                  href="javascript:void(0);" class="transform-link"><i
                                    class="fa fa-fw fa-trash-o"></i></a></span>

                            </td>
                            <?php } ?>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>Year/Month</th>
                            <th>Target</th>
                            <?php if($mainUserEdirAccess=='1'){ ?>
                            <th>Actions</th>
                            <?php } ?>
                          </tr>
                        </tfoot>
                      </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->


            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>

      <div class="tab-pane" id="kpi">
        <div class="row no-border">
          <?php if("view"==$type) { ?>
          <?php echo form_open('users/details/'.$userDetail['userid'].'/#editkpi');?>
          <div class="box-footer">

            <input type="submit" name="edit" value="Edit" class="btn btn-default" />
            <?php echo anchor('users/index', '<button type="button" class="btn btn-danger" name="cancel" value="cancel">Cancel</button>');?>
            <?php echo form_close();?>
          </div>
          <?php } ?>
        </div>
        <div class="row">
          <?php echo $this->session->flashdata('kpi_operation');?>
          <?php echo form_open('users/kpimodify', array("role"=>"form", "id"=>"userFrm"));?>
          <div class="col-md-8 col-xs-12">
            <div class="box-header">
              <?php if("view"!=$type) { ?>
              <button type="submit" class="btn btn-default" name="submit" value="formsubmitted">
                <?php 
									$addnew = false;
									echo "Save";
									if(isset($branchDetail['branch'])) {
										$addnew = false;
									} else {
										$addnew = true;
									}?>
              </button>
              <?php echo anchor('users/index', '<button type="button" class="btn btn-danger" name="cancel" value="cancel">Cancel</button>');?>

              <?php } ?>
            </div>



            <div class="form-group">
              <label>KPI Threshhold1</label>
              <input type="hidden" name="user" value="<?php echo $userDetail['userid'];?>" />
              <input type="text" class="form-control" placeholder="kpi threshold 1" name="kpithreshold1"
                id="kpithreshold2"
                value="<?php echo isset($userDetail['kpithreshold1'])?$userDetail['kpithreshold1']:"";?>" required
                <?php echo $disabled;?>>
              <?php echo form_error('kpithreshold1'); ?>
            </div>

            <div class="form-group">
              <label>KPI Threshhold2</label>
              <input type="text" class="form-control" placeholder="kpi threshold " name="kpithreshold2"
                id="kpithreshold2"
                value="<?php echo isset($userDetail['kpithreshold2'])?$userDetail['kpithreshold2']:"";?>" required
                <?php echo $disabled;?>>
              <?php echo form_error('kpithreshold2'); ?>
            </div>
            <div class="box-header">
              <?php if("view"!=$type) { ?>
              <button type="submit" class="btn btn-default" name="submit" value="formsubmitted" id="submitnewbranch">
                <?php 
									$addnew = false;
									echo "Save";
									if(isset($userDetail['user'])) {
										$addnew = false;
									} else {
										$addnew = true;
									}?>
              </button>
              <?php echo anchor('users/index', '<button type="button" class="btn btn-danger" name="cancel" value="cancel">Cancel</button>');?>

              <?php } ?>
            </div>

          </div>
          <?php echo form_close();?>
        </div>

        <div class="row no-border">
          <?php if("view"==$type) { ?>
          <?php echo form_open('users/details/'.$userDetail['userid'].'/#editkpi');?>
          <div class="box-footer">
            <input type="hidden" name="user" value="<?php echo $userDetail['userid'];?>" />
            <input type="submit" name="edit" value="Edit" class="btn btn-default" />
            <?php echo anchor('users/index', '<button type="button" class="btn btn-danger" name="cancel" value="cancel">Cancel</button>');?>
            <?php echo form_close();?>
          </div>
          <?php } ?>
        </div>
      </div>

      <div class="tab-pane" id="salesRepBlock">
        <div class="row">
          <div class="col-xs-12">
            <div class="content_box">

              <button type="submit" name="addnew" class="btn btn-success pull-right bottom5"
                onclick="return openModal('#addRepModal', 'newRep/<?php echo $account; ?>');"><i
                  class="fa fa-fw fa-plus-circle"></i>Add Sales Rep</button>

              <?php echo form_open('customer/repcodes');?>
              <div class="row">
                <div class="col-xs-12">
                  <div class="box">
                    <div class="box-body">
                      <table class="table table-bordered table-striped" id="crepsTable">
                        <?php 
									$headers = 
									'<tr>
										<th>Rep code</th>
										<th>Name</th>
										<th width="100">Actions</th>
									</tr>';
									?>
                        <thead>
                          <?php echo $headers; ?>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                          <?php echo $headers; ?>
                        </tfoot>
                      </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
              <?php echo form_close();?>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>


      <!-- End KPI --->
    </div><!-- /.tab-pane -->

  </div><!-- /.tab-content -->

  </div><!-- /.nav-tabs-custom -->
</section><!-- /.content -->

<!-- Hidden form to add new target -->
<section class="hidden-add-target-form">
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Add new target</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <?php echo form_open('users/addtarget', array("class"=>"form-horizontal"));?>
    <div class="box-body">
      <div class="form-group">
        <label for="year" class="col-sm-2 control-label">Year</label>
        <div class="col-sm-4">
          <input type="number" min="<?php echo date('Y');?>" class="form-control" id="year" name="year"
            placeholder="Year" value="<?= date("Y"); ?>" required>
        </div>
        <label for="month" class="col-sm-2 control-label">Month</label>
        <div class="col-sm-4">
          <input type="number" min="1" max="12" class="form-control" id="month" name="month" placeholder="Month"
            value="<?= date("m"); ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label for="target" class="col-sm-2 control-label">Targets</label>
        <div class="col-sm-10">
          <input type="number" min="0" class="form-control" id="target" name="target" placeholder="target" required>

          <input type="hidden" name="userid" value="<?php echo $userDetail['userid'];?>">
        </div>
      </div>
    </div><!-- /.box-body -->
    <div class="box-footer">
      <button type="button" class="btn btn-default" onclick="closeAddTargetForm();">Cancel</button>
      <button type="submit" class="btn btn-info pull-right">Save</button>
    </div><!-- /.box-footer -->
    <?php echo form_close();?>
  </div>
</section>
<section class="hidden-add-uploadtarget-form">
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Upload target(s)</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <?php echo form_open_multipart('users/uploadtarget', array("class"=>"form-horizontal","accept"=>".csv"));?>
    <div class="box-body">
      <div class="form-group">
        <label for="month" class="col-sm-2 control-label">Upload CSV</label>
        <div class="col-sm-4">
          <input type="file" name="file" class="form-control" required>
        </div>
      </div>


    </div><!-- /.box-body -->
    <div class="box-footer">
      <button type="button" class="btn btn-default" onclick="closeAdduploadTargetForm();">Cancel</button>
      <button type="submit" class="btn btn-info pull-right">Upload</button>
      <hr>
      <h5>CSV File format:</h5>
      <h5> Year Month, Sales Target</h5>
      <hr style="margin-top: 5px; margin-bottom: 5px;">
      <hr style="margin-top: 5px; margin-bottom: 5px;">

      <div class="row">
        <div class="col-sm-2">


          <h5><strong>Year Month</strong></h5>
          <h5><strong>Sales Target</strong></h5>

        </div>
        <div class="col-sm-10">

          <h5> Year and month</h5>
          <h5> Sales target</h5>

        </div>
      </div>

      <hr style="margin-top: 5px; margin-bottom: 5px;">
      <h5>e.g. 201805, 12000</h5>
      <h5>* Enter data after heading</h5>
    </div><!-- /.box-footer -->
    <?php echo form_close();?>
  </div>
</section>
<div class="modal fade bd-example-modal-sm" id="addRepModal" tabindex="-1" role="dialog"
  aria-labelledby="myLargeRepModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLargeRepModalLabel">Add new repcode to customer</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
    </div>
  </div>
</div>
<script>
var isAdmin = <?php echo $isAdmin;?>;
var loggedinuserid = '<?php echo $this->session->userdata('userid');?>';
</script>

<script>
$(document).ready(function() {
  var settings = {
    url: base_url + "users/uploadprofileimage/<?php echo $userDetail['userid'];?>",
    dragDrop: false,
    fileName: "userfile",
    allowedTypes: "jpg,jpeg,png,gif",
    showStatusAfterSuccess: false,
    showProgress: true,
    showFileCounter: false,
    showAbort: false,
    maxFileCount: 1,
    showError: true,
    returnType: "json",
    onSuccess: function(files, data, xhr) {
      var now = new Date().getTime();
      $(".img-thumbnail").prop('src', data.profilepath + '?' + now);
      $("#updateimage").val(data.filename);
    },
    showDelete: false,
    deleteCallback: function(data, pd) {
      for (var i = 0; i < data.length; i++) {
        $.post(base_url + "users/deleteprofileimage", {
            op: "delete",
            name: data[i]
          },
          function(resp, textStatus, jqXHR) {

          });
      }
      pd.statusbar.hide(); //You choice to hide/not.
    }
  }

  var uploadObj = $("#userfile").uploadFile(settings);
});
</script>
<?php if("copy"==$type) {?>
<script>
$(document).ready(function() {
  var isCheckedCopy = <?php echo isset($userDetail['active']) && ($userDetail['active']==1)?"true":"false";?>;
  checklicense(isCheckedCopy);
});
</script>
<?php }?>