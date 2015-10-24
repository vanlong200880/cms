/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'vi';
	 config.uiColor = '#9AB8F3';
    config.filebrowserBrowseUrl ='http://localhost:8080/libs/ckeditor/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = 'http://localhost:8080/cms/public/libs/ckeditor/ckfinder/ckfinder.html?type=Images';
	config.filebrowserFlashBrowseUrl = 'http://localhost:8080/cms/public/libs/ckeditor/ckfinder/ckfinder.html?type=Flash';
	config.filebrowserUploadUrl = 'http://localhost:8080/cms/public/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = 'http://localhost:8080/cms/public/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = 'http://localhost:8080/cms/public/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};
