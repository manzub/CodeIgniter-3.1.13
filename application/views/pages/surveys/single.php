<div id="y-col1">
	<div class="mod view_default">
		<div class="bd type_breakingnews type_breakingnews_default">
			<div class="remove-margin"></div>
		</div>
	</div>

	<div class="mod view_default">
		<div class="bd type_surveys type_fptoday type_fptoday_default">
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url('surveys') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
				<li><a href="<?php echo base_url() ?>">Home</a></li>
				<li><a href="<?php echo base_url('surveys') ?>">Surveys</a></li>
				<li class="active"><?php echo strlen($survey_item['title']) > 30 ? $survey_item['title'] . "..." : $survey_item['title']; ?></li>
			</ol>
			<div class="survey-container y-ln-1">
				<div class="panel panel-default no-shadow">
					<div class="panel-heading" style="padding: 10px;">
						<div class="header-flex">
							<span><span id="no_questions"><?php echo $survey_item['no_questions'] ?></span> Remaining</span>
							<!-- TODO: coin config from db -->
							<span>Get <span class="text-primary" style="font-weight: bold;"><?php echo $survey_item['reward_points'] ?> SB</span> For Completing</span>
						</div>
					</div>
					<div class="panel-body">
						<div class="" id="sv-question-items">
							<h2 id="question_text" style="font-weight: bold;font-size:14px"></h2>
							<input type="hidden" id="next_index" value="0">
							<div id="options_list" class="row" style="margin-top: 10px;">
								<div class="col-lg-12">
									<div class="input-group" style="border: 1px solid #e3e3e3;">
										<span class="input-group-addon" style="background-color: inherit;border:none">
											<input id="sv_opt" type="radio" aria-label="...">
										</span>
										<p style="margin-left:10px;line-height: 2em;"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div style="width: 100%;">
							<div style="display: flex;align-items:center;justify-content:right">
								<button disabled id="next-question" class="btn btn-primary">
									Next
									<span class="glyphicon glyphicon-arrow-right"></span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var base_url = "<?php echo base_url() ?>"
	var survey_slug = "<?php echo $survey_item['slug'] ?>"
	var questions = null;

	$(document).ready(function() {
		$.get(base_url + "surveys/fetchSurveyData/" + survey_slug, function(data) {
			console.log(data);
			const results = JSON.parse(data)
			questions = results.data;
			document.getElementById("no_questions").innerHTML = questions.length
			selectItem(0);
		})

		document.getElementById("options_list").addEventListener("click", function(event) {
			if (event.target && event.target.matches("input[type='radio']", "input[type='checkbox']")) {
				document.getElementById('next-question').removeAttribute('disabled')
			}
		});

		document.getElementById('next-question').addEventListener("click", function(event) {
			// save progress and result in completed
			let next_index = parseInt(document.getElementById("next_index").value)
			$.ajax({
				url: base_url + "surveys/completeQuestion/" + questions[next_index - 1].id,
				type: "POST",
				dataType: "json",
				data: {
					"sv_opt": document.getElementById("sv_opt").value
				},
				success: function(data) {
					if (data.status == 2) {
						window.location.href = base_url + "surveys/completed/" + survey_slug;
					}

					if (data.status == 1) {
						selectItem(next_index);
						document.getElementById("no_questions").innerHTML = parseInt(questions.length) - 1;
					}
				},
				error: function(data) {
					// log error message
					console.log(data.responseText);
				}
			})
			// move to next question
		})
	})

	function selectItem(index = null) {
		let question_item = questions[index];
		if (question_item) {
			let options_list = document.getElementById('options_list');
			let question_text = document.getElementById('question_text');
			let index_elem = document.getElementById('next_index');

			options_list.innerHTML = "";
			question_item.options.forEach(option => options_list.innerHTML += option.option_html)

			question_text.innerHTML = "";
			question_text.innerHTML = question_item.question_html

			index_elem.value = index + 1;
		}
	}
</script>
