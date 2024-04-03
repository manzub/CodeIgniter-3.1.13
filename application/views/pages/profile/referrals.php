<div id="y-col1" style="width: 78.23%;">
	<div class="mod view_default">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
			<li><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li><a href="<?php echo base_url('profile/referrals') ?>">Referrals</a></li>
		</ol>
	</div>
	<div class="mod view_default">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="header-flex">
					<h1 style="font-weight: bold;font-size:20px">Referrals</h1>
					<div class="actions" style="display: flex;align-items:center;justify-content:space-between;gap:10px">
						<div>
							<h3><strong>0</strong></h3>
							<p>Referrals</p>
						</div>
						<div>
							<h3><strong>0</strong></h3>
							<p>SB Earned</p>
						</div>
						<button class="btn btn-primary">Invite Friends</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<center>
					<h1 style="font-size: 25px;font-weight:200">You haven't invited anyone yet.</h1>
				</center>
			</div>
		</div>
	</div>
</div>
