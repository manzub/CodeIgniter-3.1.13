<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Manage
			<small>Transactions</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Transactions</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div id="messages"></div>

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Manage Transactions</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="manageTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Customer Name</th>
									<th>Coins Requested</th>
									<th>Date Time</th>
									<th>Amounts Worth</th>
									<th>Bank Name</th>
									<th>Bank Information</th>
									<th>Paid Status</th>
									<?php if (in_array('manageOrder', $user_permission)) : ?>
										<th>Action</th>
									<?php endif; ?>
								</tr>
							</thead>

						</table>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
	</section>
</div>

<?php if (in_array('manageOrder', $user_permission)) : ?>
	<!-- remove brand modal -->
	<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Remove Transaction</h4>
				</div>

				<form role="form" action="<?php echo base_url('transactions/remove') ?>" method="post" id="removeForm">
					<div class="modal-body">
						<p>Do you really want to remove?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>


			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php endif; ?>



<script type="text/javascript">
	var manageTable;
	var base_url = "<?php echo base_url(); ?>";

	$(document).ready(function() {

		$("#mainOrdersNav").addClass('active');
		$("#manageOrdersNav").addClass('active');

		// initialize the datatable 
		manageTable = $('#manageTable').DataTable({
			'ajax': base_url + 'transactions/fetchTransactions',
			'order': []
		});

	});

	// remove functions 
	function removeFunc(id) {
		if (id) {
			$("#removeForm").on('submit', function() {

				var form = $(this);

				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: {
						trnx_id: id
					},
					dataType: 'json',
					success: function(response) {

						manageTable.ajax.reload(null, false);

						if (response.success === true) {
							$("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
								'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
								'<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
								'</div>');

							// hide the modal
							$("#removeModal").modal('hide');

						} else {

							$("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
								'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
								'<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
								'</div>');
						}
					}
				});

				return false;
			});
		}
	}
</script>
