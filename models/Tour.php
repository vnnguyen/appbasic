<?php

namespace app\models;

use Yii;
use app\components\MyTaggable;

/**
 * This is the model class for table "tour".
 *
 * @property string $id
 * @property string $title
 * @property string $excerpt
 * @property string $start_date
 *
 * @property Daystour[] $daystours
 */
class Tour extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tour';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'excerpt', 'start_date'], 'required'],
            [['excerpt'], 'string'],
            [['start_date'], 'safe'],
            [['title'], 'string', 'max' => 200],
            ['tagValues', 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'taggable' => [
                'class' => MyTaggable::className(),
                'parent' => 1,
                // 'tagValuesAsArray' => false,
                // 'tagRelation' => 'tags',
                // 'tagValueAttribute' => 'name',
                // 'tagFrequencyAttribute' => 'frequency',
            ],
        ];
    }
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('post_tag_assn', ['post_id' => 'id'],
            function($query) {
                          $query->onCondition(['parent' =>1]);
                      }
        );
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'excerpt' => Yii::t('app', 'Excerpt'),
            'start_date' => Yii::t('app', 'Start Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDaystours()
    {
        return $this->hasMany(Daystour::className(), ['tour_id' => 'id']);
    }
    public function getOperators()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('at_tour_user', ['tour_id'=>'id']);
            //->andWhere(['role'=>'operator']);
            //->onCondition(['at_tour_user.role'=>'operator']);
    }
}
