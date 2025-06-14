<!DOCTYPE html>
<html>

<head>
    <title>Integrated Risk Document & Monitoring</title>

    <link href="<?= base_url('assets/vendors/bootstrap/bootstrap.template.login.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/login/styles.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>Sign In</h3>

                </div>
                <div class="card-body">
                    <?php
                    if ($message != "") {
                    ?>
                        <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div>
                    <?php } ?>


                    <?php echo form_open("auth/login"); ?>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" name="identity" class="form-control" placeholder="NPK / Email" autofocus>

                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="password">
                    </div>

                    <div class="form-group">
                        <input type="submit" value="<?php echo lang('login_submit_btn') ?>" class="btn float-right login_btn">

                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center links">
                        Integrated Risk Document <br>Management System<a href="#"></a>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a href="#">TKP & MR @2021</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>