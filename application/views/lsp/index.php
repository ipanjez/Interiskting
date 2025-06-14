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



      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-align-justify"></i>
          </div>
          <div class="card-body">
            <?php echo form_open('cms/update_menu') ?>
            <div class="form-group">
              <a href="<?php echo site_url('menulsp/create') ?>" class="btn btn-dark"><i class="fa fa-plus-circle"></i> Add Lokasi Sampling Point</a>
              <button type="submit" id="saveMenu" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
            </div>
            <div id="sideMenu" class="dd">
              <?php echo $admin_lsp2 ?>
            </div>
            <input type="hidden" name="type" value="<?php echo $this->uri->segment(3) ?>">
            <textarea name="json_menu" hidden id="tampilJsonSideMenu"></textarea>
            <?php echo form_close() ?>
          </div><!-- /.box-body -->
        </div>
      </div>

    </div>
  </div>
</main>