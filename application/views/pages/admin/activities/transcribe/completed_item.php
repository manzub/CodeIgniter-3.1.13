<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Manage
			<small>Transcribe</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"><i class="fa fa-dashboard"></i> Activities</a></li>
			<li class="active">Review Completed Item</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div id="messages"></div>

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Review Completed Item</h3>
					</div>

					<form action="" method="post">
						<div class="box-body">
							<div class="form-group">
								<?php
								$av_files = unserialize($transcribe_item['files']);
								for ($i = 0; $i < sizeof($av_files); $i++) { ?>
									<audio controls>
										<source src="<?php echo $av_files[$i] ?>" type="audio/mp3" />
									</audio>
								<?php } ?>
							</div>
							<div class="form-group">
								<label for="">User Review</label>
								<textarea class="form-control" disabled rows="6"><?php echo $completed_item['transcribe_text']; ?></textarea>
							</div>
							<div class="form-group">
								<div style="display: flex;justify-content:start;gap:10px">
									<div class="form-check">
										<input class="form-check-input" value="approve" type="radio" name="approve_deny" id="flexRadioDefault1">
										<label class="form-check-label" for="flexRadioDefault1">
											Approve
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" value="deny" type="radio" name="approve_deny" id="flexRadioDefault2" checked>
										<label class="form-check-label" for="flexRadioDefault2">
											Deny
										</label>
									</div>
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
