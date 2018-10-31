<?php
namespace common\models;

class KaseStats extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%case_stats}}';
	}

	public function attributeLabels()
	{
		return [
			'pa_destinations'=>'Travel destinations',
			'pa_pax'=>'Pax (12 or 12-15)',
			'pa_pax_ages'=>'Ages (30,32,40 or 30-40)',
			'pa_days'=>'Days (15 or 15-20)',
			'pa_start_date'=>'Start date (2015-12-25 or 2015-12 or 2015)',
			'pa_tour_type'=>'Tour type (classic, trekking, ..)',
			'pa_group_type'=>'Group type (solo, family, ..)',
			'pa_tags'=>'Tags (comma-separated)',
		];
	}

	public function rules()
	{
		return [
			[['case_id'], 'unique'],
			[['pa_destinations', 'pa_pax', 'pa_pax_ages', 'pa_days', 'pa_start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags'], 'trim'],
			[['pa_destinations'], 'required', 'message'=>'Required'],
		];
	}

	public function scenarios()
	{
		return [
			'cases/request'=>['pa_destinations', 'pa_pax', 'pa_pax_ages', 'pa_days', 'pa_start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags'],
		];
	}

	public function getKase() {
		return $this->hasOne(Kase::className(), ['id' => 'case_id']);
	}

	public function getUpdatedBy() {
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}

}
