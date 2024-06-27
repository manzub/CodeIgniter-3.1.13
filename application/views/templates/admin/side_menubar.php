<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu" data-widget="tree">

			<li id="dashboardMainMenu">
				<a href="<?php echo base_url('dashboard') ?>">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span>
				</a>
			</li>

			<?php if ($user_permission) : ?>
				<?php if (in_array('createUser', $user_permission) || in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)) : ?>
					<li class="treeview" id="mainUserNav">
						<a href="#">
							<i class="fa fa-users"></i>
							<span>Users</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if (in_array('createUser', $user_permission)) : ?>
								<li id="createUserNav"><a href="<?php echo base_url('users/create') ?>"><i class="fa fa-circle-o"></i> Add User</a></li>
							<?php endif; ?>

							<?php if (in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)) : ?>
								<li id="manageUserNav"><a href="<?php echo base_url('users') ?>"><i class="fa fa-circle-o"></i> Manage Users</a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (in_array('createGroup', $user_permission) || in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)) : ?>
					<li class="treeview" id="mainGroupNav">
						<a href="#">
							<i class="fa fa-files-o"></i>
							<span>Groups</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if (in_array('createGroup', $user_permission)) : ?>
								<li id="addGroupNav"><a href="<?php echo base_url('groups/create') ?>"><i class="fa fa-circle-o"></i> Add Group</a></li>
							<?php endif; ?>
							<?php if (in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)) : ?>
								<li id="manageGroupNav"><a href="<?php echo base_url('groups') ?>"><i class="fa fa-circle-o"></i> Manage Groups</a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (in_array('manageSurvey', $user_permission) || in_array('manageActivity', $user_permission)) { ?>
					<li class="treeview" id="mainSurveyNav">
						<a href="#">
							<i class="fa fa-cube"></i>
							<span>Surveys</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?php echo base_url('surveys/admin') ?>"><i class="fa fa-circle"></i><span>Manage Surveys</span></a></li>
						</ul>
					</li>
				<?php } ?>

				<?php if (in_array('manageReview', $user_permission) || in_array('manageActivity', $user_permission)) { ?>
					<li class="treeview" id="mainReviewNav">
						<a href="#">
							<i class="fa fa-cube"></i>
							<span>Reviews</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?php echo base_url('reviews/admin') ?>"><i class="fa fa-circle"></i><span>Manage Reviews</span></a></li>
							<?php if (in_array('reviewActivity', $user_permission)) : ?>
								<li id="reviewCompReviewItemNav"><a href="<?php echo base_url('reviews/completed_list') ?>"><i class="fa fa-circle-o"></i>Review Completed</a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php } ?>

				<?php if (in_array('manageTranscribe', $user_permission) || in_array('manageActivity', $user_permission)) { ?>
					<li class="treeview" id="mainTranscribeNav">
						<a href="#">
							<i class="fa fa-cube"></i>
							<span>Transcribe</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?php echo base_url('transcribe/admin') ?>"><i class="fa fa-circle"></i><span>Manage Transcribe</span></a></li>
							<?php if (in_array('reviewActivity', $user_permission)) : ?>
								<li id="reviewCompTranscribeItemNav"><a href="<?php echo base_url('transcribe/completed_list') ?>"><i class="fa fa-circle-o"></i>Review Completed</a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php } ?>

				<?php if (in_array('manageCategory', $user_permission)) { ?>
					<li class="treeview" id="mainActivityNav">
						<a href="#">
							<i class="fa fa-files-o"></i>
							<span>Categories</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if (in_array('createCategory', $user_permission) || in_array('manageCategory', $user_permission)) : ?>
								<li><a href="<?php echo base_url('categories') ?>"><i class="fa fa-files-o"></i><span>Manage Categories</span></a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php } ?>

				<?php if (in_array('createTransaction', $user_permission) || in_array('manageTransaction', $user_permission)) : ?>
					<li class="treeview" id="mainOrdersNav">
						<a href="#">
							<i class="fa fa-dollar"></i>
							<span>Withdrawals</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if (in_array('manageOrder', $user_permission)) : ?>
								<li id="manageOrdersNav"><a href="<?php echo base_url('transactions') ?>"><i class="fa fa-circle-o"></i> Manage Orders</a></li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (in_array('viewReports', $user_permission)) : ?>
					<li id="reportNav">
						<a href="<?php echo base_url('reports/') ?>">
							<i class="glyphicon glyphicon-stats"></i> <span>Reports</span>
						</a>
					</li>
				<?php endif; ?>


				<?php if (in_array('updateCompany', $user_permission)) : ?>
					<li id="companyNav"><a href="<?php echo base_url('company/') ?>"><i class="fa fa-files-o"></i> <span>Company</span></a></li>
				<?php endif; ?>



				<!-- <li class="header">Settings</li> -->

				<?php if (in_array('viewProfile', $user_permission)) : ?>
					<li><a href="<?php echo base_url('users/profile/') ?>"><i class="fa fa-user-o"></i> <span>Profile</span></a></li>
				<?php endif; ?>
				<?php if (in_array('updateSetting', $user_permission)) : ?>
					<li><a href="<?php echo base_url('users/setting/') ?>"><i class="fa fa-wrench"></i> <span>Setting</span></a></li>
				<?php endif; ?>

			<?php endif; ?>
			<!-- user permission info -->
			<li><a href="<?php echo base_url('auth/logout') ?>"><i class="glyphicon glyphicon-log-out"></i> <span>Logout</span></a></li>

		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
