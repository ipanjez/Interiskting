<!-- Default box -->


<div class="row">
    <div>
        <div class="box box-warning">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Rekapitulasi Kepatuhan Unit Kerja</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-center">
                                        <strong>Rekapitulasi Kepatuhan Unit Kerja Tahun <?= date('Y') ?></strong>
                                    </p>
                                    <canvas id="myChart1"></canvas>
                                    <?php
                                    //Inisialisasi nilai variabel awal
                                    $uk = "";
                                    $jumlah = null;
                                    foreach ($jum_patuh_thn_ini as $item) {
                                        $data_uk = $item->unit_kerja;
                                        $uk .= "'$data_uk'" . ", ";
                                        $jum = $item->total;
                                        $jumlah .= "$jum" . ", ";
                                    }
                                    ?>
                                    <script src=<?= base_url('assets/bower_components/chart.js/chart.js@2.8.js'); ?>> </script>


                                    <script>
                                        var ctx = document.getElementById('myChart1').getContext('2d');
                                        var chart = new Chart(ctx, {
                                            // The type of chart we want to create
                                            type: 'bar',
                                            // The data for our dataset
                                            data: {
                                                labels: [<?php echo $uk; ?>],
                                                datasets: [{
                                                    label: 'Kepatuhan terhadap Perundangan',
                                                    backgroundColor: '#ADD8E6',
                                                    borderColor: '##93C3D2',
                                                    data: [<?php echo $jumlah; ?>]
                                                }]
                                            },
                                            // Configuration options go here
                                            options: {
                                                scales: {
                                                    yAxes: [{
                                                        ticks: {
                                                            beginAtZero: true
                                                        }
                                                    }]
                                                }
                                            }
                                        });
                                    </script>

                                    <!-- /.chart-responsive -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-4">
                                    <p class="text-center">
                                        <strong>Data Dibanding tahun Sebelumnya</strong>
                                    </p>
                                    <div class="progress-group">
                                        <span class="progress-text">Kesekretariatan</span>
                                        <span class="progress-number"><b>160</b>/200</span>

                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        <span class="progress-text">Proses & Pengelolaan Energi</span>
                                        <span class="progress-number"><b>310</b>/400</span>

                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-red" style="width: 77%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        <span class="progress-text">Lingkungan Hidup</span>
                                        <span class="progress-number"><b>480</b>/800</span>

                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-green" style="width: 60%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        <span class="progress-text">Pemasaran PSO 2</span>
                                        <span class="progress-number"><b>250</b>/500</span>

                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-yellow" style="width: 50%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./box-body -->
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-green"><i class="fa fa-caret-left"></i> </span>
                                        <h5 class="description-header"><?php echo $stat1; ?></h5>
                                        <span class="description-text">TOTAL UNIT KERJA</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> </span>
                                        <h5 class="description-header"><?php echo $stat2; ?></h5>
                                        <span class="description-text">TOTAL KEPATUHAN</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> <?php $hasil = $stat1 / $stat3;
                                                                                                                        echo number_format($hasil * 100, 2) . '%'; ?></span>
                                        <h5 class="description-header"><?php echo $stat1; ?> / <?php echo $stat3; ?></h5>
                                        <span class="description-text">PROGRESS UNIT KERJA </span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block">
                                        <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> <?php $hasil = $stat2 / $stat4;
                                                                                                                        echo number_format($hasil * 100, 2) . '%'; ?></span>
                                        <h5 class="description-header"><?php echo $stat2; ?> / <?php echo $stat4; ?></h5>
                                        <span class="description-text">PROGRESS KEPATUHAN</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<!-- ./col -->


