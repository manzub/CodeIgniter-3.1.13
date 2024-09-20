<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Transactions</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Transactions</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div id="messages"></div>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Edit Transaction</h3>
          </div>

          <form action="<?php echo base_url('transactions/update') ?>" method="post">
            <div class="box-body">
              <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>

              <div class="form-group">
                <label for="">Customer Name</label>
                <input type="text" disabled value="<?php echo $customer['username'] ?>" id="" class="form-control">
              </div>

              <div class="form-group">
                <label for="">Coins Requested</label>
                <input type="text" disabled value="<?php echo $transaction_item['coins_requested'] ?>" id="" class="form-control">
              </div>

              <div class="form-group">
                <label for="">Date Time</label>
                <input type="text" disabled name="" id="" class="form-control">
              </div>

              <div class="form-group">
                <label for="">Currency</label>
                <input type="text" disabled value="<?php echo $transaction_item['currency'] ?>" id="" class="form-control">
              </div>

              <div class="form-group">
                <label for="">Amount Worth</label>
                <input type="text" disabled value="<?php echo $transaction_item['value'] ?>" id="" class="form-control">
              </div>

              <div class="form-group">
                <label for="paid_status" class="col-sm-5 control-label">Paid Status</label>
                <div class="col-sm-7">
                  <select type="text" class="form-control" id="paid_status" name="paid_status">
                    <option>Select One</option>
                    <option <?php echo $transaction_item['status'] == '1' ? 'selected' : '' ?> value="1">Completed</option>
                    <option <?php echo $transaction_item['status'] == '0' ? 'selected' : '' ?> value="0">Pending</option>
                    <option <?php echo $transaction_item['status'] == '-1' ? 'selected' : '' ?> value="-1">Rejected</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="box-footer">
              <a target="__blank" href="<?php echo base_url() . 'transactions/printDiv/' . $transaction_item['id'] ?>" class="btn btn-default">Print</a>
              <button type="submit" class="btn btn-primary">Save Changes</button>
              <a href="<?php echo base_url('transactions') ?>" class="btn btn-warning">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
