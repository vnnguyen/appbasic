<?php
namespace app\assets;

use yii\web\AssetBundle;

class CkeditorAsset extends AssetBundle
{
	public $basePath = '@webroot/assets';
	public $baseUrl = '@web/assets';
	public $depends = [
		'yii\web\JqueryAsset',
	];
	public $css = [
	];
	public $js = [
		'https://cdn.ckeditor.com/4.5.9/full-all/ckeditor.js',
		'https://cdn.ckeditor.com/4.5.9/full-all/adapters/jquery.js',
		// 'ckfinder/ckfinder.js',
	];

	// Insert CKE code into page
	public static function ckeditorJs($el = 'textarea.ckeditor', $toolbar = 'full', $ckfinder = 'ckfinder')
	{
		if ($toolbar == 'full') {
			$toolbarConfig = <<<'TXT'
	removeButtons: 'Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Save,NewPage,Preview,Print,Cut,Copy,Paste,Find,Replace,SelectAll,Scayt,BidiLtr,BidiRtl,Language,Font,FontSize,Smiley,SpecialChar,PageBreak,Flash,Templates',
	toolbar: 'Full',
	toolbarGroups: [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'links', groups: [ 'links' ] },
		'/',
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	],
TXT;
		} elseif ($toolbar == 'basic') {
			$toolbarConfig = <<<'TXT'
toolbar: [
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
		{ name: 'links', items: [ 'Link', 'Unlink'] },
		{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule'] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
],
TXT;

		} else {
			$toolbarConfig = <<<'TXT'
	removeButtons: 'Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Save,NewPage,Preview,Print,Cut,Copy,Paste,Find,Replace,SelectAll,Scayt,BidiLtr,BidiRtl,Language,Font,FontSize,Smiley,SpecialChar,PageBreak,Flash,Templates',
	toolbar: 'Full',
	toolbarGroups: [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'links', groups: [ 'links' ] },
		'/',
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	],
TXT;

		}
		$js = <<<'TXT'
$('{$el}').ckeditor({
	allowedContent: 'h1 h2 h3 h4 h5 h6 p hr dd dt sub sup iframe embed table thead tbody tfoot tr th td span strong em s a i u ul ol li img blockquote[*]{*}(*);',
	contentsCss: '/assets/css/style_ckeditor.css',
	entities: false,
	entities_greek: false,
	entities_latin: false,
	extraPlugins: 'magicline,tableresize',
	// filebrowserBrowseUrl: '/app/ckfinder',
	//filebrowserUploadUrl: '/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Image'
	height: 500,
	$toolbarConfig
	uiColor: '#ffffff',
});


TXT;
		$js = str_replace(['{$el}', '$toolbarConfig'], [$el, $toolbarConfig], $js);
		return $js;
	}
}