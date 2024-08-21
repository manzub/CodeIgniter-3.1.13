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
			<li class="active">groups</li>
		</ol>
	</section>

	<section class="content">
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
						<h3 class="box-title">Add Group</h3>
					</div>
					<form role="form" action="<?php echo base_url('groups/create') ?>" method="post">
						<div class="box-body">
							<?php echo validation_errors('<div class="alert alert-warning">', '</div>') ?>

							<div class="form-group">
								<label for="group_name">Group Name</label>
								<input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name">
							</div>

							<div class="form-group">
								<label for="permission">Permission</label>

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
											<td><input type="checkbox" name="permission[]" id="permission" value="createUser" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageUser" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Groups</td>
											<td><input type="checkbox" name="permission[]" id="permission" value="createGroup" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageGroup" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Bonuses</td>
											<td><input type="checkbox" name="permission[]" id="permission" value="createBonus" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageBonus" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Category</td>
											<td><input type="checkbox" name="permission[]" id="permission" value="createCategory" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageCategory" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Reviews</td>
											<td><input type="checkbox" name="permission[]" id="permission" value="createReview" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageReview" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Transcribe</td>
											<td><input type="checkbox" name="permission[]" id="permission" value="createTranscribe" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageTranscribe" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Surveys</td>
											<td><input type="checkbox" name="permission[]" id="permission" value="createSurvey" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageSurvey" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Transactions</td>
											<td><input type="checkbox" name="permission[]" id="permission" value="createOrder" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageOrder" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Analytics</td>
											<td> - </td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageReport" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Profile</td>
											<td> - </td>
											<td> - </td>
											<td><input type="checkbox" name="permission[]" id="permission" value="viewProfile" class="minimal"></td>
											<td> - </td>
										</tr>
										<tr>
											<td>Setting</td>
											<td>-</td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageSetting" class="minimal"></td>
											<td> - </td>
											<td> - </td>
										</tr>
										<tr>
											<td>Moderator</td>
											<td> - </td>
											<td> - </td>
											<td><input type="checkbox" name="permission[]" id="permission" value="reviewActivity" class="minimal"></td>
											<td><input type="checkbox" name="permission[]" id="permission" value="manageActivity" class="minimal"></td>
										</tr>
									</tbody>
								</table>

							</div>
						</div>
						<!-- box body end -->

						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Save Changes</button>
							<a href="<?php echo base_url('groups/') ?>" class="btn btn-warning">Back</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$("#mainGroupNav").addClass('active');
		$("#addGroupNav").addClass('active');

		$('input[type="checkbox"].minimal').iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'iradio_minimal-blue'
		});
	});
</script>
