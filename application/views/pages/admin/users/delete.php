<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Manage
			<small>Groups</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo base_url('users/') ?>">Users</a></li>
			<li class="active">Delete</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<?php if ($this->session->flashdata('alert')) { ?>
					<div class="alert <?php echo $this->session->flashdata('alert')['classname']; ?> alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<strong><?php echo $this->session->flashdata('alert')['title']; ?></strong>
						<p><?php echo $this->session->flashdata('alert')['message']; ?></p>
					</div>
				<?php } ?>

				<h1>Do you really want to remove ?</h1>

				<form action="<?php echo base_url('users/delete/' . $id) ?>" method="post">
					<input type="submit" class="btn btn-primary" name="confirm" value="Confirm">
					<a href="<?php echo base_url('users') ?>" class="btn btn-warning">Cancel</a>
				</form>

			</div>
			<!-- col-md-12 -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script type="text/javascript">
	$(document).ready(function() {
		$("#mainUserNav").addClass('active');
		$("#manageUserNav").addClass('active');
	});
</script>
