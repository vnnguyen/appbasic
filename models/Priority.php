<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "priority".
 *
 * @property string $id
 * @property string $location
 * @property integer $category
 * @property string $content
 */
class Priority extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'priority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['location', 'category', 'content'], 'required'],
            [['category'], 'integer'],
            [['location'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'location' => Yii::t('app', 'Location'),
            'category' => Yii::t('app', 'Category'),
            'content' => Yii::t('app', 'Content'),
        ];
    }
}
