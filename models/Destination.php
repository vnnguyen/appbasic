<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_destinations".
 *
 * @property string $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $status
 * @property string $parent_destination_id
 * @property string $name_en
 * @property string $name_vi
 * @property string $name_fr
 * @property string $name_local
 * @property string $country_code
 * @property string $latlng
 */
class Destination extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_destinations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'parent_destination_id', 'name_en', 'name_vi', 'name_fr', 'name_local', 'country_code', 'latlng'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'parent_destination_id'], 'integer'],
            [['status'], 'string'],
            [['name_en', 'name_vi', 'name_fr', 'name_local'], 'string', 'max' => 64],
            [['country_code'], 'string', 'max' => 2],
            [['latlng'], 'string', 'max' => 32],
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
            'parent_destination_id' => Yii::t('app', 'Parent Destination ID'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_vi' => Yii::t('app', 'Name Vi'),
            'name_fr' => Yii::t('app', 'Name Fr'),
            'name_local' => Yii::t('app', 'Name Local'),
            'country_code' => Yii::t('app', 'Country Code'),
            'latlng' => Yii::t('app', 'Latlng'),
        ];
    }
}
