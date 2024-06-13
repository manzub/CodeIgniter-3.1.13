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
        <div id="messages"></div>

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
                  <option value="1">10</option>
                  <option value="1">10</option>
                  <option value="1">10</option>
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
              <hr />
              <div class="form-group">
                <div style="border: 1px solid #e3e3e3;padding:10px">
                  <div style="display: flex;align-items:center;justify-content:space-between">
                    <h4 class="fw-bold"><strong>Survey Questions</strong></h4>
                    <button class="btn btn-primary">
                      <span class="glyphicon glyphicon-plus"></span>
                    </button>
                  </div>
                  <hr>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-8">
                        <label for="">Question 1</label>
                        <input type="text" name="" id="" class="form-control" placeholder="Question 1">
                      </div>
                      <div class="col-md-4">
                        <label for="">Options Type</label>
                        <select name="" id="" class="form-control">
                          <option value="">Single</option>
                          <option value="">Multiple</option>
                        </select>
                      </div>
                    </div>
                    <small>You can add options later</small>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
