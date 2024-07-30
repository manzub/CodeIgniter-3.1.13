<div class="content-wrapper">
	<section class="content-header">
		<h1>Dashboard <small>Control panel</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>

	<section class="content">
		<!-- moderator balance -->
		<?php if ($group_name == 'moderator') { ?>
			<div class="row">
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3><span id="coin_balance">0</span>SB</h3>
							<p>Current Balance</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<a href="<?php echo base_url('profile/redeem_points') ?>" class="small-box-footer">Withdraw <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>
		<?php } ?>
		<!-- admin boxes -->
		<?php if ($is_admin == true) { ?>
			<div class="row">
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3>0</h3>
							<p>Total Users</p>
						</div>
						<div class="icon">
							<i class="ion ion-android-people"></i>
						</div>
						<a href="<?php echo base_url('users/') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>
		<?php } ?>
	</section>
</div>


<script>
	$(document).ready(function() {
		// ajax get balance
		var y = setInterval(() => {
			$.get('<?php echo base_url('home/my_balance') ?>', function(data) {
				let result = JSON.parse(data);
				if (result.balance.total_rewards != null) {
					document.getElementById('coin_balance').innerHTML = result.balance.total_rewards
				}
			})
		}, 1000);

		window.onbeforeunload = function() {
			clearInterval(y);
		}
	})
</script>
