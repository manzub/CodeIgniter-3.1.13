<div id="y-col1">
	<div class="mod view_default">
		<div class="card card-default no-shadow">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-1"></div>
					<div class="col-lg-10">
						<center>
							<span style="font-size: 40px;" class="glyphicon glyphicon-ok"></span>
							<h1>Congratulations!</h1>
							<h3>You have completed a survey.</h3>
							<!-- TODO: categories -->
							<br>
							<form action="<?php echo  base_url('reviews/completed/'.$review_item['slug']) ?>" method="post">
								<input type="hidden" name="completed" value="<?php echo $review_item['slug'] ?>">
								<button type="submit" class="btn btn-primary">Claim Rewards</button>
							</form>
						</center>
					</div>
					<div class="col-lg-1"></div>
				</div>
			</div>
		</div>
	</div>
</div>
