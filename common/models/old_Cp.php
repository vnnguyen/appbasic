<?php
namespace common\models;

// Dịch vụ

class Cp extends MyActiveRecord
{

	public static function tableName() {
		return '{{%dv}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [
			[['grouping', 'name', 'abbr', 'search', 'unit', 'info', 'variants'], 'filter', 'filter'=>'trim'],
			[['abbr', 'search'], 'filter', 'filter'=>'strtolower'],
			[['abbr'], 'unique'],
			[['total'], 'integer'],
			[['total'], 'default', 'value'=>0],
			[['stype', 'name', 'unit'], 'required'],
		];
	}

	public function scenarios()
	{
		return [
			'cp_c'=>['stype', 'grouping', 'name', 'abbr', 'search', 'total', 'unit', 'info', 'variants'],
			'cp_u'=>['stype', 'grouping', 'name', 'abbr', 'search', 'total', 'unit', 'info', 'variants'],
		];
	}

	public function getCpt()
	{
		return $this->hasMany(Cpt::className(), ['cp_id'=>'id']);
	}

	public function getCpg()
	{
		return $this->hasMany(Cpg::className(), ['dv_id'=>'id'])
			->orderBy('from_dt DESC, name');
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
