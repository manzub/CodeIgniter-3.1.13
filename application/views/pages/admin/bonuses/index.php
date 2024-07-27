<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Manage
			<small>Bonuses</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"><i class="fa fa-dashboard"></i> Activities</a></li>
			<li class="active">Bonuses</li>
		</ol>
	</section>

	<!-- main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<?php if (in_array('createBonus', $user_permission)) : ?>
					<a href="<?php echo base_url('bonuses/create') ?>" class="btn btn-primary">Create Bonus Activity</a>
					<br /> <br />
				<?php endif; ?>

				<div id="messages">
					<?php if ($this->session->flashdata('alert')) { ?>
						<div class="alert <?php echo $this->session->flashdata('alert')['classname']; ?> alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong><?php echo $this->session->flashdata('alert')['title']; ?></strong>
							<p><?php echo $this->session->flashdata('alert')['message']; ?></p>
						</div>
					<?php } ?>
				</div>

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Manage Bonuses</h3>
					</div>
					<div class="box-body">
						<table id="manageTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Title</th>
									<th>Activity Type</th>
									<th>Reward Points</th>
									<?php if (in_array('manageBonus', $user_permission)) : ?>
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


<?php if (in_array('manageBonus', $user_permission)) : ?>
	<!-- remove brand modal -->
	<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Remove Item</h4>
				</div>

				<form role="form" action="<?php echo base_url('bonuses/delete') ?>" method="post" id="removeForm">
					<div class="modal-body">
						<p>Do you really want to delete this item?</p>
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


<script>
	var manageTable;
	var base_url = "<?php echo base_url(); ?>";

	$(document).ready(function() {

		$("#bonusesNav").addClass('active');

		// initialize the datatable
		manageTable = $('#manageTable').DataTable({
			'ajax': base_url + 'bonuses/readBonuses',
			'order': []
		});
	});

	
	// remove functions
	function removeFunc(bonus_id) {
		if (bonus_id) {
			$("#removeForm").on('submit', function() {

				var form = $(this);

				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: {
						bonus_id: bonus_id
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
