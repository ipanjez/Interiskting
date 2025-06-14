<main class="main">
	<!-- Breadcrumb-->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">Home</li>
		<li class="breadcrumb-item">
			<a href="#">Admin</a>
		</li>
		<li class="breadcrumb-item active">User</li>
		<!-- Breadcrumb Menu-->
		<li class="breadcrumb-menu d-md-down-none">
			<div class="btn-group" role="group" aria-label="Button group">
				<a class="btn" href="#">
					<i class="icon-speech"></i>
				</a>
				<a class="btn" href="./">
					<i class="icon-graph"></i>  Dashboard</a>
				<a class="btn" href="#">
					<i class="icon-settings"></i>  Settings</a>
			</div>
		</li>
	</ol>
	<div class="container-fluid">
		<div class="animated fadeIn">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
							<i class="fa fa-align-justify"></i>
						</div>
						<div class="card-body">
							<form id="myform" method="post" onsubmit="return false">
								<div class="row" style="margin-bottom: 10px">
									<div class="col-xs-12 col-md-4">
										<?php echo anchor(site_url('auth/create_user'), '<i class="fa fa-plus"></i> Create', 'class="btn btn-primary"'); ?>
									</div>
									<div class="col-xs-12 col-md-4 text-center">
										<div style="margin-top: 4px" id="message">
										</div>
									</div>
									<div class="col-xs-12 col-md-4 text-right">
										<?php echo anchor(site_url('user/excel'), '<i class="fa fa-file-excel-o"></i>  Excel', 'class="btn btn-success"'); ?>
									</div>
								</div>
								<table class="table table-responsive-sm table-bordered table-striped table-sm" id="mytable">
									<thead>
										<tr>
											<th>NAMAa</th>
											<th>EMAIL</th>
											<th>UNIT KERJA</th>
											<th>NPK</th>
											<th nowrap="nowrap">GROUP</th>
											<th>STATUS</th>
											<th>AKSI</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($users as $user) : ?>
											<tr>
												<td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?php echo htmlspecialchars($user->unit_kerja, ENT_QUOTES, 'UTF-8'); ?></td>

												<td><?php echo htmlspecialchars($user->npk, ENT_QUOTES, 'UTF-8'); ?></td>
												<td nowrap="nowrap">
													<?php $myArray = array(); ?>
													<?php foreach ($user->groups as $group) : ?>
														<?php $myArray[] = anchor("auth/edit_group/" . $group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8')); ?>
													<?php endforeach ?>
													<?= implode(', ', $myArray); ?>

												</td>
												<td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, lang('index_active_link'), 'class="btn btn-success btn-sm"') : anchor("auth/activate/" . $user->id, lang('index_inactive_link'), 'class="btn btn-danger  btn-sm"'); ?></td>
												<td nowrap="nowrap"><?php echo anchor("auth/edit_user_superadmin/" . $user->id, '<i class="fa fa-user"></i>', 'class="btn btn-warning btn-sm" data-toogle="tooltip" title="Edit User"');
																	echo " ";
																	echo anchor('users/delete/' . $user->id, '<i class="fa fa-trash"></i>', 'class="btn btn-sm btn-danger" onclick="return confirmdelete(\'users/delete/' . $user->id . '\')" data-toggle="tooltip" title="Delete"'); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</main>