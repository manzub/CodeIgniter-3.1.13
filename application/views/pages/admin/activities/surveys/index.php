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
				<div id="messages"></div>

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
</script>
