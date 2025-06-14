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
                        <a class="btn" href="#">
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
                                          <strong>Create</strong>
                                          User
                                    </div>
                                    <div class="card-body">
                                          <div class="box-body">
                                                <p><?php echo lang('create_user_subheading'); ?></p>
                                                <?php
                                                if ($message != "") {
                                                ?>
                                                      <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div>
                                                <?php } ?>
                                                <?php echo form_open("auth/create_user"); ?>

                                                <p>
                                                      <label>Nama</label>
                                                      <?php echo form_input($first_name); ?>
                                                </p>


                                                <p>
                                                      <div class="form-group">
                                                            <label>Unit Kerja</label>
                                                            <select class="selectpicker form-control" name="unit_kerja" id="unit_kerja" data-placeholder="Select a Parent" data-live-search="true" style="width: 100%;">
                                                                  <option>-- Pilih Unit Kerja -- </option>
                                                                  <?php foreach ($unit_kerja as $key) : ?>
                                                                        <option value="<?php echo $key->departemen ?> "><?php echo $key->departemen ?></option>";
                                                                  <?php endforeach ?>

                                                            </select>
                                                      </div>
                                                </p>
                                                <p>
                                                      <?php echo lang('create_user_email_label', 'email'); ?> <br />
                                                      <?php echo form_input($email); ?>
                                                </p>

                                                <p>
                                                      <label>NPK</label>
                                                      <?php echo form_input($npk); ?>
                                                </p>

                                                <p>
                                                      <?php echo lang('create_user_password_label', 'password'); ?> <br />
                                                      <?php echo form_input($password); ?>
                                                </p>

                                                <p>
                                                      <?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?> <br />
                                                      <?php echo form_input($password_confirm); ?>
                                                </p>


                                                <p><?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="btn btn-primary"'); ?>
                                                      <a href="<?php echo site_url('user') ?>" class="btn btn-danger">Cancel</a>
                                                </p>

                                                <?php echo form_close(); ?>

                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</main>