<?php
if (isset($rowdata)) {
    $arey = array();
    foreach ($rowdata as $kolom => $nilai):
        $arey[$kolom] = $nilai;
    endforeach;
    $cid = ($aep == 'salin') ? '' : $arey['product_id'];
}else {
    $cid = '';
}
?>
<link href="<?= base_url('assets/admin/vendors/kartik-fileinput/themes/explorer/theme.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/admin/vendors/kartik-fileinput/css/fileinput.min.css'); ?>" rel="stylesheet">
<script src="<?= base_url('assets/admin/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/kartik-fileinput/js/plugins/sortable.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/admin/vendors/kartik-fileinput/js/fileinput.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/admin/vendors/kartik-fileinput/js/locales/id.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/admin/vendors/kartik-fileinput/themes/explorer/theme.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/admin/vendors/kartik-fileinput/themes/gly/theme.js'); ?>" type="text/javascript"></script>
<form  role="form" id="xfrm" enctype="multipart/form-data" data-toggle="validator" class="form-horizontal" >
    <input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>">
    <input type="hidden" name="_imgnm" value="<?= (isset($arey)) ? $arey['image'] : ''; ?>">

    <div class="form-group">
        <label class="col-sm-2 control-label">Nama Produk</label>
        <div class="col-sm-6">
            <div class="fg-line">
                <input value="<?= (isset($arey)) ? $arey['product_title'] : ''; ?>" class="form-control" placeholder="nama product/pekerjaan" type="text" name="product_title" id="product_title" required data-error="Wajib diisi."  data-toggle="popover" data-placement="top" data-content="">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Deskripsi Singkat</label>
        <div class="col-sm-10">
            <div class="fg-line">
                <textarea class="form-control" placeholder="" name="summary" id="summary" ><?= (isset($arey)) ? $arey['summary'] : ''; ?></textarea>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $this->lang->line('konten', FALSE); ?></label>
        <div class="col-sm-10">
            <div class="fg-line">
                <textarea class="ckeditor" name="content" id="contentz" ><?= (isset($arey)) ? $arey['content'] : ''; ?></textarea>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">Jenis</label>
        <div class="col-sm-10">
            <div class="radio m-b-15">
                <label>
                    <input name="tags" value="product" type="radio" <?php echo ((isset($arey) && $arey['tags'] == 'product') ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Product
                </label>
            </div>
            <div class="radio m-b-15">
                <label>
                    <input name="tags" value="service" type="radio" <?php echo ((isset($arey) && $arey['tags'] == 'service') ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Service
                </label>
            </div>            
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">Pemilik Produk/Service</label>
        <div class="col-sm-3">
            <div class="select">
                <select class="form-control selectpicker" data-live-search="false" name="type_id" id="type_id" data-default="<?= (isset($arey)) ? $arey['type_id'] : ''; ?>">
                    <option value="">Pilihan</option>
                    <?php
                    $n = (isset($arey)) ? $arey['type_id'] : '';
                    $k = $this->Data_model->selectData(DB_TYPE, 'type_id', array('type_grp' => 'project'));
                    foreach ($k as $m):
                        $kapilih = ($m->type_id == $n) ? ' selected="selected"' : '';
                        echo '<option value="' . $m->type_id . '" ' . $kapilih . '>' . $m->type_name . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">Kategori</label>
        <div class="col-sm-3">
            <div class="select">
                <select class="form-control selectpicker" data-live-search="false" name="cat_id" id="cat_id" data-default="<?= (isset($arey)) ? $arey['cat_id'] : ''; ?>">
                    <option value="">Pilihan</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Publikasi</label>
        <div class="col-sm-10">
            <div class="radio m-b-15">
                <label>
                    <input name="is_published" value="1" type="radio" <?php echo ((isset($arey) && $arey['is_published'] == 1) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Ya
                </label>
            </div>
            <div class="radio m-b-15">
                <label>
                    <input name="is_published" value="0" type="radio" <?php echo ((isset($arey) && $arey['is_published'] == '') ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Tidak
                </label>
            </div>            
            <div class="help-block with-errors"></div>
        </div>
    </div>    

    <div class="form-group">
        <label class="col-sm-2 control-label">Photo Produk</label>
        <div class="col-sm-10">
            <div class="file-loading">
                <input id="file-1" type="file" name="image" accept="image/*" data-default="<?= (isset($arey)) ? $arey['image'] : ''; ?>">
            </div>
        </div>
    </div>

    <div class="p-absolute" style="top: 35px;right: 75px;">
        <button id="cancelform" type="button" class="btn btn-success btn-float" style="right:15px"><i class="zmdi zmdi-long-arrow-left"></i></button>
        <button id="saveform" type="submit" class="btn btn-info btn-float"><i class="zmdi zmdi-save"></i></button>
    </div>
</form>

<script src="<?= base_url('assets/admin/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/
bootstrap-datetimepicker.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/ckeditor/ckeditor.js'); ?>"></script>



<script type="text/javascript">
    $(document).ready(function () {
        var dp = '<?php echo DB_PRODUCT; ?>';

        CKEDITOR.replace("contentz");

        $('#cancelform').on('click', function () {
            $('#table' + dp).fadeIn('fast');
            $('#form' + dp).empty();
            $("table.dataTable").resize();
            $('#containerform').fadeOut();
        });
        $('.date-picker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('.selectpicker').selectpicker();

        $('#xfrm').formValidation({
            framework: 'bootstrap',
            excluded: ':disabled',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                tipe: {
                    validators: {
                        notEmpty: {
                            message: 'Tipe wajib dipilih'
                        }
                    }
                }
            }
        }).on('success.form.fv', function (e) {
            // Prevent form submission
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var $form = $(e.target),
                    fv = $(e.target).data('formValidation');
            var link = 'product/simpanData';
            var sData = new FormData($(this)[0]); // + '&' + $.param({'description': CKEDITOR.instances.description.getData()});
            $.ajax({
                url: link,
                type: "POST",
                data: sData,
                dataType: "html",
                beforeSend: function () {
                    $(".card #xfrm").isLoading({
                        text: "Proses Simpan",
                        position: "overlay",
                        tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'
                    });
                },
                success: function (html) {
                    setTimeout(function () {
                        myApp.oTable.fnDraw(false);
                        $('#table' + dp).fadeIn('fast');
                        $('#form' + dp).empty();
                        $('#containerform').fadeOut();
                        scrollTo();
                        $(".card #xfrm").isLoading("hide");
                        $("table.dataTable").resize();
                    }, 500);
                },
                error: function () {
                    setTimeout(function () {
                        $(".card #xfrm").isLoading("hide");
                    }, 1000);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });

        if ($('#file-1').attr('data-default') === '') {
            $("#file-1").fileinput({
                theme: 'gly',
                language: "id",
                overwriteInitial: true,
                showPreview: true,
                showClose: false,
                showCaption: false,
                showBrowse: true,
                showRemove: false,
                showDownload: false,
                showDrag: false,
                browseOnZoneClick: true,
                layoutTemplates: {
                    main1: '{preview} {remove} {browse}',
                    main2: '{preview} {remove} {browse}',
                    showDrag: false,
                },
                fileType: "image",
                initialPreviewAsData: true,
                initialPreviewFileType: 'image'
            });
        } else {
            $("#file-1").fileinput({
                theme: 'gly',
                language: "id",
                overwriteInitial: true,
                showPreview: true,
                showClose: false,
                showCaption: false,
                showBrowse: true,
                showRemove: false,
                showDownload: false,
                showDrag: false,
                browseOnZoneClick: true,
                layoutTemplates: {
                    main1: '{preview} {remove} {browse}',
                    main2: '{preview} {remove} {browse}',
                    showDrag: false,
                },
                initialPreview: [
                    "<?php echo base_url('publik/rabmag/product'); ?>/" + $('#file-1').attr('data-default'),
                ],
                initialPreviewConfig: [
                    {caption: $('#file-1').attr('data-default'), key: 1},
                ],
                fileType: "image",
                initialPreviewAsData: true,
                initialPreviewFileType: 'image'
            });
        }
        
        $('#type_id').change(function () {
            getList('product', 'cat_id', $(this).val(), 'type_id', $(this).attr("data-default"), 'product_id,product_title', 'Pilih', 'product/getList');
        });
        
        if ($('#cat_id').attr("data-default") !== "") {
            getList('product', 'cat_id', $('#type_id').val(), 'type_id', $('#cat_id').attr("data-default"), 'product_id,product_title', 'As Category', 'product/getList');
            $('.selectpicker').selectpicker('refresh');
        }
        
    });
</script>