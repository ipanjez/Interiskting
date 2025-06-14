<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Profile</li>
        <!-- Breadcrumb Menu-->
        <li class="breadcrumb-menu d-md-down-none">
            <div class="btn-group" role="group" aria-label="Button group">
                <a class="btn" href="#">
                    <i class="icon-speech"></i>
                </a>
                <a class="btn" href="./">
                    <i class="icon-graph"></i> Dashboard</a>
                <a class="btn" href="#">
                    <i class="icon-settings"></i> Settings</a>
            </div>
        </li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card">
                                        <img class="card-img-top" src="<?= base_url(); ?>assets/img/avatars/user4-128x128.jpg" alt="User profile picture">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $user->first_name; ?></h5>
                                            <p class="card-text"><?= $user->npk; ?></p>
                                            <a href="<?= base_url(); ?>auth/logout" class="btn btn-primary">Logout</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo lang('edit_user_heading'); ?></h3>

                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <p><?php echo lang('edit_user_subheading'); ?></p>
                                            <?php
                                            if ($message != "") {
                                            ?>
                                                <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div> <?php } ?>
                                            <?php echo form_open(uri_string()); ?>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email address</label>
                                                <input readonly type="email" class="form-control" id="exampleInputEmail1" value="<?= $user->email; ?>">
                                            </div>
                                            <div class="form-group">
                                                <?php echo lang('edit_user_fname_label', 'first_name'); ?> <br />
                                                <?php echo form_input($first_name); ?>
                                            </div>

                                            <div class="form-group">
                                                <label>NPK</label>
                                                <input readonly type="npk" class="form-control" id="npk" value="<?= $user->npk; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Unit Kerja</label>
                                                <input readonly type="unit_kerja" class="form-control" id="unit_kerja" value="<?= $user->unit_kerja; ?>">
                                            </div>
                                            <div class="form-group">
                                                <?php echo lang('edit_user_password_label', 'password'); ?> <br />
                                                <?php echo form_input($password); ?>
                                            </div>


                                            <div class="form-group">
                                                <?php echo lang('edit_user_password_label', 'password'); ?> <br />
                                                <?php echo form_input($password); ?>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('edit_user_password_confirm_label', 'password_confirm'); ?><br />
                                                <?php echo form_input($password_confirm); ?>
                                            </div>
                                            <?php echo form_hidden('id', $user->id); ?>
                                            <?php echo form_hidden($csrf); ?>
                                            <div class="row">
                                                <div class="col-md-12" style="margin-top:10px;">
                                                    <p><?php echo form_submit('submit', lang('edit_user_submit_btn'), 'class="btn btn-primary" style="clear:both"'); ?>
                                                        <a href="<?php echo site_url('user') ?>" class="btn btn-danger">Batal</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>