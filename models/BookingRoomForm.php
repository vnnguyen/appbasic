<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_booking_rooms".
 *
 * @property string $id
 * @property string $created_dt
 * @property string $created_by
 * @property string $updated_dt
 * @property string $updated_by
 * @property string $status
 * @property string $booking_id
 * @property string $room_type
 * @property string $pax_ids
 * @property string $note
 */
class BookingRoomForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_booking_rooms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_dt', 'created_by', 'updated_dt', 'updated_by', 'status', 'booking_id', 'room_type', 'pax_ids', 'note'], 'required'],
            [['created_dt', 'updated_dt'], 'safe'],
            [['created_by', 'updated_by', 'booking_id'], 'integer'],
            [['note'], 'string'],
            [['status'], 'string', 'max' => 20],
            [['room_type'], 'string', 'max' => 64],
            [['pax_ids'], 'string', 'max' => 128],
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
            'room_type' => Yii::t('app', 'Room Type'),
            'pax_ids' => Yii::t('app', 'Pax Ids'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
