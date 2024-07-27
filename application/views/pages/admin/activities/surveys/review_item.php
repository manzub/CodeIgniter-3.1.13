<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Review
			<small>Item #<?php echo $this->uri->segment(3) ?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Surveys</li>
			<li class="active">Review #<?php echo $this->uri->segment(3) ?></li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-23 col-xs-12">
				<div class="messages">
					<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
				</div>

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Review Item</h3>
						<p>Review the below Review Item and Approve or Deny this review item.</p>
						<p>Earn rewards for completing this activity.</p>
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
							<hr>
							<div class="jumbotron">
								<div class="container-fluid">
									<div style="display: flex;justify-content:space-between;align-items:center">
										<div class="d-block">
											<h3>Survey Questions</h3>
										</div>
										<button type="button" data-toggle="modal" data-target="#addQuestionModal" class="btn btn-primary"><i class="fa fa-plus"></i>Add</button>
									</div>
									<!-- todo add questions -->
									<ul class="list-group">
										<?php foreach ($sv_questions as $key => $value) { ?>
											<li class="list-group-item" style="text-transform: capitalize;">
												<div style="display: flex;justify-content:space-between;align-items:center">
													<h4><?php echo $value['option_style'] ?> - <?php echo $value['question_text'] ?></h4>
												</div>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>

							<div class="form-group">
								<div style="display: flex;justify-content:start;gap:10px">
									<div class="form-check">
										<input class="form-check-input" value="approve" type="radio" name="approve_deny" id="flexRadioDefault1">
										<label class="form-check-label" for="flexRadioDefault1">
											Approve
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" value="deny" type="radio" name="approve_deny" id="flexRadioDefault2" checked>
										<label class="form-check-label" for="flexRadioDefault2">
											Deny
										</label>
									</div>
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
