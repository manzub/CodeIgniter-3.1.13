<div id="y-col1">
	<div class="mod view_default">
		<div class="bd type_breakingnews type_breakingnews_default">
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
	
	<div class="jumbotron p-3">
		<h1 class="font-weight-light" style="font-size: 25px;">Answer Surveys</h1>
		<hr>
		<p class="text-muted" style="font-size: 11px;font-weight:200">We award qualified participants who meet all survey requirements and comply with our Terms. Some may not qualify based on response content or survey needs, or may be disqualified for incomplete, inconsistent, or suspect (e.g., high-speed) responses. </p>
	</div>
	<!-- content items -->
	<div class="mod view_default">
		<div class="bd type_surveys type_fptoday type_fptoday_default">
			<div class="fptoday-container y-ln-1">
				<div class="card no-shadow mb-0">
					<div class="card-header">
						<div class="d-flex align-items-center justify-content-between p-2">
							<span class="hide-offscreen">surveys</span>
							<p><strong>Your available surveys</strong></p>
							<a href="">Refresh</a>
						</div>
					</div>
					<div class="card-body" style="padding: 5px">
						<div class="mb-2" style="padding: 5px">
							<div class="verify-info pb-1">
								<p class="text-info">Verify your account to unlock rewarding survey options</p>
								<p>Earn <strong>5 SB</strong> when you verify</p>
							</div>
							<div class="verify-actions">
								<button type="button" class="btn btn-outline-primary p-1" style="font-size: 9px;">Verify Email</button>
								<button type="button" class="btn btn-outline-success p-1" style="font-size: 9px;">Verify Text</button>
							</div>
						</div>
						<div id="sv_list-items" class="list-group no-shadow">
							<div class="list-group">
								<?php foreach ($surveys as $key => $survey) { ?>
									<a href="<?php echo base_url('surveys/single/' . $survey['slug']); ?>" class="list-group-item list-group-item-action">
										<div class="d-flex w-100 justify-content-between">
											<h5 class="mb-1 font-weight-bold"><?php echo $survey['title'] ?></h5>
											<small>EST <?php echo $survey['duration'] ?> min</small>
										</div>
										<p class="mb-1">Some placeholder content in a paragraph.</p>
										<h1>
											<span class="text-link">Begin</span>
											<span class="badge badge-success"><?php echo $survey['reward_points'] ?> SB</span>
											<?php if (!empty($this->session->userdata('daily_activity'))) { ?>
												<?php $dl_slugs = array_map(function ($o) {
													return $o['slug'];
												}, $this->session->userdata('daily_activity')); ?>
												<?php if (in_array($survey['slug'], $dl_slugs)) { ?>
													<span class="badge badge-info">Daily Activity ⭐</span>
												<?php } ?>
											<?php } ?>
										</h1>
									</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<div class="footer-section small">
					<div class="footer-overlay hide"></div>
					<!-- carousel of activities -->
					<div class="navigation">
						<div class="y-nav y-bg-2 y-ln-1 clearfix">
							<div class="y-nav-count y-txt-2"><?php echo $page; ?> of <?php echo $pages; ?></div>
							<div class="y-nav-buttons">
								<?php if ($page > 1) { ?>
									<a class="prev" href="<?php echo base_url('surveys/' . ($page - 1)) ?>">
										<span class="y-fp-pg-grad y-ln-1 btn">
											<span class="y-fp-pg-controls hide-textindent">Previous set of activities</span>
										</span>
									</a>
								<?php } ?>
								<?php if ($page < $pages) { ?>
									<a class="next" href="<?php echo base_url('surveys/' . ($page + 1)) ?>">
										<span class="y-fp-pg-grad y-ln-1 btn">
											<span class="y-fp-pg-controls hide-textindent">Next set of activities</span>
										</span>
									</a>
								<? } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
