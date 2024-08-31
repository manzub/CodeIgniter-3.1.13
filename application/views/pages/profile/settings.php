<div id="y-col1" style="width: 78.23%;">
	<div class="mod view_default">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
			<li><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li><a href="<?php echo base_url('profile/settings') ?>">Settings</a></li>
		</ol>
	</div>
	<div class="mod view_default">
		<?php if ($this->session->flashdata('alert')) { ?>
			<div class="alert <?php echo $this->session->flashdata('alert')['classname']; ?> alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong><?php echo $this->session->flashdata('alert')['title']; ?></strong>
				<p><?php echo $this->session->flashdata('alert')['message']; ?></p>
			</div>
		<?php } ?>
		<div class="row equal">
			<div class="col-md-6">
				<div class="card bg-default no-shadow">
					<div class="card-header" style="padding: 5px 5px;">
						<div class="header-flex">
							<h1 style="font-size: 15px;font-weight:bold;">Account Info</h1>
							<a href="<?php echo base_url('profile/edit_user') ?>" class="btn btn-default">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
						</div>
					</div>
					<div class="card-body">
						<table class="table">
							<tbody>
								<tr>
									<td>Full Name</td>
									<td><?php echo $users_meta['full_name'] != NULL ? $users_meta['full_name'] : 'Not Set' ?></td>
								</tr>
								<tr>
									<td>Email</td>
									<td><?php echo $this->session->userdata('email') ?></td>
								</tr>
								<tr>
									<td>Phone</td>
									<td><?php echo $users_meta['phone'] != NULL ? $users_meta['phone'] : 'Not Set' ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card bg-default no-shadow">
					<div class="card-header" style="padding: 5px 5px;">
						<div class="header-flex">
							<h1 style="font-size: 15px;font-weight:bold;">Address</h1>
							<a href="<?php echo base_url('profile/edit_meta') ?>" type="button" class="btn btn-default">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
						</div>
					</div>
					<div class="card-body">
						<table class="table">
							<tbody>
								<tr>
									<td>Address</td>
									<td><?php echo $users_meta['address'] != NULL ? $users_meta['address'] : 'Not Set' ?></td>
								</tr>
								<tr>
									<td>Post Code</td>
									<td><?php echo $users_meta['post_code'] != NULL ? $users_meta['post_code'] : 'Not Set' ?></td>
								</tr>
								<tr>
									<td>Country</td>
									<td><?php echo $users_meta['country'] != NULL ? $users_meta['country'] : 'Not Set' ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card bg-default no-shadow">
					<div class="card-header">
						<div class="header-flex">
							<h1 style="font-size: 15px;font-weight:bold">Linked Accounts</h1>
						</div>
					</div>
					<div class="card-body">
						<style>
							.hidden {
								visibility: hidden;
								position: absolute;
								top: -999999px;
							}

							.well {
								min-height: 20px;
								padding: 19px;
								margin-bottom: 20px;
								background-color: #f5f5f5;
								border: 1px solid #e3e3e3;
								border-radius: 4px;
								-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
								box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
							}
						</style>
						<?php $saved_types = array_column($user_accounts, 'type'); ?>
						<div style="display: flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap: 15px;">
							<img src="<?php echo base_url('assets/icons/paypal.png') ?>" style="height:70px;width:70px;">
							<form action="<?php echo base_url('profile/add_paypal') ?>" id="paypal-form" class="hidden" method="post" style="flex:8;display: flex;align-items:center;justify-content:space-between;">
								<div class="form-group" style="width:80%;">
									<label for="">Paypal Address</label>
									<input class="form-control" name="paypal_email" placeholder="Paypal Email Address" />
								</div>
								<button class="btn btn-primary">Submit</button>
							</form>
							<?php if (in_array('paypal', $saved_types)) { ?>
								<?php $bb_key = array_search('paypal', $saved_types); ?>
								<form method="post" action="<?php echo base_url('profile/unlink_account/paypal') ?>" style="flex:8;display:flex;align-items:center;justify-content:space-between;">
									<p style="visibility: hidden;">.</p>
									<p id="paypal-text"><?php echo $user_accounts[$bb_key]['email'] ?></p>
									<button type="submit" class="btn btn-primary">Unlink Account</button>
								</form>
							<?php } else { ?>
								<p id="paypal-text">Link your PayPal account to turn your SB into cash</p>
								<button id="trigger-hide" class="btn btn-primary">Link Account</button>
							<?php } ?>
						</div>
						<div style="display: flex;align-items:center;justify-content:space-between;margin-bottom:12px">
							<img src="<?php echo base_url('assets/icons/bank.png') ?>" style="height:70px;width:70px">
							<?php if (in_array('bank', $saved_types)) { ?>
								<?php $bb_key = array_search('bank', $saved_types); ?>
								<form method="post" action="<?php echo base_url('profile/unlink_account/bank') ?>" style="flex:8;display:flex;align-items:center;justify-content:space-between;">
									<p style="visibility: hidden;">.</p>
									<p id="paypal-text"><?php echo $user_accounts[$bb_key]['account_no'] . ' | ' . $user_accounts[$bb_key]['account_holder'] ?></p>
									<button type="submit" class="btn btn-primary">Unlink Account</button>
								</form>
							<?php } else { ?>
								<p>Link your Bank Account to turn your SB into cash</p>
								<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#linkbankForm" aria-expanded="false" aria-controls="linkbankForm">Link Account</button>
							<?php } ?>
						</div>
						<div class="collapse" id="linkbankForm">
							<div class="well">
								<h3>Enter your bank details</h3>
								<form action="<?php echo base_url('profile/link_bankaccount') ?>" method="post">
									<div class="form-group">
										<label for="">Bank</label>
										<select name="bank_name" class="form-control">
											<option value="">Select One</option>
											<?php foreach ($banks as $key => $value) { ?>
												<option value="<?php echo str_replace(" ", "-", strtolower($value)); ?>"><?php echo $value ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label for="">Account Number</label>
										<input type="text" name="account_no" class="form-control" placeholder="Account Number">
									</div>
									<div class="form-group">
										<label for="">Account Holder</label>
										<input type="text" name="account_holder" placeholder="Account Holder" class="form-control">
									</div>
									<button class="btn btn-primary">Submit</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card bg-default no-shadow">
					<div class="card-header">
						<div class="header-flex">
							<h1 style="font-size: 15px;font-weight:bold">Support</h1>
						</div>
					</div>
					<div class="card-body">
						<p style="font-size: 17px;margin-bottom:5px"><a href="<?php echo base_url('home/contact_us') ?>">Contact Customer Support</a></p>
						<p><a onclick="if(window.confirm('Are you sure you want to proceed?')) { window.location.href = '<?php echo base_url('auth/deactivate_user') ?>'}" style="font-size: 17px;" href="#">Deactivate My Account</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		let trigger = document.getElementById('trigger-hide');
		trigger.addEventListener('click', function(event) {
			document.getElementById('paypal-form').classList.remove('hidden');
			document.getElementById('paypal-text').classList.add('hidden')
			trigger.classList.add('hidden');
		})
	})
</script>
