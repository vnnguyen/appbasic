<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_countries".
 *
 * @property string $code
 * @property string $name_en
 * @property string $name_vi
 * @property string $name_fr
 * @property string $name_local
 * @property string $dial_code
 */
class AtCountries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name_en', 'name_vi', 'name_fr', 'name_local', 'dial_code'], 'required'],
            [['code'], 'string', 'max' => 2],
            [['name_en', 'name_vi', 'name_fr', 'name_local'], 'string', 'max' => 64],
            [['dial_code'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_vi' => Yii::t('app', 'Name Vi'),
            'name_fr' => Yii::t('app', 'Name Fr'),
            'name_local' => Yii::t('app', 'Name Local'),
            'dial_code' => Yii::t('app', 'Dial Code'),
        ];
    }
}
