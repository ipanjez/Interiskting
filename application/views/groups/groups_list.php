<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Emergency Pond Harian</li>
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
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
        </div>
        <div class="card-body">

            <form id="myform" method="post" onsubmit="return false">

                <div class="row" style="margin-bottom: 10px">
                    <div class="col-xs-12 col-md-4">
                        <?php echo anchor(site_url('groups/create'), '<i class="fa fa-plus"></i> Create', 'class="btn bg-purple"'); ?>
                    </div>
                    <div class="col-xs-12 col-md-4 text-center">
                        <div style="margin-top: 4px" id="message">

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 text-right">
                        <?php echo anchor(site_url('groups/printdoc'), '<i class="fas fa-print"></i> Print', 'class="btn bg-maroon"'); ?>
                        <?php echo anchor(site_url('groups/excel'), '<i class="fa fa-file-excel"></i> Excel', 'class="btn btn-success"'); ?>
                        <?php echo anchor(site_url('groups/word'), '<i class="fa fa-file-word"></i> Word', 'class="btn btn-primary"'); ?>

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable" style="width:100%">
                        <thead>
                            <tr>
                                <th width=""></th>
                                <th width="10px">No</th>
                                <th>Name</th>
                                <th>Description</th>

                                <th width="80px">Action</th>
                            </tr>
                        </thead>


                    </table>
                </div>
                <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i> Hapus Data Terpilih</button>
            </form>

        </div>
    </div>

</main>