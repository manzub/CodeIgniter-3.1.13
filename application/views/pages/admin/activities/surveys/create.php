<div class="content-wrapper">
  <div class="content-header">
    <h1>
      Manage
      <small>Surveys</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Surveys</li>
    </ol>
  </div>

  <!-- Main Content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div id="messages">
          <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
        </div>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Create a New Survey</h3>
          </div>

          <form action="<?php echo base_url('surveys/create') ?>" method="post">
            <div class="box-body">
              <!-- title, categories, limits_per_user, global_limit, [question, option_type - radio, checkbox, options[]] -->
              <div class="form-group">
                <label for="survey_title">Survey Title</label>
                <input type="text" name="survey_title" id="survey_title" autocapitalize="off" placeholder="Enter Survey Title" class="form-control">
              </div>

              <div class="form-group">
                <label for="survey_cat">Categories</label>
                <select type="text" name="survey_cat[]" id="survey_cat" class="form-control select_group" multiple="multiple">
                  <?php for ($i = 1; $i < sizeof($categories); $i++) { ?>
                    <option value="<?php echo $categories[$i]['id'] ?>"><?php echo $categories[$i]['name'] ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="limit_per_user">Limit Per User</label>
                    <input type="number" name="limit_per_user" id="limit_per_user" class="form-control" value="1">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="global_limit">Global Limits</label>
                    <input type="number" name="global_limit" id="global_limit" class="form-control" value="100">
                  </div>
                </div>
              </div>

              <div class="jumbotron jumbotron-fluid">
                <div class="container">
                  <h3 class="display-4">Questions will be added later</h3>
                  <p class="lead">Create Survey Item and add questions later.</p>
                  <hr class="my-4">
                  <button type="submit" class="btn btn-lg btn-primary">Create</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>