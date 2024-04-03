<div id="y-col1" style="width: 78.23%;">
	<div class="mod view_default">
		<div class="jumbotron"></div>
	</div>
	<div class="mod view_default">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-title">
					<h1 style="font-size: 20px;font-weight:bold;text-transform:capitalize">3 ways to invite:</h1>
				</div>
			</div>
			<div class="panel-body">
				<div class="row equal">
					<div class="col-lg-12">
						<form action="<?php echo base_url('profile/send_invite_email') ?>">
							<div class="form-group">
								<div class="row">
									<div class="col-lg-12">
										<p>
											<span class="badge badge-info">1</span>
											<strong>Send a quick email</strong>
										</p>
									</div>
									<div class="col-lg-5 col-md-5">
										<label for="recipientName" style="visibility: hidden;">Recipient Name</label>
										<input type="text" class="form-control" placeholder="Recipient Name (Optional)">
									</div>
									<div class="col-lg-7 col-md-7">
										<label for="" style="visibility: hidden;">Email address</label>
										<input type="text" class="form-control" placeholder="Recipient Email Address">
									</div>
								</div>
								<hr />
								<div class="row">
									<div class="col-lg-12">
										<p>
											<span class="badge badge-info">2</span>
											<strong>Share your referral link directly</strong>
										</p>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
											<label for="" style="visibility: hidden;">Referral Link</label>
											<div class="input-group">
												<input type="text" class="form-control" placeholder="Referral link">
												<span class="input-group-btn">
													<button class="btn btn-default" type="button">
														<span class="glyphicon glyphicon-copy"></span>
														<strong>Copy</strong>
													</button>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
