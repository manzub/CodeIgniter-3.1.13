<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Manage
			<small>Reviews</small>
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
								<video height="250" src="<?php echo $review_item['short_clip'] ?>"></video>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<p><strong>User Rating</strong></p>
											<div class="rating">
												<?php for ($i = 0; $i <= $completed_item['review_rating']; $i++) { ?>
													<i style="color:gold" class="fa fa-star"></i>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<p><strong>Has Watched Movie</strong></p>
											<input class="form-control" disabled type="text" value="<?php echo $completed_item['has_watched'] == '1' ? 'Yes' : 'No'; ?>">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="">User Review</label>
								<textarea class="form-control" name="" disabled rows="6"><?php echo $completed_item['review_text'] ?></textarea>
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
