<div class="container-fluid">
    <div class="page-header">
        <div class="pull-left">
            <h1>Sub Menu <?php echo $data['rowdata']->menu_name;?></h1>
        </div>

    </div>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?= base_url('admin'); ?>">Dashboard</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="<?= base_url('admin/menu'); ?>">Menu List</a>
            </li>
            <li>
                <a href="#">Sub Menu List</a>
            </li>
        </ul>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <div class="box box-color box-bordered" id="menuParent">
                <div class="box-title">
                    <h3>
                        <i class="icon-table"></i>
                        Sub Menu <?php echo $data['rowdata']->menu_name;?>
                    </h3>
                    <div class="actions">
                        <a href="<?= base_url('admin/submenu/getForm/'.$data['rowdata']->menu_id); ?>" class="btn btn-primary"><i class="icon-plus-sign"></i> Add New</a>
                        <a href="<?= base_url('admin/submenu/getApprove/'.$data['rowdata']->menu_id); ?>" class="btn btn-primary"><i class="icon-check-sign"></i> Approvement</a>
                    </div>
                </div>
                <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="dArticle">
                        <thead>
                            <tr>
                                <th> ID </th> 
                                <th> Language </th>
                                <th> Menu Title </th>
                                <th> Ordering </th>
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
        $('#menuChild').hide();
        var parent = '<?=$data['parent'];?>';
        var opt = {
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sSearch": "<span>Search:</span> ",
                "sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
                "sLengthMenu": "_MENU_ <span>entries per page</span>"
            },
//            'sDom': "T<\"clear\">lfrtip",
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": true,
            "sAjaxSource": "../getMenu/"+parent,
            "aaSorting": [[0, "desc"]],
            "aoColumnDefs": [
                {"bVisible": false, "aTargets": [1]},
                {"sType": "numeric", "aTargets": [0, 4]},
                {"iDataSort": 1, "aTargets": [0]},
                //{"asSorting": ["desc"], "aTargets": [0]},
                {"bSortable": false, "aTargets": [1, 5]},
                {
                    "aTargets": [3],
                    "mRender": function(data, type, row) {
                        var identifier = row[3];
                        var retStr = '<input type="text" value="' + identifier + '" name="s' + row[0] + '" class="input-small span3">';
                        return retStr;
                    }
                },
                {
                    "aTargets": [4],
                    "mRender": function(data, type, row) {
                        var identifier = data;
                        var retStr = (identifier == 1) ? '<span class="label label-satgreen">Active</span>' : '<span class="label label-lightred">not Active</span>';
                        return retStr;
                    }
                },
                {
                    "aTargets": [5],
                    "mRender": function(data, type, row) {
                        var lang = (row[1] == 1) ? 2 : 1;
                        var b = ' <a href="../getForm/' + row[5] + '/' + row[0] + '/e" class="btn" title="Edit"><i class="icon-edit"></i></a>';
                        var d = ' <a href="#" id="ojkConfirm" class="btn" rel="tooltip" title="Delete ' + row[2] + '" role="button" data-default="' + row[1] + '" data-value="' + row[0] + '" data-get="../deleteMenu/" ><i class="icon-trash"></i></a>';
                        return b + d;
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
        myApp.oTable = $('#dArticle').dataTable(opt);
        //clearconsole();
        $('.dataTables_filter input').attr("placeholder", "Search here...");
        $(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
            disable_search_threshold: 9999999
        });
        //resizeContent();

        $('.table').on('click', '.btn', function(e) {
            var aid = $(this).attr('data-value');
            var alg = $(this).attr('data-default');
            var msg = $(this).attr('title');
            var gto = $(this).attr('data-get');
            $('#cid').val(aid);
            $('#langid').val(alg);
            $('#getto').val(gto);
            $('.msgojk1').html(msg);
//            if (msg == 'Sub') {
//                $('#menuParent').slideUp();
//                $('#menuChild').slideDown();
//            } else {
                if (msg !== 'Edit') {
                    $('#confirmDelete').modal();
                }
//            }
        });
        $('.table').on('change', 'input', function(e) {
            var n = $(this).val();
            var a = $(this).attr('name');
            $.ajax({
                url: '../menu/reOrderList',
                type: "POST",
                data: "aid=" + a.substring(1) + "&ord=" + n,
                success: function(html) {
                    myApp.oTable.fnDraw(false);
                }
            });
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