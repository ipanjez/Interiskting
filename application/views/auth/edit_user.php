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
              <strong>Edit</strong>
              User
            </div>
            <div class="card-body">
              <div class="box-body">
                <p><?php echo lang('edit_user_subheading'); ?></p>
                <?php
                if ($message != "") {
                ?>
                  <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div> <?php } ?>
                <?php echo form_open(uri_string()); ?>

                <div class="form-group">
                  <label>Nama </label>
                  <?php echo form_input($first_name); ?>
                </div>



                <p>
                  <div class="form-group">
                    <label>Unit Kerja </label>
                    <select class="selectpicker form-control" name="unit_kerja" id="unit_kerja" data-placeholder="Pilih Unit Kerja" data-live-search="true" style="width: 100%;">
                      <option>-- Pilih Unit Kerja -- </option>
                      <?php
                      foreach ($unit_kerja as $value) {
                        echo "<option value=\"$value->departemen\"" . (($value->departemen == $user->unit_kerja) ? 'selected="selected"' : "") . " >$value->departemen</option>";
                      }
                      ?>
                    </select>
                  </div>
                </p>
                <p>
                  <?php echo lang('create_user_email_label', 'email'); ?> <br />
                  <?php echo form_input($email); ?>
                </p>

                <div class="form-group">
                  <?php echo lang('edit_user_npk_label', 'npk'); ?> <br />
                  <?php echo form_input($npk); ?>
                </div>

                <div class="form-group">
                  <?php echo lang('edit_user_password_label', 'password'); ?> <br />
                  <?php echo form_input($password); ?>
                </div>

                <div class="form-group">
                  <?php echo lang('edit_user_password_confirm_label', 'password_confirm'); ?><br />
                  <?php echo form_input($password_confirm); ?>
                </div>
                <?php if ($this->ion_auth->is_admin()) : ?>
                  <div class="form-group">
                    <h3>Pilih sebagai :</h3>
                    <?php foreach ($groups as $group) : ?>
                      <div class="form-check">
                        <label class="col-md-3">
                          <?php
                          $gID = $group['id'];
                          $checked = null;
                          $item = null;
                          foreach ($currentGroups as $grp) {
                            if ($gID == $grp->id) {
                              $checked = ' checked="checked"';
                              break;
                            }
                          }
                          ?>
                          <input type="checkbox" name="groups[]" value="<?php echo $group['id']; ?>" <?php echo $checked; ?>>
                          <?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </label>
                      </div>
                    <?php endforeach ?>
                  </div>
                <?php endif ?>
                <?php echo form_hidden('id', $user->id); ?>
                <?php echo form_hidden($csrf); ?>



                <div class="card-footer">

                  <p><?php echo form_submit('submit', lang('edit_user_submit_btn'), 'class="btn btn-sm btn-primary" style="clear:both"'); ?>
                    <a href="<?php echo site_url('user') ?>" class="btn btn-sm btn-danger">Cancel</a>
                  </p>

                </div>


                <?php echo form_close(); ?>

              </div>





            </div>
          </div>
        </div>
        <!--/.col-->
      </div>

</main>