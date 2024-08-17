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
	<div class="alert alert-warning">
		<strong>Please submit accurate content, if your reviews are seen as spam or misleading, your account could be banned.</strong>
	</div>
	<!-- content items -->
	<div class="mod view_default">
		<div class="card panel-default no-shadow">
			<div class="card-header" style="padding: 10px;">
				<h1><strong>Transcribe Audio</strong></h1>
				<p>Listen to an audio recording and write what you wear</p>
				<small>Please ensure your transcribtion is grammatically correct.</small>
			</div>
			<div class="card-body" style="padding: 10px;">
				<div class="thumbnail">
					<audio controls>
						<source src="<?php echo unserialize($transcribe_item['files'])[0] ?>" type="audio/mp3">
						Your browser does not support the audio element.
					</audio>
					<p style="margin-top: 5px;font-weight:bold">Customer service recognition file from conversation with customer from company xyz</p>
				</div>
				<hr>
				<form action="<?php echo base_url('transcribe/completeTranscribeItem/' . $transcribe_item['slug']) ?>" method="post">
					<div class="form-group">
						<p>Write what you hear here</p>
						<textarea class="form-control" name="transcribe_text" id="" cols="30" rows="10"></textarea>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
