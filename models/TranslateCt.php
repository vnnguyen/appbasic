<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "translate_ct".
 *
 * @property integer $id
 * @property integer $ct_id
 * @property integer $day_id
 * @property string $title_t
 * @property string $content_t
 * @property integer $created_by
 * @property string $created_on
 * @property integer $updated_by
 * @property string $updated_on
 */
class TranslateCt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'translate_ct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ct_id', 'day_id', 'title_t', 'content_t', 'created_by', 'created_on', 'updated_by', 'updated_on'], 'required'],
            [['ct_id', 'day_id', 'created_by', 'updated_by'], 'integer'],
            [['content_t'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['title_t'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ct_id' => Yii::t('app', 'Ct ID'),
            'day_id' => Yii::t('app', 'Day ID'),
            'title_t' => Yii::t('app', 'Title T'),
            'content_t' => Yii::t('app', 'Content T'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_on' => Yii::t('app', 'Updated On'),
        ];
    }
}
