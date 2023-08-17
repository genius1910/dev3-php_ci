<!-- Content Header (Page header) -->

<style>
table th,
table td {
    text-align: center;
    vertical-align: middle;
}

.configuration_table th {
    width: 50%
}

table input,
table select {
    width: 80% !important;
    margin: auto !important;
    margin-top: 10px !important;
}

.configuration_table th:first-child {
    width: 5%
}

.checkbox {
    margin-bottom: 0;
    margin-top: 5px;
}
</style>

<section class="content-header">
    <h1> Configuration </h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-5 col-xs-12">
            <table class="configuration_table">
                <thead>
                    <tr>
                        <th></th>
                        <th>% Of Turnover</th>
                        <th>Customer Service Factors</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>A</td>
                        <td>
                            <input type="number" class="form-control" value="<?= $imsystem_info['apc'] ?>">
                        </td>
                        <td>
                            <select class="form-control">
                                <?php if(count($acsf_list) > 0):  ?>
                                <option value="<?= $acsf_list['multiplySD'] ?>"><?= $acsf_list['multiplySD'] ?></option>
                                <option value="<?= $acsf_list['multiplyMAD'] ?>"><?= $acsf_list['multiplyMAD'] ?>
                                </option>
                                <?php endif  ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>B</td>
                        <td>
                            <input type="number" class="form-control" value="<?= $imsystem_info['bpc'] ?>">
                        </td>
                        <td>
                            <select class="form-control">
                                <?php if(count($bcsf_list) > 0):  ?>
                                <option value="<?= $bcsf_list['multiplySD'] ?>"><?= $bcsf_list['multiplySD'] ?></option>
                                <option value="<?= $bcsf_list['multiplyMAD'] ?>"><?= $bcsf_list['multiplyMAD'] ?>
                                </option>
                                <?php endif  ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>C</td>
                        <td>
                            <input type="number" class="form-control" value="<?= $imsystem_info['cpc'] ?>">
                        </td>
                        <td>
                            <select class="form-control">
                                <?php if(count($ccsf_list) > 0):  ?>
                                <option value="<?= $ccsf_list['multiplySD'] ?>"><?= $ccsf_list['multiplySD'] ?></option>
                                <option value="<?= $ccsf_list['multiplyMAD'] ?>"><?= $ccsf_list['multiplyMAD'] ?>
                                </option>
                                <?php endif  ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <br />
                            <b># Months</b>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="number" class="form-control" value="<?= $imsystem_info['months'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            <b style="white-space:nowrap;">Seasonality Profile Level</b>
                            <select class="form-control" style="display: inline;width: fit-content !important;">
                                <option value="1"
                                    <?php $imsystem_info['seasonalityprofilelevel'] == 1 ? 'selected' : '' ?>>PAC1
                                </option>
                                <option value="2"
                                    <?php $imsystem_info['seasonalityprofilelevel'] == 2 ? 'selected' : '' ?>>PAC2
                                </option>
                                <option value="3"
                                    <?php $imsystem_info['seasonalityprofilelevel'] == 3 ? 'selected' : '' ?>>PAC3
                                </option>
                                <option value="4"
                                    <?php $imsystem_info['seasonalityprofilelevel'] == 4 ? 'selected' : '' ?>>PAC4
                                </option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-7 col-xs-12">
            <table class="">
                <thead>
                    <th>Stock Age Label</th>
                    <th># Days</th>
                    <th>Provision %</th>
                    <th>Surplus</th>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" class="form-control" value="<?= $imsystem_info['aged1label'] ?>" /></td>
                        <td><input type="number" class="form-control"
                                value="<?= $imsystem_info['aged1days'] ? $imsystem_info['aged1days'] : 0 ?>"></td>
                        <td><input type="text" class="form-control"
                                value="<?= changeNum($imsystem_info['aged1provision'],"!",2)." %" ?>">
                        </td>
                        <td>
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" <?= $imsysyem_info['aged1surplus'] ? "checked" : "" ?>>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control" value="<?= $imsystem_info['aged2label'] ?>" /></td>
                        <td><input type="number" class="form-control"
                                value="<?= $imsystem_info['aged2days'] ? $imsystem_info['aged2days'] : 0 ?>"></td>
                        <td><input type="text" class="form-control"
                                value="<?= changeNum($imsystem_info['aged2provision'],"!",2)." %" ?>">
                        </td>
                        <td>
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" <?= $imsysyem_info['aged2surplus'] ? "checked" : "" ?>>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control" value="<?= $imsystem_info['aged3label'] ?>" />
                        </td>
                        <td><input type="number" class="form-control"
                                value="<?= $imsystem_info['aged3days'] ? $imsystem_info['aged3days'] : 0 ?>"></td>
                        <td><input type="text" class="form-control"
                                value="<?= changeNum($imsystem_info['aged3provision'],"!",2)." %" ?>">
                        </td>
                        <td>
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" <?= $imsysyem_info['aged3surplus'] ? "checked" : "" ?>>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control" value="<?= $imsystem_info['aged4label'] ?>" /></td>
                        <td><input type="number" class="form-control"
                                value="<?= $imsystem_info['aged4days'] ? $imsystem_info['aged4days'] : 0 ?>"></td>
                        <td><input type="text" class="form-control"
                                value="<?= changeNum($imsystem_info['aged4provision'],"!",2)." %" ?>">
                        </td>
                        <td>
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" <?= $imsysyem_info['aged4surplus'] ? "checked" : "" ?>>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control" value="<?= $imsystem_info['aged5label'] ?>" /></td>
                        <td><input type="number" class="form-control"
                                value="<?= $imsystem_info['aged5days'] ? $imsystem_info['aged5days'] : 0 ?>"></td>
                        <td><input type="text" class="form-control"
                                value="<?= changeNum($imsystem_info['aged5provision'],"!",2)." %" ?>">
                        </td>
                        <td>
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" <?= $imsysyem_info['aged5surplus'] ? "checked" : "" ?>>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">Calculate Provision</td>
                        <td colspan="1">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" <?= $imsysyem_info['calcprovision'] ? "checked" : "" ?>>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">Based On</td>
                        <td colspan="1">
                            <select type="" class="form-control">
                                <option value="<?= $imsystem_info['agedbasedon '] ?>"></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">Excess Stock %</td>
                        <td colspan="1"><input type="text" class="form-control"
                                value="<?= ($imsystem_info['excessstockpc'] ? $imsystem_info['excessstockpc'] : "0")." %" ?>">
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</section>
<!-- Main row -->
</section>
<!-- /.content -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
<script src="<?= $this->config->item('base_folder'); ?>public/js/common.js">
</script>

<script>
$(function() {
    $('.checkbox.icheck input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});
</script>