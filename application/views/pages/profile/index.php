<div id="y-col1" style="width: 78.23%;">
	<div class="mod view_default">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
			<li><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li><a href="<?php echo base_url('profile') ?>">Profile</a></li>
		</ol>
	</div>
	<div class="jumbotron" style="padding: 10px 10px;">
		<p style="font-size: 15px;font-weight:bold">Increase your <strong class="label label-primary">Membership Level</strong></p>
		<p style="font-size: 12px;">Increase your membership to increase your chances of earning more rewards, increase your withdrawal limit and no delays on your withdrawal.</p>
		<div style="margin-top: 20px;">
			<div class="header-flex" style="margin-bottom: 10px;">
				<h2 style="font-weight: bold;">Membership Levels</h2>
				<a href="#" style="font-weight: bold;">Show More</a>
			</div>
			<div class="row">
				<?php for ($i = 0; $i < 3; $i++) { ?>
					<div class="col-md-4">
						<div class="panel panel-info">
							<div class="panel-heading" style="text-align: center;">
								<h1 style="font-size: 20px;font-weight:bold;margin-bottom:5px;">Gold</h1>
								<p style="font-size: 12px;margin:0px 0px;">2x Reward Bonus</p>
								<p style="font-size: 12px;margin: 0px 0px;">No delay on withdrawals</p>
							</div>
							<div class="panel-body" style="text-align: center;">
								<div style="display: flex;align-items:baseline;justify-content:center;margin-bottom:10px">
									<h1 style="font-size: 30px;font-weight:bold;">$20.45</h1>
									<small>/MONTHLY</small>
								</div>
								<button class="btn btn-primary">BUY</button>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="jumbotron" style="padding: 10px 10px;">
		<div class="header-flex">
			<h2 style="font-weight: bold;">My Streak!</h2>
		</div>
		<p style="font-size: 12px;">Log in everyday to claim daily bonus</p>
		<div class="row">
			<?php $last_reward = $bonus ?>
			<?php for ($i = 1; $i <= 5; $i++) { ?>
				<?php $claimed = true;
				$last_reward = round($last_reward * $multiply); ?>
				<div class="col-md-3" style="text-align: center;">
					<div class="panel <?php if (!empty($last_streak)) {
						echo $last_streak[0]['streak'].' ';
															if ($i <= intval($last_streak[0]['streak'])) {
																echo 'panel-warning';
															} else if ($i == (intval($last_streak[0]['streak']) + 1) && $this->session->userdata('bonus_available')) {
																$claimed = false;
																echo 'panel-success';
															} else {
																echo 'panel-default';
															}
														} else {
															if ($i == 1) {
																$claimed = false;
																echo 'panel-success';
															} else {
																echo 'panel-default';
															}
														} ?>">
						<div class="panel-heading">
							<h1 style="font-size: 25px;font-weight:bolder;" class="page-title">DAY <?php echo $i ?></h1>
						</div>
						<div class="panel-body">
							<h3 style="font-size: 20px;font-weight:bold;margin-bottom:10px">+<?php echo $last_reward ?> SB</h3>
							<form action="profile/claimDailyBonus" method="post">
								<button <?php echo $claimed ? 'disabled' : '' ?> class="btn btn-primary" style="text-transform: uppercase;"><?php echo $i <= intval($last_streak[0]['streak']) ? 'Claimed' : 'Claim' ?></button>
							</form>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="jumbotron" style="padding: 10px 10px;">
		<div class="header-flex">
			<h2 style="font-weight: bold;">Activities</h2>
		</div>
	</div>
</div>
