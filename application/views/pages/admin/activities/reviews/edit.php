<div class="content-wrapper">
	<div class="content-header">
		<h1>
			Manage
			<small>Review</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Reviews</li>
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
						<h3 class="box-title">Edit Review Item</h3>
					</div>

					<form method="post">
						<div class="box-body">
							<!-- title, limits per user, global limit, description, short clip (link or file), is movie / imdb link, thumbnails -->
							<div class="form-group">
								<label for="">Title</label>
								<input type="text" name="review_title" value="<?php echo $review_item['title'] ?>" class="form-control">
							</div>
							<div class="form-group">
								<label for="">Categories</label>
								<select name="categories[]" multiple id="" class="form-control">
									<?php for ($i = 1; $i < sizeof($categories); $i++) { ?>
										<option <?php if (in_array($categories[$i]['id'], explode(',', $review_item['category']))) {
															echo "selected";
														} ?> value="<?php echo $categories[$i]['id'] ?>"><?php echo $categories[$i]['name'] ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label for="">Short Description</label>
								<textarea name="short_desc" cols="30" rows="10" class="form-control"><?php echo $review_item['short_desc'] ?></textarea>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label for="">Is Movie</label>
										<select name="is_movie" id="" class="form-control">
											<?php
											$options = array('No', 'Yes');
											for ($i = 0; $i < sizeof($options); $i++) { ?>
												<option <?php if ($review_item['is_movie'] == $i) {
																	echo "selected";
																} ?> value="<?php echo $i; ?>"><?php echo $options[$i] ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md-9">
										<label for="">IMDB Link</label>
										<input type="text" name="imdb_link" value="<?php echo in_array($review_item['imdb'], array("", " ", null)) ? $review_item['imdb'] : 'N/A' ?>" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label for="">Limit Per User</label>
										<input type="number" name="limit_per_user" value="<?php echo $review_item['limit_per_user'] ?>" class="form-control">
									</div>
									<div class="col-md-6">
										<label for="">Global Limit</label>
										<select name="global_limit" id="" class="form-control">
											<?php
											$options = array(10, 50, 100, 250, 500);
											for ($i = 0; $i < sizeof($options); $i++) { ?>
												<option <?php if ($review_item['global_limit'] == $options[$i]) {
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
										<label for="">Short Clip</label>
										<div style="background-color: #e3e3e3;width:100%;height:250px;margin-bottom: 2rem">
											<div class="d-flex align-items-center justify-content-center">
												<p style="font-size: 30px;">Upload file<span class="fa fa-upload"></span></p>
											</div>
											<input type="file" name="short_clip" id="" style="visibility: hidden;">
										</div>
										<input name="short_clip_link" type="text" class="form-control" value="<?php echo $review_item['short_clip'] ?>" placeholder="paste link to file">
									</div>

									<div class="form-group">
										<label for="">Thumbnails</label>
										<div style="background-color: #e3e3e3;width:100%;height:250px;margin-bottom: 2rem">
											<div class="d-flex align-items-center justify-content-center">
												<p style="font-size: 30px;">Upload file(s)<span class="fa fa-upload"></span></p>
											</div>
											<input type="file" name="thumbnails[]" id="" style="visibility: hidden;">
										</div>
										<label for="">Link to files</label>
										<small>Add a semi column (;) seperator to link multiple files</small>
										<input type="text" name="thumbnail_links" value="<?php echo $review_item['thumbnail_large'] . ";" . $review_item['thumbnail_small']; ?>" class="form-control">
									</div>
								</div>
							</div>

							<button type="submit" class="btn btn-primary">Submit</button>
							<!-- box body end -->
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

</div>
