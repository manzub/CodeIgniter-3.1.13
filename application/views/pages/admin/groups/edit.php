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
			<li><a href="<?php echo base_url('groups/') ?>">Groups</a></li>
			<li class="active">Edit</li>
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
						<h3 class="box-title">Edit Group</h3>
					</div>
					<form role="form" action="<?php base_url('groups/update') ?>" method="post">
						<div class="box-body">

							<?php echo validation_errors('<div class="alert alert-warning">', '</div>'); ?>

							<div class="form-group">
								<label for="group_name">Group Name</label>
								<input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name" value="<?php echo $group_data['group_name']; ?>">
							</div>
							<div class="form-group">
								<label for="permission">Permission</label>

								<?php $serialize_permission = unserialize($group_data['permissions']); ?>

								<table class="table table-responsive">
									<thead>
										<tr>
											<th></th>
											<th>Create</th>
											<th>Manage</th>
											<th>Review item</th>
											<th>Review Completed</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Users</td>
											<td><input type="checkbox" class="minimal" name="permission[]" id="permission" class="minimal" value="createUser" <?php if ($serialize_permission) {
																																																																					if (in_array('createUser', $serialize_permission)) {
																																																																						echo "checked";
																																																																					}
																																																																				} ?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageUser" <?php
																																																												if ($serialize_permission) {
																																																													if (in_array('manageUser', $serialize_permission)) {
																																																														echo "checked";
																																																													}
																																																												}
																																																												?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Groups</td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createGroup" <?php
																																																													if ($serialize_permission) {
																																																														if (in_array('createGroup', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													}
																																																													?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageGroup" <?php
																																																													if ($serialize_permission) {
																																																														if (in_array('manageGroup', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													}
																																																													?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Bonuses</td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createBonus" <?php if ($serialize_permission) {
																																																														if (in_array('createBonus', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageBonus" <?php if ($serialize_permission) {
																																																														if (in_array('manageBonus', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Category</td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCategory" <?php if ($serialize_permission) {
																																																															if (in_array('createCategory', $serialize_permission)) {
																																																																echo "checked";
																																																															}
																																																														} ?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageCategory" <?php if ($serialize_permission) {
																																																															if (in_array('manageCategory', $serialize_permission)) {
																																																																echo "checked";
																																																															}
																																																														} ?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Reviews</td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createReview" <?php if ($serialize_permission) {
																																																														if (in_array('createReview', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageReview" <?php if ($serialize_permission) {
																																																														if (in_array('manageReview', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Transcribe</td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createTranscribe" <?php if ($serialize_permission) {
																																																																if (in_array('createTranscribe', $serialize_permission)) {
																																																																	echo "checked";
																																																																}
																																																															} ?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageTranscribe" <?php if ($serialize_permission) {
																																																																if (in_array('manageTranscribe', $serialize_permission)) {
																																																																	echo "checked";
																																																																}
																																																															} ?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Surveys</td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createSurvey" <?php if ($serialize_permission) {
																																																														if (in_array('createSurvey', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageSurvey" <?php if ($serialize_permission) {
																																																														if (in_array('manageSurvey', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Transactions</td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createOrder" <?php if ($serialize_permission) {
																																																														if (in_array('createOrder', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageOrder" <?php if ($serialize_permission) {
																																																														if (in_array('manageOrder', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Analytics</td>
											<td> - </td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageReport" <?php if ($serialize_permission) {
																																																														if (in_array('manageReport', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Profile</td>
											<td> - </td>
											<td> - </td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewProfile" <?php if ($serialize_permission) {
																																																														if (in_array('viewProfile', $serialize_permission)) {
																																																															echo "checked";
																																																														}
																																																													} ?>></td>
											<td> - </td>
										</tr>
										<tr>
											<td>Setting</td>
											<td>-</td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageSetting" <?php if ($serialize_permission) {
																																																															if (in_array('manageSetting', $serialize_permission)) {
																																																																echo "checked";
																																																															}
																																																														} ?>></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Moderator</td>
											<td> - </td>
											<td> - </td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="reviewActivity" <?php if ($serialize_permission) {
																																																															if (in_array('reviewActivity', $serialize_permission)) {
																																																																echo "checked";
																																																															}
																																																														} ?>></td>
											<td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="manageActivity" <?php if ($serialize_permission) {
																																																															if (in_array('manageActivity', $serialize_permission)) {
																																																																echo "checked";
																																																															}
																																																														} ?>></td>
										</tr>
									</tbody>
								</table>

							</div>
						</div>
						<!-- /.box-body -->

						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Update Changes</button>
							<a href="<?php echo base_url('groups/') ?>" class="btn btn-warning">Back</a>
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
		$("#mainGroupNav").addClass('active');
		$("#manageGroupNav").addClass('active');

		$('input[type="checkbox"].minimal').iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'iradio_minimal-blue'
		});
	});
</script>
