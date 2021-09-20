
<div class="container-fluid">
    <div class="page-header">
        <div class="pull-left">
            <h1>Category Article</h1>
        </div>

    </div>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?= base_url('kuwu'); ?>">Dashboard</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="<?= base_url('kuwu/article'); ?>">Article</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="#">List Category Article</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <div class="box box-color blue box-bordered">
                <div class="box-title">
                    <h3>List Category</h3>
                    <div class="actions">
                        <a class="btn btn-mini content-addcat" href="#"><i class="icon-plus"></i></a>
                        <a class="btn btn-mini content-slideUpcat" href="#"><i class="icon-angle-down"></i></a>
                    </div>
                </div>
                <div class="box-content nopadding" id="c1">
                    <table class="table table-hover table-nomargin table-bordered " id="dCategory">
                        <thead>
                            <tr>
                                <th> ID </th> 
                                <th> Category Name </th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3 class="ttlAAtG">&nbsp;</h3>
                    <div class="actions">
<!--                        <a class="btn btn-mini content-refresh" href="#"><i class="icon-refresh"></i></a>
                        <a class="btn btn-mini content-slideUpcat" href="#"><i class="icon-angle-down"></i></a>-->
                    </div>
                </div>
                <div class="box-content nopadding"id="c2">
                    <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="dGroupArticle">
                        <thead>
                            <tr>
                                <th> ID </th> 
                                <th> Article ID </th>
                                <th> Article Title </th>
                                <th> &nbsp;&nbsp; </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <div style="height: 20px;" id="rowAdd"></div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <form id="form">
                    <input type="hidden" name="cat_id" id="cat_id">
                    <input type="hidden" name="cat_name" id="cat_name">
                    <div class="box-title">
                        <h3 class="ttlAAtC">Add Article to Category [No Category Selected]</h3>
                        <div class="actions">
                            <button class="btn btn-primary" id="addselection">Add Selection</button>
                            <a class="btn" data-toggle="modal" href="#new-task">Add New Category</a>
                        </div>
                    </div>
                    <div class="box-content nopadding" id="c3">
                        <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="dArticle">
                            <thead>
                                <tr>
                                    <th> ID </th> 
                                    <th> Language </th>
                                    <th> Article Title </th>
                                    <th> Status </th>
                                    <th> Date </th>
                                    <th>  </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- dataTables -->
