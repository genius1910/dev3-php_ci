<!-- Content Header (Page header) -->
<section class="content-header">
	<h1> Logs </h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Logs</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<div class="loading_spinner"
						style="display: block;position: absolute;background-color: rgba(0,0,0,0.5);width: calc(100% - 20px);height: calc(100% - 15px);text-align: center;">
						<i class="fa fa-spinner fa-spin fa-fw" style="
													color: #000;
													scale: 2;
													position: absolute;
													top: 44%;
												"></i>
					</div>
					<table class="table table-bordered table-striped log-list-table users-listing">
						<thead>
							<tr>
								<th style="width:15%;">User</th>
								<th style="width:5%;">Type</th>
								<th style="width:25%;">Date & Time</th>
								<th>Description</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>User</th>
								<th>Type</th>
								<th>Date & Time</th>
								<th>Description</th>
							</tr>
						</tfoot>
						<tbody>
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</section><!-- /.content -->

<script type="text/javascript">
	$(document).ready(function () {
		/* Code for the Data Table of logs table. */
		$('.log-list-table tfoot th').each(function () {
			var title = $(this).text();
			$(this).html('<input type="text" style="width:100%" placeholder="Search ' + title + '" />');
		});

		$.ajax({
			url: '<?= base_url() ?>logs/getAllLogs',
			method: 'post',
			dataType: 'json',
			success: function (data) {
				const {
					logs
				} = data;
				let logs_data = '';
				logs.forEach(log => {
					logs_data += `
						<tr>
							<td>
								` + log['userid'] + ` - ` + log['firstname'] + log['surname'] + `
							</td>
							<td>
								` + log['type'] + `
							</td>
							<td>
								` + (new Date(log['time'])).toISOString().slice(0, 19).replace('T', ' ') + `
							</td>
							<td>
								<a href="<?= base_url() ?>logs/detail/` + log['id'] + `">` + log['description'] + `</a>
							</td>
						</tr>` ;
				});
				$(".log-list-table").prev(".loading_spinner").remove();
				$('.log-list-table tbody').html(logs_data);

				/* Code for the Data Table of logs table. */
				var table = $(".log-list-table").DataTable({
					"order": [[2, "desc"]],
					dom: 'Bfrtip',
					buttons: [
						{
							text: '<span title="Export" class="glyphicon glyphicon-export"></span>',
							action: function (e, dt, node, config) {
								var urltogo = base_url + 'logs/export_csv/';
								document.location.href = urltogo;
							}
						}]
				});

				/* Code for applying the search. */
				table.columns().every(function () {
					var that = this;
					$('input', this.footer()).on('keyup change', function () {
						if (that.search() !== this.value) {
							that
								.search(this.value)
								.draw();
						}
					});
				});
			}
		});
	});
</script>