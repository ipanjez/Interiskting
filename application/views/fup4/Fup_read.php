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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-sm table-striped " id="mytable">
                                <tr>
                                    <td width="350">Judul Kejadian Risiko</td>
                                    <td>: <?php echo $judul_kejadian; ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Kejadian</td>
                                    <td>: <?php echo $tgl_kejadian; ?></td>
                                </tr>
                                <tr>
                                    <td>Risiko</td>
                                    <td>: <?php echo $risiko; ?></td>
                                </tr>
                                <tr>
                                    <td>Tempat Kejadian</td>
                                    <td>: <?php echo $tempat_kejadian; ?></td>
                                </tr>
                                <tr>
                                    <td>Kronologis Kejadian</td>
                                    <td>: <?php echo $kronologis; ?></td>
                                </tr>
                                <tr>
                                    <td>Penyebab / Analisa Masalah</td>
                                    <td>: <?php echo $penyebab; ?></td>
                                </tr>
                                <tr>
                                    <td>No Item</td>
                                    <td>: <?php echo $no_item; ?></td>
                                </tr>
                                <tr>
                                    <td>Tindakan</td>
                                    <td>: <?php echo $tindakan; ?></td>
                                </tr>
                                <tr>
                                    <td>Kendala</td>
                                    <td>: <?php echo $kendala; ?></td>
                                </tr>
                                <tr>
                                    <td>Dampak / Akibat yabg Ditimbulkan</td>
                                    <td>: <?php echo $dampak; ?></td>
                                </tr>
                                <tr>
                                    <td>Langkah Action plan di masa mendatang (Kesimpulan dan Saran)</td>
                                    <td>: <?php echo $saran; ?></td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>: <?php echo $ket; ?></td>
                                </tr>
                                <tr>
                                    <td>Penganggung Jawab</td>
                                    <td>: <?php echo $penanggung_jawab; ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal pencatatan</td>
                                    <td>: <?php echo $waktu; ?></td>
                                </tr>
                                <tr>
                                    <td>Oleh</td>
                                    <td>: <?php echo $first_name; ?></td>
                                </tr>
                                <tr>
                                    <td>NPK</td>
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
                                    <td>Keterangan :</td>
                                    <td><?php echo $ket; ?></td>
                                </tr>
                                <tr>
                                    <td>Download File</td>
                                    <td>: <a href="<?php echo base_url(); ?>/uploads/<?php echo $nama_file; ?>" target=_blank><?php echo $nama_file; ?></a></td>
                                </tr>
                                <tr>
                                    <td><a href="<?php echo site_url('fup4') ?>" class="btn bg-danger">Kembali</a></td>
                                </tr>

                            </table>
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
                            <h1>Status</h1>
                            <br>


                            <div class="chart" id="comments">
                                <?php
                                foreach ($commentfup4 as $value) {
                                    print "<li>";


                                    if ($value->keterangan == 'inprogress') {
                                        print "Status : " . "<a class='badge badge-pill badge-primary'>In Progress</a>" .  "<br>";
                                    } else if ($value->keterangan == 'selesai') {
                                        print "Status : " . "<a class='badge badge-pill badge-danger'>Selesai</a>" .  "<br>";
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
                                        print "<a href=" . site_url() . "commentfup4/delete/" . $value->id . "/" . $id . ">" . " Hapus" . "</a>";
                                    };
                                    print "</li>";
                                } ?>
                                <br />
                                <br />
                                <br />
                                <label for="komen">Catatan</label>
                                <form action="<?= site_url('commentfup4') ?>" method="post">
                                    <textarea rows="2" cols="40" name="catatan"></textarea>
                                    <input type="hidden" name="id_fup" value="<?php echo $id ?>" />
                                    <input type="hidden" name="id_users" value="<?php echo $_SESSION['user_id'] ?>" />
                                    <br>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label><input type="radio" name="keterangan" value="inprogress">In Progress</label>
                                        <label><input type="radio" name="keterangan" value="selesai">Selesai</label>
                                    </div>
                                    <br> <br>
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
                        <h1>Upload Data penunjang Kajian Risiko</h1>
                        <form method="POST" action="<?php echo base_url('commentpdu4') ?>" enctype="multipart/form-data">
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

                                        <th>Download</th>
                                        <th>Hapus File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($commentpdu4 as $file) : ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>

                                            <td><a href="<?php echo base_url(); ?>uploads/<?php echo $file->nama_file; ?>" target=_blank><?php echo $file->nama_file; ?></a></td>
                                            <td><?php if ($_SESSION['user_id'] === $file->id_users) {
                                                    print "<a href=" . site_url() . "commentpdu4/delete/" . $file->id . "/" . $id . ">" . " Hapus" . "</a>";
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