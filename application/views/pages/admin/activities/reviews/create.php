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

	<!-- main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div id="messages">
					<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
				</div>

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Create a New Review Item</h3>
					</div>

					<form method="post">
						<div class="box-body">
							<!-- title, categories, limits per user, global limit, description, short clip (link or file), is movie / imdb link, thumbnails -->
							<div class="form-group">
								<label for="">Title</label>
								<input type="text" name="review_title" id="" class="form-control">
							</div>
							<div class="form-group">
								<label for="">Categories</label>
								<select name="categories[]" multiple id="" class="form-control">
									<?php for ($i = 1; $i < sizeof($categories); $i++) { ?>
										<option value="<?php echo $categories[$i]['id'] ?>"><?php echo $categories[$i]['name'] ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label for="">Short Description</label>
								<textarea name="short_desc" id="" cols="30" rows="10" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label for="">Is Movie</label>
										<select name="is_movie" id="" class="form-control">
											<option value="0">No</option>
											<option value="1">Yes</option>
										</select>
									</div>
									<div class="col-md-9">
										<label for="">IMDB Link</label>
										<input type="text" name="imdb_link" id="" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label for="">Limit Per User</label>
										<input type="number" value="1" name="limit_per_user" id="" class="form-control">
									</div>
									<div class="col-md-6">
										<label for="">Global Limit</label>
										<select name="global_limit" id="" class="form-control">
											<option value="10">10</option>
											<option value="50">50</option>
											<option value="100">100</option>
											<option value="250">250</option>
											<option value="500">500</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h3 class="page-header">Files</h3>
									<div class="form-group">
										<label for="">Short Clip</label>
										<div onclick="document.getElementById('shclip_upload').click()" style="background-color: #e3e3e3;width:100%;height:250px;margin-bottom: 2rem">
											<div id="sh_drop" class="d-flex align-items-center justify-content-center">
												<p style="font-size: 30px;">Upload file<span class="fa fa-upload"></span></p>
											</div>
											<input type="file" name="short_clip" id="shclip_upload" style="visibility: hidden;">
										</div>
										<input name="short_clip_link" type="text" class="form-control" placeholder="paste link to file">
									</div>

									<div class="form-group">
										<label for="">Thumbnails</label>
										<div onclick="document.getElementById('thumb_upload').click()" style="background-color: #e3e3e3;width:100%;height:250px;margin-bottom: 2rem">
											<div id="thumb_drop" class="d-flex align-items-center justify-content-center">
												<p style="font-size: 30px;">Upload file(s)<span class="fa fa-upload"></span></p>
											</div>
											<input type="file" name="thumbnails[]" multiple id="thumb_upload" style="visibility: hidden;">
										</div>
										<label for="">Link to files</label>
										<small>Add a comma (;) seperator to link multiple files</small>
										<input type="text" name="thumbnail_links" id="" class="form-control">
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
<script>
	$(document).ready(function() {
		document.getElementById('shclip_upload').addEventListener('click', function() {
			document.getElementById('sh_drop').innerHTML = '<p>Refresh to clear selected file(s)</p>'
		})

		document.getElementById('thumb_upload').addEventListener('click', function() {
			document.getElementById('thumb_drop').innerHTML = '<p>Refresh to clear selected file(s)</p>'
		})
	})
</script>

