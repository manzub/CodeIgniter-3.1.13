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
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3><?php echo $pending_transactions ?></h3>
							<p>Pending Withdrawal Requests</p>
						</div>
						<div class="icon">
							<i class="ion ion-social-usd"></i>
						</div>
						<a href="<?php echo base_url('transactions/requests') ?>" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3><?php echo $done_transactions ?></h3>
							<p>Completed Withdrawal Requests</p>
						</div>
						<div class="icon">
							<i class="ion ion-social-usd"></i>
						</div>
						<a href="<?php echo base_url('transactions/requests') ?>" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>
			<div class="row">
				<h1 style="margin:20px 15px;">Activities</h1>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3><?php echo $pending_completed_reviews ?></h3>
							<p>Review Items Available</p>
						</div>
						<div class="icon">
							<i class="ion ion-document-text"></i>
						</div>
						<a href="<?php echo base_url('reviews/completed_list') ?>" class="small-box-footer">Go to Review Items <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-blue">
						<div class="inner">
							<h3><?php echo $pending_completed_transcribe ?></h3>
							<p>Transcribe Items Available</p>
						</div>
						<div class="icon">
							<i class="ion ion-volume-medium"></i>
						</div>
						<a href="<?php echo base_url('transcribe/completed_list') ?>" class="small-box-footer">Go to Review Items <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>
		<?php } ?>

		<!-- publisher boxes -->
		<?php if ($group_name == 'publisher') { ?>
			<div class="row">
				<h2 style="margin: 20px 10px">Surveys</h2>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3><?php echo $surveys_all; ?></h3>
							<p>Total Surveys</p>
						</div>
						<div class="icon">
							<i class="ion ion-android-checkbox-outline"></i>
						</div>
						<a href="<?php echo base_url('surveys/admin') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<div class="small-box bg-red">
						<div class="inner">
							<h3><?php echo $surveys_all; ?></h3>
							<p>Completed Surveys</p>
						</div>
						<div class="icon">
							<i class="ion ion-android-checkbox-outline"></i>
						</div>
						<a href="<?php echo base_url('surveys/admin') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>

			<div class="row">
				<h2 style="margin: 20px 15px;">Reviews</h2>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-teal">
						<div class="inner">
							<h3><?php echo $reviews_all; ?></h3>
							<p>Total Review Items</p>
						</div>
						<div class="icon">
							<i class="ion ion-document-text"></i>
						</div>
						<a href="<?php echo base_url('reviews/admin') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3><?php echo $reviews_all; ?></h3>
							<p>Completed Review Items</p>
						</div>
						<div class="icon">
							<i class="ion ion-document-text"></i>
						</div>
						<a href="<?php echo base_url('reviews/admin') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>

			<div class="row">
				<h2 style="margin: 20px 15px;">Transcribe</h2>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3><?php echo $transcribe_all; ?></h3>
							<p>Total Transcribe Items</p>
						</div>
						<div class="icon">
							<i class="ion ion-volume-medium"></i>
						</div>
						<a href="<?php echo base_url('transcribe/admin') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-blue">
						<div class="inner">
							<h3><?php echo $transcribe_all ?></h3>
							<p>Completed Transcribe Items</p>
						</div>
						<div class="icon">
							<i class="ion ion-volume-medium"></i>
						</div>
						<a href="<?php echo base_url('transcribe/admin') ?>" class="small-box-footer">Go to Review Items <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>
		<?php } ?>

		<!-- admin boxes -->
		<?php if ($is_admin == true) { ?>
			<div class="row">
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3><?php echo $total_users; ?></h3>
							<p>Total Users</p>
						</div>
						<div class="icon">
							<i class="ion ion-android-people"></i>
						</div>
						<a href="<?php echo base_url('users/') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-red">
						<div class="inner">
							<h3><?php echo $admin_surveys_all; ?></h3>
							<p>Total Surveys</p>
						</div>
						<div class="icon">
							<i class="ion ion-android-checkbox-outline"></i>
						</div>
						<a href="<?php echo base_url('surveys/admin') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3><?php echo $admin_reviews_all; ?></h3>
							<p>Total Review Items</p>
						</div>
						<div class="icon">
							<i class="ion ion-document-text"></i>
						</div>
						<a href="<?php echo base_url('reviews/admin') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-blue">
						<div class="inner">
							<h3><?php echo $admin_transcribe_all; ?></h3>
							<p>Total Transcribe Items</p>
						</div>
						<div class="icon">
							<i class="ion ion-volume-medium"></i>
						</div>
						<a href="<?php echo base_url('transcribe/admin') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-teal">
						<div class="inner">
							<h3><?php echo $admin_withdrawals_all; ?></h3>
							<p>Total Withdrawal Requests</p>
						</div>
						<div class="icon">
							<i class="ion ion-social-usd"></i>
						</div>
						<a href="<?php echo base_url('transactions/') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3><?php echo $admin_completed_payouts; ?></h3>
							<p>Total Payouts</p>
						</div>
						<div class="icon">
							<i class="ion ion-social-usd"></i>
						</div>
						<a href="<?php echo base_url('transactions/') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
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
