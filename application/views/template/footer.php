<aside class="aside-menu">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab">
                <i class="icon-list"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
                <i class="icon-speech"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                <i class="icon-settings"></i>
            </a>
        </li>
    </ul>

    <!-- Tab panes-->

</aside>
</div>

<footer class="app-footer">
    <div>
        <a href="https://coreui.io">TKP&MR</a>
        <span>&copy; 2022 OVHP.</span>
    </div>
    <div class="ml-auto">
        <span>Powered by</span>
        <a href="https://coreui.io">CoreUI</a>
    </div>
</footer>
<!-- Bootstrap and necessary plugins-->
<script src="<?php echo base_url(); ?>assets/vendors/jquery/js/jquery.min.js"></script>
<!-- disabled.. embedded on direct page 
  <script src="<?php echo base_url(); ?>assets/vendors/jquery/js/jquery-ui.min.js"></script>  
<script src="<?php echo base_url(); ?>assets/vendors/moment/moment.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/fullcalendar/fullcalendar.min.js"></script>
-->
<script src="<?php echo base_url(); ?>assets/vendors/popper.js/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/tooltips.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/pace-progress/js/pace.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/@coreui/coreui/js/coreui.min.js"></script>
<script src="<?= base_url('assets/vendors/bootstrap-datepicker-gijgo/js/gijgo.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendors/bootstrap-select/js/bootstrap-select.js'); ?>"></script>
<!-- DataTables -->
<script src="<?= base_url('assets/vendors/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendors/datatables.net-bs/js/dataTables.bootstrap4.js'); ?>"></script>
<script src="<?= base_url('assets/vendors/datatables/dataTables.checkboxes.js'); ?>"></script>
<!-- alertifi -->
<script src="<?= base_url('assets/plugins/alertify/alertify.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-nestable/jquery.nestable.js'); ?>"></script>
<!-- Plugins and scripts required by this view-->
<script src="<?php echo base_url(); ?>assets/vendors/chart.js/js/chart.2.8.js"></script>

<script src="<?php echo base_url(); ?>assets/vendors/@coreui/coreui-plugin-chartjs-custom-tooltips/js/custom-tooltips.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/charts.js"></script>

<script type='text/javascript' src='<?php echo base_url(); ?>assets/vendors/ckeditor4_custom/ckeditor.js'></script>
<script>
    $(document).ready(function() {
        $("#dir").change(function() {
            var url = "<?php echo site_url('fup/add_ajax_kom'); ?>/" + $(this).val();
            $('#kom').load(url);
            return false;
        })

        $("#kom").change(function() {
            var url = "<?php echo site_url('fup/add_ajax_dep'); ?>/" + $(this).val();
            $('#dep').load(url);
            return false;
        })

        $("#dep").change(function() {
            var url = "<?php echo site_url('fup/add_ajax_bag'); ?>/" + $(this).val();
            $('#bag').load(url);
            return false;
        })

        $("#bag").change(function() {
            var url = "<?php echo site_url('fup/add_ajax_sek'); ?>/" + $(this).val();
            $('#sek').load(url);
            return false;
        })

        $("#sek").change(function() {
            var url = "<?php echo site_url('fup/add_ajax_ite'); ?>/" + $(this).val();
            $('#ite').load(url);
            return false;
        })
    });
</script>
<script>
    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
</script>


<script>
    var ckeditor = CKEDITOR.replace('ckeditor');

    CKEDITOR.disableAutoInline = true;
    //  config.extraPlugins = 'autogrow';
    //config.autoGrow_minHeight = 250;
    //config.autoGrow_maxHeight = 600;
    CKEDITOR.inline('editable');
</script>



<!-- datepicker -->
<script type="text/javascript">
    $(function() {
        $('#datepicker').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            footer: true,
            modal: true,
            header: true
        });
        $('#datepicker1').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            footer: true,
            modal: true,
            header: true
        });
        $('#datepicker2').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            footer: true,
            modal: true,
            header: true
        });
        $('#datepicker3').datepicker({
            uiLibrary: 'bootstrap4',
            minViewMode: 2,
            format: 'yyyy'
        });

        //  konfirmasi untuk upoad file 
        //    $(document).ready(function() {
        //     $('input[type="file"]').change(function(e) {
        //      var fileName = e.target.files[0].name;
        //      alert('The file "' + fileName + '" has been selected.');
        //   });
        //   });
    })
</script>



<?php (isset($code_js) ? $this->load->view($code_js) : ""); ?>
</body>

</html>