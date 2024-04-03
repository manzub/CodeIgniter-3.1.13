<div id="y-col1">
	<div class="mod view_default">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
			<li><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li><a href="<?php echo base_url('profile/settings') ?>">Settings</a></li>
			<li><a href="<?php echo base_url('profile/edit_meta') ?>">Edit Info</a></li>
		</ol>
	</div>
	<div class="mod view_default">
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="<?php echo base_url('profile/edit_meta') ?>" method="post">
					<div class="form-group">
						<label for="">Address</label>
						<input placeholder="NULL" name="address" value="<?php echo $row['address'] ?>" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="">City</label>
						<input placeholder="NULL" name="city" value="<?php echo $row['city'] ?>" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="">State</label>
						<input placeholder="NULL" name="state" value="<?php echo $row['state'] ?>" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Post Code</label>
						<input placeholder="NULL" name="post_code" value="<?php echo $row['post_code'] ?>" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Country</label>
						<input placeholder="NULL" name="country" value="<?php echo $row['country'] ?>" type="text" class="form-control">
					</div>
					<button class="btn btn-primary">Update</button>
				</form>
			</div>
		</div>
	</div>
</div>
