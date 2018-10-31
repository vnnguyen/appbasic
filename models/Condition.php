<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "condition".
 *
 * @property integer $id
 * @property string $code
 * @property string $category
 * @property string $operator
 * @property string $from
 * @property string $to
 * @property string $equal
 * @property string $description
 */
class Condition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'category', 'operator'], 'required'],
            [['description'], 'string'],
            [['code'], 'string', 'max' => 10],
            [['category', 'operator'], 'string', 'max' => 15],
            [['from'], 'string', 'max' => 50],
            [['to', 'equal'], 'string', 'max' => 100],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'category' => Yii::t('app', 'Category'),
            'operator' => Yii::t('app', 'Operator'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'equal' => Yii::t('app', 'Equal'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
