/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.toolbarGroups = [
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
        {name: 'allMedias'},
        {name: 'links'},
        {name: 'insert'},
        {name: 'allMedias'},
        {name: 'tools'},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'styles'},
        {name: 'colors'}
//		{ name: 'about' }
    ];
    config.format_span={element:"span", name: "span"};
    config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre;address;div';
    config.image2_alignClasses = [ 'align-left', 'align-center', 'align-right' ];
    config.allowedContent = true;
    config.fillEmptyBlocks = false;
    config.tabSpaces = 0;

    config.filebrowserBrowseUrl = 'http://' + self.location.host + '/sintesys/assets/admin/vendors/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl = 'http://' + self.location.host + '/sintesys/assets/admin/vendors/kcfinder/browse.php?type=image';
    config.filebrowserFlashBrowseUrl = 'http://' + self.location.host + '/sintesys/assets/admin.kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl = 'http://' + self.location.host + '/sintesys/assets/admin/vendors/kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl = 'http://' + self.location.host + '/sintesys/assets/admin/vendors/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl = 'http://' + self.location.host + '/sintesys/assets/admin/vendors/kcfinder/upload.php?type=flash';

};
