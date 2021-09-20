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
            $('#form' + $('#tabel').val()).load('berita/form');
            setTimeout(function () {
                $('#containerform').show();
                $(".page-loader").fadeOut();
                scrollTo();
            }, 300);
        });

        var jmlkolom = $('#kolom').val() - 1;

        myTable("#kkTable",
                {
                    ajaxSource: "berita/sumber/" + $('#tabel').val(),
                    columnDefs: [
                        {targets: [0], width: "45px"},
                        {targets: [1], render: function (data, type, row) {
                                return (data == 1) ? 'Indonesia' : 'Asing';
                            }},
                        {targets: [3], render: function (data, type, row) {
                                return (data == 1) ? '<span class="label bgm-green">Publish</span>' : '<span class="label bgm-red">not Publish</span>';
                            }},
                        {targets: [4, 5], width: "90px"},
                        {targets: [jmlkolom], render: function (data, type, row) {
                                var idnya = row[0];
                                var kode = row[0];
                                var display = '';
<?php if ($menuinfo->ubah == 1) { ?>
                                    display = display + ' <button class="btn btn-info waves-effect" data-kode="' + kode + '" data-bind="ubah" data-tabel="berita" data-bhs="' + row[1] + '"><i class="zmdi zmdi-edit"></i></button>';
<?php } ?>
<?php if ($menuinfo->hapus == 1) { ?>
                                    display = display + ' <button class="btn btn-danger waves-effect" data-value="' + kode + '" data-bind="hapus" data-get="berita/hapus" data-tabel="berita" data-default="' + idnya + '" data-bhs="' + row[1] + '"><i class="zmdi zmdi-delete"></i></button>';
<?php } ?>
<?php if ($menuinfo->tambah == 1) { ?>
                                    var lang = (row[1] === '1') ? 2 : 1;
                                    if (parseInt(row[6]) === 1 && parseInt(row[1]) === 1) {
                                        display = display + ' <button class="btn bgm-white  waves-effect" data-kode="' + row[0] + '" data-bind="salin" data-get="berita/form" data-tabel="berita" data-bhs="' + row[1] + '"><i class="zmdi zmdi-copy"></i></button>';
                                    } else {
                                        var display = display;
                                    }

<?php } ?>
                                return display;
                            }, sClass: "center p-r-0", sortable: false, width: "125px"},
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
