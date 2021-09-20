<?php
if (isset($rowdata)) {
    $arey = array();
    foreach ($rowdata as $kolom => $nilai):
        $arey[$kolom] = $nilai;
    endforeach;
    $cid = $arey['image_id'];
}else {
    $cid = '';
    $statusvalue = '';
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
    <input type="hidden" name="_imgnm" value="<?= (isset($arey)) ? $arey['image_path'] : ''; ?>">

    <div class="form-group">
        <label class="col-sm-2 control-label">Judul Photo/Image</label>
        <div class="col-sm-10">
            <div class="fg-line">
                <input value="<?= (isset($arey)) ? $arey['image_name'] : ''; ?>" class="form-control" placeholder="judul" type="text" name="image_name" id="image_name" required data-error="Wajib diisi."  data-toggle="popover" data-placement="top" data-content="">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div> 

    <div class="form-group">
        <label class="col-sm-2 control-label">Photo</label>
        <div class="col-sm-10">
            <div class="file-loading">
                <input id="file-1" type="file" name="image_path" accept="image/*" data-default="<?= (isset($arey)) ? $arey['image_path'] : ''; ?>">
            </div>
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-2 control-label">Relasikan Ke</label>
        <div class="col-sm-3">
            <div class="select">
                <select id="refftype" name="refftype" class="form-control selectpicker" data-live-search="false" data-default="<?= (isset($arey)) ? $arey['refftype'] : ''; ?>">
                    <option value="">--Pilihan--</option>
                    <option value="berita" <?= ((isset($arey) && $arey['refftype'] == 'berita') ? 'selected="selected"' : ''); ?>>News</option>
                    <option value="artikel" <?= ((isset($arey) && $arey['refftype'] == 'artikel') ? 'selected="selected"' : ''); ?>>Article</option>
                    <option value="project" <?= ((isset($arey) && $arey['refftype'] == 'project') ? 'selected="selected"' : ''); ?>>Project</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group" id="relatedoc">
        <label class="col-sm-2 control-label">Pilih Relasi Konten</label>
        <div class="col-sm-9">
            <div class="select">
                <select id="reffid" name="reffid" class="form-control selectpicker" data-live-search="true" data-default="<?= (isset($arey)) ? $arey['reffid'] : ''; ?>">
                    <option value="0" selected="selected"></option> 
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Photo Utama</label>
        <div class="col-sm-3">
            <div class="checkbox m-b-15">
                <label>
                    <input name="mainimg" value="1" type="checkbox" <?php echo ((isset($arey) && $arey['mainimg'] == 1) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Set sebagai photo utama
                </label>
            </div>            
            <!--<div class="help-block with-errors"></div>-->
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Aktif</label>
        <div class="col-sm-10">
            <div class="radio m-b-15">
                <label>
                    <input name="active" value="1" type="radio" <?php echo ((isset($arey) && $arey['active'] == 1) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Aktifkan
                </label>
            </div>
            <div class="radio m-b-15">
                <label>
                    <input name="active" value="0" type="radio" <?php echo ((isset($arey) && $arey['active'] == '') ? 'checked' : ''); ?>>
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
<script src="<?= base_url('assets/admin/vendors/formvalidation/formValidation.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/formvalidation/bootstrap.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var dp = '<?php echo DB_IMAGE; ?>';

        $('#cancelform').on('click', function () {
            $('#table' + dp).fadeIn('fast');
            $('#form' + dp).empty();
            $('#containerform').fadeOut();
        });

        $('.selectpicker').selectpicker();

        $('#relatedoc').hide();
        $('#refftype').change(function () {
            $('#relatedoc').slideDown();
            switch ($(this).val()) {
                case 'artikel':
                    getList($(this).val(), 'reffid', '', $(this).val() + '_id', $("#reffid").val("data-default"), 'article_id,article_title', 'Pilih', 'image/getList');
                    break;
                case 'news':
                    getList($(this).val(), 'reffid', '', $(this).val() + '_id', $("#reffid").attr("data-default"), 'news_id,news_title', 'Pilih', 'image/getList');
                    break;
                case 'project':
                    getList($(this).val(), 'reffid', '', $(this).val() + '_id', $("#reffid").attr("data-default"), 'project_id,project_title', 'Pilih', 'image/getList');
                    break;
                default:
                    break;
            }


            $('#relatedoc').slideDown();
        });
        if ($("#cid").val() !== "") {
            switch ($("#refftype").attr("data-default")) {
                case 'artikel':
                    getList($("#refftype").attr("data-default"), 'reffid', '', 'article_id', $("#reffid").attr("data-default"), 'article_id,article_title', 'Pilih', 'image/getList');
                    break;
                case 'news':
                    getList($("#refftype").attr("data-default"), 'reffid', '', 'news_id', $("#reffid").attr("data-default"), 'news_id,news_title', 'Pilih', 'image/getList');
                    break;
                case 'project':
                    getList($("#refftype").attr("data-default"), 'reffid', '', $("#refftype").attr("data-default") + '_id', $("#reffid").attr("data-default"), 'project_id,project_title', 'Pilih', 'image/getList');
                    break;
                default:
                    break;
            } 
            $('#relatedoc').slideDown();
        }

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
            var $form = $(e.target),
                    fv = $(e.target).data('formValidation');
            var link = 'image/simpanData';
            var sData = new FormData($(this)[0]);
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
                    "<?php echo base_url('publik/rabmag/image'); ?>/" + $('#file-1').attr('data-default'),
                ],
                initialPreviewConfig: [
                    {caption: $('#file-1').attr('data-default'), key: 1},
                ],
                fileType: "image",
                initialPreviewAsData: true,
                initialPreviewFileType: 'image'
            });
        }
    });
</script>