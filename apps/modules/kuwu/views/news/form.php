<?php
if (isset($data['rowdata'])) {
    $news_id = ($data['param_id'] == '') ? $data['rowdata']->news_id : $data['param_id'];
    $news_title = $data['rowdata']->news_title;
    $lang_id = $data['rowdata']->lang_id;
    $summary = $data['rowdata']->summary;
    $content = $data['rowdata']->content;
    $editor_approval = $data['rowdata']->editor_approval;
    $moderator_approval = $data['rowdata']->moderator_approval;
    $is_published = $data['rowdata']->is_published;
    $date_created = $data['rowdata']->date_created;
    $user_created = $data['rowdata']->user_created;
    $date_modified = $data['rowdata']->date_modified;
    $user_modified = $data['rowdata']->user_modified;
    $type_id = $data['rowdata']->type_id;
    $publish_date = $data['rowdata']->publish_date;
    $tags = $data['rowdata']->tags;
    $page_title = trim($data['rowdata']->page_title);
    $cat_id = ($data['rowdata']->cat_id == '') ? 0 : $data['rowdata']->cat_id;
    $statusvalue = ($editor_approval > 0) ? 1 : (($moderator_approval > 0) ? 2 : (($is_published > 0) ? 3 : 0));
    $data['param_id'] = '';
} else {
    $news_id = ($data['param_id'] == '') ? '' : $data['param_id'];
    $news_title = '';
    $lang_id = '';
    $summary = '';
    $content = '';
    $editor_approval = '';
    $moderator_approval = '';
    $is_published = '';
    $date_created = '';
    $user_created = '';
    $date_modified = '';
    $user_modified = '';
    $type_id = '';
    $publish_date = '';
    $tags = '';
    $page_title = '';
    $cat_id = '';
    $statusvalue = '';
}
?>

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
                <a href="<?= base_url('kuwu/news'); ?>">List News</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="#">Form News</a>
            </li>
        </ul>

    </div>

    <div class="row-fluid">
        <div class="span12">
            <form action="<?= base_url('kuwu/news/saveForm'); ?>" method="POST" class="form-horizontal form-bordered form-validate" id="aaa">
                <input type="hidden" name="_edit" value="<?php echo (($data['fedit'] == true && $data['optional'] == 'e') ? 1 : 0); ?>">
                <input type="hidden" name="_lang" value="<?php echo $data['lang_id']; ?>">
                <input type="hidden" name="_aid" value="<?php echo $news_id; ?>">
                <input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>">
                <div class="box box-color">
                    <div class="box-title">
                        <h3>
                            <i class="icon-edit"></i>
                            Form News
                        </h3>
                        <ul class="tabs">
                            <?php
                            if ($data['rowtype']):
                                $tipe = '<select name="type_id" id="type_id" data-rule-required="true"><option value="">-- --</option>';
                                $tipe_en = '<select name="type_id_en" id="type_id_en" data-rule-required="true"><option value="">-- --</option>';
                                foreach ($data['rowtype'] as $value) {
                                    $tipe .= '<option value="' . $value->type_id . '" ' . (($value->type_id == $type_id) ? ' selected="selected"' : '') . '>' . $value->type_name . '</option>';
                                    $tipe_en .= '<option value="' . $value->type_id . '" ' . (($value->type_id == $type_id) ? ' selected="selected"' : '') . '>' . $value->type_name . '</option>';
                                }
                                $tipe .= '</select>';
                                $tipe_en .= '</select>';
                            endif;

                            /* $catid = '<select name="cat_id" id="cat_id" data-rule-required="false"><option value="">-- --</option>';
                              $catiden = '<select name="cat_id_en" id="cat_id" data-rule-required="false"><option value="">-- --</option>';
                              if ($data['rowcat']):
                              foreach ($data['rowcat'] as $value) {
                              $catid .= '<option value="' . $value->cat_id . '" ' . (($value->cat_id == $cat_id) ? ' selected="selected"' : '') . '>' . $value->name . '</option>';
                              $catiden .= '<option value="' . $value->cat_id . '" ' . (($value->cat_id == $cat_id) ? ' selected="selected"' : '') . '>' . $value->name . '</option>';
                              }
                              endif;
                              $catid .= '</select>';
                              $catiden .= '</select>';
                             */

                            $status = '';
                            if ($data['rowstat']):
                                #$stat = ($editor_approval==0) ? 99 : $is_published;
                                foreach ($data['rowstat'] as $value) {
                                    $status .= '<option value="' . $value->status . '" ' . (($value->status == $statusvalue) ? ' selected="selected"' : '') . '>' . $value->status_name . '</option>';
                                }
                            endif;
