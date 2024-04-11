<?php if (!in_array($this->uri->segment(2), array('login', 'signup', 'settings', 'redeem_points'))) { ?>
	<?php if ($this->uri->segment(1) != 'profile') { ?>
		<div id="y-col2">
			<!-- daily activity list -->
			<?php if (!empty($this->session->userdata('daily_activity'))) { ?>
				<div class="mod view_default">
					<div class="bd type_tts type_tts_default">
						<div class="y-ln-1 clearfix typetopten">
							<!-- TODO: daily activity list -->
							<h2 class="y-txt-modhdr bdr-b y-ln-1">DAILY ACTIVITIES</h2>
							<ol class="firstcol" style="width:100%">
								<?php foreach ($dl_activity_list as $key => $item) { ?>
									<li class="y-txt-accent first" style="padding: 2px 0px;">
										<a href="<?php echo $item['link'] ?>"><?php echo $item['type']." | ".$item['title'] ?></a>
									</li>
								<?php } ?>
							</ol>
						</div>
						<dv class="ft"></dv>
					</div>
				</div>
			<?php } ?>

			<div class="mod view_default">
				<div class="bd type_ads type_ads_default">
					<div class="fpad fpad-reserve" style="min-height: 100px;">
						<!-- banner_top_right -->
						<?php if (isset($banner_top_right)) { ?>
							<?php echo $banner_top_right['extra'] != NULL ? `<a href="` . $banner_top_right['extra'] . `">` : ''; ?>
							<img src="<?php echo base_url($banner_top_right['value']) ?>" style="height:60px;width:350px">
							<?php echo $banner_top_right['extra'] != NULL ? `</a>` : '' ?>
						<?php } ?>
					</div>
					<div class="fpad fpad-reserve">
						<!-- banner_right_center -->
						<?php if (isset($banner_right_center)) { ?>
							<?php echo $banner_right_center['extra'] != NULL ? `<a href="` . $banner_top_right['extra'] . `">` : ''; ?>
							<img src="<?php echo base_url($banner_right_center['value']) ?>" border="0" height="200" width="350">
							<?php echo $banner_right_center['extra'] != NULL ? `</a>` : '' ?>
						<?php } ?>
					</div>
				</div>
			</div>
			<div id="default-p_17335519" class="mod view_default">
				<div id="default-p_17335519-bd" class="bd type_contentcarousel type_contentcarousel_default">
					<div class="carousel-pos4 carousel-pos y-ln-1">
						<div id="p_17335519-carousel" class="y-carousel-wrapper">
							<div class="y-carousel">
								<ul class="y-carousel-list">
									<li class="y-panel">
										<div id="default-p_21487697_d7f" class="mod view_default">
											<div id="default-p_21487697_d7f-bd" class="bd type_contentcarouselslideshow1 type_contentcarouselslideshow1_default">
												<div class="type4thumb">
													<div class="title-clip">
														<div class="title-wrapper">
															<h2 class="y-txt-modhdr bdr-b y-ln-1 ">
																<div class="property" style="background-image: url(https://web.archive.org/web/20100701000722im_/http://l.yimg.com/a/i/ww/met/mod/ybang_22_111908.png);_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='http://l.yimg.com/a/i/ww/met/mod/ybang_22_111908.png',sizingMethod='crop');_background-image: none;"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/" class="y-txt-modhdr">TV</a></div>Tuned in: Latest TV photos
															</h2>
														</div>
													</div>
													<div class="wrapper clearfix">

														<ul class="thumbs clearfix">
															<li class="thumb"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/the-secret-life-of-the-american-teenager/show/42563/photos/1" class="img">
																	<img class="y-ln-1" width="56" height="44" alt="Photo: ABC/Family/Randy Holmes" src="https://web.archive.org/web/20100701000722im_/http://l.yimg.com/a/i/crsl/10q4/palin_woodley_062810_56x44.jpg">
																</a><span class="link"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/the-secret-life-of-the-american-teenager/show/42563/photos/1" class="">Bristol Palin on 'American Teenager'</a></span></li>
															<li class="thumb"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/afi-life-achievement-award-a-tribute-to-mike-nichols/show/46625/photos/15" class="img">
																	<img class="y-ln-1" width="56" height="44" alt="Photo: Vince Bucci/PictureGroup" src="https://web.archive.org/web/20100701000722im_/http://l.yimg.com/a/i/crsl/2010june/crsl_tv-nichols56x44.jpg">
																</a><span class="link"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/afi-life-achievement-award-a-tribute-to-mike-nichols/show/46625/photos/15" class="">A-listers honor director Mike Nichols</a></span></li>
															<li class="thumb"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/slideshow/583/photos/1" class="img">
																	<img class="y-ln-1" width="56" height="44" alt="Photo: TNT/Memphis Beat" src="https://web.archive.org/web/20100701000722im_/http://l.yimg.com/a/i/crsl/2010june/crsl_tv-memphis56x44.jpg">
																</a><span class="link"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/slideshow/583/photos/1" class="">Meet the stars of 'Memphis Beat'</a></span></li>
															<li class="thumb"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/the-real-world-24-new-orleans/show/44554/photos/1" class="img">
																	<img class="y-ln-1" width="56" height="44" alt="Photo: MTV" src="https://web.archive.org/web/20100701000722im_/http://l.yimg.com/a/i/crsl/10q4/realworld_062810_56x44.jpg">
																</a><span class="link"><a href="https://web.archive.org/web/20100701000722/http://tv.yahoo.com/the-real-world-24-new-orleans/show/44554/photos/1" class="">Cast of 'The Real World 24: New Orleans'</a></span></li>
														</ul>
													</div>

												</div>
											</div>
											<div id="default-p_21487697_d7f-ft" class="ft"></div>
										</div>
									</li>
								</ul>
							</div>
							<div class="y-nav y-bg-2 y-ln-1 clearfix">&nbsp;</div>
						</div>
					</div>
				</div>
				<div id="default-p_17335519-ft" class="ft"></div>
			</div>

			<div class="mod view_default">
				<div class="bd type_miniad type_miniad_default">
					<div class="ctr y-ln-1 clearfix ad-holder">
						<div class="type_contentcarouselpulse_default">
							<div class="title-clip">
								<div class="title-wrapper">
									<h2 class="y-txt-modhdr">Connect</h2>
								</div>
							</div>
							<div class="pulse-wrapper clearfix">
								<ul class="pulse-list">
									<li>
										<a class="y-link-2 y-fp-pg-controls" href="">Instagram</a>
									</li>
									<li>
										<a class="y-link-2 y-fp-pg-controls" href="">Invite a Friend</a>
									</li>
									<li>
										<a class="y-link-2 y-fp-pg-controls" href="">Find More Ways to Make Money</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="default-p_24457750" class="mod view_default">
				<div id="default-p_24457750-bd" class="bd type_miniad type_miniad_default">
					<div class="ctr y-ln-1 clearfix ad-holder">
						<h2 class="y-txt-modhdr hdr strong">DAILY OFFERS</h2>
						<div id="default-u_26391790" class="mod view_default">
							<div id="default-u_26391790-bd" class="bd type_ads type_ads_default">
								<div class="promo promo-reserve"><!-- metro normal ad --><!-- metro fallback ad cms -->
									<style>
										.hdlnm a .hdlnm-img {
											float: left;
											margin-right: .5em;
										}

										.hdlnm .hdlnm-txt {
											float: left;
											line-height: 1.36em;
										}
									</style>
									<div class="hdlnm small clearfix">
										<a href="https://web.archive.org/web/20100701000722/http://shopping.yahoo.com/b:Shoes:92253310;_ylc=X3oDMTE3MmpzcjhoBF9TAzI3MTYxNDkEc2VjA2ZwLW1hcmtldHBsYWNlBHNsawNzaG9lcw---"><img src="https://web.archive.org/web/20100701000722im_/http://l.yimg.com/a/a/1-/flash/promotions/yahoo/081120/70x50iltlb_2.jpg" width="70" height="50" border="0" class="hdlnm-img" alt="Yahoo! Shopping"></a>
										<p class="hdlnm-txt">
											<a href="https://web.archive.org/web/20100701000722/http://shopping.yahoo.com/b:Shoes:92253310;_ylc=X3oDMTE3MmpzcjhoBF9TAzI3MTYxNDkEc2VjA2ZwLW1hcmtldHBsYWNlBHNsawNzaG9lcw--">Shoes for Women, Men and Kids</a>
											<br>Pumps, boots, sneakers, wedges, and more.
											<br>All the trendiest shoes are on <a href="https://web.archive.org/web/20100701000722/http://shopping.yahoo.com/b:Shoes:92253310;_ylc=X3oDMTE3MmpzcjhoBF9TAzI3MTYxNDkEc2VjA2ZwLW1hcmtldHBsYWNlBHNsawNzaG9lcw--">Yahoo! Shopping</a>.
										</p>
									</div><!-- SpaceID=0 robot -->
								</div>
							</div>
							<div id="default-u_26391790-ft" class="ft"></div>
						</div>
						<div id="default-u_30344913" class="mod view_default">
							<div id="default-u_30344913-bd" class="bd type_ads type_ads_default">
								<div class="tl1 tl1-reserve"><!-- metro normal ad --><!-- metro fallback ad cms --><!-- TL1 --><!-- SpaceID=0 robot -->
								</div>
							</div>
							<div id="default-u_30344913-ft" class="ft"></div>
						</div>
					</div>
				</div>
				<div id="default-p_24457750-ft" class="ft"></div>
			</div>
		</div>
		</div>
		</div>
	<?php } ?>
<?php } ?>
