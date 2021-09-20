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

//        $('.selectpicker').selectpicker({width: '100%'});

        $('#segarkan').on('click', function () {
            myApp.oTable.fnDraw(false);
        });

        $('#openform').on('click', function () {
            $(".page-loader").fadeIn();
            $('#table' + $('#tabel').val()).hide();
            $('#form' + $('#tabel').val()).load('role/form');
            setTimeout(function () {
                $('#containerform').show();
                $(".page-loader").fadeOut();
                scrollTo();
            }, 300);
        });

        var jmlkolom = $('#kolom').val() - 1;

        myTable("#kkTable",
                {
                    ajaxSource: "role/sumber/",
                    columnDefs: [
                        {bVisible: false, aTargets: [0]},
                        {aTargets: [4], mRender: function (data, type, row) {
                                var select = (data == 1) ? ' checked="checked"' : '';
                                var retStr = '<label class="checkbox checkbox-inline"><input type="checkbox" value="1" name="tambah" data-value="' + row[0] + '" data-role="' + row[6] + '" data-menu="' + row[7] + '" ' + select + '><i class="input-helper"></i></label>';
                                return (row[7] == 0) ? data : retStr;
                            }
                            , sClass: "center"
                        },
                        {aTargets: [5], mRender: function (data, type, row) {
                                var select = (data == 1) ? ' checked="checked"' : '';
                                var retStr = '<label class="checkbox checkbox-inline"><input type="checkbox" value="1" name="ubah" data-value="' + row[0] + '" data-role="' + row[6] + '" data-menu="' + row[7] + '" ' + select + '><i class="input-helper"></i></label>';
                                return retStr;
                            }
                            , sClass: "center"
                        },
                        {aTargets: [6], mRender: function (data, type, row) {
                                var select = (data == 1) ? ' checked="checked"' : '';
                                var retStr = '<label class="checkbox checkbox-inline"><input type="checkbox" value="1" name="hapus" data-value="' + row[0] + '" data-role="' + row[6] + '" data-menu="' + row[7] + '" ' + select + '><i class="input-helper"></i></label>';
                                return retStr;
                            }
                            , sClass: "center"
                        },
                        {aTargets: [jmlkolom], mRender: function (data, type, row) {
                                var idnya = row[0];
                                var kode = row[0];
                                var display = "";
<?php if ($menuinfo->ubah == 1) { ?>
                                    display = display + ' <button class="btn btn-info waves-effect" data-kode="' + kode + '" data-bind="ubah" data-tabel="role" data-bhs="' + row[1] + '"><i class="zmdi zmdi-edit"></i></button>';
<?php } ?>
<?php if ($menuinfo->hapus == 1) { ?>
                                    display = display + ' <button class="btn btn-danger waves-effect" data-value="' + kode + '" data-bind="hapus" data-get="role/hapus" data-tabel="role" data-default="' + idnya + '" data-bhs="' + row[1] + '"><i class="zmdi zmdi-delete"></i></button>';
<?php } ?>
                                if (row[0] == null) {
                                    display = '';
                                }
                                return display
                            }
                            , sClass: "text-center p-r-0"
                            , sWidth: "110px"}
                    ],
                    fnDrawCallback: function (settings) {
                        var api = this.api();
                        var rows = api.rows({page: 'current'}).nodes();
                        var last = null;
                        api.column(1, {page: 'current'}).data().each(function (group, i) {
                            if (last !== group) {
                                var s = api.column(0, {page: 'current'}).data()[i];
                                var thisRow = $(rows).eq(i).before(
                                        '<tr class="group bgm-gray" style=""><td colspan="7"> <a href="javascript:;" class="f-700 text-uppercase c-white" data-default="' + group + '" data-value="' + s + '"> <i class=zmdi zmdi-assignment-check"></i>' + group + '</a></td></tr>'
                                        );

                                var maxHeight = api.tables().settings()[0]['oScroll']['sY'].replace(/\D/g, '');
                                var newHeight = $(api.tables().containers()[0]).find(".dataTables_scrollBody").height() + $(thisRow).height()

                                if (newHeight < maxHeight) {
                                    $(api.tables().containers()[0]).find(".dataTables_scrollBody").height(newHeight)
                                }

                                last = group;
                            }
                        });

                    }
                }
        );

        $('.table').on('click', 'input:checkbox', function (e) {
            var n = $(this).attr("data-menu");
            var m = $(this).attr("data-value");
            var a = $(this).attr('name');
            $.ajax({
                url: 'role/ubahRole',
                type: "POST",
                data: "aid=" + a + "&aod=" + m + "&ord=" + n,
                success: function (html) {
//                myApp.oTable.fnDraw(false);
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

    });

</script>
