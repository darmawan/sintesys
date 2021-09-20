<?php
if (isset($rowdata)) {
    $arey = array();
    foreach ($rowdata as $kolom => $nilai):
        $arey[$kolom] = $nilai;
    endforeach;
    $cid = $arey['slider_id'];
    $statusvalue = ($arey['editor_approval'] > 0) ? 1 : (($arey['moderator_approval'] > 0) ? 2 : (($arey['is_published'] > 0) ? 3 : 0));
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
    <input type="hidden" name="_imgnm" value="<?= (isset($arey)) ? $arey['image'] : ''; ?>">

    <div class="form-group">
        <label class="col-sm-2 control-label">Judul Photo/Image</label>
        <div class="col-sm-10">
            <div class="fg-line">
                <input value="<?= (isset($arey)) ? $arey['slider_text'] : ''; ?>" class="form-control" placeholder="judul" type="text" name="slider_text" id="slider_text" required data-error="Wajib diisi."  data-toggle="popover" data-placement="top" data-content="">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div> 

    <div class="form-group">
        <label class="col-sm-2 control-label">Status Slider</label>
        <div class="col-sm-3">
            <div class="select">
                <select class="form-control selectpicker" data-live-search="false" name="status_slider" id="status_slider">
                    <?php
                    $n = $statusvalue;
                    $k = $this->Data_model->selectData(DB_STATUS, 'status');
                    foreach ($k as $m):
                        $kapilih = ($m->status == $n) ? ' selected="selected"' : '';
                        echo '<option value="' . $m->status . '" ' . $kapilih . '>' . ((trim($m->status_name) == "New Article") ? "New Slider" : $m->status_name) . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Slider Teks</label>
        <div class="col-sm-10">
            <div class="fg-line">
                <textarea class="ckeditor" name="description" id="description" ><?= (isset($arey)) ? $arey['description'] : ''; ?></textarea>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Photo</label>
        <div class="col-sm-10">
            <div class="file-loading">
                <input id="file-1" type="file" name="image" accept="image/*" data-default="<?= (isset($arey)) ? $arey['image'] : ''; ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Kategori Album</label>
        <div class="col-sm-3">
            <div class="select">
                <select id="imagepos" name="imagepos" class="form-control selectpicker" data-live-search="false" data-default="<?= (isset($arey)) ? $arey['imagepos'] : ''; ?>">
                    <option value="">--Pilihan--</option>
                    <option value="center" <?= ((isset($arey) && $arey['imagepos'] == 'center') ? 'selected="selected"' : ''); ?>>Tengah</option>
                    <option value="left" <?= ((isset($arey) && $arey['imagepos'] == 'left') ? 'selected="selected"' : ''); ?>>Kiri</option>
                    <option value="right" <?= ((isset($arey) && $arey['imagepos'] == 'right') ? 'selected="selected"' : ''); ?>>Kanan</option>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $this->lang->line('tgl_buat', FALSE); ?></label>
        <div class="col-sm-2">
            <div class="fg-line">
                <input value="<?= (isset($arey)) ? $arey['date_created'] : ''; ?>" class="form-control date-picker" placeholder="" type="text" name="date_created" id="date_created" data-toggle="popover" data-placement="top" data-content="">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $this->lang->line('tgl_publikasi', FALSE); ?></label>
        <div class="col-sm-2">
            <div class="fg-line">
                <input value="<?= (isset($arey)) ? $arey['published_date'] : ''; ?>" class="form-control date-picker" placeholder="" type="text" name="published_date" id="published_date" data-toggle="popover" data-placement="top" data-content="">
            </div>
            <div class="help-block with-errors"></div>
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
<script src="<?= base_url('assets/admin/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/
bootstrap-datetimepicker.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/ckeditor/ckeditor.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var dp = '<?php echo DB_SLIDER; ?>';
       
        CKEDITOR.replace("description");
        $('#cancelform').on('click', function () {
            $('#table' + dp).fadeIn('fast');
            $('#form' + dp).empty();
            $('#containerform').fadeOut();
        });

        $('.selectpicker').selectpicker();
        $('.date-picker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

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
//            CKEDITOR.instances.description.destroy();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var $form = $(e.target),
                    fv = $(e.target).data('formValidation');
            var link = 'slider/simpanData';
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
                    "<?php echo base_url('publik/rabmag/slider'); ?>/" + $('#file-1').attr('data-default'),
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