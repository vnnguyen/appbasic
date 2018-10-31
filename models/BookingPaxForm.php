<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_booking_pax".
 *
 * @property string $id
 * @property string $created_dt
 * @property string $created_by
 * @property string $updated_dt
 * @property string $updated_by
 * @property string $status
 * @property string $booking_id
 * @property string $user_id
 * @property string $name
 * @property string $is_repeating
 * @property string $passport_file
 * @property string $data
 * @property string $note
 */
class BookingPaxForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_booking_pax';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_dt', 'created_by', 'updated_dt', 'updated_by', 'status', 'booking_id', 'user_id', 'name', 'passport_file', 'data', 'note'], 'required'],
            [['created_dt', 'updated_dt'], 'safe'],
            [['created_by', 'updated_by', 'booking_id', 'user_id'], 'integer'],
            [['is_repeating', 'data', 'note'], 'string'],
            [['status'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 64],
            [['passport_file'], 'string', 'max' => 128],
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
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'is_repeating' => Yii::t('app', 'Is Repeating'),
            'passport_file' => Yii::t('app', 'Passport File'),
            'data' => Yii::t('app', 'Data'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
