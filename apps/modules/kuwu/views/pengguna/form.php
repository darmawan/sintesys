<?php
if (isset($rowdata)) {
    $arey = array();
    foreach ($rowdata as $kolom => $nilai):
        $arey[$kolom] = $nilai;
    endforeach;
    $cid = ($aep=='salin') ? '' : $arey['user_id'];
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
        <label class="col-sm-2 control-label">Nama Depan</label>
        <div class="col-sm-6">
            <div class="fg-line">
                <input value="<?= (isset($arey)) ? $arey['first_name'] : ''; ?>" class="form-control" placeholder="nama depan" type="text" name="first_name" id="first_name" required data-error="Wajib diisi."  data-toggle="popover" data-placement="top" data-content="">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Nama Belakang</label>
        <div class="col-sm-6">
            <div class="fg-line">
                <input value="<?= (isset($arey)) ? $arey['last_name'] : ''; ?>" class="form-control" placeholder="nama belakang" type="text" name="last_name" id="last_name" data-toggle="popover" data-placement="top" data-content="">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-4">
            <div class="fg-line">
                <input type="email" required data-email="true" required data-error="Wajib diisi, alamat email belum benar"  placeholder="isi dengan alamat email"  class="form-control input-sm" id="email" name="email" value="<?= (isset($arey)) ? $arey['email'] : ''; ?>">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-4">
            <div class="fg-line">
                <input type="hidden" name="passold" value="<?= (isset($arey)) ? $arey['password'] : ''; ?>">
                <input type="password" name="password" placeholder="isi dengan password" class="form-control input-sm" value="">
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">Role</label>
        <div class="col-sm-3">
            <div class="fg-line">
                <div class="select">
                    <select class="form-control selectpicker " required data-error="Wajib dipilih" name="role_id" id="role_id">
                        <option value="">Pilihan</option>
                        <?php
                        $role = (isset($arey)) ? $arey['role_id'] : '';
                        $a = $this->Data_model->selectData(DB_ROLE, "kode", "", "");
                        foreach ($a as $row) {
                            $select = ($row->kode == $role) ? ' selected="selected"' : '';
                            echo '<option value="' . $row->kode . '" ' . $select . '>' . $row->nama_role . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="help-block with-errors"></div>
        </div>                                    
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Aktif</label>
        <div class="col-sm-10">
            <div class="radio m-b-15">
                <label>
                    <input name="isActive" value="1" type="radio" <?php echo ((isset($arey) && $arey['isActive'] == 1) ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    Aktif
                </label>
            </div>
            <div class="radio m-b-15">
                <label>
                    <input name="isActive" value="0" type="radio" <?php echo ((isset($arey) && $arey['isActive'] == '') ? 'checked' : ''); ?>>
                    <i class="input-helper"></i>
                    non Aktif
                </label>
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

    <div class="p-absolute" style="top: 35px;right: 75px;">
        <button id="cancelform" type="button" class="btn btn-success btn-float" style="right:15px"><i class="zmdi zmdi-long-arrow-left"></i></button>
        <button id="saveform" type="submit" class="btn btn-info btn-float"><i class="zmdi zmdi-save"></i></button>
    </div>
</form>
<script src="<?= base_url('assets/admin/vendors/formvalidation/formValidation.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/formvalidation/bootstrap.min.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var dp = '<?php echo DB_USER; ?>';

        $('#cancelform').on('click', function () {
            $('#table' + dp).fadeIn('fast');
            $('#form' + dp).empty();
            $('#containerform').fadeOut();
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
            var $form = $(e.target),
                    fv = $(e.target).data('formValidation');
            var link = 'pengguna/simpanData';
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
                    "<?php echo base_url('publik/profil'); ?>/" + $('#file-1').attr('data-default'),
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