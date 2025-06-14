<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">###</li>
        <li class="breadcrumb-item active">No item</li>

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

                            <form action="<?php echo $action; ?>" method="post">


                                <div class="form-group">
                                    <label>Tempat Kejadian</label>
                                    <select class="selectpicker form-control" name="dir" id="dir" data-live-search="true" style="width: 100%;">
                                        <option value="">-- Pilih Direktorat --</option>
                                        <?php
                                        foreach ($dir as $value) {
                                            echo '<option value="' . $value->id . '">' . $value->nama . '</option>';
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

                                <div class="form-group">
                                    <select class="form-control" name="bag" id="bag">
                                        <option>Select Bagian</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="sek" id="sek">
                                        <option>Select Seksi</option>
                                    </select>
                                </div>

                                <br>
                                <div class="form-group">
                                    <label for="varchar">No item :<?php echo form_error('no_item') ?></label>
                                    <input type="text" class="form-control" name="no_item" id="no_item" placeholder="Nomer item" value="<?php echo $no_item; ?>" />
                                </div>

                                <div class="form-group">
                                    <label for="varchar">Nama item :<?php echo form_error('nama_item') ?></label>
                                    <input type="text" class="form-control" name="nama_item" id="nama_item" placeholder="Nama item" value="<?php echo $nama_item; ?>" />
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('item') ?>" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>