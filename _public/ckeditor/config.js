/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.toolbar =
		[
			[ 'Source','NewPage','Preview' ],
			[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],
			[ 'Find','Replace','-','SelectAll','-','Scayt' ],
			[ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
			[ 'Image','Flash','Table','HorizontalRule','SpecialChar','PageBreak'],
			[ 'Styles','Format' ],
			[ 'Bold','Italic','Strike','-','RemoveFormat' ]
		];

	config.format_tags = 'p;h1;h2;pre';
	config.resize_minWidth = 200;
	config.resize_maxWidth = 1200;

	// 한국어설정
	config.language = 'ko';
	
	// 필터링 해제
	config.allowedContent = true;
	
	//업로드경로
	config.filebrowserUploadUrl = '/uploader/upload';

};
