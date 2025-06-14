<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/iconpicker/css/bootstrap-iconpicker.css" />
<style type="text/css">
  .input-group-addon {
    padding: 0px;
  }
</style>

<main class="main">
  <!-- Breadcrumb-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item">
      <a href="#">Admin</a>
    </li>
    <li class="breadcrumb-item active">Menu</li>
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
        <div class="col-sm-8">
          <div class="card">
            <div class="card-header">
              <i class="fa fa-align-justify"></i>
            </div>
            <div class="card-body">
              <form action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                  <!-- <label for="int">Sort <?php echo form_error('sort') ?></label> -->
                  <?php
                  if ($sort == "") {
                    $sort = "1";
                  }
                  if ($level == "") {
                    $level = "2";
                  }
                  ?>
                  <input type="hidden" class="form-control" name="sort" id="sort" placeholder="Sort" value="<?php echo $sort; ?>" />
                </div>
                <div class="form-group">
                  <!-- <label for="int">Level <?php echo form_error('level') ?></label> -->
                  <input type="hidden" class="form-control" name="level" id="level" placeholder="Level" value="<?php echo $level; ?>" />
                </div>
                <div class="form-group">
                  <label>Parent</label>
                  <select class="selectpicker form-control" name="parent_id" id="parent_id" data-placeholder="Select a Parent" data-live-search="true" style="width: 100%;">
                    <option value="0">-- Pilih Parent -- </option>
                    <?php
                    foreach ($parent as $key => $value) {
                      echo "<option value=\"$value->id_menu\"" . (($value->id_menu == $parent_id) ? 'selected="selected"' : "") . " >$value->label</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="varchar">Label <?php echo form_error('label') ?></label>
                  <input type="text" class="form-control" name="label" id="label" placeholder="Label" value="<?php echo $label; ?>" />
                </div>
                <div class="form-group">
                  <label for="varchar">Link <?php echo form_error('link') ?></label>
                  <input type="text" class="form-control" name="link" id="link" placeholder="Link" value="<?php echo $link; ?>" />
                </div>
                <div class="form-group">
                  <label for="varchar">Id <?php echo form_error('id') ?></label>
                  <input type="text" class="form-control" name="id" id="id" placeholder="Id" value="<?php echo $id; ?>" />
                </div>
                <div class="form-group">
                  <label>Menu Type</label>
                  <select class="form-control" name="id_menu_type" id="id_menu_type" data-placeholder="Select a Menu Type" style="width: 100%;">
                    <?php
                    foreach ($menu_type as $key => $value) {
                      echo "<option value=\"$value->id_menu_type\"" . (($value->id_menu_type == $id_menu_type) ? 'selected="selected"' : "") . " >$value->type</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Groups / Role </label>
                  <select class="selectpicker" multiple="multiple" data-placeholder="Select a Groups" style="width: 100%;" name="id_groups" id="id_groups" data-live-search="true" data-selected="1,2" onchange="getval(this)">
                    <?php
                    foreach ($groups as $key => $val) {
                      echo "<option value=\"$val->id\">$val->name</option>";
                    }
                    ?>
                  </select>
                </div>
                <input type="hidden" id="id_groupss" name="id_groupss" class="form-control"></input>
                <input type="hidden" name="id_menu" value="<?php echo $id_menu; ?>" />
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('cms/menu/side-menu') ?>" class="btn btn-danger">Cancel</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>