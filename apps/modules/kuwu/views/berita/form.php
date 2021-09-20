<?php
if (isset($rowdata)) {
    $arey = array();
    foreach ($rowdata as $kolom => $nilai):
        $arey[$kolom] = $nilai;
    endforeach;
    $cid = ($salin == 'salin') ? '' : $arey['news_id'];
    $statusvalue = ($arey['editor_approval'] > 0) ? 1 : (($arey['moderator_approval'] > 0) ? 2 : (($arey['is_published'] > 0) ? 3 : 0));
}else {
    $cid = '';
    $statusvalue = '';
}
if (isset($rowdataen)) {
    $areyen = array();
    foreach ($rowdataen as $kolom => $nilai):
        $areyen[$kolom] = $nilai;
    endforeach;
    $cid = ($salin == 'salin') ? '' : (($cid == '') ? $areyen['news_id'] : $cid);
    $statusvalueen = ($areyen['editor_approval'] > 0) ? 1 : (($areyen['moderator_approval'] > 0) ? 2 : (($areyen['is_published'] > 0) ? 3 : 0));
}else {
    $cid = ($cid <> '') ? $cid : '';
    $statusvalueen = '';
}
?>
<form class="form-horizontal" role="form" id="xfrm"  enctype="multipart/form-data">
    <input type="hidden" name="cid" value="<?php echo $cid; ?>">
    <input type="hidden" name="_aid" value="<?php echo $aep; ?>">
    <div role="tabpanel">
        <ul class="tab-nav tab-nav-left" role="tablist" data-tab-color="red">
            <?php if ($aep == '') { ?>
                <li class="active"><a href="#indonesia" aria-controls="indonesia" role="tab" data-toggle="tab">Indonesia</a></li>
                <li class=""><a href="#english" aria-controls="english" role="tab"  data-toggle="tab">English</a></li>
            <?php } else { ?>
                <li class="<?php echo ($bhs == 1) ? 'active' : ''; ?>" <?php echo ($bhs == 2) ? 'disabled' : ''; ?>><a href="<?php echo ($aep <> '') ? 'javascript:;' : '#indonesia'; ?>" aria-controls="indonesia" <?php echo ($bhs == 1) ? 'role="tab"' : ''; ?>>Indonesia</a></li>
                <li class="<?php echo ($bhs == 2) ? 'active' : ''; ?>" <?php echo ($bhs == 1) ? 'disabled' : ''; ?>><a href="<?php echo ($aep <> '') ? 'javascript:;' : '#english'; ?>" aria-controls="english" role="tab"  <?php echo ($bhs == 2) ? 'role="tab"' : ''; ?>>English</a></li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane <?php echo ($aep == '' || $bhs == 1) ? 'active' : ''; ?>" id="indonesia">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $this->lang->line('judul', FALSE); ?></label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input value="<?= (isset($arey)) ? $arey['news_title'] : ''; ?>" class="form-control" placeholder="judul" type="text" name="news_title" id="news_title" required data-error="Wajib diisi."  data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div> 

                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $this->lang->line('status_news', FALSE); ?></label>
                    <div class="col-sm-3">
                        <div class="select">
                            <select class="form-control selectpicker" data-live-search="false" name="status_news" id="status_news">
                                <?php
                                $n = $statusvalue;
                                $k = $this->Data_model->selectData(DB_STATUS, 'status');
                                foreach ($k as $m):
                                    $kapilih = ($m->status == $n) ? ' selected="selected"' : '';
                                    echo '<option value="' . $m->status . '" ' . $kapilih . '>' . $m->status_name . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $this->lang->line('tipe_news', FALSE); ?></label>
                    <div class="col-sm-3">
                        <div class="select">
                            <select class="form-control selectpicker" data-live-search="false" name="type_id" id="type_id">
                                <option value="">Pilihan</option>
                                <?php
                                $n = (isset($arey)) ? $arey['type_id'] : '';
                                $k = $this->Data_model->selectData(DB_TYPE, 'type_id', array('type_grp' => 'news'));
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
                    <label class="col-sm-2 control-label"><?php echo $this->lang->line('kategori_news', FALSE); ?></label>
                    <div class="col-sm-3">
                        <div class="select">
                            <select class="form-control selectpicker" data-live-search="false" name="cat_id" id="cat_id">
                                <option value="">Pilihan</option>
                                <?php
                                $n = (isset($arey)) ? $arey['cat_id'] : '';
                                $k = $this->Data_model->ambilDataWhere(DB_CATEGORY_NEWS, array("1" => 1), "1", "asc", 'cat_id,name', 'cat_id,name');
                                if ($k) {
                                    foreach ($k as $m):
                                        $kapilih = ($m->cat_id == $n) ? ' selected="selected"' : '';
                                        echo '<option value="' . $m->cat_id . '" ' . $kapilih . '>' . $m->name . '</option>';
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $this->lang->line('ringkasan', FALSE); ?></label>
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
                            <textarea class="ckeditor" id="contentz" ><?= (isset($arey)) ? $arey['content'] : ''; ?></textarea>
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
                            <input value="<?= (isset($arey)) ? $arey['publish_date'] : ''; ?>" class="form-control date-picker" placeholder="" type="text" name="publish_date" id="publish_date" data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $this->lang->line('judul_halaman', FALSE); ?></label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input value="<?= (isset($arey)) ? $arey['page_title'] : ''; ?>" class="form-control" placeholder="judul halaman" type="text" name="page_title" id="page_title" data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $this->lang->line('tag', FALSE); ?></label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input value="<?= (isset($arey)) ? $arey['tags'] : ''; ?>" data-role="tagsinput" class="form-control" type="text" name="tags" id="tags" data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane <?php echo ($bhs == 2) ? 'active' : ''; ?>" id="english">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input value="<?= (isset($areyen)) ? $areyen['news_title'] : ''; ?>" class="form-control" placeholder="title" type="text" name="news_title_en" id="news_title_en" <?php echo ($aep == '') ? '' : 'required'; ?> data-error="Wajib diisi."  data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div> 

                <div class="form-group">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-3">
                        <div class="select">
                            <select <?php echo (isset($arey) && $statusvalue <> '') ? (($statusvalueen <> 0) ? '' : ' disabled') : ''; ?> class="form-control selectpicker" data-live-search="false" name="status_news_en" id="status_news_en">
                                <?php
                                $n = $statusvalue;
                                $k = $this->Data_model->selectData(DB_STATUS, 'status');
                                foreach ($k as $m):
                                    $kapilih = ($m->status == $n) ? ' selected="selected"' : '';
                                    echo '<option value="' . $m->status . '" ' . $kapilih . '>' . $m->status_name . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">News Type</label>
                    <div class="col-sm-3">
                        <div class="select">
                            <select <?php echo (isset($arey) && $arey['type_id'] <> '') ? ((isset($areyen) && $areyen['type_id'] <> '') ? '' : ' disabled') : ''; ?> class="form-control selectpicker" data-live-search="false" name="type_id_en" id="type_id_en">
                                <option value="">Pilihan</option>
                                <?php
                                $n = (isset($areyen)) ? $areyen['type_id'] : ((isset($arey)) ? $arey['type_id'] : '');
                                $k = $this->Data_model->selectData(DB_TYPE, 'type_id', array('type_grp' => 'news'));
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
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-3">
                        <div class="select">
                            <select <?php echo (isset($arey) && $arey['cat_id'] <> '') ? ((isset($areyen) && $areyen['cat_id'] <> '') ? '' : ' disabled') : ''; ?> class="form-control selectpicker" data-live-search="false" name="cat_id_en" id="cat_id_en">
                                <option value="">Pilihan</option>
                                <?php
                                $n = (isset($areyen)) ? $areyen['cat_id'] : ((isset($arey)) ? $arey['cat_id'] : '');
                                $k = $this->Data_model->ambilDataWhere(DB_CATEGORY_NEWS, array("1" => 1), "1", "asc", 'cat_id,name', 'cat_id,name');
                                if ($k) {
                                    foreach ($k as $m):
                                        $kapilih = ($m->cat_id == $n) ? ' selected="selected"' : '';
                                        echo '<option value="' . $m->cat_id . '" ' . $kapilih . '>' . $m->name . '</option>';
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Summary</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <textarea class="form-control" placeholder="" name="summary_en" id="summary_en" ><?= (isset($areyen)) ? $areyen['summary'] : ''; ?></textarea>
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Content</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <textarea class="ckeditor" placeholder="" id="contenty" ><?= (isset($areyen)) ? $areyen['content'] : ''; ?></textarea>
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Created Date</label>
                    <div class="col-sm-2">
                        <div class="fg-line">
                            <input <?php echo (isset($arey) && $arey['date_created'] <> '') ? ' disabled' : ''; ?> value="<?= (isset($areyen)) ? $areyen['date_created'] : ((isset($arey) && $arey['date_created'] <> '') ? $arey['date_created'] : ''); ?>" class="form-control date-picker" placeholder="" type="text" name="date_created_en" id="date_created_en" data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Publish Date</label>
                    <div class="col-sm-2">
                        <div class="fg-line">
                            <input <?php echo (isset($arey) && $arey['publish_date'] <> '') ? ' disabled' : ''; ?> value="<?= (isset($areyen)) ? $areyen['publish_date'] : ((isset($arey) && $arey['publish_date'] <> '') ? $arey['publish_date'] : ''); ?>" class="form-control date-picker" placeholder="" type="text" name="publish_date_en" id="publish_date_en" data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Page Title</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input value="<?= (isset($areyen)) ? $areyen['page_title'] : ''; ?>" class="form-control" placeholder="judul halaman" type="text" name="page_title_en" id="page_title_en" data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input value="<?= (isset($areyen)) ? $areyen['tags'] : ''; ?>" data-role="tagsinput" class="form-control" type="text" name="tags_en" id="tags_en" data-toggle="popover" data-placement="top" data-content="">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
        </div>        
    </div>    

    <div class="p-absolute" style="top: 35px;right: 75px;">
        <button id="cancelform" type="button" class="btn btn-success btn-float" style="right:15px"><i class="zmdi zmdi-long-arrow-left"></i></button>
        <button id="saveform" type="submit" class="btn btn-info btn-float"><i class="zmdi zmdi-save"></i></button>
    </div>
</form>

<script src="<?= base_url('assets/admin/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/
bootstrap-datetimepicker.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/vendors/ckeditor/ckeditor.js'); ?>"></script>

<script type="text/javascript">

    $(document).ready(function () {
        var dp = '<?php echo DB_NEWS; ?>';
<?php
if ($aep <> '') {
    if ($bhs == 1) {
        ?>
                CKEDITOR.replace("contentz");
                $('#english div').html('');
    <?php } else { ?>
                CKEDITOR.replace("contenty");
                $('#indonesia div').html('');
        <?php
    }
} else {
    ?>
            CKEDITOR.replace("contentz");
            CKEDITOR.replace("contenty");
<?php } ?>

        $('#cancelform').on('click', function () {
            $('#table' + dp).fadeIn('fast');
            $('#form' + dp).empty();
            $('#containerform').fadeOut();
        });

        $('select').selectpicker(); //{width:'100%'}
        $("#tags").tagsinput('items')

        $('.date-picker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $('#xfrm').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
            } else {
                $("input, select").removeAttr('disabled');

<?php
if ($aep <> '') {
    if ($bhs == 1) {
        ?>
                        var link = 'berita/simpanData/1';
                        var sData = $('#xfrm').serialize() + '&' + $.param({'content': CKEDITOR.instances.contentz.getData()});
    <?php } else { ?>
                        var link = 'berita/simpanData/2';
                        var sData = $('#xfrm').serialize() + '&' + $.param({'content_en': CKEDITOR.instances.contenty.getData()});
        <?php
    }
} else {
    ?>
                    var link = 'berita/simpanData/3';
                    var sData = $('#xfrm').serialize() + '&' + $.param({'content': CKEDITOR.instances.contentz.getData()}) + '&' + $.param({'content_en': CKEDITOR.instances.contenty.getData()});
<?php } ?>
                $.ajax({
                    url: link,
                    type: "POST",
                    data: sData,
                    dataType: "html",
                    beforeSend: function () {
                        $(".card #form" + dp).isLoading({
                            text: "Proses Simpan",
                            position: "overlay",
                            tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'
                        });
                    },
                    success: function (html) {
                        setTimeout(function () {
                            $(".card #form" + dp).isLoading("hide");
                            myApp.oTable.fnDraw(false);
                            $('#table' + dp).fadeIn('fast');
                            $('#containerform').fadeOut();
                            $('#form' + dp).html('');
                            scrollTo();
                            notify('Data berhasil disimpan!', 'success');
                        }, 500);
                    },
                    error: function () {
                        setTimeout(function () {
                            $(".card #form" + dp).isLoading("hide");
                        }, 1000);
                    }
                });
                return false;
            }
            return false;
        });

    });

</script>