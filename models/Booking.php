<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_bookings".
 *
 * @property string $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $status
 * @property string $status_dt
 * @property string $finish
 * @property string $finish_dt
 * @property string $status_old
 * @property string $case_id
 * @property string $deal_id
 * @property string $product_id
 * @property string $valid_until
 * @property string $propose_to
 * @property string $prices
 * @property string $terms
 * @property string $price
 * @property string $currency
 * @property string $start_date
 * @property string $pax
 * @property string $note
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_bookings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'status_dt', 'finish', 'finish_dt', 'status_old', 'case_id', 'deal_id', 'product_id', 'valid_until', 'propose_to', 'prices', 'terms', 'price', 'currency', 'start_date', 'pax', 'note'], 'required'],
            [['created_at', 'updated_at', 'status_dt', 'finish_dt', 'valid_until', 'start_date'], 'safe'],
            [['created_by', 'updated_by', 'case_id', 'deal_id', 'product_id', 'pax'], 'integer'],
            [['propose_to', 'prices', 'terms', 'note'], 'string'],
            [['price'], 'number'],
            [['status', 'finish', 'status_old'], 'string', 'max' => 20],
            [['currency'], 'string', 'max' => 3],
        ];
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
            'status' => Yii::t('app', 'Status'),
            'status_dt' => Yii::t('app', 'Status Dt'),
            'finish' => Yii::t('app', 'Finish'),
            'finish_dt' => Yii::t('app', 'Finish Dt'),
            'status_old' => Yii::t('app', 'Status Old'),
            'case_id' => Yii::t('app', 'Case ID'),
            'deal_id' => Yii::t('app', 'Deal ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'valid_until' => Yii::t('app', 'Valid Until'),
            'propose_to' => Yii::t('app', 'Propose To'),
            'prices' => Yii::t('app', 'Prices'),
            'terms' => Yii::t('app', 'Terms'),
            'price' => Yii::t('app', 'Price'),
            'currency' => Yii::t('app', 'Currency'),
            'start_date' => Yii::t('app', 'Start Date'),
            'pax' => Yii::t('app', 'Pax'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    public function getCase() {
        return $this->hasOne(AtCase::className(), ['id' => 'case_id']);
    }

    public function getOwner() {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getPeople() {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('at_booking_user', ['booking_id'=>'id']);
    }

    // public function getReport() {
    //     return $this->hasOne(BookingReport::className(), ['booking_id' => 'id']);
    // }

    public function getProduct() {
        return $this->hasOne(AtCt::className(), ['id' => 'product_id']);
    }

    public function getPax() {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('at_booking_user', ['booking_id'=>'id']);
    }

    public function getInvoices() {
        return $this->hasMany(Invoice::className(), ['booking_id' => 'id']);
    }

    public function getPayments() {
        return $this->hasMany(Payment::className(), ['booking_id' => 'id']);
    }
}
