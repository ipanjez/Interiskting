<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Data Ahli MR</li>
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
            <div class="row ">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-sm table-striped " id="mytable">
                                <tr>
                                    <td>Tanggal pencatatan</td>
                                    <td>: <?php echo $waktu; ?></td>
                                </tr>
                                <tr>
                                    <td>Oleh</td>
                                    <td>: <?php echo $npk; ?></td>
                                </tr>
                                <tr>
                                    <td>Unit Kerja</td>
                                    <td>: <?php echo $unit_kerja; ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Update Terakhir</td>
                                    <td>: <?php echo $time_update; ?></td>
                                </tr>
                                <tr>
                                    <td>Update Oleh</td>
                                    <td>: <?php echo $npk_update; ?></td>
                                </tr>
                                <tr>
                                    <td>nama</td>
                                    <td>: <?php echo $nama; ?></td>
                                </tr>

                                <tr>
                                    <td>Expired</td>
                                    <td>: <?php echo $tgl_kejadian; ?></td>
                                </tr>
                                <tr>
                                    <td>Unit Kerja</td>
                                    <td>: <?php echo $tempat_kejadian; ?></td>
                                </tr>

                                <tr>
                                    <td>Keterangan :</td>
                                    <td><?php echo $ket; ?></td>
                                </tr>
                                <tr>
                                    <td>Download Sertifikat</td>
                                    <td>: <a href="<?php echo base_url(); ?>/uploads/<?php echo $nama_file; ?>" target=_blank><?php echo $nama_file; ?></a></td>
                                </tr>
                                <tr>
                                    <td><a href="<?php echo site_url('fup') ?>" class="btn bg-danger">Kembali</a></td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <strong>Catatan</strong>
                        </div>
                        <div class="card-body">
                            <div class="box-body" style="min-height: 150px">
                                <div class="chart" id="comments">
                                    <?php
                                    foreach ($commentfup as $value) {
                                        print "<li>";


                                        if ($value->keterangan == 'aktif') {
                                            print "Status : " . "<a class='badge badge-pill badge-primary'>Aktif</a>" .  "<br>";
                                        } else if ($value->keterangan == 'inaktif') {
                                            print "Status : " . "<a class='badge badge-pill badge-danger'>Inaktif</a>" .  "<br>";
                                        } else {
                                            print "Status : " . "<a class='badge badge-pill badge-secondary'>Belum Monitoring</a>" .  "<br>";
                                        };
                                        print $value->catatan . " ";
                                        // print $this->util->format_sqldate_to_fin($value->waktu);
                                        print str_repeat("&nbsp;", 3);
                                        print "( Oleh : " . $value->first_name . " - ";
                                        // print str_repeat("&nbsp;", 3);
                                        print $value->unit_kerja . " - ";
                                        print $value->waktu . " )";
                                        if ($_SESSION['user_id'] === $value->id_users) {
                                            print "<a href=" . site_url() . "commentfup/delete/" . $value->id . "/" . $id . ">" . " Hapus" . "</a>";
                                        };
                                        print "</li>";
                                    } ?>
                                    <br />
                                    <br />
                                    <br />
                                    <label for="komen">Catatan</label>
                                    <form action="<?= site_url('commentfup') ?>" method="post">
                                        <textarea rows="2" cols="40" name="catatan"></textarea>
                                        <input type="hidden" name="id_fup" value="<?php echo $id ?>" />
                                        <input type="hidden" name="id_users" value="<?php echo $_SESSION['user_id'] ?>" />
                                        <br>
                                        <br>
                                        <br>
                                        <div class="form-group">
                                            <label><input type="radio" name="keterangan" value="aktif">Aktif</label>
                                            <label><input type="radio" name="keterangan" value="inaktif">Inaktif</label>
                                        </div>
                                        <br> <br>
                                        <button type="submit" class="btn btn-primary">Catat</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <div class="row ">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <h1>Upload Evidence PDU</h1>
                            <form method="POST" action="<?php echo base_url('commentpdu') ?>" enctype="multipart/form-data">
                                <input type="hidden" name="id_fuppdu" value="<?php echo $id ?>" />

                                <input type="file" name="file">
                                <button type="submit">Upload</button>

                            </form>
                            <br>
                            <?php if ($this->session->flashdata('success')) : ?>
                                <div class=" alert-success">
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error')) : ?>
                                <div class="alert-danger">
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="file-list">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width='10'>No</th>
                                            <th>Nama File</th>
                                            <th>Download</th>
                                            <th>Hapus File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($commentpdu as $file) : ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo $file->nama_file; ?></td>
                                                <td><a href="<?php echo base_url(); ?>uploads/<?php echo $file->nama_file; ?>" target=_blank><?php echo $file->nama_file; ?></a></td>
                                                <td><?php if ($_SESSION['user_id'] === $file->id_users) {
                                                        print "<a href=" . site_url() . "commentpdu/delete/" . $file->id . "/" . $id . ">" . " Hapus" . "</a>";
                                                    }; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>