<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Progress Pekerjaan</li>
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
                                    <label for="varchar">Tugas:<?php echo form_error('tugas') ?></label>
                                    <input class="form-control" name="tugas" id="tugas"><?php echo $tugas; ?>
                                </div>
                                <br>

                                <div class="form-group">
                                    <label for="varchar">PIC :<?php echo form_error('pic') ?></label>
                                    <input class="form-control" name="pic" id="pic"><?php echo $pic; ?>
                                </div>
                                <br>
                                <label for="tgl_kejadian">Batas Waktu </label>
                                <div class="input-group date">

                                    <input width="250" type="text" class="form-control" name="tgl_deadline" id="datepicker1" value="<?php echo ($tgl_deadline); ?>"> <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>Unit Kerja Pemberi Mandat</label>
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
                                <div class="form-group">
                                    <label for="varchar">Resume :<?php echo form_error('resume') ?></label>
                                    <textarea class="ckeditor" name="resume" id="ckeditor1"><?php echo $resume; ?> </textarea>
                                </div>
                                <br>
                                <div class="form-group ">
                                    <label>Upload Max 10 Mb; PDf / DOC / DOCX</label>
                                    <input type="file" name="nama_file">
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="id_users" value="<?php echo $_SESSION['user_id'] ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('fup5') ?>" class="btn btn-danger">Cancel</a>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</main>