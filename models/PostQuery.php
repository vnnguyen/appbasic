<?php
namespace app\models;

use creocoder\taggable\TaggableQueryBehavior;

class PostQuery extends \yii\db\ActiveQuery
{
    public function behaviors()
    {
        return [
            TaggableQueryBehavior::className(),
        ];
    }
}
