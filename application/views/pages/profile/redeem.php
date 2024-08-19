<div id="y-col1" style="width: 78.23%;">
	<div class="mod view_default">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
			<li><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li><a href="<?php echo base_url('profile') ?>">Profile</a></li>
			<li><a href="<?php echo base_url('profile/redeem_points') ?>">Redeem SB</a></li>
		</ol>
	</div>

	<div class="mod view_default">
		<div class="bd type_breakingnews type_breakingnews_default">
			<?php echo validation_errors(); ?>
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

	<div class="alert alert-secondary">
		<div class="d-flex align-items-center justify-content-start">
			<h1 style="font-size: 25px;font-weight:bold">Balance:</h1>
			<h1 class="text-muted" style="font-size: 25px;font-weight:bold"><?php echo $balance ?> = <?php echo $balance_converted ?></h1>
		</div>
	</div>

	<div class="mod view_default">
		<div class="card bg-default">
			<div class="card-header">
				<h1 class="panel-title"><strong>Redeem SB</strong></h1>
				<p>Redeem your earned points with our available options</p>
			</div>
			<div class="card-body">
				<div class="row">
					<?php foreach ($withdraw_options as $key => $value) { ?>
						<div class="col-sm-6 col-md-6 mb-2" style="padding:10px">
							<div class="d-flex justify-content-between align-items-center shadow-sm" style="border: 1px solid #000;padding:10px">
								<div class="img">
									<img src="<?php echo base_url('assets/images/withdrawal.png') ?>" style="height: 150px;">
								</div>
								<div class="content">
									<h1 style="font-size: 25px;"><strong><?php echo $value ?> SB</strong></h1>
									<p>Withdraw to your saved payment option.</p>
									<form method="post" action="<?php echo base_url('profile/redeem_points') ?>">
										<input type="hidden" name="amount" value="<?php echo $value ?>">
										<div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
											<select name="bank_type" class="form-control">
												<option value="paypal">Paypal</option>
												<option value="bank">Bank Transfer</option>
											</select>
											<button class="mt-2 btn btn-primary" role="button">Request</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="col-md-8 mb-2" style="padding:10px">
						<div class="d-flex justify-content-start align-items-center shadow-sm" style="border: 1px solid #000;">
							<div class="img">
								<img src="<?php echo base_url('assets/images/withdrawal.png') ?>" style="height: 150px;">
							</div>
							<div class="content">
								<h1 style="font-size: 25px;">Enter Amount</h1>
								<p>Withdraw to your saved payment option.</p>
								<form method="post" action="<?php echo base_url('profile/redeem_points') ?>">
									<input class="form-control" type="number" name="amount" max="<?php echo $balance_in_sb ?>" value="0">
									<div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
										<select name="bank_type" class="form-control">
											<option value="paypal">Paypal</option>
											<option value="bank">Bank Transfer</option>
										</select>
										<button class="mt-2 btn btn-primary" role="button">Request</button>
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
