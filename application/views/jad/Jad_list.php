<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Calendar</li>
        <li class="breadcrumb-item active">Jadwal</li>
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
                <div class="container">

                    <div id='calendar'></div>
                    <script src="<?php echo base_url(); ?>assets/vendors/fullcalendar/jquery.1.11.1.min.js"></script>
                </div>
            </div>

            <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Tambah Event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="error"></div>
                            <form class="form-horizontal" id="crud-form">
                                <input type="hidden" id="start">
                                <input type="hidden" id="end">
                               <div class="form-group">
                                    <label class="control-label" for="title">Aktifitas (terlihat di timeline)</label>
                                    <div>
                                        <input id="title" name="title" type="text" class="form-control input-md" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="description">Deskipsi (Terlihat di tooltip)</label>
                                    <div>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                </div>
								 <div class="form-group">
                                    <label class="control-label" for="link">Tempat / Link</label>
                                    <div>
                                        <input id="link" name="link" type="text" class="form-control input-md" />
                                    </div>
                                </div>
								 <div class="form-group">
                                    <label class="control-label" for="waktu">Waktu</label>
                                    <div>
                                        <input id="waktu" name="waktu" type="text" class="form-control input-md" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="pic">PIC</label>
                                    <div>
                                        <input id="pic" name="pic" type="text" class="form-control input-md" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="target">Target waktu</label>
                                    <div>
                                        <input id="target" name="target" type="text" class="form-control input-md" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="tls">Tindak Lanjut dan Status</label>
                                    <div>
                                        <input id="tls" name="tls" type="text" class="form-control input-md" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="color">Color</label>
                                    <div>
                                        <input id="color" name="color" class="form-control input-md" readonly="readonly" />
                                        <span class="help-block">Click to pick a color</span>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Send message</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>
    </div>
</main>