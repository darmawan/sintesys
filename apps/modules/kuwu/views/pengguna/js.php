<!-- #################################################################
Div untuk Windows Modal JQuery untuk konfirmasi aksi penghapusan data
################################################################## -->
<?php echo modalKonfirmasi(); ?>

<!-- #################################################################
Load library javascript untuk datables untuk kebutuhan daftar data
################################################################## -->
<script src="<?= base_url('assets/admin/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/datatables/DataTables-1.10.9/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/datatables/DataTables-1.10.9/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/datatables/Responsive-1.0.7/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js'); ?>"></script>
<!-- ##################### EOF Load library ##################### -->
<!-- #################################################################
Load library javascript validator untuk kebutuhan form 
################################################################## -->
<script src="<?= base_url('assets/admin/js/validator.min.js'); ?>" type="text/javascript"></script>
<!-- ##################### EOF Load library ##################### -->
<script type="text/javascript">
    var myApp = myApp || {};
    $(function () {
        $('#containerform').hide();

        $('.selectpicker').selectpicker({width: '100%'});

        $('#segarkan').on('click', function () {
            myApp.oTable.fnDraw(false);
        });

        $('#openform').on('click', function () {
            $(".page-loader").fadeIn();
            $('#table' + $('#tabel').val()).hide();
            $('#form' + $('#tabel').val()).load('pengguna/form');
            setTimeout(function () {
                $('#containerform').show();
                $(".page-loader").fadeOut();
                scrollTo();
            }, 300);
        });

        var jmlkolom = $('#kolom').val() - 1;

        myTable("#kkTable",
                {
                    ajaxSource: "pengguna/sumber/",
                    columnDefs: [
                        {targets: [0], width: "5%"},
                        {targets: [1, 2, ], width: "15%"},
                        {targets: [3, 5, 6], width: "10%"},
                        {targets: [4], render: function (data, type, row) {
                                return (data == 1) ? '<span class="label bgm-green">Aktif</span>' : '<span class="label bgm-red">not Aktif</span>';
                            }, sClass: "text-center", width: "10%"},
                        {targets: [jmlkolom], render: function (data, type, row) {
                                var idnya = row[0];
                                var kode = row[0];
                                var display = '';
<?php if ($menuinfo->ubah == 1) { ?>
                                    display = display + ' <button class="btn btn-info waves-effect" data-kode="' + kode + '" data-bind="ubah" data-tabel="pengguna" data-bhs="' + row[1] + '"><i class="zmdi zmdi-edit"></i></button>';
<?php } ?>
<?php if ($menuinfo->hapus == 1) { ?>
                                    display = display + ' <button class="btn btn-danger waves-effect" data-value="' + kode + '" data-bind="hapus" data-get="pengguna/hapus" data-tabel="pengguna" data-default="' + idnya + '" data-bhs="' + row[1] + '"><i class="zmdi zmdi-delete"></i></button>';
<?php } ?>
<?php if ($menuinfo->tambah == 1) { ?>
                                    display = display + ' <button class="btn bgm-white  waves-effect" data-kode="' + row[0] + '" data-bind="salin" data-get="pengguna/form" data-tabel="pengguna" data-bhs="' + row[1] + '"><i class="zmdi zmdi-copy"></i></button>';
<?php } ?>
                                return display;
                            }, sClass: "center p-r-0", sortable: false, width: "20%"},
                    ]
                }
        );

        $('#reset').on('click', function () {
            $('#file').val('');
            $('#label').text('Pilih File');
            $(this).addClass('hidden');
        });

        $("#btnYes").bind("click", function () {
            var link = $("#getto").val();
            $.ajax({url: link, type: "POST", data: "cid=" + $("#cid").val() + "&cod=" + $("#cod").val(), dataType: "html", beforeSend: function () {
                    if (link != "#") {
                    }
                }, success: function (html) {
                    myApp.oTable.fnDraw(false);
                    $("#myConfirm").modal("hide")
                }})
        });

        $('.selectpicker').selectpicker('refresh');

    });

</script>
