<div class="content-wrapper">
	<div class="content-header">
		<h1>
			Manage
			<small>Transcribe Item</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Surveys</li>
		</ol>
	</div>

	<!-- main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div id="messages">
					<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
				</div>

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Edit Transcribe Item</h3>
					</div>

					<form method="post">
						<div class="box-body">
							<!-- title, categories, limit_per_user, global_limit, files -->
							<div class="form-group">
								<label for="">Title</label>
								<input type="text" name="transcribe_title" value="<?php echo $transcribe_item['title'] ?>" class="form-control">
							</div>
							<div class="form-group">
								<label for="">Categories</label>
								<select name="categories[]" multiple id="" class="form-control">
									<?php for ($i = 1; $i < sizeof($categories); $i++) { ?>
										<option <?php if (in_array($categories[$i]['id'], explode(',', $transcribe_item['category']))) {
															echo "selected";
														} ?> value="<?php echo $categories[$i]['id'] ?>"><?php echo $categories[$i]['name'] ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label for="">Limit Per User</label>
										<input type="number" name="limit_per_user" value="<?php echo $transcribe_item['limit_per_user'] ?>" class="form-control">
									</div>
									<div class="col-md-6">
										<label for="">Global Limit</label>
										<select name="global_limit" id="" class="form-control">
											<?php
											$options = array(10, 50, 100, 250, 500);
											for ($i = 0; $i < sizeof($options); $i++) { ?>
												<option <?php if ($transcribe_item['global_limit'] == $options[$i]) {
																	echo "selected";
																} ?> value="<?php echo $options[$i] ?>"><?php echo $options[$i] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<h3 class="page-header">Files</h3>
									<div class="form-group">
										<label for="">Audio Files</label>
										<div style="background-color: #e3e3e3;width:100%;height:250px;margin-bottom: 2rem">
											<div class="d-flex align-items-center justify-content-center">
												<p style="font-size: 30px;">Upload file<span class="fa fa-upload"></span></p>
											</div>
											<input type="file" name="av_files[]" id="" style="visibility: hidden;">
										</div>
										<label for="">Link to files</label>
										<small>Add a comma (;) seperator to link multiple files</small>
										<input type="text" name="files_links" value="<?php echo $files_html ?>" class="form-control">
									</div>
								</div>
							</div>

							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
