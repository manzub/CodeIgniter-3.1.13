<div class="wrapper">
	<div class="jumbotron" style="padding: 30px 30px;">
		<h1 style="font-size: 40px;">Frequently Asked Questions</h1>
		<p class="lead text-muted">Find answers to frequently asked questions to make your experience easier.</p>
	</div>

	<div id="accordion">
		<div class="card">
			<div class="card-header" id="howitworksHeader">
				<h5 class="mb-0">
					<button class="btn btn-link" data-toggle="collapse" data-target="#howitworks" aria-expanded="false" aria-controls="howitworks">
						How does SurveyVine work?
					</button>
				</h5>
			</div>

			<div id="howitworks" class="collapse" aria-labelledby="howitworksHeader" data-parent="#accordion">
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<div class="card" style="width: 18rem;">
								<img src="..." class="card-img-top" style="height:150px" alt="...">
								<div class="card-body text-center">
									<h5 class="card-title" style="font-size: 15px;"><strong>Join SurveyVine</strong></h5>
									<p class="card-text">Create an account with us.</p>
									<p class="card-text">We reward our members with free cash everyday for things dey already do online.</p>
									<span><br /></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card" style="width: 18rem;">
								<img src="..." class="card-img-top" style="height:150px" alt="...">
								<div class="card-body text-center">
									<h5 class="card-title" style="font-size: 15px;"><strong>Earn SB</strong></h5>
									<p class="card-text">Answer surveys, review short video clips, transcribe audio recording and complete other activities to earn points.</p>
									<span><br /></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card" style="width: 18rem;">
								<img src="..." class="card-img-top" style="height:150px" alt="...">
								<div class="card-body text-center">
									<h5 class="card-title" style="font-size: 15px;"><strong>Redeem Points</strong></h5>
									<p class="card-text">Redeem your earned points to PayPal or accepted local currencies.</p>
									<span><br /></span>
									<span><br /></span>
								</div>
							</div>
						</div>
						<div class="col-md-12 text-center" style="margin: 15px 0px">
							<a href="<?php echo base_url('auth/signup') ?>" class="btn btn-lg text-light btn-primary">Start earning points</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header" id="referralHeader">
				<h5 class="mb-0">
					<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#referral" aria-expanded="false" aria-controls="referrals">
						How do I Earn points from Referrals?
					</button>
				</h5>
			</div>
			<div id="referral" class="collapse" aria-labelledby="referralHeader" data-parent="#accordion">
				<div class="card-body">
					<div style="font-size: 15px;">
						<p>For each new person <a href="<?php echo base_url('profile/referrals') ?>">referred</a> to surveyvine.com, the referrer will earn 10% of the referrals earning.</p>
						<p>The categories eligible for th 10% Mareferral program include the categories along the top menu bar:</p>
						<span><strong>Surveys</strong>, <strong>Reviews</strong>, <strong>Transcribe</strong></span>
						<p>You will continue earning 10% from your referrals for as long as your referral is active on surveyvine.com, with no maximum limit.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header" id="paymentsHeader">
				<h5 class="mb-0">
					<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#payments" aria-expanded="false" aria-controls="payments">
						Payment Options
					</button>
				</h5>
			</div>
			<div id="payments" class="collapse" aria-labelledby="paymentsHeader" data-parent="#accordion">
				<div class="card-body" style="font-size: 15px;">
					<h1 style="font-size: 20px;"><strong>How do i set a preferred payment option.</strong></h1>
					<span><br /></span>
					<p>On the sidebar menu select (Settings) to go the settings page.</p>
					<p>On the settings page you can save your payment options. <strong>PayPal</strong> and any other accepted local currency.</p>
					<p>On request for withdrawal you will be prompted to select your preferred payment option.</p>
					<span><br /></span>
					<span><br /></span>
					<h1 style="font-size: 20px;"><strong>How long does withdrawals take.</strong></h1>
					<span><br /></span>
					<p>Withdrawals typically take 2-3 business days from when the amount is requested to arrive in your selected payout option.</p>
					<p>Withdrawals are not automatic. if any issues experienced regarding a requested payment. contact us on <a href="mailto:contact@surveyvine.com">contact@surveyvine.com</a></p>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header" id="specialAccountsHeader">
				<h5 class="mb-0">
					<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#specialAccounts" aria-expanded="<?php echo ($this->input->get('show') != null && $this->input->get('show') == 'modsnpubs') ? 'true' : 'false'; ?>" aria-controls="specialAccounts">
						Moderator and Publisher Accounts
					</button>
				</h5>
			</div>
			<div id="specialAccounts" class="collapse <?php echo ($this->input->get('show') != null && $this->input->get('show') == 'modsnpubs') ? 'show' : ''; ?>" aria-labelledby="specialAccountsHeader" data-parent="#accordion">
				<div class="card-body" style="font-size:15px">
					<h1 class="h2 font-weight-bold">Moderators</h1>
					<div class="jumbotron" style="font-size:15px;font-family:monospace;text-align:center">
						<p>Our moderator program is another way for you to earn as a member of surveyvine.com. Every activity completed on surveyvine.com will need to be reviewed to ensure the quality of the reveiws. Our moderator program allows selected members to earn money while reviewing these activities for spam or false information.</p>
						<div class="row my-3">
							<div class="col-md-6">
								<img src="<?php echo base_url('assets/images/marketing.png') ?>" height="300" alt="">
							</div>
							<div class="col-md-6">
								<img src="<?php echo base_url('assets/images/investigation.png') ?>" height="300" alt="">
							</div>
						</div>
						<p>As a moderator you can either approve or deny submitted activities and earn points for your reviews. <strong>spam reviews or false contents could earn you/the user a strike.</strong></p>
						<a href="<?php echo base_url('auth/signup?type=moderator') ?>" class="mt-2 rounded btn btn-lg btn-outline-primary">Get Started</a>
					</div>
					<hr>
					<h1 class="h2 font-weight-bold">Publishers</h1>
					<div class="jumbotron" style="font-size:15px;font-family:monospace;text-align:center">
						<p>Registered publishers on surveyvine.com will have access to post activities like surveys, reviews and transcribe items on our platform to gather feedback and conduct general surveys. Publishers pay an initial sign in fee and have access to the full platform. activities completed are made available on your publisher dashboard for you to export/convert to cvs/txt file format.</p>
						<div class="row my-3">
							<div class="col-md-6">
								<img src="<?php echo base_url('assets/images/new-post.png') ?>" height="300" alt="">
							</div>
							<div class="col-md-6">
								<img src="<?php echo base_url('assets/images/post-production.png') ?>" height="300" alt="">
							</div>
						</div>
						<p>Publishers cannot earn rewards from the platform and dont have to pay members to complete activities.</p>
						<a href="<?php echo base_url('auth/signup?type=publisher') ?>" class="mt-2 rounded btn btn-lg btn-outline-primary">Become a publisher</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
