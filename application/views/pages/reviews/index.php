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
		<h1 class="font-weight-light" style="font-size: 25px;">Review</h1>
		<hr>
		<p class="text-muted" style="font-size: 11px;font-weight:200">
			Watch a video clips and leave a review to earn points. Please ensure your reviews are grammatically accurate as moderators will need to verify the reviews
		</p>
	</div>
	<!-- content items -->
	<div class="mod view_default">
		<div class="bd type_surveys type_fptoday type_fptoday_default">
			<div class="fptoday-container y-ln-1">
				<div class="card card-default no-shadow" style="margin-bottom:0px">
					<div class="card-header" style="padding:10px;">
						<div class="header-flex">
							<div class="page-title" style="font-size: 20px;">Review</div>
							<a href="">Refresh</a>
						</div>
						<small>Your available actions</small>
					</div>
					<div class="card-body" style="padding: 10px;">
						<div class="list-group">
							<?php foreach ($activities as $key => $item) { ?>
								<a href="<?php echo base_url('reviews/single/'.$item['slug']) ?>" class="list-group-item list-group-item-action">
									<div class="d-flex w-100 justify-content-between">
										<div class="d-block">
											<div class="d-flex align-items-center" style="gap:10px">
												<img src="<?php echo $item['thumbnail_small'] != NULL ? $item['thumbnail_small'] : base_url('assets/phs/pictures.png') ?>" alt="" style="height: 60px;">
												<div class="d-block">
													<h1><strong><?php echo $item['title'] ?></strong></h1>
													<p><?php echo nl2br(substr($item['short_desc'], 0, 50)) ?>...</p>
													<small class="text-primary">Review</small>
												</div>
											</div>
										</div>
										<small>3 days ago</small>
									</div>
								</a>
							<?php } ?>
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
									<a class="prev" href="<?php echo base_url('reviews/' . ($page - 1)) ?>">
										<span class="y-fp-pg-grad y-ln-1 btn">
											<span class="y-fp-pg-controls hide-textindent">Previous set of activities</span>
										</span>
									</a>
								<?php } ?>
								<?php if ($page < $pages) { ?>
									<a class="next" href="<?php echo base_url('reviews/' . ($page + 1)) ?>">
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
