<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">###</li>
        <li class="breadcrumb-item active">No Item</li>
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
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i>
            </div>
            <div class="card-body">
                <form id="myform" method="post" onsubmit="return false">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-xs-12 col-md-4">
                            <?php echo anchor(site_url('item/create'), '<i class="fa fa-plus"></i> Create', 'class="btn btn-primary"'); ?>
                        </div>
                        <div class="col-xs-12 col-md-4 text-center">
                            <div style="margin-top: 4px" id="message">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4 text-right">
                            <?php echo anchor(site_url('item/excel'), '<i class="fa fa-file-excel"></i> Excel', 'class="btn btn-success"'); ?>
                        </div>
                    </div>

                    <table class="table table-responsive-sm table-striped " id="mytable">
                        <thead>
                            <tr>
                                <th></th>
                                <th width="10px">No</th>
                                <th>Departemen</th>
                                <th>Bagian</th>
                                <th>Seksi</th>
                                <th>No Item</th>
                                <th>Nama Item</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    <button class="btn btn-danger col-xs-12 col-md-2 text-left" type="submit"><i class="fa fa-trash"></i> Hapus Data Terpilih</button>
                </form>
            </div>
        </div>
    </div>
</main>