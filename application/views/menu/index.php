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

        <div class="col-sm-4">
          <div class="card">
            <div class="card-header">
              <i class="fa fa-align-justify"></i>
            </div>
            <div class="card-body">
              <div class="list-group">
                <?php foreach ($menu_type as $value) : ?>
                  <?php
                  $url = urldecode(str_replace(' ', '-', strtolower($value->type)));
                  $active = '';
                  if ($url == $this->uri->segment(3))
                    $active = ' active ';
                  ?>
                  <?php if ($value->id_menu_type != 1) : ?>
                    <a href="<?php echo site_url('cms/menu_type/' . $url . '/delete/' . $value->id_menu_type) ?>" title="Delete menu type" class="btn btn-danger " style="margin-bottom: 10px;"><i class="fa fa-trash"></i> Delete</a>
                  <?php endif ?>
                  <a href="<?php echo site_url('cms/menu/' . $url) ?>" class="list-group-item <?php echo $active ?>"><?php echo $value->type ?></a>
                  <br>
                <?php endforeach ?>
              </div>
              <div class="form-group">
                <a href="<?php echo site_url('menu_type') ?>" class="btn btn-primary btn-block btn-flat"><i class="fa fa-plus-circle"></i> Manage Menu Type</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-8">
          <div class="card">
            <div class="card-header">
              <i class="fa fa-align-justify"></i>
            </div>
            <div class="card-body">
              <?php echo form_open('cms/update_menu') ?>
              <div class="form-group">
                <a href="<?php echo site_url('menu/create') ?>" class="btn btn-dark"><i class="fa fa-plus-circle"></i> Add Menu</a>
                <button type="submit" id="saveMenu" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
              </div>
              <div id="sideMenu" class="dd">
                <?php echo $admin_menu ?>
              </div>
              <input type="hidden" name="type" value="<?php echo $this->uri->segment(3) ?>">
              <textarea name="json_menu" hidden id="tampilJsonSideMenu"></textarea>
              <?php echo form_close() ?>
            </div><!-- /.box-body -->
          </div>
        </div>
      </div>
    </div>
  </div>
</main>