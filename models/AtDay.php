<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_days".
 *
 * @property string $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $status
 * @property string $rid
 * @property string $parent_day_id
 * @property string $booking_id
 * @property integer $step
 * @property string $day
 * @property string $name
 * @property string $body
 * @property string $image
 * @property string $meals
 * @property string $guides
 * @property string $transport
 * @property string $note
 */
class AtDay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_days';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'rid', 'parent_day_id', 'booking_id', 'day', 'name', 'body', 'image', 'meals', 'guides', 'transport', 'note'], 'required'],
            [['created_at', 'updated_at', 'day'], 'safe'],
            [['created_by', 'updated_by', 'rid', 'parent_day_id', 'booking_id', 'step'], 'integer'],
            [['status', 'body', 'note'], 'string'],
            [['name', 'image'], 'string', 'max' => 128],
            [['meals'], 'string', 'max' => 3],
            [['guides', 'transport'], 'string', 'max' => 64],
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
            'rid' => Yii::t('app', 'Rid'),
            'parent_day_id' => Yii::t('app', 'Parent Day ID'),
            'booking_id' => Yii::t('app', 'Booking ID'),
            'step' => Yii::t('app', 'Step'),
            'day' => Yii::t('app', 'Day'),
            'name' => Yii::t('app', 'Name'),
            'body' => Yii::t('app', 'Body'),
            'image' => Yii::t('app', 'Image'),
            'meals' => Yii::t('app', 'Meals'),
            'guides' => Yii::t('app', 'Guides'),
            'transport' => Yii::t('app', 'Transport'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
