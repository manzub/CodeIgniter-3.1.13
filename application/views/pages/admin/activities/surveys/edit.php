<div class="content-wrapper">
	<div class="content-header">
		<h1>
			Manage
			<small>Survey</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Surveys</li>
		</ol>
	</div>

	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div id="messages">
					<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
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

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Edit Survey Item</h3>
					</div>

					<form action="" method="post">
						<div class="box-body">
							<!-- title, limit per user, global limit, add question -->
							<div class="form-group">
								<label for="">Title</label>
								<input class="form-control" type="text" name="survey_title" value="<?php echo $survey_item['title'] ?>">
							</div>
							<div class="form-group">
								<label for="">Categories</label>
								<select name="categories[]" multiple id="" class="form-control">
									<?php for ($i = 1; $i < sizeof($categories); $i++) { ?>
										<option <?php if (in_array($categories[$i]['id'], explode(',', $survey_item['category']))) {
															echo "selected";
														} ?> value="<?php echo $categories[$i]['id'] ?>"><?php echo $categories[$i]['name'] ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label for="">Limit Per User</label>
										<input type="number" name="limit_per_user" value="<?php echo $survey_item['limit_per_user'] ?>" class="form-control">
									</div>
									<div class="col-md-6">
										<label for="">Global Limit</label>
										<select name="global_limit" id="" class="form-control">
											<?php
											$options = array(10, 50, 100, 250, 500);
											for ($i = 0; $i < sizeof($options); $i++) { ?>
												<option <?php if ($survey_item['global_limit'] == $options[$i]) {
																	echo "selected";
																} ?> value="<?php echo $options[$i] ?>"><?php echo $options[$i] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<hr>
							<div class="jumbotron">
								<div class="container-fluid">
									<div style="display: flex;justify-content:space-between;align-items:center">
										<div class="d-block">
											<h3>Survey Questions</h3>
											<p>Add or Manage Questions here</p>
										</div>
										<button type="button" data-toggle="modal" data-target="#addQuestionModal" class="btn btn-primary"><i class="fa fa-plus"></i>Add</button>
									</div>
									<!-- todo add questions -->
									<ul class="list-group">
										<?php foreach ($sv_questions as $key => $value) { ?>
											<li class="list-group-item" style="text-transform: capitalize;">
												<div style="display: flex;justify-content:space-between;align-items:center">
													<h4><?php echo $value['option_style'] ?> - <?php echo $value['question_text'] ?></h4>
													<a onclick="window.confirm('Are you sure?')" href="<?php echo base_url('surveys/deleteQuestion/'. $value['id']) ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
												</div>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>

							<div class="form-group">
                <a href="<?php echo base_url('surveys/admin') ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
								<button class="btn btn-primary">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addQuestionModalLabel">Add Question</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="<?php echo base_url('surveys/addQuestion/' . $survey_item['slug']) ?>" method="post">
				<div class="modal-body">
					<div class="form-group">
						<div class="row">
							<div class="col-md-9">
								<label for="">Question</label>
								<input type="text" name="question_text" id="" class="form-control">
							</div>
							<div class="col-md-3">
								<label for="">Question Style</label>
								<select name="option_style" id="" class="form-control">
									<option value="single">Single</option>
									<option value="checkbox">Checkbox</option>
								</select>
							</div>
						</div>
					</div>
					<div class="jumbotron">
						<div class="container">
							<h3>Options</h3>
							<hr>
							<div class="form-group">
								<label for="">Option 1</label>
								<input type="text" name="option_text[]" id="" class="form-control">
							</div>
							<div class="form-group">
								<label for="">Option 2</label>
								<input type="text" name="option_text[]" id="" class="form-control">
							</div>
							<div class="form-group">
								<label for="">Option 3</label>
								<input type="text" name="option_text[]" id="" class="form-control">
							</div>
							<div class="form-group">
								<label for="">Option 4</label>
								<input type="text" name="option_text[]" id="" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>
