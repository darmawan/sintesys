<?php
if (isset($data['rowdata'])) {
    $menu_id = ($data['param_id'] == '') ? $data['rowdata']->menu_id : $data['param_id'];
    $menu_name = $data['rowdata']->menu_name;
    $lang_id = $data['rowdata']->lang_id;
    $parent_id = $data['rowdata']->parent_id;
    $active = $data['rowdata']->is_active;
    $ordering = $data['rowdata']->ordering;
    $date_created = $data['rowdata']->date_created;
    $type_id = $data['rowdata']->type_id;
    $reference_id = $data['rowdata']->reference_id;
    $level_menu = trim($data['rowdata']->level_menu);
    $menu_url = trim($data['rowdata']->menu_url);
    $statusvalue = ($active > 0) ? 1 : 0;
    $data['param_id'] = '';
} else {
    $menu_id = ($data['param_id'] == '') ? '' : $data['param_id'];
    $menu_name = '';
    $lang_id = '';
    $parent_id = '';
    $active = '';
    $moderator_approval = '';
    $ordering = '';
    $date_created = '';
    $type_id = '';
    $reference_id = '';
    $level_menu = '';
    $cat_id = '';
    $statusvalue = '';
    $menu_url = '';
}
?>

<div class="container-fluid">
    <div class="page-header">
        <div class="pull-left">
            <h1>Sub Menu</h1>
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
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="#">Form Sub Menu</a>
            </li>
        </ul>

    </div>

    <div class="row-fluid">
        <div class="span12">
            <form action="<?= base_url('admin/menu/saveForm'); ?>" method="POST" class="form-horizontal form-bordered form-validate" id="aaa">
                <input type="hidden" name="_edit" value="<?php echo (($data['fedit'] == TRUE && $data['optional'] == 'e') ? 1 : 0); ?>">
                <input type="hidden" name="_lang" value="<?php echo $data['lang_id']; ?>">
                <input type="hidden" name="_aid" value="<?php echo $menu_id; ?>">
                <div class="box box-color">
                    <div class="box-title">
                        <h3>
                            <i class="icon-edit"></i>
                            Form Sub Menu
                        </h3>
                        <ul class="tabs">
                            <?php
                            $parent = ' <select name="parent_id"><option>--</option>';
                            $parent_eng = ' <select name="parent_id_en"><option>--</option>';
                            if ($data['rowparent']):
                                foreach ($data['rowparent'] as $value) {
                                    $parent .= '<option value="' . $value->menu_id . '" ' . (($value->menu_id == $parent_id) ? ' selected="selected"' : '') . '>' . $value->menu_name . '</option>';
                                    $parent_eng .= '<option value="' . $value->menu_id . '" ' . (($value->menu_id == $parent_id) ? ' selected="selected"' : '') . '>' . $value->menu_name . '</option>';
                                }
                            endif;
                            $parent .= '</select>';
                            $parent_eng .= '</select>';
                            
                            $refarticle = ' <select name="reference_id"><option>--</option>';
                            $refarticle_eng = ' <select name="reference_id_en"><option>--</option>';
                            if ($data['rowarticle']):
                                foreach ($data['rowarticle'] as $value) {
                                    $refarticle .= '<option value="' . $value->article_id . '" ' . (($value->article_id == $reference_id) ? ' selected="selected"' : '') . '>' . $value->article_title . '</option>';
                                    $refarticle_eng .= '<option value="' . $value->article_id . '" ' . (($value->article_id == $reference_id) ? ' selected="selected"' : '') . '>' . $value->article_title . '</option>';
                                }
                            endif;
                            $refarticle .= '</select>';
                            $refarticle_eng .= '</select>';

                            $activeid = 'active';
                            $activeeng = 'active';
                            $frmtab = '';
                            $kontenind = '<li class=""><a href="#t7" data-toggle="tab">Indonesia</a></li>';
                            $konteneng = '<li class=""><a href="#t8" data-toggle="tab">English</a></li>';
                            $formind = '
                                    <input type="hidden" name="lang_" value="1">
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Nama Menu</label>
                                        <div class="controls">
                                            <input type="text" name="menu_name" id="menu_name" class="input-xlarge" data-rule-required="true" data-rule-minlength="2" value="' . $menu_name . '">
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label class="control-label" for="textarea">Induk Menu</label>
                                        <div class="controls">
                                            '.$parent.'
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Tanggal Dibuat</label>
                                        <div class="controls">
                                            <input type="text" name="date_created" id="date_created" class="input-medium datepick" value="' . $date_created . '">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Level Menu</label>
                                        <div class="controls">
                                            <input type="text" name="level_menu" id="level_menu" class="input-mini" data-rule-required="false" value="' . $level_menu . '">
                                        </div>
                                    </div>-->
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Referensi Artikel</label>
                                        <div class="controls">
                                            <div class="span12">
                                                '.$refarticle.'
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Menu URL</label>
                                        <div class="controls">
                                            <input type="text" name="menu_url" id="menu_url" class="input-xlarge" data-rule-required="false" data-rule-minlength="2" value="' . $menu_url . '">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Active</label>
                                        <div class="controls">
                                            <div class="check-demo-col">
                                                <div class="check-line">
                                                        <input type="radio" id="c7" class="icheck-me" name="active" data-skin="square" data-color="blue" ' . (($active == 1) ? 'checked' : '') . ' value="1"> <label class="inline" for="c7">Active</label>
                                                </div>
                                                <div class="check-line">
                                                        <input type="radio" id="c8" class="icheck-me" name="active" data-skin="square" data-color="blue" ' . (($active == 0 || $active == '') ? 'checked' : '') . '  value="0"> <label class="inline" for="c8">Not Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" class="btn btn-primary" value="Simpan">
                                        <button type="button" class="btn">Batal</button>
                                    </div>
                                ';
                            $formeng = '
                                    
                                    <input type="hidden" name="lang_" value="2">
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Article Title</label>
                                        <div class="controls">
                                            <input type="text" name="menu_name_en" id="menu_name_en" class="input-xlarge" data-rule-required="true" data-rule-minlength="2" value="' . $menu_name . '">
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label class="control-label" for="textarea">Parent</label>
                                        <div class="controls">
                                            '.$parent_eng.'
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Date Created</label>
                                        <div class="controls">
                                            <input type="text" name="date_created_en" id="date_created_en" class="input-medium datepick" value="' . $date_created . '">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Menu Level</label>
                                        <div class="controls">
                                            <input type="text" name="level_menu_en" id="level_menu_en" class="input-mini" data-rule-required="false" value="' . $level_menu . '">
                                        </div>
                                    </div> -->
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Article Reference</label>
                                        <div class="controls">
                                            <div class="span12">
                                                '.$refarticle_eng.'
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Menu URL</label>
                                        <div class="controls">
                                            <input type="text" name="menu_url_en" id="menu_url_en" class="input-xlarge" data-rule-required="false" data-rule-minlength="2" value="' . $menu_url . '">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label for="textfield" class="control-label">Active</label>
                                        <div class="controls">
                                            <div class="check-demo-col">
                                                <div class="check-line">
                                                        <input type="radio" id="c7" class="icheck-me" name="active_en" data-skin="square" data-color="blue" ' . (($active == 1) ? 'checked' : '') . ' value="1"> <label class="inline" for="c7">Active</label>
                                                </div>
                                                <div class="check-line">
                                                        <input type="radio" id="c8" class="icheck-me" name="active_en" data-skin="square" data-color="blue" ' . (($active == 0 || $active == '') ? 'checked' : '') . '  value="0"> <label class="inline" for="c8">Not Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" class="btn btn-primary" value="Simpan">
                                        <button type="button" class="btn">Batal</button>
                                    </div>
                                ';

                            if ($data['fedit'] == TRUE) {
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