<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">###</li>
        <li class="breadcrumb-item active">Seksi</li>

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
                                    <label>Bagian</label>
                                    <select class="selectpicker form-control" name="id_bag" id="id_bag" data-placeholder="Pilih Bagian" data-live-search="true" style="width: 100%;">
                                        <option value="0">-- Pilih Bagian --</option>
                                        <?php

                                        foreach ($dataku as $key => $value) {
                                            echo "<option value=\"$value->id\"" . (($value->id == $id_bag) ? 'selected="selected"' : "") . " >$value->nama</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="varchar">Seksi :<?php echo form_error('seksi') ?></label>
                                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama seksi" value="<?php echo $nama; ?>" />
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('seksi') ?>" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>