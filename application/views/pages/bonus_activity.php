<div id="y-col1">
	<div class="mod view_default">
		<div class="card card-default no-shadow">
			<div class="card-header">
				<h1 class="card-title"><strong>Bonus Activities</strong></h1>
				<p>Complete bonus activities to earn extra rewards</p>
			</div>
			<div class="card-body">
				<table class="table" style="width:100%">
					<thead style="width:100%">
						<tr>
							<th style="font-weight: bold;width:50%">Activity</th>
							<th style="font-weight: bold;width:25%">Reward</th>
							<th style="font-weight: bold;width:25%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($bonuses as $key => $value) { ?>
							<tr>
								<td style="text-align:center;vertical-align:middle"><?php echo $value['activity_type'] . " - " . substr($value['title'], 0, 70) ?></td>
								<td style="text-align:center;vertical-align:middle"><?php echo $value['reward_points'] ?>SB</td>
								<td style="text-align:center;vertical-align:middle"><a href="<?php echo $value['link'] ?>" style="color:white" class="btn rounded btn-primary">Start</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="mod view_default">
		<h3 class="page-title">More Links...</h3>
		<div class="list-group">
			<a href="#" class="list-group-item list-group-item-action active" aria-current="true">
				Available Bonuses
			</a>
			<a href="<?php echo base_url('home') ?>" class="list-group-item list-group-item-action">Claim Daily Bonus</a>
			<a href="<?php echo base_url('profile') ?>" class="list-group-item list-group-item-action">CLaim Login Bonus</a>
		</div>
	</div>
</div>