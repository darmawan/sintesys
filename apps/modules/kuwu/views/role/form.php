<?php
if (isset($rowdata)) {
    $arey = array();
    foreach ($rowdata as $kolom => $nilai):
        $arey[$kolom] = $nilai;
    endforeach;
    $cid = ($aep == 'salin') ? '' : $arey['kode'];
}else {
    $cid = '';
}
?>
<form class="form-horizontal" role="form" id="xfrm"  enctype="multipart/form-data">
    <!--<form  role="form" id="xfrm" enctype="multipart/form-data"  class="form-horizontal">-->
    <input type="hidden" name="cid" id="cid" value="<?= $cid; ?>"> 

    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">Role</label>
        <div class="col-sm-3">
            <div class="select">
                <select class="form-control selectpicker " required data-error="Wajib dipilih" name="role" id="role">
                    <option value="">Pilihan</option>
                    <?php
                    $p = (isset($arey)) ? $arey['role'] : '';
                    $a = $this->Data_model->selectData('ad_role', "kode", "", "");
                    foreach ($a as $row) {
                        $select = ($row->kode == $p) ? ' selected="selected"' : '';
                        echo '<option value="' . $row->kode . '" ' . $select . '>' . $row->nama_role . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>                                    
    </div>

    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">Menu</label>
        <div class="col-sm-6">
            <div class="select">
                <select class="form-control selectpicker" data-live-search="true" required data-error="Wajib dipilih" name="menu" id="menu">
                    <option value="">Pilihan</option>
                    <?php
                    $menu = (isset($arey)) ? $arey['menu'] : '';
                    $q = "select * from (
                            select t1.urutan as urut_induk, t1.kode AS induk, t1.nama_menu as menu_induk, 
                            t2.urutan as urut_anak, t2.kode as anak, t2.nama_menu as menu_anak  
                            from ad_menu_admin as t1 join ad_menu_admin t2 on t1.kode = t2.induk WHERE t1.aktif=1 AND t2.aktif=1 order by t1.kode) as aep 
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
        <label class="col-sm-2 control-label">Otorisasi</label>
        <div class="col-sm-10">
            <div class="checkbox m-b-15">
                <label>
                    <input name="tambah" value="1" type="checkbox" <?php echo ((isset($arey) && $arey['tambah'] == 1) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Tambah
                </label>
            </div> 
            <div class="checkbox m-b-15">
                <label>
                    <input name="ubah" value="1" type="checkbox" <?php echo ((isset($arey) && $arey['ubah'] == 1) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Ubah
                </label>
            </div> 
            <div class="checkbox m-b-15">
                <label>
                    <input name="hapus" value="1" type="checkbox" <?php echo ((isset($arey) && $arey['hapus'] == 1) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Hapus
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
        var dp = '<?php echo DB_ROLE; ?>';

        $('#cancelform').on('click', function () {
            $('#table' + dp).fadeIn('fast');
            $('#form' + dp).empty();
            $('#containerform').fadeOut();
        });

        $('#xfrm').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
            } else {
                var link = 'role/simpanData';
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

        $('select').selectpicker();
    });
</script>