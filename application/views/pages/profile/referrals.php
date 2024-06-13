<div id="y-col1" style="width: 78.23%;">
	<div class="mod view_default">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
			<li><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li><a href="<?php echo base_url('profile/referrals') ?>">Referrals</a></li>
		</ol>
	</div>
	<div class="mod view_default">
		<div class="card bg-default">
			<div class="card-header">
				<div class="header-flex">
					<h1 style="font-weight: bold;font-size:20px">Referrals</h1>
					<div class="actions" style="display: flex;align-items:center;justify-content:space-between;gap:10px">
						<div>
							<h3><strong><?php echo count($my_referrals); ?></strong></h3>
							<p>Referrals</p>
						</div>
						<div>
							<h3><strong><?php echo $total_earned ?></strong></h3>
							<p>SB Earned</p>
						</div>
						<a href="<?php echo base_url('profile/invite') ?>" class="btn btn-primary" style="color:white">Invite Friends</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<?php if (empty($my_referrals)) { ?>
					<center>
						<h1 style="font-size: 25px;font-weight:200">You haven't invited anyone yet.</h1>
					</center>
				<?php } else { ?>
					<!-- list items -->
					<table class="table" style="width:100%;font-size:17px">
						<thead>
							<tr>
								<th><strong>Account</strong></th>
								<th><strong>Status</strong></th>
								<th><strong>Last Activity</strong></th>
								<th><strong>Total Earned</strong></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($my_referrals as $key => $ref_item) { ?>
								<tr>
									<td><?php echo $ref_item['user_info'] ?></td>
									<td><?php echo $ref_item['status'] ?></td>
									<td><?php echo $ref_item['last_activity'] ?></td>
									<td><?php echo $ref_item['total_earned'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
