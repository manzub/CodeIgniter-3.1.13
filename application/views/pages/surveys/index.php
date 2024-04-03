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
				<div class="panel panel-default no-shadow">
					<div class="panel-heading" style="padding:10px;">
						<div class="header-flex">
							<div class="page-title" style="font-size: 20px;">Answer Survey</div>
							<a href="">Refresh</a>
						</div>
						<small>Your available surveys</small>
					</div>
					<div class="panel-body" style="padding: 10px;">
						<div id="sv_list-items" class="list-group no-shadow">
							<?php foreach ($surveys as $key => $survey) { ?>
								<a href="<?php echo base_url('surveys/single/' . $survey['slug']); ?>" class="list-group-item">
									<h4 class="list-group-item-heading" style="font-size: 14px;font-weight:bold">
										EST <span style="text-transform: uppercase;"><?php echo $survey['duration'] ?>min</span>
										<span class="glyphicon glyphicon-thumbs-up"></span>
										<span style="text-transform: uppercase;">guaranteed</span>
										<span class="label label-primary"><?php echo $survey['reward_points'] ?> SB</span>
									</h4>
									<p class="list-group-item-text">
										<?php echo $survey['title'] ?>
										<p>Begin</p>
									</p>
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

<script type="text/javascript">
	var base_url = "<?php echo base_url() ?>"

	$(document).ready(function() {
		// $.ajax({
		// 	url: base_url + 'surveys/getSurveysPagination'
		// })
	})
</script>