<div class="row">
    <div class="box box-danger">
        <div class="row">
            <div class="col-md-12">
                <div class="box  box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Rekapatulasi Laporan Monitoring Per Unit Kerja Tahun <?= date('Y') ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-3">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Bulanan </h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="chart-responsive">
                                            <canvas id="myChart2" height="200"></canvas>
                                            <?php
                                            //Inisialisasi nilai variabel awal
                                            $uk = "";
                                            $jumlah = null;
                                            foreach ($jum_bul_peruk_thn_ini as $item) {
                                                $data_uk = $item->unit_kerja;
                                                $uk .= "'$data_uk'" . ", ";
                                                $jum = $item->total;
                                                $jumlah .= "$jum" . ", ";
                                            }
                                            ?>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChart2').getContext('2d');
                                            var chart = new Chart(ctx, {
                                                // The type of chart we want to create
                                                type: 'pie',
                                                // The data for our dataset
                                                data: {
                                                    labels: [<?php echo $uk; ?>],
                                                    datasets: [{
                                                        label: 'Data Unit Kerja',
                                                        backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)', 'rgb(168, 72, 50)', 'rgb(168, 50, 68)', 'rgb(113, 168, 50)', 'rgb(50, 168, 78)', 'rgb(50, 146, 168)', 'rgb(50, 81, 168)', 'rgb(81, 50, 168)'],
                                                        borderColor: '#ffffff',
                                                        data: [<?php echo $jumlah; ?>]
                                                    }]
                                                },
                                                // Configuration options go here
                                                options: {
                                                    legend: {
                                                        display: false,
                                                        position: 'right',
                                                        align: 'end'
                                                    }
                                                }
                                            });
                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Triwulan</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="chart-responsive">
                                            <canvas id="myChart3" height="200"></canvas>
                                            <?php
                                            //Inisialisasi nilai variabel awal
                                            $uk = "";
                                            $jumlah = null;
                                            foreach ($jum_tri_peruk_thn_ini as $item) {
                                                $data_uk = $item->unit_kerja;
                                                $uk .= "'$data_uk'" . ", ";
                                                $jum = $item->total;
                                                $jumlah .= "$jum" . ", ";
                                            }
                                            ?>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChart3').getContext('2d');
                                            var chart = new Chart(ctx, {
                                                // The type of chart we want to create
                                                type: 'pie',
                                                // The data for our dataset
                                                data: {
                                                    labels: [<?php echo $uk; ?>],
                                                    datasets: [{
                                                        label: 'Data Unit Kerja',
                                                        backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)', 'rgb(168, 72, 50)', 'rgb(168, 50, 68)', 'rgb(113, 168, 50)', 'rgb(50, 168, 78)', 'rgb(50, 146, 168)', 'rgb(50, 81, 168)', 'rgb(81, 50, 168)'],
                                                        borderColor: '#ffffff',
                                                        data: [<?php echo $jumlah; ?>]
                                                    }]
                                                },
                                                // Configuration options go here
                                                options: {
                                                    legend: {
                                                        display: false,
                                                        position: 'right',
                                                        align: 'end'
                                                    }
                                                }
                                            });
                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Semester ></h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">

                                        <div class="chart-responsive">
                                            <canvas id="myChart4" height="200"></canvas>
                                            <?php
                                            //Inisialisasi nilai variabel awal
                                            $uk = "";
                                            $jumlah = null;
                                            foreach ($jum_sem_peruk_thn_ini as $item) {
                                                $data_uk = $item->unit_kerja;
                                                $uk .= "'$data_uk'" . ", ";
                                                $jum = $item->total;
                                                $jumlah .= "$jum" . ", ";
                                            }
                                            ?>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChart4').getContext('2d');
                                            var chart = new Chart(ctx, {
                                                // The type of chart we want to create
                                                type: 'pie',

                                                // The data for our dataset
                                                data: {
                                                    labels: [<?php echo $uk; ?>],
                                                    datasets: [{
                                                        label: 'Data Unit Kerja',
                                                        backgroundColor: ['rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)', 'rgb(168, 72, 50)', 'rgb(168, 50, 68)', 'rgb(113, 168, 50)', 'rgb(50, 168, 78)', 'rgb(50, 146, 168)', 'rgb(50, 81, 168)', 'rgb(81, 50, 168)', 'rgb(255, 99, 132)', ],
                                                        borderColor: '#ffffff',
                                                        data: [<?php echo $jumlah; ?>]
                                                    }]
                                                },
                                                // Configuration options go here
                                                options: {
                                                    legend: {
                                                        display: false,
                                                        position: 'right',
                                                        align: 'end'
                                                    }
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Tahunan </h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">

                                        <div class="chart-responsive">
                                            <canvas id="myChart5" height="200"></canvas>
                                            <?php
                                            //Inisialisasi nilai variabel awal
                                            $uk = "";
                                            $jumlah = null;
                                            foreach ($jum_tah_peruk_thn_ini as $item) {
                                                $data_uk = $item->unit_kerja;
                                                $uk .= "'$data_uk'" . ", ";
                                                $jum = $item->total;
                                                $jumlah .= "$jum" . ", ";
                                            }
                                            ?>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChart5').getContext('2d');
                                            var chart = new Chart(ctx, {
                                                // The type of chart we want to create
                                                type: 'pie',

                                                // The data for our dataset
                                                data: {
                                                    labels: [<?php echo $uk; ?>],
                                                    datasets: [{
                                                        label: 'Data Unit Kerja',
                                                        backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)', 'rgb(168, 72, 50)', 'rgb(168, 50, 68)', 'rgb(113, 168, 50)', 'rgb(50, 168, 78)', 'rgb(50, 146, 168)', 'rgb(50, 81, 168)', 'rgb(81, 50, 168)'],
                                                        borderColor: '#ffffff',
                                                        data: [<?php echo $jumlah; ?>]
                                                    }]
                                                },
                                                // Configuration options go here
                                                options: {
                                                    legend: {
                                                        display: false,
                                                        position: 'right',
                                                        align: 'end'
                                                    }
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">TOTAL : <?php echo $total_mon_bulanan_thn_ini; ?></h5>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">TOTAL : <?php echo $total_mon_triwulan_thn_ini; ?></h5>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">TOTAL : <?php echo $total_mon_semester_thn_ini; ?></h5>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block">
                                    <h5 class="description-header">TOTAL : <?php echo $total_mon_tahun_thn_ini; ?></h5>
                                </div>
                                <!-- /.description-block -->
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- ./box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- /.box-body -->
</div>


<!-- Main row -->
<div class="row">
    <!-- Left col kolom kiri div 1 div 2 dst -->
    <div class="col-md-8">
        <!-- MAP & BOX PANE -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Rekapitulasi Kepatuhan Unit Kerja Tahun <?= date('Y', strtotime('-1 years')) ?></h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->

            <div>
                <canvas id="myChart6"></canvas>
                <?php
                //Inisialisasi nilai variabel awal
                $uk = "";
                $jumlah = null;
                foreach ($jum_patuh_thn_lalu as $item) {
                    $data_uk = $item->unit_kerja;
                    $uk .= "'$data_uk'" . ", ";
                    $jum = $item->total;
                    $jumlah .= "$jum" . ", ";
                }
                ?>
            </div>
            <script>
                var ctx = document.getElementById('myChart6').getContext('2d');
                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'bar',
                    // The data for our dataset
                    data: {
                        labels: [<?php echo $uk; ?>],
                        datasets: [{
                            label: 'Data Unit Kerja',
                            backgroundColor: '#ADD8E6',
                            borderColor: '##93C3D2',
                            data: [<?php echo $jumlah; ?>]
                        }]
                    },
                    // Configuration options go here
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            </script>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <!-- div 2 -->

    </div>
    <!-- /.col -->

    <!-- right col kolom kanan div  1 div 2 dst -->
    <div class="col-md-4">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text"> CHARTS 9 Total Unit Kerja</span>
                <span class="info-box-number"><?php echo $stat1; ?> / <?php echo $stat3; ?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: <?php $hasil = $stat1 / $stat3;
                                                            echo number_format($hasil * 100) . '%'; ?>"></div>
                </div>
                <span class="progress-description">

                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Kepatuhan</span>
                <span class="info-box-number"><?php echo $stat2; ?> / <?php echo $stat4; ?></span> </span>
                <div class="progress">
                    <div class="progress-bar" style="width: <?php $hasil = $stat2 / $stat4;
                                                            echo number_format($hasil * 100) . '%'; ?>"></div>
                </div>
                <span class="progress-description">

                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Progress Unit Kerja</span>
                <span class="info-box-number"><?php $hasil = $stat1 / $stat3;
                                                echo number_format($hasil * 100) . '%'; ?></span>

                <div class="progress">
                    <div class="progress-bar" style="width: <?php $hasil = $stat1 / $stat3;
                                                            echo number_format($hasil * 100) . '%'; ?>"></div>
                </div>
                <span class="progress-description">
                    Data <?= date('Y') ?> dibandingkan dengan <?= date('Y', strtotime('-1 years')) ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Progress Kepatuhan</span>
                <span class="info-box-number"><?php $hasil = $stat2 / $stat4;
                                                echo number_format($hasil * 100) . '%'; ?></span>

                <div class="progress">
                    <div class="progress-bar" style="width: <?php $hasil = $stat2 / $stat4;
                                                            echo number_format($hasil * 100) . '%'; ?>"></div>
                </div>
                <span class="progress-description">
                    Data <?= date('Y') ?> dibandingkan dengan <?= date('Y', strtotime('-1 years')) ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<div class="row">
    <div class="box box-danger">
        <div class="row">
            <div class="col-md-12">
                <div class="box  box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Rekapatulasi Laporan Monitoring Per Unit Kerja Tahun <?= date('Y', strtotime('-1 years')) ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-3">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Bulanan </h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="chart-responsive">
                                            <canvas id="myChart7" height="200"></canvas>
                                            <?php
                                            //Inisialisasi nilai variabel awal
                                            $uk = "";
                                            $jumlah = null;
                                            foreach ($jum_bul_peruk_thn_lalu as $item) {
                                                $data_uk = $item->unit_kerja;
                                                $uk .= "'$data_uk'" . ", ";
                                                $jum = $item->total;
                                                $jumlah .= "$jum" . ", ";
                                            }
                                            ?>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChart7').getContext('2d');
                                            var chart = new Chart(ctx, {
                                                // The type of chart we want to create
                                                type: 'pie',
                                                // The data for our dataset
                                                data: {
                                                    labels: [<?php echo $uk; ?>],
                                                    datasets: [{
                                                        label: 'Data Unit Kerja',
                                                        backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)', 'rgb(168, 72, 50)', 'rgb(168, 50, 68)', 'rgb(113, 168, 50)', 'rgb(50, 168, 78)', 'rgb(50, 146, 168)', 'rgb(50, 81, 168)', 'rgb(81, 50, 168)'],
                                                        borderColor: '#ffffff',
                                                        data: [<?php echo $jumlah; ?>]
                                                    }]
                                                },
                                                // Configuration options go here
                                                options: {
                                                    legend: {
                                                        display: false,
                                                        position: 'right',
                                                        align: 'end'
                                                    }
                                                }
                                            });
                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Triwulan</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="chart-responsive">
                                            <canvas id="myChart8" height="200"></canvas>
                                            <?php
                                            //Inisialisasi nilai variabel awal
                                            $uk = "";
                                            $jumlah = null;
                                            foreach ($jum_tri_peruk_thn_lalu as $item) {
                                                $data_uk = $item->unit_kerja;
                                                $uk .= "'$data_uk'" . ", ";
                                                $jum = $item->total;
                                                $jumlah .= "$jum" . ", ";
                                            }
                                            ?>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChart8').getContext('2d');
                                            var chart = new Chart(ctx, {
                                                // The type of chart we want to create
                                                type: 'pie',
                                                // The data for our dataset
                                                data: {
                                                    labels: [<?php echo $uk; ?>],
                                                    datasets: [{
                                                        label: 'Data Unit Kerja',
                                                        backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)', 'rgb(168, 72, 50)', 'rgb(168, 50, 68)', 'rgb(113, 168, 50)', 'rgb(50, 168, 78)', 'rgb(50, 146, 168)', 'rgb(50, 81, 168)', 'rgb(81, 50, 168)'],
                                                        borderColor: '#ffffff',
                                                        data: [<?php echo $jumlah; ?>]
                                                    }]
                                                },
                                                // Configuration options go here
                                                options: {
                                                    legend: {
                                                        display: false,
                                                        position: 'right',
                                                        align: 'end'
                                                    }
                                                }
                                            });
                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Semester </h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">

                                        <div class="chart-responsive">
                                            <canvas id="myChart9" height="200"></canvas>
                                            <?php
                                            //Inisialisasi nilai variabel awal
                                            $uk = "";
                                            $jumlah = null;
                                            foreach ($jum_sem_peruk_thn_lalu as $item) {
                                                $data_uk = $item->unit_kerja;
                                                $uk .= "'$data_uk'" . ", ";
                                                $jum = $item->total;
                                                $jumlah .= "$jum" . ", ";
                                            }
                                            ?>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChart9').getContext('2d');
                                            var chart = new Chart(ctx, {
                                                // The type of chart we want to create
                                                type: 'pie',

                                                // The data for our dataset
                                                data: {
                                                    labels: [<?php echo $uk; ?>],
                                                    datasets: [{
                                                        label: 'Data Unit Kerja',
                                                        backgroundColor: ['rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)', 'rgb(168, 72, 50)', 'rgb(168, 50, 68)', 'rgb(113, 168, 50)', 'rgb(50, 168, 78)', 'rgb(50, 146, 168)', 'rgb(50, 81, 168)', 'rgb(81, 50, 168)', 'rgb(255, 99, 132)', ],
                                                        borderColor: '#ffffff',
                                                        data: [<?php echo $jumlah; ?>]
                                                    }]
                                                },
                                                // Configuration options go here
                                                options: {
                                                    legend: {
                                                        display: false,
                                                        position: 'right',
                                                        align: 'end'
                                                    }
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Tahunan </h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">

                                        <div class="chart-responsive">
                                            <canvas id="myChart10" height="200"></canvas>
                                            <?php
                                            //Inisialisasi nilai variabel awal
                                            $uk = "";
                                            $jumlah = null;
                                            foreach ($jum_tah_peruk_thn_lalu as $item) {
                                                $data_uk = $item->unit_kerja;
                                                $uk .= "'$data_uk'" . ", ";
                                                $jum = $item->total;
                                                $jumlah .= "$jum" . ", ";
                                            }
                                            ?>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChart10').getContext('2d');
                                            var chart = new Chart(ctx, {
                                                // The type of chart we want to create
                                                type: 'pie',

                                                // The data for our dataset
                                                data: {
                                                    labels: [<?php echo $uk; ?>],
                                                    datasets: [{
                                                        label: 'Data Unit Kerja',
                                                        backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)', 'rgb(175, 238, 239)', 'rgb(168, 72, 50)', 'rgb(168, 50, 68)', 'rgb(113, 168, 50)', 'rgb(50, 168, 78)', 'rgb(50, 146, 168)', 'rgb(50, 81, 168)', 'rgb(81, 50, 168)'],
                                                        borderColor: '#ffffff',
                                                        data: [<?php echo $jumlah; ?>]
                                                    }]
                                                },
                                                // Configuration options go here
                                                options: {
                                                    legend: {
                                                        display: false,
                                                        position: 'right',
                                                        align: 'end'
                                                    }
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">TOTAL : <?php echo $total_mon_bulanan_thn_lalu; ?></h5>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">TOTAL : <?php echo $total_mon_triwulan_thn_lalu; ?></h5>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">TOTAL : <?php echo $total_mon_semester_thn_lalu; ?></h5>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block">
                                    <h5 class="description-header">TOTAL : <?php echo $total_mon_tahun_thn_lalu; ?></h5>
                                </div>
                                <!-- /.description-block -->
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- ./box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- /.box-body -->
</div>



<div class="row">
    <div class="col-md-6">
        <!-- TABLE: LATEST ORDERS -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Unit Kerja</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable11">
                        <thead>
                            <tr>
                                <th width=""></th>
                                <th width="10px">No</th>
                                <th>Unit Kerja</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
</div>