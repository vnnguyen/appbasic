<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dvc".
 *
 * @property int $id
 * @property string $created_dt
 * @property int $created_by
 * @property string $updated_dt
 * @property int $updated_by
 * @property string $status
 * @property string $name
 * @property string $description
 * @property string $number
 * @property int $supplier_id
 * @property int $venue_id
 * @property string $signed_dt
 * @property string $amended_dt
 * @property string $note
 * @property string $valid_from_dt
 * @property string $valid_until_dt
 * @property string $body
 *
 * @property Dvd[] $dvds
 */
class Dvc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dvc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_dt', 'created_by', 'updated_dt', 'updated_by', 'name', 'description', 'number', 'supplier_id', 'venue_id', 'signed_dt', 'amended_dt', 'note', 'valid_from_dt', 'valid_until_dt', 'body'], 'required'],
            [['created_dt', 'updated_dt', 'signed_dt', 'amended_dt', 'valid_from_dt', 'valid_until_dt'], 'safe'],
            [['created_by', 'updated_by', 'supplier_id', 'venue_id'], 'integer'],
            [['status', 'note', 'body'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 256],
            [['number'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'number' => Yii::t('app', 'Number'),
            'supplier_id' => Yii::t('app', 'Supplier ID'),
            'venue_id' => Yii::t('app', 'Venue ID'),
            'signed_dt' => Yii::t('app', 'Signed Dt'),
            'amended_dt' => Yii::t('app', 'Amended Dt'),
            'note' => Yii::t('app', 'Note'),
            'valid_from_dt' => Yii::t('app', 'Valid From Dt'),
            'valid_until_dt' => Yii::t('app', 'Valid Until Dt'),
            'body' => Yii::t('app', 'Body'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDvds()
    {
        return $this->hasMany(Dvd::className(), ['dvc_id' => 'id']);
    }
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id'=>'supplier_id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
    }

    public function getDvd()
    {
        return $this->hasMany(Dvd::className(), ['dvc_id'=>'id']);
    }

    public function getCp()
    {
        return $this->hasMany(Cp::className(), ['dvc_id'=>'id']);
    }
}
