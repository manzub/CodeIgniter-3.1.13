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

	<div class="jumbotron" style="padding: 10px;">
		<h1 style="font-size: 15px;"><strong>Review Video</strong></h1>
		<p>Give your review a short clip or a movie, positive or negative and a rating</p>
		<small>Please ensure your reviews are grammatically correct.</small>
	</div>
	<!-- content items -->
	<div class="mod view_default">
		<div class="card card-default no-shadow">
			<div class="card-body" style="padding: 10px;">
				<div id="video-info" class="video-info">
					<video controls style="border: 1px solid #000;width:100%">
						<source src="<?php echo base_url($review_item['short_clip']) ?>">
					</video>
					<h1 style="font-size: 20px;font-weight:bold"><?php echo $review_item['title'] ?></h1>
					<p><?php echo nl2br($review_item['short_desc']) ?></p>
					<p>
						<?php foreach ($categories as $key => $item) { ?>
							<span class="badge badge-primary"><?php echo $item ?></span>
						<?php } ?>
					</p>
					<?php if ($review_item['is_movie']) { ?>
						<p>IMDB: <a href="<?php echo $review_item['imdb'] ?>"><?php echo $review_item['imdb'] ?></a></p>
					<?php } ?>
				</div>
				<div id="rv_action" class="review_action">
					<hr>
					<form action="<?php echo base_url('reviews/completeReviewItem/'.$review_item['slug']) ?>" method="post">
						<div class="form-group">
							<p><label for="" style="margin-bottom: 0px;">Leave A Review:</label></p>
							<div style="display: flex;align-items: start;width:75%;margin-bottom: 7px;">
								<small>Leave an informative positive or negative review on the clip you just watched.</small>
							</div>
							<p><strong><span id="word-count">0</span>/1500</strong></p>
							<textarea class="form-control" name="review_text" id="text_input" cols="30" rows="10" maxlength="1500"></textarea>
						</div>

						<div class="form-group rating-container">
							<p>Leave a rating:</p>
							<input id="rv_rating" type="hidden" name="review_rating" value="">
							<div class="btn-group" role="group" aria-label="...">
								<button onclick="document.getElementById('rv_rating').value = 'dislike'" type="button" class="btn btn-secondary">
									<span class="bi bi-hand-thumbs-down"></span>
								</button>
								<button onclick="document.getElementById('rv_rating').value = 'like'" type="button" class="btn btn-success">
									<span class="bi bi-hand-thumbs-up"></span>
								</button>
								<button onclick="document.getElementById('rv_rating').value = 'heart'" type="button" class="btn btn-danger">
									<span class="bi bi-heart"></span>
								</button>
							</div>
						</div>

						<button type="submit" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		var area = document.querySelector('textarea');
		if (area.addEventListener) {
			area.addEventListener('input', function() {
				updateWordCount();
				// event handling code for sane browsers
			}, false);
		} else if (area.attachEvent) {
			area.attachEvent('onpropertychange', function() {
				updateWordCount();
				// IE-specific event handling code
			});
		}

		function updateWordCount() {
			let word_count = String(area.value).length;
			document.getElementById('word-count').innerHTML = word_count;
		}
	})
</script>