#$status     .= '';

                            $activeid = 'active';
                            $activeeng = 'active';
                            $frmtab = '';
                            $kontenind = '<li class=""><a href="#t7" data-toggle="tab">Indonesia</a></li>';
                            $konteneng = '<li class=""><a href="#t8" data-toggle="tab">English</a></li>';
                            $formind = '
                <input type="hidden" name="lang_" value="1">
                <div class="control-group">
                    <label for="textfield" class="control-label">Judul</label>
                    <div class="controls">
                        <input type="text" name="news_title" id="news_title" class="input-xxlarge" data-rule-required="true" data-rule-minlength="2" value="' . $news_title . '">
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Status</label>
                    <div class="controls">
                    <select name="status_news">' . $status . '</select>
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Tipe </label>
                    <div class="controls">' . $tipe . '
                    </div>
                </div>
                <!--<div class="control-group">
                    <label for="textfield" class="control-label">Kategori </label>
                    <div class="controls">
                    </div>
                </div>-->
                <div class="control-group">
                    <label class="control-label" for="textarea">Resume </label>
                    <div class="controls">
                        <textarea class="input-block-level" id="summary" name="summary">' . $summary . '</textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label for="content" class="control-label">Konten</label>
                    <div class="controls">
                        <textarea name="content" class="ckeditor span12" rows="5">' . $content . '</textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Tanggal Dibuat</label>
                    <div class="controls">
                        <input type="text" name="date_created" id="date_created" class="input-medium datepick" value="' . $date_created . '">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Tanggal Publikasi</label>
                    <div class="controls">
                        <input type="text" name="publish_date" id="publish_date" class="input-medium datepick" value="' . $publish_date . '">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Judul Halaman</label>
                    <div class="controls">
                        <input type="text" name="page_title" id="page_title" class="input-xlarge" data-rule-required="false" value="' . $page_title . '">
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Tags</label>
                    <div class="controls">
                        <div class="span12"><input type="text" name="tags" id="tags" class="tagsinput" value="' . $tags . '"></div>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="Simpan">
                    <a href="javascript:;" class="btn batal" data-uri="http://localhost/newpasca/admin/news">Batal</a>
                </div>
            ';
                            $formeng = '
                <input type="hidden" name="lang_" value="2">
                <div class="control-group">
                    <label for="textfield" class="control-label">News Title</label>
                    <div class="controls">
                        <input type="text" name="news_title_en" id="news_title_en" class="input-xxlarge" data-rule-required="true" data-rule-minlength="2" value="' . $news_title . '">
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Status</label>
                    <div class="controls">
                    <select name="status_news_en">' . $status . '</select>
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Type </label>
                    <div class="controls">' . $tipe_en . '
                    </div>
                </div>
                <!--<div class="control-group">
                    <label for="textfield" class="control-label">Category </label>
                    <div class="controls">
                    </div>
                </div>-->
                <div class="control-group">
                    <label class="control-label" for="textarea">Resume </label>
                    <div class="controls">
                        <textarea class="input-block-level" id="summary_en" name="summary_en">' . $summary . '</textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label for="content" class="control-label">Content</label>
                    <div class="controls">
                        <textarea name="content_en" class="ckeditor span12" rows="5">' . $content . '</textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Date Created</label>
                    <div class="controls">
                        <input type="text" name="date_created_en" id="date_created_en" class="input-medium datepick" value="' . $date_created . '">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Publish Date</label>
                    <div class="controls">
                        <input type="text" name="publish_date_en" id="publish_date_en" class="input-medium datepick" value="' . $publish_date . '">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Page Titile</label>
                    <div class="controls">
                        <input type="text" name="page_title_en" id="page_title_en" class="input-xlarge" data-rule-required="false" value="' . $page_title . '">
                    </div>
                </div>
                <div class="control-group">
                    <label for="textfield" class="control-label">Tags</label>
                    <div class="controls">
                        <div class="span12"><input type="text" name="tags_en" id="tags_en" class="tagsinput" value="' . $tags . '"></div>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="Save">
                    <a href="javascript:;" class="btn batal" data-uri="http://localhost/newpasca/admin/news">Cancel</a>
                </div>
            ';
                            if ($data['fedit'] == true) {
                                if ($data['lang_id'] == 1) {
                                    $frmtab = str_replace('class=""', 'class="active"', $kontenind);
                                    $formeng = '';
                                    $formind = $formind;
                                    $activeeng = false;
                                } else {
                                    $frmtab = str_replace('class=""', 'class="active"', $konteneng);
                                    $formind = '';
                                    $formeng = $formeng;
                                    $activeid = false;
                                }
                            } else {
                                echo str_replace('class=""', 'class="active"', $kontenind) . $konteneng;
                                $activeeng = false;
                            }
                            echo $frmtab;
                            ?>
                        </ul>
                    </div>
                    <div class="box-content">
                        <div class="tab-content">

                            <div class="tab-pane <?= $activeid; ?>" id="t7">
                                <div class="box-content nopadding">
                                    <?php echo $formind; ?>
                                </div>
                            </div>
                            <div class="tab-pane <?= $activeeng; ?>" id="t8">
                                <div class="box-content nopadding">
                                    <?php echo $formeng; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- Validation -->
<script src="<?= base_url('assets/admin/js/plugins/validation/jquery.validate.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/plugins/validation/additional-methods.min.js'); ?>"></script>
<!-- CKEditor -->
<script src="<?= base_url('assets/admin/js/plugins/ckeditor/ckeditor.js'); ?>"></script>

