<div class="modal fade" id="confirmUnPubish" data-modal-color="bluegray" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Konfirmasi</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cid" id="ucid"><input type="hidden" name="langid" id="ulangid">
                <input type="hidden" name="getto" id="ugetto">
                <p class="umsgojk1"></p>
            </div>
            <div class="modal-footer">
                <button aria-hidden="true" data-dismiss="modal" class="btn btn-link">Tidak</button>
                <button data-dismiss="modal" class="btn btn-link" id="uYes">Ya</button>
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
<script>
    var myApp = myApp || {};
    var aSelected = [];
    var xSelected = [];
    var ySelected = [];
    var kode, rol;
    $(document).ready(function () {
        $('.masifapprove').hide();
        var opt0 = {
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
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "sumber/",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
//                {"bVisible": false, "aTargets": [1]},
                {"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [5]},
                {targets: [0], width: "50px"},
                {targets: [2], render: function (data, type, row) {
                        var m = '<img width="120" style="background-color:rgba(153, 153, 153,0.8);" src="<?php echo base_url('publik/rabmag/slider/thumb'); ?>/' + data + '">';
                        return m;
                    }, width: "180px"},
                {targets: [3], bVisible: false},
                {
                    "aTargets": [4],
                    "mRender": function (data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-success">Published</span>' : '<span class="label label-danger">need approvement</span>';
                        return retStr;
                    }, width: "120px", sClass: "text-center"
                },
//                {targets: [4], width: "70px"},
                {
                    "aTargets": [6],
                    "mRender": function (data, type, row) {
                        var bt = '<a href="#" id="ojkConfirm" class="btn btn-danger" rel="confirmUnPubish" title="Unpublish ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="unPublish"><i class="zmdi zmdi-close"></i></a>';
                        return bt;
                    }, width: "50px"
                },
//                {"bVisible": false, "aTargets": [5]},
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
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "sumber/editor",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                {targets: [3], bVisible: false},
                {
                    "aTargets": [0],
                    "mRender": function (data, type, row) {
                        var identifier = data;
                        var retStr = '<input type="checkbox" name="check[]" value="' + identifier + '" class="selek"><input type="hidden" value="' + row[1] + '">';
                        return retStr;
                    }, "bSortable": false, width: "50px"
                },
                {targets: [2], render: function (data, type, row) {
                        var m = '<img width="120" style="background-color:rgba(153, 153, 153,0.8);" src="<?php echo base_url('publik/rabmag/slider/thumb'); ?>/' + data + '">';
                        return m;
                    }, width: "180px"},
                {
                    "aTargets": [4],
                    "mRender": function (data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-success">Published</span>' : '<span class="label label-danger">need approvement</span>';
                        return retStr;
                    }, width: "120px", sClass: "text-center"
                },
                {
                    "aTargets": [4],
                    "mRender": function (data, type, row) {
                        var identifier = row[5];
                        var retStr = identifier;
                        return retStr;
                    }, width: "70px"
                },
                {targets: [5], width: "90px"},
                {
                    "aTargets": [6],
                    "mRender": function (data, type, row) {
                        var bt = '<a href="javascript:;" class="btn btn-primary" rel="confirmUnPubish" title="Approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="approveArtikel/e"><i class="zmdi zmdi-check"></i></a>&nbsp;';
                        bt += '<a href="javascript:;" class="btn btn-danger" rel="confirmUnPubish" title="Batalakan status approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="unapproveArtikel/e"><i class="zmdi zmdi-close"></i></a>&nbsp;';
//                        bt += '<a href="getForm/' + row[1] + '/' + row[0] + '/e" class="btn btn-default" title="Edit"><i class="zmdi zmdi-edit"></i></a>';
                        return bt;
                    }, "bSortable": false, sClass: "p-5 text-center", width: "130px"
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
            "fnRowCallback": function (nRow, aoData, iDisplayIndex) {
                if (jQuery.inArray(aoData.DT_RowId, aSelected) !== -1) {
                    var a = '#' + aoData.DT_RowId + ' input[type="checkbox"]';
                    $(nRow).addClass('row_selected').find("input").attr('checked', true);

                }
                $(nRow).on('click', function () {
                    var idRow = aoData[0];
                    var valc = $(nRow).addClass('row_selected').find("input").attr('checked');
                    var vald = $(nRow).addClass('row_selected').find('input[type="hidden"]').val();
                    if (valc == 'checked') {
                        $(nRow).removeClass('row_selected').find("input").attr('checked', false);
                    } else {
                        $(nRow).addClass('row_selected').find("input").attr('checked', true);
                    }
                    var index = (valc == 'checked') ? nRow : jQuery.inArray(aoData.DT_RowId, xSelected);
                    if (index === -1) {
                        xSelected.push(idRow);
                        ySelected.push(vald);
                    } else {
                        xSelected.splice(index, 1);
                    }
                    (xSelected.length > 0) ? $('.masifapprove').fadeIn() : $('.masifapprove').fadeOut();
                    rol = "e";
                });
            },
            "oColVis": {
                "buttonText": "Change columns <i class='icon-angle-down'></i>"
            },
        };
        var opt2 = {
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
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "sumber/moderator",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                //{"iDataSort": 1, "aTargets": [0]},
                {"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [0, 5]},
                {targets: [3], bVisible: false},
                {
                    "aTargets": [0],
                    "mRender": function (data, type, row) {
                        var identifier = data;
                        var retStr = '<input type="checkbox" name="check[]" value="' + identifier + '"><input type="hidden" value="' + row[1] + '">';
                        return retStr;
                    }, width: "50px"
                },
                {targets: [2], render: function (data, type, row) {
                        var m = '<img width="120" style="background-color:rgba(153, 153, 153,0.8);" src="<?php echo base_url('publik/rabmag/slider/thumb'); ?>/' + data + '">';
                        return m;
                    }, width: "180px"},
                {
                    targets: [4],
                    "mRender": function (data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-success">Approved</span>' : '<span class="label label-danger">need approvement</span>';
                        return retStr;
                    }, sClass: "text-center", width: "130px"
                },
                {targets: [5], width: "90px"},
                {
                    "aTargets": [6],
                    "mRender": function (data, type, row) {
                        var bt = '<a href="#" id="ojkConfirm" class="btn btn-primary" rel="confirmUnPubish" title="Approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="approveArtikel/m"><i class="zmdi zmdi-check"></i></a>&nbsp;';
                        bt += '<a href="#" id="ojkConfirm" class="btn btn-danger" rel="confirmUnPubish" title="Batalakan status approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="unapproveArtikel/m"><i class="zmdi zmdi-close"></i></a>&nbsp;';
                        return bt;
                    }, sClass: "p-5 text-center", width: "130px"
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
            "fnRowCallback": function (nRow, aoData, iDisplayIndex) {
                if (jQuery.inArray(aoData.DT_RowId, aSelected) !== -1) {
                    var a = '#' + aoData.DT_RowId + ' input[type="checkbox"]';
                    $(nRow).addClass('row_selected').find("input").attr('checked', true);

                }
                $(nRow).on('click', function () {
                    var idRow = aoData[0];
                    var valc = $(nRow).addClass('row_selected').find("input").attr('checked');
                    var vald = $(nRow).addClass('row_selected').find('input[type="hidden"]').val();
                    if (valc == 'checked') {
                        $(nRow).removeClass('row_selected').find("input").attr('checked', false);
                    } else {
                        $(nRow).addClass('row_selected').find("input").attr('checked', true);
                    }
                    var index = (valc == 'checked') ? nRow : jQuery.inArray(aoData.DT_RowId, xSelected);
                    if (index === -1) {
                        xSelected.push(idRow);
                        ySelected.push(vald);
                    } else {
                        xSelected.splice(index, 1);
                    }
                    (xSelected.length > 0) ? $('.masifapprove').fadeIn() : $('.masifapprove').fadeOut();
                    rol = "m";
                });
            },
            "oColVis": {
                "buttonText": "Change columns <i class='icon-angle-down'></i>"
            },
        }
        var opt3 = {
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
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "sumber/publisher",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                //{"iDataSort": 1, "aTargets": [0]},
                {"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [0, 5]},
                {targets: [3], bVisible: false},
                {
                    "aTargets": [0],
                    "mRender": function (data, type, row) {
                        var identifier = data;
                        var retStr = '<input type="checkbox" name="check[]" value="' + identifier + '"><input type="hidden" value="' + row[1] + '">';
                        return retStr;
                    }, width: "50px"
                },
                {targets: [2], render: function (data, type, row) {
                        var m = '<img width="120" style="background-color:rgba(153, 153, 153,0.8);" src="<?php echo base_url('publik/rabmag/slider/thumb'); ?>/' + data + '">';
                        return m;
                    }, width: "180px"},
                {
                    "aTargets": [4],
                    "mRender": function (data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-success">Publish</span>' : '<span class="label label-danger">not Publish</span>';
                        return retStr;
                    }, sClass: "text-center", width: "130px"
                },
                {targets: [5], width: "90px"},
                {
                    "aTargets": [6],
                    "mRender": function (data, type, row) {
                        var bt = '<a href="#" id="ojkConfirm" class="btn btn-primary" rel="confirmUnPubish" title="Approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="approveArtikel/p"><i class="zmdi zmdi-check"></i></a>&nbsp;';
                        bt += '<a href="#" id="ojkConfirm" class="btn btn-danger" rel="confirmUnPubish" title="Batalkan status approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="unapproveArtikel/p"><i class="zmdi zmdi-close"></i></a>&nbsp;';
//                        bt += '<a href="article/getForm/' + row[1] + '/' + row[0] + '/e" class="btn btn-default" title="Edit"><i class="zmdi zmdi-edit"></i></a>';
                        return bt;
                    }, sClass: "p-5 text-center", width: "130px"
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
            "fnRowCallback": function (nRow, aoData, iDisplayIndex) {
                if (jQuery.inArray(aoData.DT_RowId, aSelected) !== -1) {
                    var a = '#' + aoData.DT_RowId + ' input[type="checkbox"]';
                    $(nRow).addClass('row_selected').find("input").attr('checked', true);

                }
                $(nRow).on('click', function () {
                    var idRow = aoData[0];
                    var valc = $(nRow).addClass('row_selected').find("input").attr('checked');
                    var vald = $(nRow).addClass('row_selected').find('input[type="hidden"]').val();
                    if (valc == 'checked') {
                        $(nRow).removeClass('row_selected').find("input").attr('checked', false);
                    } else {
                        $(nRow).addClass('row_selected').find("input").attr('checked', true);
                    }
                    var index = (valc == 'checked') ? nRow : jQuery.inArray(aoData.DT_RowId, xSelected);
                    if (index === -1) {
                        xSelected.push(idRow);
                        ySelected.push(vald);
                    } else {
                        xSelected.splice(index, 1);
                    }
                    (xSelected.length > 0) ? $('.masifapprove').fadeIn() : $('.masifapprove').fadeOut();
                    rol = "p";
                });
            },
            "oColVis": {
                "buttonText": "Change columns <i class='icon-angle-down'></i>"
            },
        }
        myApp.oTable = $('#dArticle').dataTable(opt0);
        myApp.oTable = $('#EditorApproval').dataTable(opt1);
        myApp.oTable = $('#ModeratorApproval').dataTable(opt2);
        myApp.oTable = $('#PublisherApproval').dataTable(opt3);
        //clearconsole();
        $(".dataTables_filter input").attr("placeholder", "pencarian..").removeClass('input-sm');
        $(".dataTables_info, .dataTables_length").addClass("p-l-15");
        $(".dataTables_paginate").addClass("p-r-15 p-b-10");

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
        $('.table').on('click', ':checkbox.cekbok', function (e) {
            var id = $(this).closest('table').attr('id');
            kode = id;
            var table = $("#" + id).DataTable();
            rol = $("#" + id).attr("data-kel");
            var cells = table.column(0).nodes(),
                    state = this.checked;
            xSelected = [];
            ySelected = [];

            for (var i = 0; i < cells.length; i += 1) {
                cells[i].querySelector("input[type='checkbox']").checked = state;
                if (state == true) {
                    xSelected.push(cells[i].querySelector("input[type='checkbox']").value)
                    ySelected.push(cells[i].querySelector("input[type='hidden']").value)
                }
//                console.log(cells[i].querySelector("input[type='hidden']").value);
            }
            if (state == true) {
                $('.masifapprove').fadeIn();
            } else {
                $('input:checkbox').removeAttr('checked');
                xSelected = [];
                ySelected = [];
                $('.masifapprove').fadeOut();
            }
        })
        $('.massapprove').on('click', function () {
            var allVals = [];
            var allValsz = [];
            $('#' + kode).dataTable().$(':checked').each(function () {
                allVals.push($(this).val());
                allValsz.push($(this).next('input').val());
//                console.log($(this).next('input').val());
            });
            if (allVals.length > 0) {
                var stringifiedData1 = JSON.stringify(allVals);
                var stringifiedData2 = JSON.stringify(allValsz);
            } else {
                var stringifiedData1 = JSON.stringify(xSelected);
                var stringifiedData2 = JSON.stringify(ySelected);
                console.log(stringifiedData1);
                console.log(stringifiedData2);
            }

//            $('#' + kode).dataTable().$("input[type='hidden']").each(function () {
//                allValsz.push($(this).val());
//            });
//            console.log(JSON.stringify(xSelected));

            $.ajax({
                url: "approveAll/" + rol,
                type: "POST",
                data: {'DTO': stringifiedData1, 'DTP': stringifiedData2},
                dataType: "html",
                beforeSend: function () {

                },
                success: function (html) {
                    $('input:checkbox').removeAttr('checked');
                    var $lmTable1 = $("#dArticle").dataTable({bRetrieve: true});
                    var $lmTable2 = $("#EditorApproval").dataTable({bRetrieve: true});
                    var $lmTable3 = $("#ModeratorApproval").dataTable({bRetrieve: true});
                    var $lmTable4 = $("#PublisherApproval").dataTable({bRetrieve: true});
                    $lmTable1.fnDraw();
                    $lmTable2.fnDraw();
                    $lmTable3.fnDraw();
                    $lmTable4.fnDraw();
                    $('.masifapprove').fadeOut();
                }
            })

        })
        $('.massunapprove').on('click', function () {
            var allVals = [];
            var allValsz = [];
            $('#' + kode).dataTable().$(':checked').each(function () {
                allVals.push($(this).val());
                allValsz.push($(this).next('input').val());
            });
            if (allVals.length > 0) {
                var stringifiedData1 = JSON.stringify(allVals);
                var stringifiedData2 = JSON.stringify(allValsz);
            } else {
                var stringifiedData1 = JSON.stringify(xSelected);
                var stringifiedData2 = JSON.stringify(ySelected);
                console.log(stringifiedData1);
                console.log(stringifiedData2);
            }
            $.ajax({
                url: "unapproveAll/" + rol,
                type: "POST",
                data: {'DTO': stringifiedData1, 'DTP': stringifiedData2},
                dataType: "html",
                beforeSend: function () {

                },
                success: function (html) {
                    $('input:checkbox').removeAttr('checked');
                    var $lmTable1 = $("#dArticle").dataTable({bRetrieve: true});
                    var $lmTable2 = $("#EditorApproval").dataTable({bRetrieve: true});
                    var $lmTable3 = $("#ModeratorApproval").dataTable({bRetrieve: true});
                    var $lmTable4 = $("#PublisherApproval").dataTable({bRetrieve: true});
                    $lmTable1.fnDraw();
                    $lmTable2.fnDraw();
                    $lmTable3.fnDraw();
                    $lmTable4.fnDraw();
                    $('.masifapprove').fadeOut();
                }
            })

        })
        $('#uYes').on('click', function () {
            var link = $('#ugetto').val();
            $.ajax({
                url: link,
                type: "POST",
                data: "cid=" + $('#ucid').val() + "&langid=" + $('#ulangid').val(),
                dataType: "html",
                beforeSend: function () {

                },
                success: function (html) {
                    if (link != '#') {
                        var $lmTable1 = $("#dArticle").dataTable({bRetrieve: true});
                        var $lmTable2 = $("#EditorApproval").dataTable({bRetrieve: true});
                        var $lmTable3 = $("#ModeratorApproval").dataTable({bRetrieve: true});
                        var $lmTable4 = $("#PublisherApproval").dataTable({bRetrieve: true});
                        $lmTable1.fnDraw();
                        $lmTable2.fnDraw();
                        $lmTable3.fnDraw();
                        $lmTable4.fnDraw();
                        $('.masifapprove').fadeOut();
                        //myApp.oTable.fnDraw(false); 
                    }
                }
            })
        });

        $('.tab-nav a').click(function () {
            $('.masifapprove').hide();
            $('input:checkbox').removeAttr('checked');
        })

    });
</script>
