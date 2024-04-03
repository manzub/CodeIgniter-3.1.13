<div id="y-col1">
	<div class="mod view_default">
		<div class="panel panel-default no-shadow">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1"></div>
					<div class="col-lg-10">
						<center>
							<span style="font-size: 40px;" class="glyphicon glyphicon-ok"></span>
							<h1>Congratulations!</h1>
							<h3>You have completed a survey.</h3>
							<!-- TODO: categories -->
							<br>
							<form action="<?php echo  base_url('surveys/completed/'.$survey_item['slug']) ?>" method="post">
								<input type="hidden" name="completed" value="<?php echo $survey_item['slug'] ?>">
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
