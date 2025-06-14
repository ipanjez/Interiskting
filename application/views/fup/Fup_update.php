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
                                    <label for="varchar">Nama :<?php echo form_error('nama') ?></label>
                                    <input class="form-control" name="nama" id="nama" value="<?php echo $nama; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Unit Kerja</label>
                                    <select class="selectpicker form-control" name="id_tempat_kejadian" id="id_tempat_kejadian" data-live-search="true" style="width: 100%;">
                                        <option>--Pilih Departemen--</option>

                                        <?php
                                        foreach ($dep as $key => $value) {
                                            echo "<option value=\"$value->id\"" . (($value->id == $id_tempat_kejadian) ? 'selected="selected"' : "") . " >$value->nama</option>";
                                        }
                                        ?>

                                    </select>
                                </div>

                                <label for="tgl_kejadian">Masa berlaku</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" name="tgl_kejadian" id="datepicker" value="<?php echo ($tgl_kejadian); ?>"> <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>Jenis Sertifikat</label>
                                    <select class="selectpicker form-control" name="jenis_sertifikat" id="jenis_sertifikat" data-placeholder="Pilih Jenis Sertifikat" data-live-search="true" style="width: 100%;">
                                        <option value="0">-- Pilih Jenis Sertifikat --</option>
                                        <?php
                                        foreach ($data_sertifikat as  $value) {
                                            echo "<option value=\"$value->id\"" . (($value->jenis == $jenis_sertifikat) ? 'selected="selected"' : "") . " >$value->jenis</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="varchar">No Sertifikat :<?php echo form_error('no_sertifikat') ?></label>
                                    <input class="form-control" name="no_sertifikat" id="no_sertifikat" value="<?php echo $no_sertifikat; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="varchar">Ket :<?php echo form_error('ket') ?></label>
                                    <textarea class="ckeditor" name="ket" id="ckeditor1"><?php echo $ket; ?> </textarea>
                                </div>
                                <br>

                                <div class="form-group ">
                                    <label>Upload Max 10 Mb; PDf / DOC / DOCX</label>
                                    <input type="file" name="nama_file">
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('fup') ?>" class="btn btn-danger">Cancel</a>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</main>