<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "positions".
 *
 * @property integer $id
 * @property string $name
 * @property integer $qty
 * @property string $status
 * @property string $description
 */
class Position extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'positions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'qty', 'status', 'description'], 'required'],
            [['qty'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'qty' => Yii::t('app', 'Qty'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
