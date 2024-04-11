<div id="y-col1">
	<div class="alert_message">
		<div class="bd type_breakingnews type_breakingnews_default">
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
	</div>
	<!-- content items -->
	<div class="mod view_default">
		<div class="panel panel-default no-shadow">
			<div class="panel-heading" style="padding: 10px;">
				<h1 style="font-size: 15px;"><strong>Review Video</strong></h1>
				<p>Give your review a short clip or a movie, positive or negative and a rating</p>
				<small>Please ensure your reviews are grammatically correct.</small>
			</div>
			<div class="panel-body" style="padding: 10px;">
				<div class="video-info hidden">
					<video controls style="border: 1px solid #000;width:100%">
						<source src="file:///Users/wizzdom/Downloads/1108312879-preview.mp4">
					</video>
					<h1 style="font-size: 20px;font-weight:bold">Moana</h1>
					<p>In ancient Polynesia, when a terrible curse incurred by the demigod Maui reaches Moana's island, she answers the Ocean's call to seek out Maui to set things right.</p>
					<p>
						<span class="badge badge-primary">Adventure</span>
						<span class="badge badge-primary">Animation</span>
						<span class="badge badge-primary">Comedy</span>
					</p>
					<p>IMDB: <a href="">imdb.com/movies/sdsds/ds</a></p>
				</div>

				<div class="review_action">
					<div class="d-flex align-items-center" style="gap:10px">
						<img src="<?php echo base_url('assets/phs/pictures.png') ?>" alt="" style="height: 60px;">
						<div class="d-block">
							<h1><strong>Review the avengers movie</strong></h1>
							<p>some short description</p>
							<small style="color:blue">action link</small>
						</div>
					</div>
					<hr>
					<form action="" method="post">
						<div class="form-group">
							<p><label for="" style="margin-bottom: 0px;">Leave A Review:</label></p>
							<div style="display: flex;align-items: start;width:75%;margin-bottom: 7px;">
								<small>Leave an informative positive or negative review on the clip you just watched.</small>
							</div>
							<p><strong>0/1500</strong></p>
							<textarea class="form-control" name="review_text" id="" cols="30" rows="10"></textarea>
						</div>

						<div class="form-group rating-container">
							<p>Leave a rating:</p>
							<input type="hidden" name="review_rating" value="dislike">
							<div class="btn-group" role="group" aria-label="...">
								<button type="button" class="btn btn-default">
									<span class="glyphicon glyphicon-thumbs-down"></span>
								</button>
								<button type="button" class="btn btn-default">
									<span class="glyphicon glyphicon-thumbs-up"></span>
								</button>
								<button type="button" class="btn btn-default">
									<span class="glyphicon glyphicon-heart"></span>
								</button>
							</div>
						</div>

						<button class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
