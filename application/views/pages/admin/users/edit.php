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
						<h3 class="box-title">Edit User</h3>
					</div>
					<form role="form" action="<?php base_url('users/create') ?>" method="post">
						<div class="box-body">

							<?php echo validation_errors(); ?>

							<div class="form-group">
								<label for="groups">Groups</label>
								<select class="form-control" id="groups" name="groups">
									<option value="">Select Groups</option>
									<?php foreach ($group_data as $k => $v) : ?>
										<option value="<?php echo $v['id'] ?>" <?php if ($user_group['id'] == $v['id']) {
																															echo 'selected';
																														} ?>><?php echo $v['group_name'] ?></option>
									<?php endforeach ?>
								</select>
							</div>

							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $user_data['username'] ?>" autocomplete="off">
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $user_data['email'] ?>" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="email">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="">Select Status</option>
									<?php foreach ($user_status as $key => $value) { ?>
										<option value="<?php echo $value ?>" <?php if ($user_data['status'] == $value) {
											echo "selected";
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group">
								<div class="alert alert-info alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Leave the password field empty if you don't want to change.
								</div>
							</div>

							<div class="form-group">
								<label for="password">Password</label>
								<input type="text" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
							</div>

							<div class="form-group">
								<label for="cpassword">Confirm password</label>
								<input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
							</div>

							<div class="form-group">
								<h3 class="text-uppercase">Strikes: <?php echo $user_data['strike'] ?></h3>
								<span id="strikeuser" user-id="<?php echo $user_data['user_id'] ?>" class="btn btn-danger"><i class="fa fa-times"></i> Add Strike</span>
								<span id="nostrikeuser" user-id="<?php echo $user_data['user_id'] ?>" class="btn btn-info"><i class="fa fa-check"></i> Remove A Strike</span>
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
	var base_url = '<?php echo base_url() ?>';
	$(document).ready(function() {
		$("#groups").select2();
		$("#status").select2();

		$("#mainUserNav").addClass('active');
		$("#manageUserNav").addClass('active');

		let action = document.getElementById('strikeuser');
		action.addEventListener('click', function(data) {
			if (window.confirm('Are you sure you want to continue?')) {
				let user_id = action.getAttribute('user-id');
				window.location.href = base_url + 'users/strike/' + user_id;
			}
		})

		let action2 = document.getElementById('nostrikeuser');
		action2.addEventListener('click', function(data) {
			if (window.confirm('Are you sure you want to continue?')) {
				let user_id = action.getAttribute('user-id');
				window.location.href = base_url + 'users/remove_strike/' + user_id;
			}
		})
	});
</script>
