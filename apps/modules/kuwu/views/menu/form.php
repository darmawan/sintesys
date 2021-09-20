<?php
if (isset($rowdata)) {
    $arey = array();
    foreach ($rowdata as $kolom => $nilai):
        $arey[$kolom] = $nilai;
    endforeach;
    $cid = ($aep == 'salin') ? '' : $arey['menu_id'];
}else {
    $cid = '';
}
?>
<form class="form-horizontal" role="form" id="xfrm"  enctype="multipart/form-data">
    <input type="hidden" name="cid" id="cid" value="<?= $cid; ?>"> 

    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">Pilih Menu Induk (opsional)</label>
        <div class="col-sm-3">
            <div class="select">
                <select class="form-control selectpicker " name="parent_id" id="parent_id" data-default="<?= (isset($arey)) ? $arey['parent_id'] : ''; ?>">
                    <option value="">Pilihan</option>
                    <?php
                    $menu = (isset($arey)) ? $arey['parent_id'] : '';
                    $q = "select * from (
                            select t1.ordering as urut_induk, t1.menu_id AS induk, t1.menu_name as menu_induk, 
                            t2.ordering as urut_anak, t2.menu_id as anak, t2.menu_name as menu_anak  
                            from " . DB_MENU . "  as t1 join " . DB_MENU . " t2 on t1.menu_id = t2.parent_id WHERE t1.is_active=1 AND t2.is_active=1 order by t1.menu_id) as aep 
                            order by urut_induk , urut_anak ";
                    $a = $this->Data_model->jalankanQuery($q, 3);
                    $s = '';
                    foreach ($a as $row) {
                        $kls = ($row->anak > 0) ? ' class="m-l-10"' : '';
                        $select = ($row->anak == $menu) ? ' selected="selected"' : '';
                        if ($s <> $row->induk) {
                            $select = ($row->induk == $menu) ? ' selected="selected"' : '';
                            echo '<option value="' . $row->induk . '" ' . $select . ' style="font-weight:bold;" class="text-uppercase c-green">' . $row->menu_induk . '</option>';
                        }
                        echo '<option value="' . $row->anak . '" ' . $select . ' class="m-l-10">' . $row->menu_anak . '</option>';
                        $s = $row->induk;
                    }
                    ?>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>                                    
    </div>
      
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">Pilih Jenis</label>
        <div class="col-sm-3">
            <div class="select">
                <select class="form-control selectpicker " required data-error="Wajib dipilih" name="type_id" id="type_id" data-default="<?= (isset($arey)) ? $arey['type_id'] : ''; ?>">
                    <option value="">Pilihan</option>
                    <?php
                    $p = (isset($arey)) ? $arey['type_id'] : '';
                    $a = $this->Data_model->jalankanQuery('SELECT * FROM ad_type where type_name <>"-" group by type_grp', 3);
                    foreach ($a as $row) {
                        $select = ($row->type_id == $p) ? ' selected="selected"' : '';
                        echo '<option value="' . $row->type_id . '" ' . $select . '>' . $row->type_name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>                                    
    </div>
    <div class="form-group" id="relatedoc">
        <label class="col-sm-2 control-label">Pilih Relasi Konten</label>
        <div class="col-sm-9">
            <div class="select">
                <select id="reference_id" name="reference_id" class="form-control selectpicker" data-live-search="true" data-default="<?= (isset($arey)) ? $arey['reference_id'] : ''; ?>">
                    <option value="0" selected="selected"></option> 
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Menu URL</label>
        <div class="col-sm-10">
            <div class="fg-line">
                <input value="<?= (isset($arey)) ? $arey['menu_url'] : ''; ?>" class="form-control" placeholder="judul" type="text" name="menu_url" id="menu_url" data-toggle="popover" data-placement="top" data-content="">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Aktif</label>
        <div class="col-sm-10">
            <div class="radio m-b-15">
                <label>
                    <input name="is_active" value="1" type="radio" <?php echo ((isset($arey) && $arey['is_active'] == 1) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Aktifkan
                </label>
            </div>
            <div class="radio m-b-15">
                <label>
                    <input name="is_active" value="0" type="radio" <?php echo ((isset($arey) && $arey['is_active'] == '' && $arey['is_active'] == 0) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    non Aktifkan
                </label>
            </div>            
            <div class="help-block with-errors"></div>
        </div>
    </div>


    <div class="p-absolute" style="top: 35px;right: 75px;">
        <button id="cancelform" type="button" class="btn btn-success btn-float" style="right:15px"><i class="zmdi zmdi-long-arrow-left"></i></button>
        <button id="saveform" type="submit" class="btn btn-info btn-float"><i class="zmdi zmdi-save"></i></button>
    </div>

</form>

<script>
    $(function () {
        var dp = '<?php echo DB_MENU; ?>';
        $('select').selectpicker();
        $('#cancelform').on('click', function () {
            $('#table' + dp).fadeIn('fast');
            $('#form' + dp).empty();
            $('#containerform').fadeOut();
        });

        $('#xfrm').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
            } else {
                var link = 'menu/simpanData';
                var sData = new FormData($(this)[0]);
                $.ajax({
                    url: link,
                    type: "POST",
                    data: sData,
                    dataType: "html",
                    beforeSend: function () {
                        $(".card .card-body").isLoading({
                            text: "Proses Simpan",
                            position: "overlay",
                            tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'
                        });
                    },
                    success: function (html) {
                        setTimeout(function () {
                            $(".card .card-body").isLoading("hide");
                            myApp.oTable.fnDraw(false);
                            $('#table' + dp).fadeIn('fast');
                            $('#form' + dp).empty();
                            $('#containerform').fadeOut();
                        }, 1000);
                    },
                    error: function () {
                        setTimeout(function () {
                            $(".card .card-body").isLoading("hide");
                        }, 1000);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
                return false;
            }
            return false;
        });

        if ($("#cid").val() !== "") {
            if ($("#refftype").attr("data-default") == 'berita') {
                getList('berita', 'reference_id', '', 'news_id', $("#reference_id").attr("data-default"), 'news_id,news_title', 'Pilih', 'image/getList');
            } else {
                getList('artikel', 'reference_id', '', 'article_id', $("#reference_id").attr("data-default"), 'article_id,article_title', 'Pilih', 'image/getList');

            }
        }


    });
</script>