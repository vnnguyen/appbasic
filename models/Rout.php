<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rout".
 *
 * @property string $id
 * @property integer $dv_id
 * @property integer $venue_id
 * @property string $content
 */
class Rout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rout';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dv_id', 'venue_id', 'content'], 'required'],
            [['dv_id', 'venue_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dv_id' => Yii::t('app', 'Dv ID'),
            'venue_id' => Yii::t('app', 'Venue ID'),
            'content' => Yii::t('app', 'Content'),
        ];
    }
}
