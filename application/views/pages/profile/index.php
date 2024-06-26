<div id="y-col1" style="width: 78.23%;">
	<div class="mod view_default">
		<div class="bd type_breakingnews type_breakingnews_default">
			<?php if ($this->session->flashdata('alert')) { ?>
				<div class="alert <?php echo $this->session->flashdata('alert')['classname']; ?> alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong><?php echo $this->session->flashdata('alert')['title']; ?></strong>
					<p><?php echo $this->session->flashdata('alert')['message']; ?></p>
				</div>
			<?php } ?>
		</div>
	</div>

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
						<div class="card card-info">
							<div class="card-header" style="text-align: center;">
								<h1 style="font-size: 20px;font-weight:bold;margin-bottom:5px;">Gold</h1>
								<p style="font-size: 12px;margin:0px 0px;">2x Reward Bonus</p>
								<p style="font-size: 12px;margin: 0px 0px;">No delay on withdrawals</p>
							</div>
							<div class="card-body" style="text-align: center;">
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
			<?php echo $streaks_html ?>
		</div>
	</div>
	<div class="jumbotron" style="padding: 10px 10px;">
		<div class="header-flex">
			<h2 style="font-weight: bold;">Activities</h2>
		</div>
	</div>
</div>
