<?php
namespace app\common\models;

class Product extends MyActiveRecord
{


	public static $types = [
		'tour'=>['id'=>1, 'name'=>'Private tour', 'alias'=>'tour'],
		'vpctour'=>['id'=>2, 'name'=>'VPC tour', 'alias'=>'vpctour'],
		'tcgtour'=>['id'=>3, 'name'=>'TCG tour', 'alias'=>'tcgtour'],
	];
	
	public static function tableName() {
		return '{{%ct}}';
	}

	public function attributeLabels() {
		return [
		];
	}

	public function rules() {
		return [
			[['title', 'about', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'tags', 'client_ref'], 'safe'],
			[['offer_type', 'language', 'title', 'about', 'pax', 'day_from', 'price_unit', 'price_for', 'price_until'], 'safe', 'message'=>'Required'],
			[['pax'], 'integer', 'min'=>0],
			[['price'], 'number', 'min'=>0],
			[['price'], 'default', 'value'=>0],
			[['day_from', 'price_until'], 'date', 'format'=>'Y-m-d', 'message'=>'Date must be of "yyyy-mm-dd" format'],

			[['op_code'], 'unique'],
			[['op_code', 'op_name'], 'required'],
		];
	}

	public function scenarios() {
		return [
			'product/c/prod'=>['title', 'about', 'language', 'pax', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
			'product/u/prod'=>['title', 'about', 'language', 'pax', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
			'products_c'=>['title', 'about', 'offer_type', 'pax', 'esprit', 'points', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
			'products_u'=>['title', 'about', 'offer_type', 'language', 'pax', 'day_from', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
			'products_copy'=>['title', 'summary'],
			'products_u-op'=>['op_code', 'op_name'],
			'product/ref'=>['client_ref'],
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

	public function getBookings()
	{
		return $this->hasMany(Booking::className(), ['product_id'=>'id']);
	}

	public function getDays()
	{
		return $this->hasMany(Day::className(), ['rid'=>'id']);
	}

	public function getTournotes()
	{
		return $this->hasMany(Tournote::className(), ['product_id'=>'id']);
	}

	public function getTourStats() {
		return $this->hasOne(TourStats::className(), ['tour_id' => 'id']);
	}

	public function getTour()
	{
		return $this->hasOne(Tour::className(), ['ct_id'=>'id']);
	}

	public function getGuides()
	{
		return $this->hasMany(TourGuide2::className(), ['tour_id'=>'id']);
	}
}
