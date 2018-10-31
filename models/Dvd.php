<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dvd".
 *
 * @property int $id
 * @property string $updated_dt
 * @property int $updated_by
 * @property int $dvc_id
 * @property string $stype
 * @property string $code
 * @property string $def
 * @property string $desc
 *
 * @property Dvc $dvc
 */
class Dvd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dvd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_dt', 'updated_by', 'dvc_id', 'stype', 'code', 'def', 'desc'], 'required'],
            [['updated_dt'], 'safe'],
            [['updated_by', 'dvc_id'], 'integer'],
            [['stype'], 'string'],
            [['code'], 'string', 'max' => 20],
            [['def', 'desc'], 'string', 'max' => 256],
            [['dvc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dvc::className(), 'targetAttribute' => ['dvc_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'dvc_id' => Yii::t('app', 'Dvc ID'),
            'stype' => Yii::t('app', 'Stype'),
            'code' => Yii::t('app', 'Code'),
            'def' => Yii::t('app', 'Def'),
            'desc' => Yii::t('app', 'Desc'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDvc()
    {
        return $this->hasOne(Dvc::className(), ['id' => 'dvc_id']);
    }
}
