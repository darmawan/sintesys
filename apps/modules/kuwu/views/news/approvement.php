
<div class="container-fluid">
    <div class="page-header">
        <div class="pull-left">
            <h1>Approvement News</h1>
        </div>

    </div>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?= base_url('admin'); ?>">Dashboard</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="<?= base_url('admin/news'); ?>">List News</a>
            </li>
            <li>
                <a href="#">Approvement News</a>
            </li>
        </ul>
        <div class="close-bread">
            <a href="#">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="box-title">
                    <h3>


                    </h3>
                    <ul class="tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#t1">Editor Approval</a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#t2">Moderator Approval</a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#t3">Publisher Approval</a>
                        </li>
                    </ul>
                </div>
                <div class="box-content">
                    <div class="tab-content">
                        <div id="t1" class="tab-pane active">
                            <div class="box-content nopadding">
                                <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="EditorApproval">
                                    <thead>
                                        <tr>
                                            <th class='with-checkbox'><input type="checkbox" name="check_all" id="check_all"></th> 
                                            <th> Language </th>
                                            <th> News Title </th>
                                            <th> Status </th>
                                            <th> Date </th>
                                            <th> Option </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div id="t2" class="tab-pane">
                            <div class="box-content nopadding">
                                <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="ModeratorApproval">
                                    <thead>
                                        <tr>
                                            <th class='with-checkbox'><input type="checkbox" name="check_all" id="check_all"></th> 
                                            <th> Language </th>
                                            <th> News Title </th>
                                            <th> Status </th>
                                            <th> Date </th>
                                            <th> Option </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div id="t3" class="tab-pane">
                            <div class="box-content nopadding">
                                <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="PublisherApproval">
                                    <thead>
                                        <tr>
                                            <th class='with-checkbox'><input type="checkbox" name="check_all" id="check_all"></th> 
                                            <th> Language </th>
                                            <th> News Title </th>
                                            <th> Status </th>
                                            <th> Date </th>
                                            <th> Option </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3>
                        <i class="icon-table"></i>
                        Published News
                    </h3>
                </div>
                <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="dNews">
                        <thead>
                            <tr>
                                <th> ID </th> 
                                <th> Language </th>
                                <th> News Title </th>
                                <th> Status </th>
                                <th> Date </th>
                                <th>  </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="<?= base_url('assets/admin/css/plugins/chosen/chosen.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url() . 'assets/admin/css/plugins/datatable/TableTools.css'; ?>">
