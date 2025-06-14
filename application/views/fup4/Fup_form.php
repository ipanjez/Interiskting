<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Lost Event Management</li>
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
                                    <label for="varchar">Kejadian Risiko:<?php echo form_error('judul_kejadian') ?></label>
                                    <input class="form-control" name="judul_kejadian" id="judul_kejadian"><?php echo $judul_kejadian; ?>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Risiko:<?php echo form_error('risiko') ?></label>
                                    <input class="form-control" name="risiko" id="risiko"><?php echo $risiko; ?>
                                </div>

                                <label for="tgl_kejadian">Tanggal Kejadian</label>
                                <div class="input-group date">
                                    <input width="250" type="text" class="form-control" name="tgl_kejadian" id="datepicker1" value="<?php echo ($tgl_kejadian); ?>"> <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>Tempat Kejadian *) wajib dipilih</label>
                                    <select class="selectpicker form-control" name="dir" id="dir" data-live-search="true" style="width: 100%;">
                                        <option value="">-- Pilih Direktorat *) --</option>
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
                                        <option>Select Kompartemen *)</option>
                                    </select>
                                </div>
                                <div class="form-group">

                                    <select class="form-control" name="dep" id="dep">
                                        <option>Select Departemen *)</option>
                                    </select>
                                </div>
                                <br>

                                <br>
                                <div class="form-group">
                                    <label for="varchar">Kronologis Kejadian :<?php echo form_error('kronologis') ?></label>
                                    <textarea class="ckeditor" name="kronologis" id="ckeditor1"><?php echo $kronologis; ?> </textarea>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Penyebab / Analisa masalah :<?php echo form_error('penyebab') ?></label>
                                    <textarea class="ckeditor" name="penyebab" id="ckeditor2"><?php echo $penyebab; ?> </textarea>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">No item :<?php echo form_error('no_item') ?></label>
                                    <input class="form-control" name="no_item" id="ckeditor3"><?php echo $no_item; ?> </input>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Tindakan :<?php echo form_error('tindakan') ?></label>
                                    <textarea class="ckeditor" name="tindakan" id="ckeditor4"><?php echo $tindakan; ?> </textarea>

                                </div>
                                <div class="form-group">
                                    <label for="varchar">Kendala :<?php echo form_error('kendala') ?></label>
                                    <textarea class="ckeditor" name="kendala" id="ckeditor5"><?php echo $kendala; ?> </textarea>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Dampak / Akibat yang ditimbulkan :<?php echo form_error('dampak') ?></label>
                                    <textarea class="ckeditor" name="dampak" id="ckeditor6"><?php echo $dampak; ?> </textarea>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Langkah action plan di masa mendatang (Kesimpulan dan Saran) :<?php echo form_error('saran') ?></label>
                                    <textarea class="ckeditor" name="saran" id="ckeditor7"><?php echo $saran; ?> </textarea>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Ket :<?php echo form_error('ket') ?></label>
                                    <textarea class="ckeditor" name="ket" id="ckeditor8"><?php echo $ket; ?> </textarea>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="varchar">Penganggung Jawab :<?php echo form_error('penanggung_jawab') ?></label>
                                    <input class="form-control" name="penanggung_jawab" id="penanggung_jawab"><?php echo $penanggung_jawab; ?>
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