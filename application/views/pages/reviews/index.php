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
	<!-- content items -->
	<div class="mod view_default">
		<div class="bd type_surveys type_fptoday type_fptoday_default">
			<div class="fptoday-container y-ln-1">
				<div class="panel panel-default no-shadow" style="margin-bottom:0px">
					<div class="panel-heading" style="padding:10px;">
						<div class="header-flex">
							<div class="page-title" style="font-size: 20px;">Review</div>
							<a href="">Refresh</a>
						</div>
						<small>Your available actions</small>
					</div>
					<div class="panel-body" style="padding: 10px;">
						<div class="list-group">
							<a href="<?php echo base_url('reviews/single/10') ?>" class="list-group-item" style="padding: 7px;">
								<div class="d-flex align-items-center" style="gap:10px">
									<img src="<?php echo base_url('assets/phs/pictures.png') ?>" alt="" style="height: 60px;">
									<div class="d-block">
										<h1><strong>Review the avengers movie</strong></h1>
										<p>some short description</p>
										<small style="color:blue">action link</small>
									</div>
								</div>
							</a>
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
