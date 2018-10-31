<?php
namespace common\models;

class Dv extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dv';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [['name', 'search', 'stype', 'object_type', 'is_dependent', 'object', 'specs', 'venue_id', 'by_company_id', 'from_loc', 'to_loc', 'booking_conds', 'use_conds', 'note', 'valid_from', 'valid_until'], 'trim'],
            [['name', 'search', 'stype', 'object_type', 'is_dependent'], 'required'],
            [['valid_from', 'valid_until'], 'default', 'value'=>'0000-00-00'],
            [['venue_id', 'by_company_id'], 'default', 'value'=>0],
        ];
    }

    public function scenarios()
    {
        return [
            'dv/c'=>['name', 'search', 'stype', 'object_type', 'is_dependent', 'object', 'specs', 'venue_id', 'by_company_id', 'from_loc', 'to_loc', 'booking_conds', 'use_conds', 'note', 'valid_from', 'valid_until'],
            'dv/u'=>['name', 'search', 'stype', 'object_type', 'is_dependent', 'object', 'specs', 'venue_id', 'by_company_id', 'from_loc', 'to_loc', 'booking_conds', 'use_conds', 'note', 'valid_from', 'valid_until'],
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

    public function getByCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'by_company_id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
    }

    public function getDvg()
    {
        // Maybe not needed
        return $this->hasMany(Dvg::className(), ['dv_id'=>'id']);
    }

    public function getDvt()
    {
        // Maybe not needed
        return $this->hasMany(Dvt::className(), ['dv_id'=>'id']);
    }
}
