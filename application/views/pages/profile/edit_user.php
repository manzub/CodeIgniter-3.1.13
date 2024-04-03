<div id="y-col1">
	<div class="mod view_default">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
			<li><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li><a href="<?php echo base_url('profile/settings') ?>">Settings</a></li>
			<li><a href="<?php echo base_url('profile/edit_meta') ?>">Edit User</a></li>
		</ol>
	</div>
	<div class="mod view_default">
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="<?php echo base_url('profile/edit_user') ?>" method="post">
					<div class="form-group">
						<label for="">Full Name</label>
						<input placeholder="NULL" name="full_name" value="<?php echo $row['full_name'] ?>" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Username</label>
						<input placeholder="NULL" name="username" value="<?php echo $this->session->userdata('username') ?>" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Email</label>
						<input placeholder="NULL" name="email" value="<?php echo $this->session->userdata('email') ?>" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Phone</label>
						<input placeholder="NULL" name="phone" value="<?php echo $row['phone'] ?>" type="text" class="form-control">
					</div>
					<button class="btn btn-primary">Update</button>
				</form>
			</div>
		</div>
	</div>
</div>
