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

    (function ($) {
        $('#containerform').hide();

        $('.selectpicker').selectpicker({width: '100%'});

        $('#segarkan').on('click', function () {
            myApp.oTable.fnDraw(false);
        });

        $('#openform').on('click', function () {
            $(".page-loader").fadeIn()
            $('#form' + $('#tabel').val()).load("slider/form", function (response, status, xhr) {
                if (status == "error") {
                    var msg = "Sorry but there was an error: ";
                    alert(msg + xhr.status + " " + xhr.statusText);
                } else {
                    $('#table' + $('#tabel').val()).hide();
                    $('#containerform').show();
                     $(".page-loader").fadeOut();
                    scrollTo();
                }
            });

        });

        var jmlkolom = $('#kolom').val() - 1;

        myTable("#kkTable",
                {
                    ajaxSource: "slider/sumber/" + $('#tabel').val(),
                    columnDefs: [
                        {targets: [0], width: "45px"},
                        {targets: [2], render: function (data, type, row) {
                                var m = '<img width="120" style="background-color:rgba(153, 153, 153,0.8);" src="<?php echo base_url('publik/rabmag/slider/thumb'); ?>/' + data + '">';
                                return m;
                            }, width: "180px"},
                        {targets: [3], render: function (data, type, row) {
                                var identifier = row[3];
                                var retStr = '<input type="text" value="' + identifier + '" name="s' + row[0] + '" class="form-control input-sm" style="width: 50px;text-align: center;">';
                                return retStr;
                            }, sClass: "center p-r-0", width: "35px"
                        },
                        {
                            "aTargets": [4],
                            "mRender": function (data, type, row) {
                                var identifier = data;
                                var retStr = (identifier == '1') ? '<span class="label label-success">Published</span>' : '<span class="label label-danger">need Published</span>';
                                return retStr;
                            }, width: "120px", sClass: "text-center"
                        },
                        {targets: [5], width: "95px"},
                        {targets: [jmlkolom], render: function (data, type, row) {
                                var idnya = row[0];
                                var kode = row[0];
                                var display = '';
<?php if ($menuinfo->ubah == 1) { ?>
                                    display = display + ' <button class="btn btn-info waves-effect" data-kode="' + kode + '" data-bind="ubah" data-tabel="slider" data-bhs="' + row[6] + '"><i class="zmdi zmdi-edit"></i></button>';
<?php } ?>
<?php if ($menuinfo->hapus == 1) { ?>
                                    display = display + ' <button class="btn btn-danger waves-effect" data-value="' + kode + '" data-bind="hapus" data-get="slider/hapus" data-tabel="slider" data-default="' + idnya + '" data-bhs="' + row[2] + '"><i class="zmdi zmdi-delete"></i></button>';
<?php } ?>
                                return display;
                            }, sClass: "center p-r-0", sortable: false, width: "100px"},
                    ]
                }
        );

        $('.table').on('change', 'input', function (e) {
            var n = $(this).val();
            var a = $(this).attr('name');
            $.ajax({
                url: 'slider/reOrder',
                type: "POST",
                data: "aid=" + a.substring(1) + "&ord=" + n,
                success: function (html) {
                    myApp.oTable.fnDraw(false);
                }
            });
        });

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

    }(jQuery));

</script>
