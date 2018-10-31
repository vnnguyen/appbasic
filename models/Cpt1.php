<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cpt1".
 *
 * @property integer $id
 * @property integer $cpt_id
 * @property integer $dv_id
 */
class Cpt1 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cpt1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cpt_id', 'dv_id'], 'required'],
            [['cpt_id', 'dv_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cpt_id' => Yii::t('app', 'Cpt ID'),
            'dv_id' => Yii::t('app', 'Dv ID'),
        ];
    }
}
