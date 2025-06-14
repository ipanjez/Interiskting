<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Memo To File</li>
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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                                <?php if ($this->session->flashdata('error_msg')) {
                                ?>
                                    <div id="infoMessage" class="alert alert-danger"><?php echo $this->session->flashdata('error_msg');; ?></div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="varchar">Judul :<?php echo form_error('judul') ?></label>
                                    <input class="form-control" name="judul" id="judul" value="<?php echo $judul; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="varchar">Tempat :<?php echo form_error('tempat') ?></label>
                                    <input class="form-control" name="tempat" id="tempat" value="<?php echo $tempat; ?>">
                                </div>
                                <label for="tgl_kejadian">Tanggal </label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" name="tgl_kejadian" id="datepicker" value="<?php echo ($tgl_kejadian); ?>"> <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                                <div class="form-group">
                                    <label for="varchar">Daftar Hadir :<?php echo form_error('daftar_hadir') ?></label>
                                    <textarea class="ckeditor" name="daftar_hadir" id="ckeditor1"><?php echo $daftar_hadir; ?> </textarea>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Resume :<?php echo form_error('resume') ?></label>
                                    <textarea class="ckeditor" name="resume" id="ckeditor2"><?php echo $resume; ?> </textarea>
                                </div>
                                <br>
                                 <div class="form-group">
                                    <label for="varchar">PIC :<?php echo form_error('pic_narasumber') ?></label>
                                    <input class="form-control" name="pic_narasumber" id="pic_narasumber" value="<?php echo $pic_narasumber; ?>">
                                </div>
                                <div class="form-group ">
                                    <label>Upload Max 10 Mb; PDf / DOC / DOCX</label>
                                    <input type="file" name="nama_file">
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('fup2') ?>" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</main>