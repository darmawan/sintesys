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
<script src="<?= base_url('assets/admin/vendors/formvalidation/formValidation.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/formvalidation/bootstrap.min.js'); ?>"></script>
<!-- ##################### EOF Load library ##################### -->
<script type="text/javascript">
    var myApp = myApp || {};
    var myApp2 = myApp2 || {};
    $(function () {
        $("#tablekategori").hide();
        $('#containerform').hide();

        $('.selectpicker').selectpicker({width: '100%'});

        $('#segarkan').on('click', function () {
            $("table.dataTable").resize();
            myApp.oTable.fnDraw(false);
            myApp2.oTable.fnDraw(false);
        });

        $('#openform').on('click', function () {
            $(".page-loader").fadeIn();
            $('#table' + $('#tabel').val()).hide();
            $('#form' + $('#tabel').val()).load('product/form');
            setTimeout(function () {
                $('#containerform').show();
                $(".page-loader").fadeOut();
                scrollTo();
            }, 300);
        });

        $('#openformcat').on('click', function () {
            $("#tablekategori").removeClass('fadeInUp').addClass('fadeInDown');
            $("table.dataTable").resize();
            $('#table' + $('#tabel').val()).hide();
            $('#form' + $('#tabel').val()).hide();
            $("#tablekategori").show();
        });

        $("#closeformcat").on('click', function () {
            $("#tablekategori").removeClass('fadeInDown').addClass('fadeInUp');
            $("#tablekategori").hide();
//            null != myApp2.oTable && myApp2.oTable.fnDestroy();
//            null != myApp2.oTable && myApp2.oTable.fnClearTable();
//            myApp2.oTable.fnClearTable();
//            myApp2.oTable.fnDestroy();
            $("table.dataTable").resize();
            $('#table' + $('#tabel').val()).show();
            $('#form' + $('#tabel').val()).show();
        });

        $("#cancleformcat").on('click', function () {
            $(':input', '#zfrm').val('');
            $('.selectpicker').selectpicker('refresh');
        });

        var jmlkolom = $('#kolom').val() - 1;
        myTable("#kkTable",
                {
                    ajaxSource: "product/sumber/",
                    aaSorting: [[2, "asc"]],
                    columnDefs: [
                        {bVisible: false, aTargets: [2]},
                        {targets: [0], width: "5%"},                        
                        {targets: [1], render: function (data, type, row) {
                                return (row[6] == 'induk') ? data + ' - Product Category' : data;
                            }, width: "25%"},
                        {targets: [2], width: "25%"},
                        {targets: [3, 4], width: "10%"},
                        {targets: [5], render: function (data, type, row) {
                                return (data == 1) ? '<span class="label bgm-green">Aktif</span>' : '<span class="label bgm-red">not Aktif</span>';
                            }, sClass: "text-center", width: "10%"},
                        {targets: [jmlkolom], render: function (data, type, row) {
                                var idnya = row[0];
                                var kode = row[0];
                                var display = '';
<?php if ($menuinfo->ubah == 1) { ?>
                                    display = display + ' <button class="btn btn-info waves-effect m-b-5" data-kode="' + kode + '" data-bind="ubah" data-tabel="product" data-bhs="' + kode + '"><i class="zmdi zmdi-edit"></i></button>';
<?php } ?>
<?php if ($menuinfo->hapus == 1) { ?>
                                    display = display + ' <button class="btn btn-danger waves-effect m-b-5" data-value="' + kode + '" data-bind="hapus" data-get="product/hapus" data-tabel="product" data-default="' + idnya + '" data-bhs="' + row[1] + '"><i class="zmdi zmdi-delete"></i></button>';
<?php } ?>
<?php if ($menuinfo->tambah == 1) { ?>
                                    display = display + ' <button class="btn btn-default  waves-effect m-b-5" data-kode="' + row[0] + '" data-bind="salin" data-get="product/form" data-tabel="product" data-bhs="' + row[6] + '"><i class="zmdi zmdi-copy"></i></button>';
<?php } ?>
                                return display;
                            }, sClass: "text-center p-r-0", sortable: false, width: "15%"},
                    ],
                    fnDrawCallback: function (settings) {
                        var api = this.api();
                        var rows = api.rows({page: 'current'}).nodes();
                        var last = null;
                        api.column(2, {page: 'current'}).data().each(function (group, i) {
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
                        })
                    }
                }
        );
        
        

        $('#zfrm').formValidation({
            framework: 'bootstrap',
            excluded: ':disabled'
        }).on('success.form.fv', function (e) {
            e.preventDefault();

            var $form = $(e.target),
                    fv = $(e.target).data('formValidation');
            var link = 'product/simpanDataKat';
            var sData = new FormData($(this)[0]);
            $.ajax({
                url: link,
                type: "POST",
                data: sData,
                dataType: "html",
                beforeSend: function () {
                    $(".card #zfrm").isLoading({
                        text: "Proses Simpan",
                        position: "overlay",
                        tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'
                    });
                },
                success: function (html) {
                    setTimeout(function () {
                        myApp2.oTable.fnDraw(false);
                        $(':input', '#zfrm').val('');
                        $('.selectpicker').selectpicker('refresh');
                        scrollTo();
                        $(".card #zfrm").isLoading("hide");
                    }, 500);
                },
                error: function () {
                    setTimeout(function () {
                        $(".card #zfrm").isLoading("hide");
                    }, 1000);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
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

function getTMP() {
     myTable2("#isikategori",
                {
                    ajaxSource: "product/sumberkat/",
                    columnDefs: [
                        {targets: [0], bVisible: false},
                        {targets: [1, 2], width: "40%"},
                        {targets: [3], render: function (data, type, row) {
                                var kode = row[0];
                                var display = '';
<?php if ($menuinfo->ubah == 1) { ?>
                                    display = display + ' <button class="btn btn-info waves-effect" data-kode="' + kode + '" data-bind="ubah" data-tabel="product" data-tipe="' + row[4] + '" data-nama="' + row[1] + '"><i class="zmdi zmdi-edit"></i></button>';
<?php } ?>
<?php if ($menuinfo->hapus == 1) { ?>
                                    display = display + ' <button class="btn btn-danger waves-effect" data-value="' + kode + '" data-bind="hapus" data-get="product/hapus" data-tabel="product" data-default="' + kode + '" data-bhs="' + row[1] + '"><i class="zmdi zmdi-delete"></i></button>';
<?php } ?>
                                return display;
                            }, sClass: "center p-r-0", sortable: false, width: "20%"},
                    ]
                }
        );
}
</script>
