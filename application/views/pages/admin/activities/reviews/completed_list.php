<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Reviews
			<small>Completed Item</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Reviews</li>
			<li class="active">Completed Items</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
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
						<h3 class="box-title">Manage Completed Review Items</h3>
					</div>

					<div class="box-body">
						<table id="manageTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Title</th>
									<th>Rating</th>
									<th>Completed On</th>
									<th>Reward Earned</th>
									<?php if (in_array('manageReview', $user_permission) || in_array('manageActivity', $user_permission)) : ?>
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

		$("#mainReviewNav").addClass('active');
		$("#reviewCompReviewItemNav").addClass('active');

		// initialize the datatable
		manageTable = $('#manageTable').DataTable({
			'ajax': base_url + 'reviews/readCompletedItems',
			'order': []
		});

	});
</script>
