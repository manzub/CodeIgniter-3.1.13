<div id="y-col1" style="width: 78.23%;">
	<div class="mod view_default">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('home') ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
			<li><a href="<?php echo base_url('profile') ?>"><?php echo $this->session->userdata('username') ?></a></li>
			<li><a href="<?php echo base_url('profile') ?>">Profile</a></li>
			<li><a href="<?php echo base_url('profile/redeem_points') ?>">Redeem SB</a></li>
		</ol>
	</div>

	<div class="mod view_default">
		<div class="card bg-default">
			<div class="card-header">
				<h1 class="panel-title"><strong>Redeem SB</strong></h1>
				<p>Redeem your earned points with our available options</p>
			</div>
			<div class="card-body">
				<div class="row">
					<?php for ($i = 0; $i < 4; $i++) { ?>
						<div class="col-sm-6 col-md-4 mb-2">
							<div class="card">
								<img class="card-img-top" src="..." alt="..." style="height: 150px;">
								<div class="card-body">
									<h3>Thumbnail label</h3>
									<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
									<p>
										<a href="#" class="btn btn-primary" role="button">Button</a>
										<a href="#" class="btn btn-secondary" role="button">Button</a>
									</p>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
