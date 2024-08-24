<div id="y-col1">
	<div class="mod view_default">
		<div class="bd type_fptoday type_fptoday_default">

			<?php echo validation_errors('<div class="alert alert-warning">', '</div>') ?>
			<?php if ($this->session->flashdata('alert')) { ?>
				<div class="alert <?php echo $this->session->flashdata('alert')['classname']; ?> alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong><?php echo $this->session->flashdata('alert')['title']; ?></strong>
					<p><?php echo $this->session->flashdata('alert')['message']; ?></p>
				</div>
			<?php } ?>

			<div class="row">
				<div class="col-md-12 mx-auto">
					<div class="card bg-light">
						<div class="card-header">
							<div class="text-center">
								<h1 style="font-size:20px;font-weight:bold">Leave us a message</h1>
								<p>Do you have a requests or queries, leave us a message at <a href="mailto://contact@surveyvine.com">contact@surveyvine.com</a></p>
							</div>
						</div>
						<div class="card-body mt-auto mx-auto p-2">
							<div class="container">
								<form action="<?php echo base_url('home/contact_us') ?>" role="form" method="post">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="">First Name*</label>
												<input type="text" required class="form-control" name="fname" placeholder="Enter your firstname">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Last Name</label>
												<input type="text" class="form-control" name="lname" placeholder="Enter your lastname">
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="">Email address*</label>
										<input type="email" required class="form-control" name="email" placeholder="enter your email address">
									</div>
									<div class="form-group">
										<label for="">Please select a category*</label>
										<select id="form_need" name="category" class="form-control" required="required" data-error="Please select a category.">
											<option value="" selected disabled>--Select Your Issue--</option>
											<option value="account">I am having troubles with my account.</option>
											<option value="referrals">Referrals</option>
											<option value="payments">Payments/Withdrawals</option>
											<option value="website">Website/Activities</option>
											<option value="other">Other</option>
										</select>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="form_message">Message *</label>
												<textarea id="form_message" name="message" class="form-control" placeholder="Write your message here." rows="4" required="required" data-error="Please, leave us a message."></textarea>
											</div>
										</div>

										<div class="col-md-12">
											<input type="submit" class="btn btn-primary btn-send pt-2 btn-block" value="Send Message">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
