<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Manage
			<small>Users</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Users</li>
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

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Add User</h3>
					</div>
					<form role="form" action="<?php base_url('users/create') ?>" method="post">
						<div class="box-body">

							<?php echo validation_errors(); ?>

							<div class="form-group">
								<label for="groups">Groups</label>
								<select class="form-control" id="groups" name="groups">
									<option value="">Select Groups</option>
									<?php foreach ($group_data as $k => $v) : ?>
										<option value="<?php echo $v['id'] ?>"><?php echo $v['group_name'] ?></option>
									<?php endforeach ?>
								</select>
							</div>

							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off">
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off">
							</div>

							<div class="form-group">
								<label for="password">Password</label>
								<input type="text" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
							</div>

							<div class="form-group">
								<label for="cpassword">Confirm password</label>
								<input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
							</div>

						</div>
						<!-- /.box-body -->

						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Save Changes</button>
							<a href="<?php echo base_url('users/') ?>" class="btn btn-warning">Back</a>
						</div>
					</form>
				</div>
				<!-- /.box -->
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
		$("#groups").select2();

		$("#mainUserNav").addClass('active');
		$("#createUserNav").addClass('active');

	});
</script>
