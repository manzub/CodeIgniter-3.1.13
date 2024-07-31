<div id="y-masthead">
	<div class="mod view_default">
		<div class="bd type_masthead type_masthead_default">
			<div class="mh-wrap us clearfix y-fp-pg-grad">
				<div class="logo-container">
					<div class="mod view_default">
						<div class="bd type_ads type_ads_default">
							<div class="hdrm hdrm-reserve">
								<h1>
									<span style="background: transparent url(<?php echo base_url('assets/phs/yahoo_logo_us.png') ?>)" class="logo">SurveyMonkey!</span>
								</h1>
							</div>
						</div>
						<div class="ft"></div>
					</div>
				</div>
				<form action="" class="search-form">
					<fieldset>
						<ul class="stabs large">
							<li class="small tab first <?php echo $this->uri->segment(1) == '' ? 'selected' : '' ?> y-mast-sprite">
								<a href="<?php echo base_url() ?>" class="y-mast-sprite y-mast-txt web">
									<span class="tab-cover y-mast-bg" style="padding-left:0em;padding-right:0em;">Home</span>
								</a>
							</li>
							<li class="small tab <?php echo $this->uri->segment(1) == 'surveys' ? 'selected' : '' ?>">
								<a href="<?php echo base_url('surveys') ?>" class="y-mast-sprite y-mast-txt web">
									<span class="tab-cover y-mast-bg" style="padding-left:0em;padding-right:0em;">Answer</span>
								</a>
							</li>
							<li class="small tab <?php echo $this->uri->segment(1) == 'reviews' ? 'selected' : '' ?>">
								<a href="<?php echo base_url('reviews') ?>" class="y-mast-link review">
									<span class="tab-cover y-mast-bg-hide" style="padding-left:0em;padding-right:0em;">Review</span>
								</a>
							</li>
							<li class="small tab <?php echo $this->uri->segment(1) == 'transcribe' ? 'selected' : '' ?>">
								<a href="<?php echo base_url('transcribe') ?>" class="y-mast-link transcribe">
									<span class="tab-cover y-mast-bg-hide" style="padding-left:0em;padding-right:0em;">Transcribe</span>
								</a>
							</li>
							<li class="small tab">
								<a href="" class="y-mast-link transcribe">
									<span class="tab-cover y-mast-bg-hide" style="padding-left:0em;padding-right:0em;">Shop</span>
								</a>
							</li>
							<li class="small">
								<a href="" class="y-mast-link more">
									<span class="tab-cover y-mast-bg-hide" style="padding-left:0em;padding-right:0em;">More</span>
								</a>
							</li>
						</ul>
						<div class="searchwrapper tabpanel selected">
							<div class="searchwrapper-border y-mast-ln-dk">
								<div class="searchwrapper-inner y-mast-sprite y-mast-ln-lt">
									<input id="searchInput" type="text" autocomplete="off" title="Web Search" placeholder="Search Pages, Settings...." class="input-query med-large">
									<span class="button-wrapper">
										<button class="searchsubmit med-large y-fp-pg-grad" value="Web Search" type="submit" id="search-submit">Web Search</button>
									</span>
								</div>
								<div id="search-contents" class="searchwrapper-contents y-mast-ln-dk">
									<ul id="searchUL">
										<li><a href="<?php echo base_url('profile') ?>">Profile</a></li>
										<li><a href="<?php echo base_url('surveys') ?>">Surveys</a></li>

										<li><a href="<?php echo base_url('auth/logout') ?>">Log Out</a></li>
										<li><a href="<?php echo base_url('profile/redeem_sb') ?>">Withdraw/Redeem SB</a></li>

										<li><a href="<?php echo base_url('bonuses') ?>">Bonuses</a></li>
										<li><a href="<?php echo base_url('home/coming_soon') ?>">Shop</a></li>
										<li><a href="<?php echo base_url('home/contact_us') ?>">Contact Us</a></li>
									</ul>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="y-header">
	<div class="mod view_default">
		<div class="bd type_navbar type_navbar_default">
			<div class="navbar-wrapper y-fp-ln-pg">
				<div class="promotions">
					<ul>
						<li class="y-hdr-ln my small first">
							<a href="<?php echo base_url('profile') ?>" class="y-hdr-link">My Account</a>
						</li>
						<li class="y-hdr-ln homepage promotion small">
							<a href="" class="y-hdr-link icon link">Rewards</a>
						</li>
						<?php if (!empty($this->session->userdata('logged_in'))) { ?>
							<li class="y-hdr-ln homepage promotion small">
								<a href="" class="y-hdr-link icon link" style="color:black">
									<strong><span id="coin-balance">0</span> SB</strong>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
				<div class="help">
					<ul>
						<li class="y-hdr-ln tuc first">
							<div class="mod view_default">
								<div class="bd type_tuc type_tuc_default">
									<ul>
										<?php if (!empty($this->session->userdata('logged_in'))) { ?>
											<?php if ($this->session->userdata('bonus_available')) { ?>
												<li class="y-hdr-ln account-sign-in strong small first">
													<a href="<?php echo base_url('profile') ?>" class="y-hdr-link">Daily Bonus Available</a>
												</li>
											<?php } else { ?>
												<li class="y-hdr-ln account-sign-in strong small first">
													<span class="y-hdr-link">Bonus Available in: <span id="time-countdown"></span></span>
												</li>
											<?php } ?>
										<?php } else { ?>
											<li class="y-hdr-ln account-sign-in strong small first">
												<a href="<?php echo base_url('auth/login') ?>" class="y-hdr-link">Sign In</a>
											</li>
											<li class="y-hdr-ln account-sign-up strong small">
												<a href="<?php echo base_url('auth/signup') ?>" class="y-hdr-link">
													<span class="y-hdr-text">New Here? </span>Sign Up
												</a>
											</li>
										<?php } ?>
										<!-- update user status during activities via session -->
										<li class="y-hdr-ln status small su-loggedout <?php echo empty($this->session->userdata('logged_in')) ? "dropdown" : "" ?>">
											<?php if (empty($this->session->userdata('logged_in'))) { ?>
												<!-- on click to do dropdown -->
												<a href="#" class="y-hdr-txt y-ln-1 short-status dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">What are you doing?</a>
												<div class="su-dropdown su-loggedout dropdown-menu" aria-labelledby="dropdownMenu1">
													<div class="su-dropdown-outer">
														<div class="su-dropdown-inner y-pa-bg-open y-pa-ln-open-dk">
															<div class="su-dropdown-bd">
																<div class="oops-msg">
																	<!-- todo icons -->
																	<h3 class="oops">Please sign in to update your status.</h3>
																</div>
																<p class="oops">
																	<a class="cta-btn" href="<?php echo base_url('auth/login') ?>">Sign In</a>
																	<button class="cancel-btn">Cancel</button>
																</p>
															</div>
														</div>
													</div>
												</div>
											<?php } else { ?>
												<span class="y-hdr-txt y-ln-1 short-status">
													<strong><?php echo $this->session->userdata('username') ?></strong> |
													<?php echo $this->session->userdata('curr_status')['text']; ?>
												</span>
											<?php } ?>
										</li>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var php_last_time = "<?php echo $this->session->userdata('last_time') ? $this->session->userdata('last_time') : 'null' ?>"
		if (php_last_time != 'null') {
			let date1 = new Date(php_last_time);
			let date2 = new Date();
			const diffTime = Math.abs(date2 - date1);
			const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
			if (diffDays < 2) {
				date1.setHours(date1.getHours() + Math.round(date1.getMinutes() / 60));
				date1.setMinutes(0, 0, 0);
				let hours_diff = 24 - date1.getHours();
				date2.setHours(date1.getHours() + hours_diff);

				var countDownDate = date2.getTime();
				var x = setInterval(function() {
					var now = new Date().getTime();
					// Find the distance between now and the count down date
					var distance = countDownDate - now;
					// Time calculations for hours, minutes and seconds
					var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
					var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
					var seconds = Math.floor((distance % (1000 * 60)) / 1000);

					// Output the result in an element with id="demo"
					document.getElementById("time-countdown").innerHTML = hours + "h " +
						minutes + "m " + seconds + "s ";
					// If the count down is over, write some text 
					if (distance < 0) {
						clearInterval(x);
						document.getElementById("time-countdown").innerHTML = "EXPIRED";
					}
				}, 1000);
			}
		}

		// ajax get balance
		var y = setInterval(() => {
			$.get('<?php echo base_url('home/my_balance') ?>', function(data) {
				let result = JSON.parse(data);
				if (result.balance.total_rewards != null) {
					document.getElementById('coin-balance').innerHTML = result.balance.total_rewards
				}
			})
		}, 1000);

		// js search inputs
		document.getElementById('searchInput').addEventListener("keyup", function() {
			document.getElementById('search-contents').style.display = "block";

			// Declare variables
			var input, filter, ul, li, a, i, txtValue;
			input = document.getElementById('searchInput');
			filter = input.value.toUpperCase();
			ul = document.getElementById("searchUL");
			li = ul.getElementsByTagName('li');

			// Loop through all list items, and hide those who don't match the search query
			for (i = 0; i < li.length; i++) {
				a = li[i].getElementsByTagName("a")[0];
				txtValue = a.textContent || a.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					li[i].style.display = "";
				} else {
					li[i].style.display = "none";
				}
			}
		})
	})

	window.onbeforeunload = function(e) {
		clearInterval(y)
		clearInterval(x)
	}
</script>
