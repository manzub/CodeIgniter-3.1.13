<div class="container-fluid" style="margin: 10px 0px;">
	<div class="mod view_default"></div>
	<div class="jumbotron">
		<div class="row">
			<div class="col-md-7">
				<div class="thumbnail">
					<a href="#">
            <?php if(isset($banner_home_bottom)) { ?>
              <!-- banner_page_home_bottom -->
              <img style="border:1px solid #000" width="500px" src="<?php echo base_url($banner_home_bottom['value']) ?>" />
            <?php }else { ?>
              <img src="https://web.archive.org/web/20130410174940im_/https://a248.e.akamai.net/sec.yimg.com/a/ya/yahoo_gdm_ccs/r1_metro_mail_car.jpg" alt="">
            <?php } ?>
          </a>
				</div>
        <!-- TODO: advertise home -->
				<p><a class="btn btn-primary btn-lg" href="#" style="color:white" role="button">Learn more</a></p>
			</div>
			<div class="col-md-5">
				<?php if ($this->session->flashdata('alert')) { ?>
					<div class="alert <?php echo $this->session->flashdata('alert')['classname']; ?> alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<strong><?php echo $this->session->flashdata('alert')['title']; ?></strong>
						<p><?php echo $this->session->flashdata('alert')['message']; ?></p>
					</div>
				<?php } ?>
				<div class="panel panel-default no-shadow">
					<div class="panel-heading">
						<h1 style="font-size: 17px;font-weight:bold" class="panel-title">Already have an account?</h1>
						<p style="font-size: 12px;">Sign in here.</p>
						<a href="<?php echo base_url('auth/login') ?>" style="color: white;" class="btn btn-block btn-lg btn-primary">Sign in</a>
					</div>
					<div class="panel-body">
						<h1 class="page-title" style="font-size: 17px;font-weight:bold">Create an account:</h1>
						<p style="font-size: 12px;">Sign up.</p>
						<span></span>
						<form method="post">
							<div class="form-group">
								<label for="usernameInput">Username</label>
								<input class="form-control" type="text" name="username" id="usernameInput">
								<small>(e.g example123)</small>
							</div>
							<div class="form-group">
								<label for="emailInput">Email Address</label>
								<input class="form-control" type="email" name="email" id="emailInput">
								<small>(e.g example@example.com)</small>
							</div>
							<div class="form-group">
								<label for="pwInput">Password</label>
								<input class="form-control" type="password" name="password" id="pwInput">
                <input type="checkbox" onclick="showPassword()">Show Password
							</div>
							<div class="form-group">
								<label for="ref_code">Referral Code (Optional)</label>
								<input <?php echo $this->uri->segment(3) != '' ? 'disabled' : '' ?> class="form-control" type="text" name="ref_code" id="ref_code" value="<?php echo $this->uri->segment(3) != '' ? $this->uri->segment(3) : null ?>">
							</div>
							<hr>
							<button class="btn btn-primary">Sign In</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
  function showPassword() {
    var x = document.getElementById("pwInput");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
</script>