<script src="<?= base_url('assets/admin/js/plugins/chosen/chosen.jquery.min.js'); ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/jquery.dataTables.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/TableTools.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/ColReorderWithResize.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/ColVis.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/jquery.dataTables.columnFilter.js'; ?>"></script>
<script src="<?= base_url() . 'assets/admin/js/plugins/datatable/jquery.dataTables.grouping.js'; ?>"></script>
<script>
    var myApp = myApp || {};
    $(document).ready(function() {
       var opt0 = {
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sSearch": "<span>Search:</span> ",
                "sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
                "sLengthMenu": "_MENU_ <span>entries per page</span>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "getList/",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                {"bVisible": false, "aTargets": [1]},
                {"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [5]},
                {
                    "aTargets": [1],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? 'indonesia' : 'english';
                        return retStr;
                    }
                },
                {
                    "aTargets": [3],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-satgreen">Published</span>' : '<span class="label label-lightred">need approvement</span>';
                        return retStr;
                    }
                },
                {
                    "aTargets": [5],
                    "mRender": function(data, type, row) {
                        var bt = '<a href="#" id="ojkConfirm" class="btn" rel="confirmUnPubish" title="Unpublish ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="unPublish"><i class="icon-upload-alt"></i></a>';
                        return bt;
                    }
                },
//                {"bVisible": false, "aTargets": [5]},
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
            "sAjaxSource": "getList/editor",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                //{"iDataSort": 1, "aTargets": [0]},
                {"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [0, 5]},
                {
                    "aTargets": [0],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = '<input type="checkbox" name="check[]" value="' + identifier + '">';
                        return retStr;
                    }
                },
                {
                    "aTargets": [1],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? 'indonesia' : 'english';
                        return retStr;
                    }
                },
                {
                    "aTargets": [3],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-satgreen">Approved</span>' : '<span class="label label-lightred">need approvement</span>';
                        return retStr;
                    }
                },
                {
                    "aTargets": [4],
                    "mRender": function(data, type, row) {
                        var identifier = row[5];
                        var retStr = identifier;
                        return retStr;
                    }
                },    
                {
                    "aTargets": [5],
                    "mRender": function(data, type, row) {
                        var bt = '<a href="#" id="ojkConfirm" class="btn" rel="confirmUnPubish" title="Approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="approveNews/e"><i class="icon-ok"></i></a>&nbsp;';
                        bt += '<a href="#" id="ojkConfirm" class="btn" rel="confirmUnPubish" title="Cancel approving ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="unapproveNews/e"><i class="icon-remove"></i></a>&nbsp;';
                        bt += '<a href="getForm/' + row[1] + '/' + row[0] + '/e" class="btn" title="Edit"><i class="icon-edit"></i></a>';
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
        var opt2 = {
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sSearch": "<span>Search:</span> ",
                "sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
                "sLengthMenu": "_MENU_ <span>entries per page</span>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "getList/moderator",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                //{"iDataSort": 1, "aTargets": [0]},
                {"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [0, 5]},
                {
                    "aTargets": [0],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = '<input type="checkbox" name="check[]" value="' + identifier + '">';
                        return retStr;
                    }
                },
                {
                    "aTargets": [1],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? 'indonesia' : 'english';
                        return retStr;
                    }
                },
                {
                    "aTargets": [3],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-satgreen">Approved</span>' : '<span class="label label-lightred">need approvement</span>';
                        return retStr;
                    }
                },
                {
                    "aTargets": [4],
                    "mRender": function(data, type, row) {
                        var identifier = row[5];
                        var retStr = identifier;
                        return retStr;
                    }
                },    
                {
                    "aTargets": [5],
                    "mRender": function(data, type, row) {
                        var bt = '<a href="#" id="ojkConfirm" class="btn" rel="confirmUnPubish" title="Approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="approveNews/m"><i class="icon-ok"></i></a>&nbsp;';
                        bt += '<a href="#" id="ojkConfirm" class="btn" rel="confirmUnPubish" title="Cancel approving ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="unapproveNews/m"><i class="icon-remove"></i></a>&nbsp;';
                        bt += '<a href="news/getForm/' + row[1] + '/' + row[0] + '/e" class="btn" title="Edit"><i class="icon-edit"></i></a>';
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
        }
        var opt3 = {
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sSearch": "<span>Search:</span> ",
                "sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
                "sLengthMenu": "_MENU_ <span>entries per page</span>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "getList/publisher",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"sType": "numeric", "aTargets": [0, 1]},
                //{"iDataSort": 1, "aTargets": [0]},
                {"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [0, 5]},
                {
                    "aTargets": [0],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = '<input type="checkbox" name="check[]" value="' + identifier + '">';
                        return retStr;
                    }
                },
                {
                    "aTargets": [1],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? 'indonesia' : 'english';
                        return retStr;
                    }
                },
                {
                    "aTargets": [3],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-satgreen">Publish</span>' : '<span class="label label-lightred">not Publish</span>';
                        return retStr;
                    }
                },
                {
                    "aTargets": [4],
                    "mRender": function(data, type, row) {
                        var identifier = row[5];
                        var retStr = identifier;
                        return retStr;
                    }
                },    
                {
                    "aTargets": [5],
                    "mRender": function(data, type, row) {
                        var bt = '<a href="#" id="ojkConfirm" class="btn" rel="confirmUnPubish" title="Approve ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="approveNews/p"><i class="icon-ok"></i></a>&nbsp;';
                        bt += '<a href="#" id="ojkConfirm" class="btn" rel="confirmUnPubish" title="Cancel approving ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="unapproveNews/p"><i class="icon-remove"></i></a>&nbsp;';
                        bt += '<a href="news/getForm/' + row[1] + '/' + row[0] + '/e" class="btn" title="Edit"><i class="icon-edit"></i></a>';
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
        }
        myApp.oTable = $('#dNews').dataTable(opt0);
        myApp.oTable = $('#EditorApproval').dataTable(opt1);
        myApp.oTable = $('#ModeratorApproval').dataTable(opt2);
        myApp.oTable = $('#PublisherApproval').dataTable(opt3);
        //clearconsole();
        $('.dataTables_filter input').attr("placeholder", "Search here...");
        $(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
            disable_search_threshold: 9999999
        });


        
        $('.table').on('click', '.btn', function(e) {
            var aid = $(this).attr('data-value');
            var alg = $(this).attr('data-default');
            var msg = $(this).attr('title');
            var gto = $(this).attr('data-get');
            var fn = $('#'+ $(this).attr('rel'));
            if($(this).attr('rel')=='confirmUnPubish') {
                $('#ucid').val(aid);
                $('#ulangid').val(alg);
                $('#ugetto').val(gto);
                $('.umsgojk1').html(msg);
            }else{
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
                data: "cid=" + $('#ucid').val() + "&langid=" + $('#ulangid').val(),
                dataType: "html",
                beforeSend: function() {
                    if (link != '#') {
                    }
                },
                success: function(html) {
                    if (link != '#') {
                        var $lmTable1 = $("#dNews").dataTable( { bRetrieve : true } );
                        var $lmTable2 = $("#EditorApproval").dataTable( { bRetrieve : true } );
                        var $lmTable3 = $("#ModeratorApproval").dataTable( { bRetrieve : true } );
                        var $lmTable4 = $("#PublisherApproval").dataTable( { bRetrieve : true } );
                        $lmTable1.fnDraw();
                        $lmTable2.fnDraw();
                        $lmTable3.fnDraw();
                        $lmTable4.fnDraw();
                        //myApp.oTable.fnDraw(false); 
                    }
                }
            })
        });
//        $('#aepYes').on('click', function() {
//            var link = $('#getto').val();
//            $.ajax({
//                url: link,
//                type: "POST",
//                data: "cid=" + $('#cid').val() + "&langid=" + $('#langid').val(),
//                dataType: "html",
//                beforeSend: function() {
//                    if (link != '#') {
//                    }
//                },
//                success: function(html) {
//                    if (link != '#') {
//                        myApp.oTable.fnDraw(false);
//                        //updatePage('#tab1',html); 
//                    }
//                }
//            })
//        })
    });
</script>