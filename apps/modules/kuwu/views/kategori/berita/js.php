<div class="modal fade" id="confirmUnPubish" data-modal-color="bluegray" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Konfirmasi Penghapusan</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cid" id="ucid"><input type="hidden" name="langid" id="ulangid">
                <input type="hidden" name="getto" id="ugetto">
                <p class="umsgojk1"></p>
            </div>
            <div class="modal-footer">
                <button aria-hidden="true" data-dismiss="modal" class="btn btn-link">Tidak</button>
                <button data-dismiss="modal" class="btn btn-link" id="uYes">Ya, Hapus</button>
            </div>
        </div>
    </div>    
</div>
<div class="modal fade" id="new-task" data-modal-color="bluegray" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kategori Baru</h4>
            </div>
            <div class="modal-body bgm-white">
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="zmdi zmdi-label"></i></span>
                    <div class="fg-line">
                        <input name="categorys" id="categorys" class="form-control input-lg" placeholder="nama kategori" type="text">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" id="addCatbtn">Tambahkan</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

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
    var oTable;
    var aSelected = [];
    var xSelected = [];
    var myApp = myApp || {};
    var myApp2 = myApp2 || {};
    var myApp3 = myApp3 || {};
    $(function () {
        $("#addselection").hide();
        $("#newTask, #tambahkat").on('click', function () {
            $("#new-task").modal();
        });
        var opt0 = {
            displayLength: 10,
            language: {
                loadingRecords: "Tunggu sejenak - memuat...",
                search: "<span></span> ",
                lengthMenu: "_MENU_ ",
                info: " <span>_START_</span> s/d <span>_END_</span> dari <span>_TOTAL_</span> data",
                emptyTable: "Tidak ada data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Tidak ada data",
                paginate: {
                    next: '<i class="zmdi zmdi-chevron-right"></i>',
                    previous: '<i class="zmdi zmdi-chevron-left"></i>'
                }
            },
            "pagingType": "simple",
            "lengthChange": false,
            "bLength": false,
//            "bFilter": true,
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "getKategori/",
//            "aaSorting": [[0, "asc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0]},
                {targets: [0], width: "50px"},
                {targets: [2], "bVisible": false}
            ],
            "fnServerData": function (sSource, aoData, fnRowCallback, oSettings) {
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnRowCallback
                });
            },
            "fnRowCallback": function (nRow, aoData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).on('click', function () {
                    var idRow = aoData[0];
                    var titleRow = aoData[1];
                    scrollKa($('.breadcrumb'));
                    $("#cat_id").val(idRow);
                    $("#cat_name").val(titleRow);
                    $(".ttlAAtC").html("Tambah Berita ke Kategori " + titleRow);
                    $(".ttlAAtG").html("Berita dalam Kategori " + titleRow);
                    $("#addselection").fadeIn();
                    showGroupBerita(idRow);
                });
            },
            "oColVis": {
                "buttonText": "Change columns <i class='icon-angle-down'></i>"
            },
        };
        myApp.oTable = $('#dCategory').dataTable(opt0);
        myApp3.oTable = $('#dBerita').dataTable();
        myApp2.oTable = $('#dGroupBerita').dataTable({"bLengthChange": false});
        $(".dataTables_filter input").attr("placeholder", "pencarian..").removeClass('input-sm');

        $('#xfrm').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
            } else {
                var link = 'simpanKeKategori';
                var sData = oTable.$('input').serialize();
                var stringifiedData = JSON.stringify(xSelected);
                $.ajax({
                    url: link,
                    type: "POST",
                    data: {'DTO': stringifiedData, 'cat_id': $("#cat_id").val(), 'cat_name': $("#cat_name").val()},
                    dataType: "html",
                    beforeSend: function () {
                        $("#xform").isLoading({
                            text: "Proses Simpan",
                            position: "overlay",
                            tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'
                        });
                    },
                    success: function (html) {
                        setTimeout(function () {
                            var $lmTable1 = $("#dBerita").dataTable({bRetrieve: true});
                            var $lmTable2 = $("#dGroupBerita").dataTable({bRetrieve: true});
                            $lmTable1.fnDraw();
                            $lmTable2.fnDraw();
                            if (html != '') {
                                $(".ttlAAtG").html("Berita In Category " + $("#cat_name").val());
                                showGroupBerita(html);
                                var $lmTable3 = $("#dCategory").dataTable({bRetrieve: true});
                                $lmTable3.fnDraw();
                            }
                            $("#xform").isLoading("hide");
                            scrollTo();
                            notify('Data berhasil disimpan!', 'success');
                        }, 500);
                    },
                    error: function () {
                        setTimeout(function () {
                            $("#xform").isLoading("hide");
                        }, 1000);
                    }
                });
                return false;
            }
            return false;
        });

        $('.table').on('click', '.btn', function (e) {
            var aid = $(this).attr('data-value');
            var alg = $(this).attr('data-default');
            var msg = $(this).attr('title');
            var gto = $(this).attr('data-get');
            var fn = $('#' + $(this).attr('rel'));
            if ($(this).attr('rel') == 'confirmUnPubish') {
                $('#ucid').val(aid);
                $('#ulangid').val(alg);
                $('#ugetto').val(gto);
                $('.umsgojk1').html(msg);
            } else {
                $('#cid').val(aid);
                $('#langid').val(alg);
                $('#getto').val(gto);
                $('.msgojk1').html(msg);
            }
            fn.modal();
        });
        $('#uYes').on('click', function () {
            var link = $('#ugetto').val();
            $.ajax({
                url: link,
                type: "POST",
                data: "cid=" + $('#ucid').val() + "&catid=" + $('#ulangid').val(),
                dataType: "html",
                beforeSend: function () {
                    if (link != '#') {
                    }
                },
                success: function (html) {
                    if (link != '#') {
                        var $lmTable1 = $("#dBerita").dataTable({bRetrieve: true});
                        var $lmTable2 = $("#dGroupBerita").dataTable({bRetrieve: true});
                        $lmTable1.fnDraw();
                        $lmTable2.fnDraw();
                    }
                }
            })
        });
        $("#addCatbtn").click(function () {
            $("#cat_name").val($("#categorys").val());
            $("#cat_id").val('');
            $(".ttlAAtC").html("Tambah Berita ke Kategori " + $("#categorys").val());
            showGroupBerita(0);
            $("html,body").animate({scrollTop: $('#listberita').offset().top - 100}, 300);
            $("#addselection").show();
            $("#new-task").modal("hide")
        });
        $("#segarkan").on('click', function () {
            myApp.oTable.fnDraw(false);
        });
        $("#segarkan2").on('click', function () {
            myApp2.oTable.fnDraw(false);
        });
    });
    function showGroupBerita(p) {
        var opt2 = {
            displayLength: 5,
            language: {
                loadingRecords: "Tunggu sejenak - memuat...",
                search: "<span></span> ",
                lengthMenu: "_MENU_ ",
                info: " <span>_START_</span> s/d <span>_END_</span> dari <span>_TOTAL_</span> data",
                emptyTable: "Tidak ada data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Tidak ada data",
                paginate: {
                    next: '<i class="zmdi zmdi-chevron-right"></i>',
                    previous: '<i class="zmdi zmdi-chevron-left"></i>'
                }
            },
            "pagingType": "simple",
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "getCategoryBerita/" + p,
            "aaSorting": [[0, "asc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0]},
                {"asSorting": ["asc"], "aTargets": [0]},
                {"bVisible": false, "aTargets": [0]},
                {targets: [1], width: "10%"},
                {
                    "aTargets": [3],
                    "mRender": function (data, type, row) {
                        var bt = '<a href="javascript:;" id="ojkConfirm" class="btn btn-danger" rel="confirmUnPubish" title="Hapus dari kategori untuk ' + row[2] + '" role="button" data-default="' + row[0] + '" data-value="' + row[1] + '" data-get="hapusDariKategori"><i class="zmdi zmdi-delete"></i></a>&nbsp;';
                        return bt;
                    }, sortable: false, sClass: "text-center", width: "15%"
                },
            ],
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                });
            },
            "oColVis": {
                "buttonText": "Change columns <i class='icon-angle-down'></i>"
            },
        };
        var opt1 = {
//            displayLength: 5,
            language: {
                loadingRecords: "Tunggu sejenak - memuat...",
                search: "<span></span> ",
                lengthMenu: "_MENU_ ",
                info: " <span>_START_</span> s/d <span>_END_</span> dari <span>_TOTAL_</span> data",
                emptyTable: "Tidak ada data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Tidak ada data",
                paginate: {
                    next: '<i class="zmdi zmdi-chevron-right"></i>',
                    previous: '<i class="zmdi zmdi-chevron-left"></i>'
                }
            },
            "pagingType": "simple",
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "getList/" + p,
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                {targets: [0], width: "10%"},
//                {"asSorting": ["desc"], "aTargets": [0]},
                {targets: [1], render: function (data, type, row) {
                        return (data == 1) ? 'Indonesia' : 'Asing';
                    }, width: "10%"},
                {targets: [2], width: "55%"},
                {targets: [3], render: function (data, type, row) {
                        return (data == 1) ? '<span class="label bgm-green">Publish</span>' : '<span class="label bgm-red">not Publish</span>';
                    }, width: "2%"},
                {targets: [4], width: "15%"},
                {
                    "aTargets": [5],
                    "mRender": function (data, type, row) {
                        var bt = '<input type="checkbox" name="check[]" value="' + row[0] + '">';
                        return bt;
                    }, sortable: false, sClass: "text-center", width: "10%"
                },
//                {"bVisible": false, "aTargets": [1, 3, 4]},
            ],
            "fnServerData": function (sSource, aoData, fnRowCallback, oSettings) {

                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnRowCallback
                });
            },
            "fnRowCallback": function (nRow, aoData, iDisplayIndex) {
                if (jQuery.inArray(aoData.DT_RowId, aSelected) !== -1) {
                    var a = '#' + aoData.DT_RowId + ' input[type="checkbox"]';
                    $(nRow).addClass('row_selected').find("input").attr('checked', true);

                }
                $(nRow).on('click', function () {
                    var idRow = aoData[0];
                    var valc = $(nRow).addClass('row_selected').find("input").attr('checked');

                    if (valc == 'checked') {
                        $(nRow).removeClass('row_selected').find("input").attr('checked', false);
                    } else {
                        $(nRow).addClass('row_selected').find("input").attr('checked', true);
                    }
                    var index = (valc == 'checked') ? nRow : jQuery.inArray(aoData.DT_RowId, xSelected);
                    if (index === -1) {
                        xSelected.push(idRow);
                    } else {
                        xSelected.splice(index, 1);
                    }
                });
            },
            "oColVis": {
                "buttonText": "Change columns <i class='icon-angle-down'></i>"
            },
        };

        null != myApp2.oTable && myApp2.oTable.fnDestroy();
        myApp2.oTable = $('#dGroupBerita').dataTable(opt2);
        null != myApp3.oTable && myApp3.oTable.fnDestroy();
        myApp3.oTable = $('#dBerita').dataTable(opt1);
        oTable = $('#dBerita').dataTable();
        $(".dataTables_filter input").attr("placeholder", "pencarian..").removeClass('input-sm');
    }
</script>
