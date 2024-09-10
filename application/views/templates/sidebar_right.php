<?php if (!in_array($this->uri->segment(2), array('login', 'signup', 'forgot_password', 'reset_password', 'verify', 'settings', 'redeem_points', 'faq'))) { ?>
  <?php if ($this->uri->segment(1) != 'profile') { ?>
    <div id="y-col2">
      <!-- daily activity list -->
      <?php if (!empty($this->session->userdata('daily_activity'))) { ?>
        <div class="mod view_default">
          <div class="bd type_tts type_tts_default">
            <div class="y-ln-1 clearfix typetopten">
              <!-- daily activity list -->
              <h2 class="y-txt-modhdr bdr-b y-ln-1">DAILY ACTIVITIES</h2>
              <ol class="firstcol" style="width:100%">
                <?php foreach ($dl_activity_list as $key => $item) { ?>
                  <li class="y-txt-accent first" style="padding: 2px 0px;">
                    <a href="<?php echo $item['link'] ?>"><?php echo $item['type'] . " | " . $item['title'] ?></a>
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
          <div class="fpad fpad-reserve" style="min-height: 50px;margin-bottom:10px;border:1px solid #000">
            <!-- banner_top_right -->
            <a target="_blank" rel="noreferrer" href="https://potsaglu.net/4/7762248">
              <?php if (isset($banner_top_right)) { ?>
                <?php echo $banner_top_right['extra'] != NULL ? `<a href="` . $banner_top_right['extra'] . `">` : ''; ?>
                <img src="<?php echo base_url($banner_top_right['value']) ?>" style="height:60px;width:350px">
                <?php echo $banner_top_right['extra'] != NULL ? `</a>` : '' ?>
              <?php } ?>
            </a>
          </div>
          <div class="fpad fpad-reserve" style="border: 1px solid #000">
            <!-- banner_right_center -->
            <a target="_blank" rel="noreferrer" href="https://dukingdraon.com/4/7778091">
              <?php if (isset($banner_right_center)) { ?>
                <?php echo $banner_right_center['extra'] != NULL ? `<a href="` . $banner_top_right['extra'] . `">` : ''; ?>
                <img src="<?php echo base_url($banner_right_center['value']) ?>" height="200" width="350">
                <?php echo $banner_right_center['extra'] != NULL ? `</a>` : '' ?>
              <?php } ?>
            </a>
          </div>
        </div>
      </div>

      <div class="mod view_default">
        <div class="bd type_miniad type_miniad_default">
          <div class="ctr y-ln-1 clearfix ad-holder">
            <div class="type_contentcarouselpulse_default">
              <div class="title-clip">
                <div class="title-wrapper">
                  <h2 class="y-txt-modhdr font-weight-bold">Ways to Earn</h2>
                </div>
              </div>
              <div class="pulse-wrapper clearfix">
                <ul class="pulse-list">
                  <li><a class="y-link-2 y-fp-pg-controls" href="<?php echo base_url('surveys') ?>">Answer Surveys</a></li>
                  <li><a class="y-link-2 y-fp-pg-controls" href="<?php echo base_url('profile/invite') ?>">Invite a Friend</a></li>
                  <li><a class="y-link-2 y-fp-pg-controls" href="<?php echo base_url('reviews') ?>">Complete Other Activities</a></li>
                  <li><a class="y-link-2 y-fp-pg-controls" href="<?php echo base_url('auth/signup?type=moderator') ?>">Want to become a moderator? Learn more</a></li>
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
                    <?php if (empty($this->session->userdata('logged_in'))) { ?>
                      <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Why Miss Out!</h4>
                        <p>Create an account to discover ways to earn.</p>
                      </div>
                    <?php } else { ?>
                      <div class="hdlnm small clearfix">
                        <div class="hdlm-text">
                          <a href="<?php echo base_url('profile') ?>" class="font-weight-bold h6">Bonuses</a>
                          <p>View your bonuses</p>
                        </div>
                      </div>
                    <?php } ?>
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

      <div class="mod view_default">
        <div class="bd type_ads type_ads_default">
          <div class="fpad fpad-reserve" style="border:1px solid #000">
            <!-- banner_home_bottom -->
            <a target="_blank" rel="noreferrer" href="https://mordoops.com/4/7778095">
              <?php if (isset($banner_home_bottom)) { ?>
                <?php echo $banner_home_bottom['extra'] != NULL ? `<a href="` . $banner_top_right['extra'] . `">` : ''; ?>
                <img src="<?php echo base_url($banner_home_bottom['value']) ?>" border="0" height="200" width="350">
                <?php echo $banner_home_bottom['extra'] != NULL ? `</a>` : '' ?>
              <?php } ?>
            </a>
          </div>
        </div>
      </div>
    </div>
    </div>
    </div>
  <?php } ?>
<?php } ?>
