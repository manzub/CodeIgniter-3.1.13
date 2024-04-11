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
				<h1><strong>Transcribe Audio</strong></h1>
				<p>Listen to an audio recording and write what you wear</p>
				<small>Please ensure your transcribtion is grammatically correct.</small>
			</div>
			<div class="panel-body" style="padding: 10px;">
				<div class="thumbnail">
					<div class="d-flex align-items-center justify-content-between" style="gap:10px">
						<!-- <img src="<?php echo base_url('assets/phs/cd.png') ?>" style="height: 35px;width:35px"> -->
						<div style="width:100%;height:30px">
							<span style="background: url('<?php echo base_url('assets/phs/sound-wave.png') ?>');background-repeat:repeat-x;width:100%;height:30px;display:inline-block"></span>
						</div>
						<h1 style="font-size: 10px;"><strong>1:59</strong></h1>
						<button class="btn btn-default">
							<span class="glyphicon glyphicon-play"></span>
						</button>
					</div>
					<p style="margin-top: 5px;font-weight:bold">Customer service recognition file from conversation with customer from company xyz</p>
				</div>
				<form action="<?php echo base_url('transcribe/completeTranscribe/'.$this->uri->segment(3)) ?>" method="post">
					<div class="form-group">
						<textarea class="form-control" name="transcribe text" id="" cols="30" rows="10"></textarea>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
