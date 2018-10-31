<?php

namespace app\models;

use Yii;
use app\components\MyTaggable;

/**
 * This is the model class for table "at_ngaymau".
 *
 * @property string $id
 * @property string $uo
 * @property string $ub
 * @property string $ngaymau_title
 * @property string $ngaymau_body
 * @property string $ngaymau_tags
 * @property string $ngaymau_image
 * @property string $ngaymau_meals
 * @property string $ngaymau_transport
 * @property string $ngaymau_hotels
 * @property string $ngaymau_guides
 * @property string $ngaymau_services
 * @property string $language
 */
class AtNgaymau extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_ngaymau';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uo', 'ub', 'ngaymau_title', 'ngaymau_body', 'ngaymau_tags', 'ngaymau_image', 'ngaymau_meals', 'ngaymau_transport', 'ngaymau_hotels', 'ngaymau_guides', 'ngaymau_services'], 'required'],
            [['uo'], 'safe'],
            [['ub'], 'integer'],
            [['ngaymau_body', 'ngaymau_tags', 'ngaymau_meals', 'ngaymau_services', 'language'], 'string'],
            [['ngaymau_title', 'ngaymau_image', 'ngaymau_transport', 'ngaymau_hotels', 'ngaymau_guides'], 'string', 'max' => 128],
            ['tagValues', 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'taggable' => [
                'class' => MyTaggable::className(),
                'parent' => 2,
                // 'tagValuesAsArray' => false,
                // 'tagRelation' => 'tags',
                // 'tagValueAttribute' => 'name',
                // 'tagFrequencyAttribute' => 'frequency',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('post_tag_assn', ['post_id' => 'id'],
            function($query) {
                          $query->onCondition(['parent' =>2]);
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
            'uo' => Yii::t('app', 'Uo'),
            'ub' => Yii::t('app', 'Ub'),
            'ngaymau_title' => Yii::t('app', 'Ngaymau Title'),
            'ngaymau_body' => Yii::t('app', 'Ngaymau Body'),
            'ngaymau_tags' => Yii::t('app', 'Ngaymau Tags'),
            'ngaymau_image' => Yii::t('app', 'Ngaymau Image'),
            'ngaymau_meals' => Yii::t('app', 'Ngaymau Meals'),
            'ngaymau_transport' => Yii::t('app', 'Ngaymau Transport'),
            'ngaymau_hotels' => Yii::t('app', 'Ngaymau Hotels'),
            'ngaymau_guides' => Yii::t('app', 'Ngaymau Guides'),
            'ngaymau_services' => Yii::t('app', 'Ngaymau Services'),
            'language' => Yii::t('app', 'Language'),
        ];
    }
    public static function find()
    {
        return new PostQuery(get_called_class());
    }
}
