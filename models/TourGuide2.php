<?php

namespace app\models;
use Yii;

class TourGuide2 extends \yii\db\ActiveRecord
{
    public static function tableName() {
        return 'at_tour_guides';
    }

    public function getGuide()
    {
        return $this->hasOne(User::className(), ['id'=>'guide_user_id']);
    }

    public function getTour()
    {
        return $this->hasOne(AtCt::className(), ['id'=>'tour_id']);
    }
}