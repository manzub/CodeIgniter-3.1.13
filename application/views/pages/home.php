<div id="y-col1">
	<div class="mod view_default">
		<div class="bd type_breakingnews type_breakingnews_default">
			<div class="remove-margin"></div>
		</div>
	</div>
	<!-- content items -->
	<div class="mod view_default">
		<div class="bd type_fptoday type_fptoday_default">
			<div class="fptoday-container y-ln-1">
				<div class="main-story has-date">
					<h2 class="title-date">
						<span class="title y-txt-modhdr medium">TODAY</span>
						<span class="date y-txt-2 medium" style="font-family: inherit;"> - March 31, 2024</span>
						<span class="daily-activity medium" style="color: #444;"> | 1/3 ✅ Daily Activity</span>
						<span class="daily-left medium">| 🕖 13:00</span>
					</h2>
					<h2 class="package-header">
						<a href="" class="pack-title">
							<div style="background-color:#e3e3e3;height: 150px;"></div>
						</a>
					</h2>
					<h3 class="">
						<a class="x3-large" style="font-family: inherit;">Star's 'interview' starts spat with Fox</a>
					</h3>
					<div class="left-body medium">
						<p class="y-txt-1 y-ln-1">
							A ticked-off LL Cool J says a promo for Sarah Palin's new show was not what it seemed.
							<a href="" class="small y-fp-pg-controls right-angle-quote">Review</a>
						</p>
					</div>
					<ul class="related-links">
						<li class="related-story y-fp-pg-controls-nt search">
							<a href="" class="small">More Vides to Review</a>
						</li>
						<li class="related-story y-fp-pg-controls-nt search">
							<a href="" class="small">other Activities</a>
						</li>
					</ul>
				</div>
				<div class="footer-section small">
					<div class="footer-overlay hide"></div>
					<!-- carousel of activities -->
					<div class="carousel-container">
						<div class="y-carousel">
							<ol class="y-carousel-list y-fp-pg-grad">
								<li class="y-panel clearfix">
									<a href="" class=" y-fp-pg-grad y-ln-4 item selected">
										<span class="y-fp-pg-controls indicator"></span>
										<img class="image y-ln-4 y-bg-1" alt="">
										<span class="medium item-label">Some Content</span>
									</a>
									<a href="" class=" y-fp-pg-grad trans-border item">
										<span class="y-fp-pg-controls indicator"></span>
										<img class="image y-ln-4 y-bg-1" alt="">
										<span class="medium item-label">Some Content</span>
									</a>
									<a href="" class=" y-fp-pg-grad trans-border item">
										<span class="y-fp-pg-controls indicator"></span>
										<img class="image y-ln-4 y-bg-1" alt="">
										<span class="medium item-label">Some Content</span>
									</a>
									<a href="" class=" y-fp-pg-grad trans-border item">
										<span class="y-fp-pg-controls indicator"></span>
										<img class="image y-ln-4 y-bg-1" alt="">
										<span class="medium item-label">Some Content</span>
									</a>
								</li>
							</ol>
						</div>
					</div>
					<div class="navigation">
						<div class="y-nav y-bg-2 y-ln-1 clearfix">
							<div class="y-nav-count y-txt-2">1 of 5</div>
							<div class="y-nav-buttons">
								<a class="prev" href="#">
									<span class="y-fp-pg-grad y-ln-1 btn">
										<span class="y-fp-pg-controls hide-textindent">Previous set of activities</span>
									</span>
								</a>
								<a class="next" href="#">
									<span class="y-fp-pg-grad y-ln-1 btn">
										<span class="y-fp-pg-controls hide-textindent">Next set of activities</span>
									</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- content items -->
	<div class="mod view_default">
		<div class="bd type_news type_news_default">
			<div class="tabwrapper y-ln-1" style="min-height: 21.2em;">
				<div class="y-bg-2 y-ln-1 tab-container ">
					<div class="tab-holder">
						<ul class="y-tablist y-tl-news small clearfix intl-us">
							<li class="selected">
								<a href="" class=" y-ln-1 y-bg-2  first">Surveys</a>
							</li>
							<li>
								<a href="" class=" y-ln-1 y-bg-2 ">Transcribe</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="y-tabpanels">
					<div class="tabpanel clearfix selected">
						<ul class="stories medium clearfix">
							<?php foreach ($surveys as $key => $sv_item) { ?>
								<li>
									<a href="" class="y-fp-pg-controls bullet y-link-1"><?php echo $sv_item['title'] ?> <span class="label label-primary"><?php echo $sv_item['reward_points'] ?></span></a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
