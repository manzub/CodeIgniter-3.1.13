<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Manage
			<small>Surveys</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Surveys</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<?php if (in_array('createSurvey', $user_permission)) : ?>
					<a href="<?php echo base_url('surveys/create') ?>" class="btn btn-primary">Create New Item</a>
					<br /> <br />
				<?php endif; ?>

				<div class="messages">
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
						<h3 class="box-title">Manage Surveys</h3>
					</div>

					<div class="box-body">
						<table id="manageTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Title</th>
									<th>No of Questions</th>
									<th>Global Limit</th>
									<th>Reward Points</th>
									<th>Status</th>
									<?php if (in_array('manageSurvey', $user_permission) || in_array('manageActivity', $user_permission)) : ?>
										<th>Action</th>
									<?php endif; ?>
								</tr>
							</thead>

						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Remove Activity</h4>
			</div>

			<form role="form" action="<?php echo base_url('surveys/delete') ?>" method="post" id="removeForm">
				<div class="modal-body">
					<p>Do you really want to delete this activity?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script>
	var manageTable;
	var base_url = "<?php echo base_url(); ?>";

	$(document).ready(function() {

		$("#mainSurveyNav").addClass('active');

		// initialize the datatable
		manageTable = $('#manageTable').DataTable({
			'ajax': base_url + 'surveys/readSurveyItems',
			'order': []
		});

	});

	// remove functions
	function removeFunc(slug) {
		if (slug) {
			$("#removeForm").on('submit', function() {
				var form = $(this);

				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: {
						slug: slug
					},
					dataType: 'json',
					success: function(response) {
						manageTable.ajax.reload(null, false);
						console.log(response);
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
