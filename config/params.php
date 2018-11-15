<?php
date_default_timezone_set('UTC');
define('NOW', date('Y-m-d H:i:s'));
define('USER_ID', 1);
define('DIR', '/');
define('UPLOAD_PATH', '/var/www/manage.kienviet.net/upload/');
define('UPLOAD_URL', 'http://manage.kienviet.net/upload/');
define('SITE_PATH', '/var/www/manage.kienviet.net/');
define('SITE_URL', 'http://manage.kienviet.net/');
// URI, SEGS, SEGn
// $_REQUEST_URI = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
// define('URI', DIR == '/' ? $_REQUEST_URI : substr($_REQUEST_URI, strlen(trim(DIR, '/').'/')));
// $_URI_SEGMENTS = explode('/', URI);
// define('SEGS', empty($_URI_SEGMENTS) ? 0 : count($_URI_SEGMENTS));
for ($i = 1; $i <= 9; $i ++)
	define('SEG'.$i, isset($_URI_SEGMENTS[$i - 1]) ? $_URI_SEGMENTS[$i - 1] : '');

Yii::setAlias('common', 'D:/wamp/www/appbasic/common/');
return [
	'brand_name'=>'Amica Travel',
	'page_title'=>'',
	'page_meta_title'=>'',
    'common' => '@app',

	'allLanguages'=>['en', 'vi', 'fr'],

	'yesNoList' => [
		'yes'=>Yii::t('reg', 'Yes'),
		'no'=>Yii::t('reg', 'No'),
	],
	'genderList' => [
		'male'=>Yii::t('reg', 'Male'),
		'female'=>Yii::t('reg', 'Female'),
	],
	'paymentMethodList' => [
		'transfer'=>Yii::t('reg', 'Bank transfer'),
		'card'=>Yii::t('reg', 'Credit card'),
		'cash'=>Yii::t('reg', 'Cash'),
		'other'=>Yii::t('reg', 'Other'),
	],
	'roomTypeList' => [
		'single'=>Yii::t('reg', 'Single room'),
		'double'=>Yii::t('reg', 'Double room (one bed for two)'),
		'twin'=>Yii::t('reg', 'Twin room (two separated beds)'),
		'triple'=>Yii::t('reg', 'Triple room (three separated beds)'),
		'double+extra'=>Yii::t('reg', 'Double room with one extra bed'),
		'family'=>Yii::t('reg', 'Family room'),
	],

];
