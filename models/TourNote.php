<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tour_note".
 *
 * @property string $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $ct_id
 * @property string $day_id
 * @property string $icon
 * @property string $color
 * @property string $content
 */
class TourNote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tour_note';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'ct_id', 'day_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'ct_id', 'day_id'], 'integer'],
            [['content'], 'string'],
            [['icon', 'color'], 'string', 'max' => 20],
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
            'ct_id' => Yii::t('app', 'Ct ID'),
            'day_id' => Yii::t('app', 'Day ID'),
            'icon' => Yii::t('app', 'Icon'),
            'color' => Yii::t('app', 'Color'),
            'content' => Yii::t('app', 'Content'),
        ];
    }
}
