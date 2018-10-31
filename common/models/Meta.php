<?php
namespace common\models;

class Meta extends MyActiveRecord
{

	public static function tableName() {
		return '{{%meta}}';
	}

	public function attributeLabels() {
		return [
			'k'=>'Key',
			'v'=>'Value',
			'x'=>'Note/Extra',
			'f'=>'Format',
		];
	}

	public function rules()
	{
		return [
			[['k', 'v'], 'required'],
			[['v'], 'email', 'when'=>function($model) { return $model->k == 'email'; }, 'whenClient'=>"function (attribute, value) {return $('#meta-1-k').val() == 'email';}"],
		];
	}

}
