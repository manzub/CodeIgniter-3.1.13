<div class="content-wrapper">
	<div class="content-header">
		<h1>
			Manage
			<small>Bonuses</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="">Activities</li>
			<li class="active">Bonuses</li>
		</ol>
	</div>

	<!-- Main Content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div id="messages">
					<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
				</div>

				<div class="box">
					<div class="box-header">
						<h3 class="box-title" style="font-size: 30px;">Create New Bonus Item</h3>
						<p>Select an already published activity below and reward bonus points for completing that activity.</p>
					</div>

					<form action="<?php echo base_url('bonuses/create') ?>" method="post">
						<div class="box-body">

							<!-- form items -->
							<div class="form-group">
								<label>Activity Type</label>
								<select name="activity_type" required class="form-control">
									<option value="sv">Survey</option>
									<option value="rv">Review</option>
									<option value="av">Transcribe</option>
								</select>
							</div>

							<div class="form-group">
								<label>Select An Activity Below</label>
								<select name="activity_id" required class="form-control" id="select2form">
									<option selected disabled>Choose option that matches selected activity type...</option>
									<optgroup label="Survey Items">
										<?php foreach ($all_surveys as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['title'] ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="Review Items">
										<?php foreach ($all_reviews as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['title'] ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="Transcribe Items">
										<?php foreach ($all_transcribe as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['title'] ?></option>
										<?php } ?>
									</optgroup>
								</select>
							</div>

							<div class="form-group">
								<label>Reward Points</label>
								<input required type="number" class="form-control" name="reward_points" value="0" placeholder="Reward Points...">
							</div>

							<div class="form-group">
								<label>Limit</label>
								<input type="number" name="global_limit" value="10" class="form-control">
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>


<script>
	$(document).ready(function() {
		$('#select2form').select2();
	})
</script>
