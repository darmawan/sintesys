<div class="container-fluid">
    <div class="page-header">
        <div class="pull-left">
            <h1>News</h1>
        </div>
    </div>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?= base_url('kuwu'); ?>">Dashboard</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="#">News</a>
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
            <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3>
                        <i class="icon-table"></i>
                        News List
                    </h3>
                    <div class="actions">
                        <a href="<?= base_url('kuwu/news/getForm'); ?>" class="btn btn-primary"><i class="icon-plus-sign"></i> Add New</a>
                        <a href="<?= base_url('kuwu/news/getApprove'); ?>" class="btn btn-primary"><i class="icon-check-sign"></i> Approvement</a>
                    </div>
                </div>
                <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="dNews">
                        <thead>
                            <tr>
                                <th> ID </th> 
                                <th> Language </th>
                                <th> News Title </th>
                                <th> Date </th>
                                <th> Status </th>
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
        var opt = {
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sSearch": "<span>Search:</span> ",
                "sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
                "sLengthMenu": "_MENU_ <span>entries per page</span>"
            },
            "bAutoWidth": true,
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "news/getNews/",
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                // {"bVisible": false, "aTargets": [0]},
                {"sType": "numeric", "aTargets": [0]},
                {"bSortable": false, "aTargets": [4]},
                {"aTargets": [0],sWidth: "25px"},
                {
                    "aTargets": [1],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier === "1") ? 'indonesia' : 'english';
                        return retStr;
                    },
                    sClass: "center",
                    sWidth: "45px"
                },
                // {"aTargets": [2],sWidth: "35%"},
                {"aTargets": [3],sWidth: "75px"},
                {
                    "aTargets": [4],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-satgreen">Publish</span>' : '<span class="label label-lightred">not Publish</span>';
                        return retStr;
                    },sWidth: "85px"
                },
                {
                    "aTargets": [5],
                    "mRender": function(data, type, row) {
                        var lang = (row[1] === '1') ? 2 : 1;
                        if(parseInt(row[6]) === 1 && parseInt(row[1]) === 1) {
                            // if(parseInt(row[6]) === 1 && parseInt(row[1]) === 2) {
                                var ok = ' <a href="news/getForm/' + lang + '/' + row[0] + '" class="btn" title="Add another language"><i class="icon-plus"></li></a>';
                            }else{
                                var ok = '';
                            // }

                        }
                        // var ok = (parseInt(row[6]) === 1 && parseInt(row[1]) === 1) ? ' <a href="news/getForm/' + lang + '/' + row[0] + '" class="btn" title="Add another language"><i class="icon-plus"></li></a>' : ((row[6] === '1' && row[1] === '2') ? '':'');
                        var bt = '<a href="news/getForm/' + row[1] + '/' + row[0] + '/e" class="btn" title="Edit"><i class="icon-edit"></i></a> ';
                        bt += '<a href="#" id="ojkConfirm" class="btn" rel="tooltip" title="Delete ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="news/removeData" ><i class="icon-trash"></i></a>';
                        return bt + ok;

                        // var btn = '';
                        // var lang = (row[1] == 1) ? 2 : 1;
                        // var ed = '<a href="news/getForm/1/' + row[0] + '/e" class="btn" title="Edit"><i class="icon-edit"></i></a>';
                        // var del = ' <a href="#" id="ojkConfirm" class="btn" rel="tooltip" title="Delete ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="news/removeData" ><i class="icon-trash"></i></a>';
<?php if ($edit == 1) { ?>
                            var btn = btn + ed;
<?php } else { ?>
                            var btn = '';
<?php } ?>
<?php if ($delete == 1) { ?>
                            var btn = btn + del;
<?php } else { ?>
                            var btn = btn;
<?php } ?>
                        return btn;
                    },sWidth: "80px"
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
        myApp.oTable = $('#dNews').dataTable(opt);
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
            $('#cid').val(aid);
            $('#langid').val(alg);
            $('#getto').val(gto);
            $('.msgojk1').html(msg);
            if (msg !== 'Edit') {
                $('#confirmDelete').modal();
            }
        });
        $('#aepYes').on('click', function() {
            var link = $('#getto').val();
            $.ajax({
                url: link,
                type: "POST",
                data: "cid=" + $('#cid').val() + "&langid=" + $('#langid').val(),
                dataType: "html",
                beforeSend: function() {
                    if (link != '#') {
                    }
                },
                success: function(html) {
                    if (link != '#') {
                        myApp.oTable.fnDraw(false);
                        //updatePage('#tab1',html); 
                    }
                }
            })
        })
    });
</script>