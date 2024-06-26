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
		<h1>Transcribe</h1>
		<hr>
		<p>Listen to audio recordings and write what you hear.</p>
	</div>
	<!-- content items -->
	<div class="mod view_default">
		<div class="bd type_surveys type_fptoday type_fptoday_default">
			<div class="fptoday-container y-ln-1">
				<div class="card card-default no-shadow" style="margin-bottom:0px">
					<div class="card-header" style="padding:10px;">
						<div class="header-flex">
							<div class="page-title" style="font-size: 20px;">Transcribe</div>
							<a href="">Refresh</a>
						</div>
						<small>Your available actions</small>
					</div>
					<div class="card-body" style="padding: 10px;">
						<div class="list-group">
							<?php foreach ($activities as $key => $item) { ?>
								<a href="<?php echo base_url('transcribe/single/' . $item['slug']) ?>" class="p-2 list-group-item list-group-item-action">
									<div class="d-flex align-items-center justify-content-between" style="gap:10px">
										<img src="<?php echo base_url('assets/phs/cd.png') ?>" style="height: 35px;width:35px">
										<div style="width:100%;height:30px">
											<span style="background: url('<?php echo base_url('assets/phs/sound-wave.png') ?>');background-repeat:repeat-x;width:100%;height:30px;display:inline-block"></span>
										</div>
										<h1 style="font-size: 10px;"><strong>1:59</strong></h1>
									</div>
									<p style="margin-top: 5px;font-weight:bold"><?php echo substr($item['title'], 0, 30) ?>...</p>
									<span style="font-size: 10px" class="text-primary">Transcribe</span>
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
