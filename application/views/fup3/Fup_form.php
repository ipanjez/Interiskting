<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Kajian Risiko</li>
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
                                    <label for="varchar">Nama Kajian Risiko:<?php echo form_error('nama') ?></label>
                                    <input class="form-control" name="nama" id="nama"><?php echo $nama; ?>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>Risk Owner</label>
                                    <select class="selectpicker form-control" name="dir" id="dir" data-live-search="true" style="width: 100%;">
                                        <option value="">-- Pilih Direktorat --</option>
                                        <?php
                                        foreach ($dir as $value) {
                                            echo '<option value="' . $value->id . '">' . $value->nama . '</option>';
                                        }
                                        ?>
                                        <?php
                                        foreach ($parent as $key => $value) {
                                            echo "<option value=\"$value->id_menu\"" . (($value->id_menu == $parent_id) ? 'selected="selected"' : "") . " >$value->label</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">

                                    <select class="form-control" name="kom" id="kom">
                                        <option>Select Kompartemen</option>
                                    </select>
                                </div>
                                <div class="form-group">

                                    <select class="form-control" name="dep" id="dep">
                                        <option>Select Departemen</option>
                                    </select>
                                </div>

                                <br>

                                <label for="tahun">Tahun </label>
                                <div class="input-group date">

                                    <input width="250" type="text" class="form-control" name="tahun" id="datepicker3" value="<?php echo ($tahun); ?>"> <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                                <br>



                                <div class="form-group">
                                    <label for="varchar">No Surat Masuk :<?php echo form_error('no_surat_in') ?></label>
                                    <input class="form-control" name="no_surat_in" id="no_surat_in"><?php echo $no_surat_in; ?>
                                </div>
                                <br>
                                <label for="tgl_kejadian">Tanggal Masuk </label>
                                <div class="input-group date">

                                    <input width="250" type="text" class="form-control" name="tgl_in" id="datepicker1" value="<?php echo ($tgl_in); ?>"> <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                                <br>

                                <div class="form-group">
                                    <label for="varchar">No Surat Keluar:<?php echo form_error('no_surat_out') ?></label>
                                    <input class="form-control" name="no_surat_out" id="no_surat_out"><?php echo $no_surat_out; ?>
                                </div>
                                <br>
                                <label for="tgl_kejadian">Tanggal Keluar </label>
                                <div class="input-group date">

                                    <input width="250" type="text" class="form-control" name="tgl_out" id="datepicker2" value="<?php echo ($tgl_out); ?>"> <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Keterangan :<?php echo form_error('ket') ?></label>
                                    <input class="form-control" name="ket" id="ket"><?php echo $ket; ?>
                                </div>
                                <br>
                                <div class="form-group ">
                                    <label>Upload Max 10 Mb; PDf / DOC / DOCX</label>
                                    <input type="file" name="nama_file">
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="id_users" value="<?php echo $_SESSION['user_id'] ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('fup3') ?>" class="btn btn-danger">Cancel</a>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</main>