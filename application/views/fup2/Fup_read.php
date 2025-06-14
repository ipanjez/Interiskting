<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">File Upload</li>
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
                                    <td>Download File</td>
                                    <td> <a href="<?php echo base_url(); ?>/uploads/<?php echo $nama_file; ?>" target=_blank><?php echo $nama_file; ?></a></td>
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
                                    foreach ($commentfup2 as $value) {
                                        print "<li>";
                                        print $value->catatan . " ";
                                        // print $this->util->format_sqldate_to_fin($value->waktu);
                                        print str_repeat("&nbsp;", 3);
                                        print "( Oleh : " . $value->first_name . " - ";
                                        // print str_repeat("&nbsp;", 3);
                                        print $value->unit_kerja . " - ";
                                        print $value->waktu . " )";
                                        if ($_SESSION['user_id'] === $value->id_users) {
                                            print "<a href=" . site_url() . "commentfup2/delete/" . $value->id . "/" . $id . ">" . " Hapus" . "</a>";
                                        };
                                        print "</li>";
                                    } ?>
                                    <br />
                                    <br />
                                    <br />
                                    <label for="komen">Catatan</label>
                                    <form action="<?= site_url('commentfup2') ?>" method="post">
                                        <textarea rows="2" cols="40" name="catatan"></textarea>
                                        <input type="hidden" name="id_fup2" value="<?php echo $id ?>" />
                                        <input type="hidden" name="id_users" value="<?php echo $_SESSION['user_id'] ?>" />
                                        <br>
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
                            <table class="table table-responsive-sm table-striped " id="mytable">

                                <tr>
                                    <td>Judul :</td>
                                    <td><?php echo $judul; ?></td>
                                </tr>

                                <tr>
                                    <td>Tanggal Kejadian :</td>
                                    <td><?php echo $tgl_kejadian; ?></td>
                                </tr>
                                <tr>
                                    <td>Tempat :</td>
                                    <td><?php echo $tempat; ?></td>
                                </tr>
                                <tr>
                                    <td>Daftar Hadir :</td>
                                    <td><?php echo $daftar_hadir; ?></td>
                                </tr>

                                <tr>
                                    <td>Resume :</td>
                                    <td><?php echo $resume; ?></td>
                                </tr>

                                <tr>
                                    <td><a href="<?php echo site_url('fup2') ?>" class="btn bg-danger">Kembali</a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>