<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_booking_flights".
 *
 * @property string $id
 * @property string $created_dt
 * @property string $created_by
 * @property string $updated_dt
 * @property string $updated_by
 * @property string $status
 * @property string $booking_id
 * @property string $stype
 * @property string $number
 * @property string $departure_port
 * @property string $arrival_port
 * @property string $departure_dt
 * @property string $arrival_dt
 * @property string $pax_ids
 * @property string $note
 */
class BookingFlightForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_booking_flights';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_dt', 'created_by', 'updated_dt', 'updated_by', 'status', 'booking_id', 'stype', 'number', 'departure_port', 'arrival_port', 'departure_dt', 'arrival_dt', 'pax_ids', 'note'], 'required'],
            [['created_dt', 'updated_dt', 'departure_dt', 'arrival_dt'], 'safe'],
            [['created_by', 'updated_by', 'booking_id'], 'integer'],
            [['note'], 'string'],
            [['status', 'stype', 'number'], 'string', 'max' => 20],
            [['departure_port', 'arrival_port'], 'string', 'max' => 64],
            // [['pax_ids'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'booking_id' => Yii::t('app', 'Booking ID'),
            'stype' => Yii::t('app', 'Stype'),
            'number' => Yii::t('app', 'Number'),
            'departure_port' => Yii::t('app', 'Departure Port'),
            'arrival_port' => Yii::t('app', 'Arrival Port'),
            'departure_dt' => Yii::t('app', 'Departure Dt'),
            'arrival_dt' => Yii::t('app', 'Arrival Dt'),
            'pax_ids' => Yii::t('app', 'Pax Ids'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
