<?php
namespace common\models;

class Dvc extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dvc';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [[
                'name', 'number', 'description', 'note',
                ], 'trim'],
            [[
                'name',
                ], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'dvc/c'=>[
                'name', 'number', 'description', 'note',
                ],
            'dvc/u'=>[
                'name', 'number', 'description', 'note',
                ],
        ];
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
