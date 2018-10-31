<?php
namespace common\models;

// Dịch vụ

class Dv extends MyActiveRecord
{

	public static function tableName() {
		return '{{%dv}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [];
	}

	public function getDvt()
	{
		return $this->hasMany(Dvt::className(), ['dv_id'=>'id']);
	}

	public function getCompany()
	{
		return $this->hasOne(Company::className(), ['id'=>'company_id']);
	}

	public function getVenue()
	{
		return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
	}

}
