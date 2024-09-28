<div class="profile-home" id="y-col1" style="width: 72%">
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
			<li class="breadcrumb-item"><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li class="breadcrumb-item active"><a href="<?php echo base_url('profile') ?>">Profile</a></li>
		</ol>
	</div>

	<div class="alert alert-secondary">
		<div class="d-flex align-items-center justify-content-start">
			<h1 style="font-size: 25px;font-weight:bold">Balance:</h1>
			<h1 class="text-muted" style="font-size: 25px;font-weight:bold"><?php echo $balance ?> = <?php echo $balance_converted ?></h1>
		</div>
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
				<?php foreach ($plans as $value) { ?>
					<div class="col-md-4">
						<div class="card card-info">
							<div class="card-header" style="text-align: center;">
								<h1 style="font-size: 20px;font-weight:bold;margin-bottom:5px;"><?php echo $value['name'] ?></h1>
								<p style="font-size: 12px;margin:0px 0px;"><?php echo $value['multiplier'] ?>x Reward Bonus</p>
								<p style="font-size: 12px;margin: 0px 0px;">No delay on withdrawals</p>
							</div>
							<div class="card-body" style="text-align: center;">
								<div style="display: flex;align-items:baseline;justify-content:center;margin-bottom:10px">
									<h1 style="font-size: 25px;font-weight:bold;"><?php echo number_format($value['price'], 0) . $this->session->userdata('currency')['currency'] ?></h1>
									<small>/MONTHLY</small>
								</div>
								<button onclick="window.alert('This function is not available yet.')" class="btn btn-primary">BUY</button>
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
		<table class="table table-responsive" style="width:100%">
			<thead>
				<tr>
					<th>Activity</th>
					<th>Message</th>
					<th>Time</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($activities_list as $key => $value) { ?>
					<tr>
						<td><?php echo $value['activity'] ?></td>
						<td><?php echo $value['message'] ?></td>
						<td><?php echo date('m-d H:i', strtotime($value['last_modified'])) ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
