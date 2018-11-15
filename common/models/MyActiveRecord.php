<?php
namespace common\models;

use yii\db\ActiveRecord;

class MyActiveRecord extends ActiveRecord
{
	public static $statusList = [
	''=>'-',
	'on'=>'On',
	'off'=>'Off',
	'draft'=>'Draft',
	'deleted'=>'Deleted',
	];
	public static $yesNoList = [
	''=>'-',
	'yes'=>'Yes',
	'no'=>'No',
	];
}
