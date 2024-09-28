<div class="container-fluid" style="margin: 10px 0px;">
  <div class="mod view_default"></div>
  <div class="jumbotron">
    <div class="row">
      <div class="col-md-7">
        <div class="thumbnail">
          <a href="#">
            <?php if (isset($banner_home_bottom)) { ?>
              <!-- banner_page_home_bottom -->
              <img style="border:1px solid #000" width="100%" src="<?php echo base_url($banner_home_bottom['value']) ?>" />
            <?php } else { ?>
              <img src="https://web.archive.org/web/20130410174940im_/https://a248.e.akamai.net/sec.yimg.com/a/ya/yahoo_gdm_ccs/r1_metro_mail_car.jpg" alt="">
            <?php } ?>
          </a>
        </div>
        <!-- todo advertise home -->
        <p><a class="btn btn-primary btn-lg" href="#" style="color:white" role="button">Learn more</a></p>
      </div>
      <div class="col-md-5">
        <?php echo validation_errors('<div class="alert alert-warning">', '</div>'); ?>
        <?php if ($this->session->flashdata('alert')) { ?>
          <div class="alert <?php echo $this->session->flashdata('alert')['classname']; ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <strong><?php echo $this->session->flashdata('alert')['title']; ?></strong>
            <p><?php echo $this->session->flashdata('alert')['message']; ?></p>
          </div>
        <?php } ?>
        <div class="panel panel-default no-shadow">
          <div class="panel-heading">
            <h1 style="font-size: 17px;font-weight:bold" class="panel-title">Don't have an account?</h1>
            <p style="font-size: 12px;">Signup is easy.</p>
            <a href="<?php echo base_url('auth/signup') ?>" style="color: white;" class="btn btn-block btn-lg btn-primary">Sign up for SurveyVine</a>
          </div>
          <br>
          <div class="panel-body">
            <h1 class="page-title" style="font-size: 17px;font-weight:bold">Verify Account</h1>
            <p style="font-size: 12px;"></p>
            <p style="font-size: 12px;">Enter verification code sent to your email below.</p>
            <span></span>
            <form action="<?php echo base_url('auth/verify') ?>" method="post" class="">
              <div class="form-group">
                <label for="emailInput">Email</label>
                <input class="form-control" required type="email" name="email" id="emailInput">
                <small>(e.g example@example.com)</small>
              </div>
              <div class="form-group">
                <label for="codeInput">Code</label>
                <input class="form-control" required type="text" name="code" id="codeInput" value="<?php echo $this->data['code'] ?>">
                <small>(e.g 12SF3F)</small>
              </div>
              <hr>
              <button class="btn btn-primary">Send Email</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
