<div id="y-content" class="clearfix y-ln-accent">
	<?php if (!in_array($this->uri->segment(2), array('login', 'signup'))) { ?>
		<div id="y-cols" class="clearfix y-fp-ln-pg">
			<div id="y-pa" class="y-fp-pg-vgrad y-ln-2">
				<div id="y-pa-cont">
					<div class="mod view_default">
						<div class="bd type_pa type_pa_default">
							<div class="y-pa-cont">
								<div class="pa-right-shadow">
									<div class="pa-wrapper y-bg-4">
										<div class="pa-main">
											<div class="pa-top y-fp-pg-grad y-ln-1">
												<div class="pa-heading">
													<h2 class="y-txt-modhdr">MY FAVORITES</h2>
												</div>
											</div>
											<!-- sidebar options -->
											<div class="pa-middle clearfix y-ln-2">
												<div class="pa-panel-myapps y-bg-1">
													<div>
														<div class="pa-apps-userlist y-ln-1">
															<div class="pa-userlist-cont">
																<div class="pa-userlist-col pa-userlist-selected">
																	<div class="med-small pa-apps-list">
																		<ul class="med-small pa-apps-list">
																			<li class="y-fp-pg-controls no-edit first pa-btn-app">
																				<a href="<?php echo base_url('home') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																					<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/tasks-' . rand(0, 1) . '.png') ?>" />
																					<span class="y-pa-txt-button pa-btn-title">Activities</span>
																				</a>
																			</li>
																			<li class="y-fp-pg-controls pa-btn-app">
																				<a href="<?php echo base_url('bonuses/') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																					<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/bonus-' . rand(0, 1) . '.png') ?>" />
																					<span class="y-pa-txt-button pa-btn-title">Bonuses</span>
																				</a>
																			</li>
																			<li class="y-fp-pg-controls pa-btn-app">
																				<a href="<?php echo base_url('profile/referrals') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																					<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/referral-' . rand(0, 1) . '.png') ?>" />
																					<span class="y-pa-txt-button pa-btn-title">Referrals</span>
																				</a>
																			</li>
																			<li class="y-fp-pg-controls pa-btn-app">
																				<a href="" class="pa-btn-link y-fp-pg-controls y-ln-1">
																					<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/gift-card-' . rand(0, 1) . '.png') ?>" />
																					<span class="y-pa-txt-button pa-btn-title">Gift Cards</span>
																				</a>
																			</li>
																		</ul>
																	</div>
																</div>
															</div>
														</div>
														<!-- recommended -->
														<div class="pa-apps-promolist y-ln-1">
															<h3 class="x-small y-txt-3 y-bg-2 y-ln-1">RECOMMENDED</h3>
															<ul class="med-small pa-apps-list">
																<li class="y-fp-pg-controls no-edit first pa-btn-app">
																	<a href="<?php echo base_url('profile') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																		<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/my-account-' . rand(0, 1) . '.png') ?>" />
																		<span class="y-pa-txt-button pa-btn-title">My Account</span>
																	</a>
																</li>
																<li class="y-fp-pg-controls pa-btn-app">
																	<a href="<?php echo base_url('profile/settings') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																		<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/settings-' . rand(0, 1) . '.png') ?>" />
																		<span class="y-pa-txt-button pa-btn-title">Settings</span>
																	</a>
																</li>
																<li class="y-fp-pg-controls pa-btn-app">
																	<a href="<?php echo base_url('profile/settings') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																		<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/settings-' . rand(0, 1) . '.png') ?>" />
																		<span class="y-pa-txt-button pa-btn-title">Preferrences</span>
																	</a>
																</li>
																<li class="y-fp-pg-controls"></li>
																<li class="y-fp-pg-controls no-edit first pa-btn-app">
																	<a href="<?php echo base_url('profile/redeem_points') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																		<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/redeem-' . rand(0, 1) . '.png') ?>" />
																		<span class="y-pa-txt-button pa-btn-title">Redeem SB</span>
																	</a>
																</li>
																<li class="y-fp-pg-controls no-edit first pa-btn-app">
																	<a href="<?php echo base_url('profile/invite') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																		<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/invite-' . rand(0, 1) . '.png') ?>" />
																		<span class="y-pa-txt-button pa-btn-title">Invite Friends</span>
																	</a>
																</li>
																<?php if (!empty($this->session->userdata('logged_in'))) { ?>
																	<li class="y-fp-pg-controls no-edit first pa-btn-app">
																		<a href="<?php echo base_url('auth/logout') ?>" class="pa-btn-link y-fp-pg-controls y-ln-1">
																			<img class="pa-btn-icon" src="<?php echo base_url('assets/icons/logout-' . rand(0, 1) . '.png') ?>" />
																			<span class="y-pa-txt-button pa-btn-title">Log Out</span>
																		</a>
																	</li>
																<?php } ?>
															</ul>
														</div>
														<!-- more ads -->
														<div class="mod view_default" style="padding-top: 10px;">
															<div class="bd type_ads type_ads_default">
																<div class="fpad fpad-reserve" style="min-height: 100px;margin-bottom:10px">
																	<!--banner_siderbar_left_center  -->
																	<?php if (isset($banner_sidebar_left_center)) { ?>
																		<?php echo $banner_sidebar_left_center['extra'] != NULL ? `<a href="` . $banner_top_right['extra'] . `">` : ''; ?>
																		<img src="<?php echo base_url($banner_sidebar_left_center['value']) ?>" style="height:200px;width:190px">
																		<?php echo $banner_sidebar_left_center['extra'] != NULL ? `</a>` : '' ?>
																	<?php } ?>
																</div>
																<div class="fpad fpad-reserve" style="min-height: 100px;">
																	<!-- banner_sidebar_left_bottom  -->
																	<?php if (isset($banner_sidebar_left_bottom)) { ?>
																		<?php echo $banner_sidebar_left_bottom['extra'] != NULL ? `<a href="` . $banner_top_right['extra'] . `">` : ''; ?>
																		<img src="<?php echo base_url($banner_sidebar_left_bottom['value']) ?>" style="height:200px;width:190px">
																		<?php echo $banner_sidebar_left_bottom['extra'] != NULL ? `</a>` : '' ?>
																	<?php } ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="pa-footer"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
