<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Manage
			<small>Surveys</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="">Surveys</li>
			<li class="active">Results</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12 col-xs-12">

				<div class="box">
					<div class="box-header">
						<h1>Survey Results</h1>
						<h3 class="box-title">Below are results for your survey item - "<?php echo $survey_item['title'] ?>"</h3>
						<p>View the results of your published survey</p>
						<button onclick="window.print()" class="btn btn-primary"><i class="ion ion-printer"></i> Print Page</button>
					</div>
					<div class="box-body">
						<div class="row">
							<?php foreach ($survey_questions as $value) { ?>
								<div class="col-md-6">
									<h1 class="text-center"><?php echo $value['question_text'] ?></h1>
									<div id="<?php echo str_replace(" ", "-", $value['question_text']) ?>"></div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	var base_url = '<?php echo base_url() ?>';
	var survey_id = '<?php echo $survey_item['id'] ?>';
	$(document).ready(function() {
		$.ajax(base_url + 'surveys/fetchSurveyResultsChart/' + survey_id).done(function(data) {
			let result = JSON.parse(data);
			result.forEach(item => {
				Morris.Donut({
					element: item.question,
					data: item.options
				});
			});
		})
	})
</script>
