/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'en';
	config.uiColor = '#9AB8F3';
    config.filebrowserBrowseUrl ='http://localhost/cms/public/libs/ckeditor/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = 'http://localhost/cms/public/libs/ckeditor/ckfinder/ckfinder.html?type=Images';
	config.filebrowserFlashBrowseUrl = 'http://localhost/cms/public/libs/ckeditor/ckfinder/ckfinder.html?type=Flash';
	config.filebrowserUploadUrl = 'http://localhost/cms/public/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = 'http://localhost/cms/public/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = 'http://localhost/cms/public/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
    
    config.toolbar = 'Admin';
    

//    config.toolbar = [
//           ['Bold','Italic','Strike'],['SelectAll','RemoveFormat'],
//           ['Link','Unlink','Anchor'],['NumberedList','BulletedList'],['Image'], 
//           ['Cut','Copy','Paste','PasteText','PasteFromWord','-','SpellChecker'],
//           ['Maximize','ShowBlocks'],
//           '/',
//           ['Source'],['Format'],['TextColor','BGColor']['Smiley','SpecialChar'],['Undo','Redo','-','Find','Replace']
//    ];
};

CKEDITOR.config.toolbar_Admin = [
        ['Source'],
        ['Source','Maximize','ShowBlocks'],
        ['Bold','Italic','Strike'],
        ['SelectAll','RemoveFormat'],
        ['Link','Unlink','Anchor'],
        ['NumberedList','BulletedList'],
        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'], 
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','SpellChecker'],
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor'],
        ['Smiley','SpecialChar'],
        ['Undo','Redo','-','Find','Replace'],
        ['Link','Unlink','Anchor'],['NumberedList','BulletedList'],
    ] ;
    
    
CKEDITOR.replace( 'ckeditor_excerpt', {
    toolbar: [
            { name: 'document', items : [ 'Source'] },
            [ '-', 'Undo', 'Redo' ],			// Defines toolbar group without name.																				// Line break - next group will be placed in new line.
            { name: 'basicstyles', items: [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] }
        ],
        uiColor : '#9AB8F3'
});
