<div id="y-col1">
	<div class="mod view_default">
		<div class="bd type_breakingnews type_breakingnews_default">
			<div class="alert alert-success p-2">
				<h3 class="alert-heading font-weight-bold" style="font-size: 15px;">Login Bonus Available!</h3>
				<p>Click <a href="<?php echo base_url('profile') ?>" class="text-dark alert-link">here</a> to claim login bonus.</p>
			</div>
		</div>
	</div>
	<!-- ads  -->
	<!-- content items -->
	<!-- TODO: bonus info/ daily activity count. daily login bonus link -->
	<div class="mod view_default">
		<div class="bd type_fptoday type_fptoday_default">
			<div class="fptoday-container y-ln-1">
				<div id="p_13872472-main_story" class="main-story has-date">
					<div class="title-date">
						<div class="d-flex align-items-center justify-content-between">
							<div class="d-flex align-items-center">
								<span class="title y-txt-modhdr medium">TODAY</span>
								<span> - <?php echo date("F d, Y") ?></span>
							</div>
							<div class="d-flex align-items-center">
								<span class="title"><?php echo $completed_dl_count ?>/3 Daily Activities</span>
							</div>
						</div>
					</div>
					<div class="main-story-content y-bg-1">
						<h2 class="package-header ">
							<a class="pack-title" href="<?php echo base_url('reviews/single/' . $reviews[0]['slug']) ?>">
								<img id="p_13872472-header-image" class="" src="<?php echo base_url($reviews[0]['thumbnail_large']) ?>" alt="<?php echo $reviews[0]['title'] ?>">
							</a>
						</h2>
						<h3 class="">
							<a class="x3-large" href="<?php echo base_url('reviews/single/' . $reviews[0]['slug']) ?>" style="font-family: inherit;"><?php echo $reviews[0]['title'] ?></a>
						</h3>
						<div class="left-body medium" style="width: 85%;">
							<p class="y-txt-1 y-ln-1" style="line-height:1.231">
								<?php echo substr($reviews[0]['short_desc'], 0, 110) ?>...
								<a href="<?php echo base_url($reviews[0]['slug']) ?>" class="small y-fp-pg-controls right-angle-quote" style="font-family: inherit;">
									Review
								</a>
							</p>
						</div>
					</div>
				</div>
				<div class="footer-section">
					<div class="footer-overlay hide"></div>
					<!-- carousel of activities -->
					<div id="p_13872472_carousel_container" class="carousel-container small">
						<div class="y-carousel">
							<ol class="y-carousel-list y-fp-pg-grad">
								<li id="p_13872472-panel0" class="y-panel clearfix ">
									<a class="y-fp-pg-grad y-ln-4 item selected" href="<?php echo base_url('reviews/single/' . $reviews[0]['slug']) ?>">
										<span class="y-fp-pg-controls indicator"></span>
										<img class="image y-ln-4 y-bg-1" src="<?php echo base_url($reviews[0]['thumbnail_large']) ?>" alt="<?php echo $reviews[0]['title'] ?>">
										<span class="medium item-label" style="font-family: inherit;line-height:inherit;"><?php echo substr($reviews[0]['title'], 0, 40) ?></span>
									</a>
									<?php for ($i = 1; $i < count($reviews); $i++) { ?>
										<a class="y-fp-pg-grad trans-border item" href="<?php echo base_url('reviews/single/' . $reviews[0]['slug']) ?>">
											<span class="y-fp-pg-controls indicator"></span>
											<img class="image y-ln-2 y-bg-1" src="<?php echo base_url($reviews[0]['thumbnail_large']) ?>" alt="<?php echo $reviews[0]['title'] ?>">
											<span class="medium item-label" style="font-family: inherit;line-height:inherit;"><?php echo $reviews[0]['title'] ?></span>
										</a>
									<?php } ?>
								</li>
								<li id="p_13872472-panel1" class="y-panel clearfix empty hide "> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> </li>
								<li id="p_13872472-panel2" class="y-panel clearfix empty hide "> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> </li>
								<li id="p_13872472-panel3" class="y-panel clearfix empty hide "> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> </li>
								<li id="p_13872472-panel4" class="y-panel clearfix empty hide "> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> </li>
								<li id="p_13872472-panel5" class="y-panel clearfix empty hide "> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> </li>
								<li id="p_13872472-panel6" class="y-panel clearfix empty hide "> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> <span class="y-fp-pg-grad empty trans-border item"></span> </li>
							</ol>
						</div>
					</div>
					<!-- no nav -->
					<div class="navigation no-visibility">
						<div class="y-nav y-bg-2 y-ln-1 clearfix">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--  -->
	<div id="ad-west"></div>
	<!-- content items -->
	<div id="default-p_13923988" class="mod view_default">
		<div id="default-p_13923988-bd" class="bd type_news type_news_default">
			<h2 class="hide-offscreen">Surveys</h2>
			<div class="tabwrapper y-ln-1" style="min-height:21.2em;">
				<div class="y-bg-2 y-ln-1 tab-container ">
					<div class="tab-holder">
						<ul id="sv-av-tab" role="tablist" class="nav nav-tabs y-tablist y-tl-news small clearfix intl-us">
							<!-- TODO: more tab items -->
							<li class="nav-item selected" role="presentation">
								<a class="nav-link active y-ln-1 y-bg-2 first" id="surveys-tab" data-toggle="tab" data-target="#surveys" type="button" role="tab" aria-controls="surveys" aria-selected="true">SURVEYS</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link y-ln-1 y-bg-2" id="transcribe-tab" data-toggle="tab" data-target="#transcribe" type="button" role="tab" aria-controls="transcribe" aria-selected="false">TRANSCRIBE</a>
							</li>
						</ul>
					</div>
					<div class="y-fp-pg-grad y-ln-1 move-icon-container">
						<a href="<?php echo base_url('home') ?>" class="y-fp-pg-controls" title="Refresh">
							<i class="bi bi-arrow-repeat"></i>
						</a>
					</div>
				</div>
				<div style="padding: 5px 10px;" class=" tab-content" id="sv-av-contents">
					<div class="tab-pane fade show active tabpanel clearfix selected" id="surveys" role="tabpanel" aria-labelledby="surveys-tab">
						<ul class="stories medium clearfix">
							<?php foreach ($surveys as $key => $sv_item) { ?>
								<li>
									<div class="d-flex align-items-center justify-content-between">
										<a class="y-fp-pg-controls bullet y-link-1" href="<?php echo base_url('surveys/single/' . $sv_item['slug']) ?>"><?php echo substr($sv_item['title'], 0, 40) ?></a>
										<div class="">
											<span class="badge badge-success"><?php echo $sv_item['reward_points'] ?> SB</span>
											<span class="badge badge-info">Category</span>
										</div>
									</div>
								</li>
							<?php } ?>
							<!-- <li class="one-link">·<a class=" y-link-1" href="https://web.archive.org/web/20100701000722/http://sports.yahoo.com/nba">NBA</a></li>
							<li class="one-link">·<a class=" y-link-1" href="https://web.archive.org/web/20100701000722/http://sports.yahoo.com/nhl">NHL</a></li> -->
						</ul>
						<div class="more-link small">
							<h3 class="y-txt-2">More:</h3>
							<ul class="more itn">
								<li><a href="<?php echo base_url('surveys') ?>">Surveys</a></li>
							</ul>
						</div>
						<p class="small y-txt-3 time-stamp">updated <?php echo date('h:i A') ?></p>
					</div>
					<div class="tab-pane fade tabpanel clearfix" id="transcribe" role="tabpanel" aria-labelledby="transcribe-tab">
						<ul class="stories medium clearfix">
							<?php foreach ($transcribe as $key => $av_item) { ?>
								<li>
									<div class="d-flex align-items-center justify-content-between">
										<a class="y-fp-pg-controls bullet y-link-1" href="<?php echo base_url('transcribe/single/' . $av_item['slug']) ?>"><?php echo substr($av_item['title'], 0, 40) ?></a>
										<div class="">
											<span class="badge badge-success"><?php echo $av_item['reward_points'] ?> SB</span>
											<span class="badge badge-info">Category</span>
										</div>
									</div>
								</li>
							<?php } ?>
						</ul>
						<div class="more-link small">
							<h3 class="y-txt-2">More:</h3>
							<ul class="more itn">
								<li><a href="<?php echo base_url('transcribe') ?>">Transcribe</a></li>
							</ul>
						</div>
						<p class="small y-txt-3 time-stamp">updated <?php echo date('h:i A') ?></p>
					</div>
					<div class="tabpanel clearfix"></div>
					<div class="tabpanel clearfix"></div>
				</div>
			</div>
		</div>
		<div id="default-p_13923988-ft" class="ft"></div>
	</div>
	<!-- ads -->

</div>