<link rel="stylesheet" href="<?php echo base_url() . 'assets/admin/css/plugins/datatable/TableTools.css'; ?>">
<!-- dataTables -->
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/jquery.dataTables.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/TableTools.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/ColReorderWithResize.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/ColVis.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/jquery.dataTables.columnFilter.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/jquery.dataTables.grouping.js'; ?>"></script>
<script>
    var oTable;
    var aSelected = [];
    var xSelected = [];
    var myApp = myApp || {};
    var myApp2 = myApp2 || {};
    $("#addselection").hide();

    $(document).ready(function() {
//        content = $('body').find(".box-content#c3");
//        content.slideToggle('fast', function(){ });

        var opt0 = {
            "bFilter": false,
            "bPaginate": false,
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "kategori/getKategori/",
            "aaSorting": [[0, "asc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0]},
                {"asSorting": ["asc"], "aTargets": [0]},
            ],
            "fnServerData": function(sSource, aoData, fnRowCallback, oSettings) {
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnRowCallback
                });
            },
            "fnRowCallback": function(nRow, aoData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).on('click', function() {
                    var idRow = aoData[0];
                    var titleRow = aoData[1];
                    $("#cat_id").val(idRow);
                    $("#cat_name").val(titleRow);
                    $(".ttlAAtC").html("Add Article to Category " + titleRow);
                    $(".ttlAAtG").html("Article In Category " + titleRow);
                    $("#addselection").show();
                    showGroupArticle(idRow);
                });
            },
            "oColVis": {
                "buttonText": "Change columns <i class='icon-angle-down'></i>"
            },
        };

        myApp.oTable = $('#dCategory').dataTable(opt0);
        myApp.oTable = $('#dArticle').dataTable();
        myApp.oTable = $('#dGroupArticle').dataTable({"bLengthChange": false});

        $('.dataTables_filter input').attr("placeholder", "Search here...");
        $(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
            disable_search_threshold: 9999999
        });

        $('#form').submit(function() {
            var sData = oTable.$('input').serialize();
            var stringifiedData = JSON.stringify(xSelected);
            $.ajax({
                type: "POST",
                url: 'category/saveToCategory',
                data: {'DTO': stringifiedData, 'cat_id': $("#cat_id").val(), 'cat_name': $("#cat_name").val()},
                success: function(data)
                {
                    var $lmTable1 = $("#dArticle").dataTable({bRetrieve: true});
                    var $lmTable2 = $("#dGroupArticle").dataTable({bRetrieve: true});
                    $lmTable1.fnDraw();
                    $lmTable2.fnDraw();
                    if (data != '') {
                        $(".ttlAAtG").html("Article In Category " + $("#cat_name").val());
                        showGroupArticle(data);
                        var $lmTable3 = $("#dCategory").dataTable({bRetrieve: true});
                        $lmTable3.fnDraw();
                        //content = $('body').find(".box-content#c1,.box-content#c2");
                        //content.slideToggle('fast', function() {});
                    }
                }
            });
            return false;
        });
        $('.table').on('click', '.btn', function(e) {
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
        $('#uYes').on('click', function() {
            var link = $('#ugetto').val();
            $.ajax({
                url: link,
                type: "POST",
                data: "cid=" + $('#ucid').val() + "&catid=" + $('#ulangid').val(),
                dataType: "html",
                beforeSend: function() {
                    if (link != '#') {
                    }
                },
                success: function(html) {
                    if (link != '#') {
                        var $lmTable1 = $("#dArticle").dataTable({bRetrieve: true});
                        var $lmTable2 = $("#dGroupArticle").dataTable({bRetrieve: true});
                        $lmTable1.fnDraw();
                        $lmTable2.fnDraw();
                    }
                }
            })
        });
        $("#addCatbtn").click(function(){
            showGroupArticle(0);
            $("#addselection").show();
        });
        
    });
    function showGroupArticle(p) {
        $("#dGroupArticle").dataTable().fnDestroy();
        $("#dArticle").dataTable().fnDestroy();
        var opt2 = {
            "bLengthChange": false,
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sSearch": "<span>Search:</span> ",
                "sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
                "sLengthMenu": "_MENU_ <span>entries per page</span>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "category/getCategoryArticle/" + p,
            "aaSorting": [[0, "asc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0]},
                {"asSorting": ["asc"], "aTargets": [0]},
                {"bVisible": false, "aTargets": [0]},
                {
                    "aTargets": [3],
                    "mRender": function(data, type, row) {
                        var bt = '<a href="#" id="ojkConfirm" class="btn" rel="confirmUnPubish" title="Remove from category for ' + row[2] + '" role="button" data-default="' + row[0] + '" data-value="' + row[1] + '" data-get="category/removeFromCategory"><i class="icon-remove"></i></a>&nbsp;';
                        return bt;
                    }
                },
            ],
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
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
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sSearch": "<span>Search:</span> ",
                "sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
                "sLengthMenu": "_MENU_ <span>entries per page</span>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "category/getList/" + p,
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                {"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [5]},
                {
                    "aTargets": [5],
                    "mRender": function(data, type, row) {
                        var bt = '<input type="checkbox" name="check[]" value="' + row[0] + '">';
                        return bt;
                    }
                },
                {"bVisible": false, "aTargets": [1, 3, 4]},
            ],
            "fnServerData": function(sSource, aoData, fnRowCallback, oSettings) {

                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnRowCallback
                });
            },
            "fnRowCallback": function(nRow, aoData, iDisplayIndex) {
                if (jQuery.inArray(aoData.DT_RowId, aSelected) !== -1) {
                    var a = '#' + aoData.DT_RowId + ' input[type="checkbox"]';
                    $(nRow).addClass('row_selected').find("input").attr('checked', true);

                }
                $(nRow).on('click', function() {
                    var idRow = aoData[0];
                    var valc = $(nRow).addClass('row_selected').find("input").attr('checked');
                    
                    if(valc == 'checked') {
                        $(nRow).removeClass('row_selected').find("input").attr('checked', false);
                    }else{
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
        myApp.oTable = $('#dGroupArticle').dataTable(opt2);
        myApp.oTable = $('#dArticle').dataTable(opt1);
        oTable = $('#dArticle').dataTable();
    }
</script>