<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "way".
 *
 * @property integer $id
 * @property string $name
 * @property string $acro
 * @property integer $sl
 * @property string $unit
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $note
 */
class Way extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'way';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'acro', 'sl', 'unit', 'status'], 'required'],
            [['sl', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['note'], 'string'],
            [['name'], 'string', 'max' => 200],
            [['acro', 'unit', 'status'], 'string', 'max' => 20],
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
            'acro' => Yii::t('app', 'Acro'),
            'sl' => Yii::t('app', 'Sl'),
            'unit' => Yii::t('app', 'Unit'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
