<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_ct".
 *
 * @property string $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $owner
 * @property string $status
 * @property string $op_status
 * @property string $uid
 * @property string $offer_type
 * @property integer $offer_count
 * @property string $about
 * @property integer $day_count
 * @property string $day_from
 * @property string $day_until
 * @property integer $pax
 * @property string $title
 * @property string $intro
 * @property string $esprit
 * @property string $points
 * @property string $conditions
 * @property string $others
 * @property string $summary
 * @property string $tags
 * @property string $promo
 * @property string $price
 * @property string $price_unit
 * @property string $price_for
 * @property string $price_until
 * @property string $prices
 * @property string $image
 * @property string $day_ids
 * @property string $language
 * @property string $client_ref
 * @property string $op_code
 * @property string $op_name
 * @property string $op_finish
 * @property string $op_finish_dt
 */
class AtCt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_ct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'status', 'uid', 'offer_type', 'offer_count', 'about', 'day_count', 'day_from', 'day_until', 'pax', 'title', 'intro', 'esprit', 'points', 'conditions', 'others', 'summary', 'tags', 'promo', 'price_until', 'prices', 'image', 'day_ids', 'client_ref', 'op_code', 'op_name', 'op_finish', 'op_finish_dt'], 'required'],
            [['created_at', 'updated_at', 'day_from', 'day_until', 'price_until', 'op_finish_dt'], 'safe'],
            [['created_by', 'updated_by', 'offer_count', 'day_count', 'pax', 'price'], 'integer'],
            [['owner', 'status', 'op_status', 'offer_type', 'intro', 'esprit', 'points', 'conditions', 'others', 'summary', 'tags', 'promo', 'price_unit', 'price_for', 'prices', 'day_ids', 'language'], 'string'],
            [['uid'], 'string', 'max' => 10],
            [['about', 'title', 'image', 'op_name'], 'string', 'max' => 128],
            [['client_ref', 'op_code'], 'string', 'max' => 64],
            [['op_finish'], 'string', 'max' => 20],
        ];
    }

    public function getDays() {
        return $this->hasMany(AtDay::className(), ['rid' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'owner' => Yii::t('app', 'Owner'),
            'status' => Yii::t('app', 'Status'),
            'op_status' => Yii::t('app', 'Op Status'),
            'uid' => Yii::t('app', 'Uid'),
            'offer_type' => Yii::t('app', 'Offer Type'),
            'offer_count' => Yii::t('app', 'Offer Count'),
            'about' => Yii::t('app', 'About'),
            'day_count' => Yii::t('app', 'Day Count'),
            'day_from' => Yii::t('app', 'Day From'),
            'day_until' => Yii::t('app', 'Day Until'),
            'pax' => Yii::t('app', 'Pax'),
            'title' => Yii::t('app', 'Title'),
            'intro' => Yii::t('app', 'Intro'),
            'esprit' => Yii::t('app', 'Esprit'),
            'points' => Yii::t('app', 'Points'),
            'conditions' => Yii::t('app', 'Conditions'),
            'others' => Yii::t('app', 'Others'),
            'summary' => Yii::t('app', 'Summary'),
            'tags' => Yii::t('app', 'Tags'),
            'promo' => Yii::t('app', 'Promo'),
            'price' => Yii::t('app', 'Price'),
            'price_unit' => Yii::t('app', 'Price Unit'),
            'price_for' => Yii::t('app', 'Price For'),
            'price_until' => Yii::t('app', 'Price Until'),
            'prices' => Yii::t('app', 'Prices'),
            'image' => Yii::t('app', 'Image'),
            'day_ids' => Yii::t('app', 'Day Ids'),
            'language' => Yii::t('app', 'Language'),
            'client_ref' => Yii::t('app', 'Client Ref'),
            'op_code' => Yii::t('app', 'Op Code'),
            'op_name' => Yii::t('app', 'Op Name'),
            'op_finish' => Yii::t('app', 'Op Finish'),
            'op_finish_dt' => Yii::t('app', 'Op Finish Dt'),
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

    // public function getTournotes()
    // {
    //     return $this->hasMany(Tournote::className(), ['product_id'=>'id']);
    // }

    // public function getTourStats() {
    //     return $this->hasOne(TourStats::className(), ['tour_id' => 'id']);
    // }

    public function getTour()
    {
        return $this->hasOne(AtTours::className(), ['ct_id'=>'id']);
    }

    public function getGuides()
    {
        return $this->hasMany(TourGuide2::className(), ['tour_id'=>'id']);
    }
}
