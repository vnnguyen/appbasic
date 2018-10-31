<?php
namespace common\models;

class Incident extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{incidents}}';
	}

	public function rules()
	{
		return [
			[['stype', 'name', 'tour_id', 'involving', 'status'], 'trim'],
			[['stype', 'name', 'tour_id', 'involving', 'status'], 'required'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

	public function getTour()
	{
		return $this->hasOne(Product::className(), ['id'=>'tour_id']);
	}

}
