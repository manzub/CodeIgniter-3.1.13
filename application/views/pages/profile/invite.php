<div id="y-col1" style="width: 78.23%;">
  <div class="mod view_default">
    <?php if ($this->session->flashdata('alert')) { ?>
      <div class="alert <?php echo $this->session->flashdata('alert')['classname']; ?> alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <strong><?php echo $this->session->flashdata('alert')['title']; ?></strong>
        <p><?php echo $this->session->flashdata('alert')['message']; ?></p>
      </div>
    <?php } ?>
    <div class="jumbotron" style="margin-bottom: 0px;">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h1 style="font-size: 17px;">Earn a <strong><?php echo $ref_bonus ?> SB</strong> Bonus for every referral!*</h1>
            <div class="d-flex align-items-center justify-content-between" style="margin-top: 10px;">
              <img src="<?php echo base_url('assets/phs/bonus.svg') ?>" style="height: 100px;width:100px;flex:1" />
              <div class="contents" style="flex: 2;">
                <p style="font-size: 15px;">When your referral completes <?php echo $ref_reward_config ?> activities in their first month, you get a <?php echo $ref_bonus ?> SB Bonus!</p>
                <a href="#refer-form">Start Now</a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <h1 style="font-size: 17px;">Earn a <strong><?php echo $ref_interest ?>%</strong> Bonus for every referral!*</h1>
            <div class="d-flex align-items-center justify-content-between" style="margin-top: 10px;">
              <img src="<?php echo base_url('assets/phs/ongoing-reward.svg') ?>" style="height: 100px;width:100px;flex:1" />
              <div class="contents" style="flex: 2;">
                <p style="font-size: 15px;">Get <?php echo $ref_interest ?>% of all your referrals earnings for the life of their account.</p>
                <a href="#refer-form">Start Now</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="refer-form" class="mod view_default">
    <div class="card bg-default">
      <div class="card-header">
        <div class="page-title">
          <h1 style="font-size: 20px;font-weight:bold;text-transform:capitalize">3 ways to invite:</h1>
        </div>
      </div>
      <div class="card-body">
        <div class="row equal">
          <div class="col-lg-12">
            <form action="<?php echo base_url('profile/send_invite_email') ?>" method="post">
              <div class="row">
                <div class="col-lg-12">
                  <p>
                    <span class="badge badge-info">1</span>
                    <strong>Send a quick email</strong>
                  </p>
                </div>
                <div class="col-lg-5 col-md-5">
                  <div class="form-group">
                    <label for="recipientName" style="visibility: hidden;">Recipient Name</label>
                    <input name="recipient_user" type="text" class="form-control" placeholder="Recipient Name (Optional)">
                  </div>
                </div>
                <div class="col-lg-7 col-md-7">
                  <div class="form-group">
                    <label for="" style="visibility: hidden;">Email address</label>
                    <input name="recipient_email" type="email" class="form-control" placeholder="Recipient Email Address">
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group">
                    <button class="btn btn-primary">SEND</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-lg-12">
            <p>
              <span class="badge badge-info">2</span>
              <strong>Share your referral link directly</strong>
            </p>
          </div>
          <div class="col-lg-12 col-md-12">
            <div class="form-group">
              <label for="" style="visibility: hidden;">Referral Link</label>
              <div class="input-group">
                <input id="form-input" type="text" class="form-control" placeholder="Referral link" value="<?php echo base_url('auth/signup/' . $ref_code) ?>">
                <span class="input-group-btn">
                  <button id="copy-button" class="btn btn-secondary" type="button">
                    <span class="bi bi-copy"></span>
                    <strong>Copy</strong>
                  </button>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    function fallbackCopyTextToClipboard(text) {
      var textArea = document.createElement("textarea");
      textArea.value = text;

      // Avoid scrolling to bottom
      textArea.style.top = "0";
      textArea.style.left = "0";
      textArea.style.position = "fixed";

      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();

      try {
        var successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
        console.log('Fallback: Copying text command was ' + msg);
      } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
      }

      document.body.removeChild(textArea);
    }

    function copyToClipboard() {
      let text = document.getElementById('form-input').value;
      if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
      }
      navigator.clipboard.writeText(text).then(function() {
        console.log('Async: Copying to clipboard was successful!');
      }, function(err) {
        console.error('Async: Could not copy text: ', err);
      });
    }

    let copyButton = document.getElementById('copy-button');
    copyButton.addEventListener('click', function(event) {
      copyToClipboard();
    })
  })
</script>