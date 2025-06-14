<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">###</li>
        <li class="breadcrumb-item active">Kompartemen</li>

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
                                    <label>Direktorat</label>
                                    <select class="selectpicker form-control" name="id_dir" id="id_dir" data-placeholder="Pilih Direktorat" data-live-search="true" style="width: 100%;">
                                        <option value="0">-- Pilih Direktorat --</option>
                                        <?php

                                        foreach ($dataku as $key => $value) {
                                            echo "<option value=\"$value->id\"" . (($value->id == $id_dir) ? 'selected="selected"' : "") . " >$value->nama</option>";
                                        }
                                        ?>
                                    </select>
                                </div>




                                <div class="form-group">
                                    <label for="varchar">Kompartemen :<?php echo form_error('kompartemen') ?></label>
                                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama kompartemen" value="<?php echo $nama; ?>" />
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('kompartemen') ?>" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>