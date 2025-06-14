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
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <strong>Deaktivasi</strong>
                            User
                        </div>
                        <div class="card-body">
                            <p><?php echo lang('deactivate_subheading'); ?></p>
                            <?php echo form_open("auth/deactivate/" . $user->id); ?>
                            <p>
                                <?php echo lang('deactivate_confirm_y_label', 'confirm'); ?>
                                <input type="radio" name="confirm" value="yes" checked="checked" />
                                <?php echo lang('deactivate_confirm_n_label', 'confirm'); ?>
                                <input type="radio" name="confirm" value="no" />
                            </p>
                            <?php echo form_hidden($csrf); ?>
                            <?php echo form_hidden(array('id' => $user->id)); ?>
                            <p><?php echo form_submit('submit', lang('deactivate_submit_btn'), 'class="btn btn-primary"'); ?></p>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>