<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_ngaymau".
 *
 * @property string $id
 * @property string $created_dt
 * @property string $created_by
 * @property string $updated_dt
 * @property string $updated_by
 * @property string $owner
 * @property string $parent_id
 * @property integer $sorder
 * @property string $title
 * @property string $body
 * @property string $tags
 * @property string $image
 * @property string $meals
 * @property string $transport
 * @property string $guides
 * @property string $note
 * @property string $language
 */
class AtNgaymau extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ngaymau';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'owner' => Yii::t('app', 'Owner'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'sorder' => Yii::t('app', 'Sorder'),
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
            'tags' => Yii::t('app', 'Tags'),
            'image' => Yii::t('app', 'Image'),
            'meals' => Yii::t('app', 'Meals'),
            'transport' => Yii::t('app', 'Transport'),
            'guides' => Yii::t('app', 'Guides'),
            'note' => Yii::t('app', 'Note'),
            'language' => Yii::t('app', 'Language'),
        ];
    }
}
