<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "post".
 *
 * @property string $id
 * @property string $post_title
 * @property string $post_status
 * @property string $post_excerpt
 * @property string $post_content
 * @property string $attach_file
 * @property string $date_issued
 * @property string $start_day
 * @property string $expiry_day
 * @property string $people_signing
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class Post extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_title', 'post_status', 'post_excerpt', 'post_content', 'date_issued', 'start_day', 'people_signing', 'create_by', 'create_at'], 'required'],
            [['post_content'], 'string'],
            [['date_issued', 'start_day', 'expiry_day', 'create_at', 'update_at'], 'safe'],
            [['post_title'], 'string', 'max' => 256],
            [['post_status'], 'string', 'max' => 20],
            [['post_excerpt'], 'string', 'max' => 600],
            [['attach_file'], 'file'],
            [['people_signing'], 'string', 'max' => 100],
            [['create_by', 'update_by'], 'integer', 'max' => 50],
            // [['date_issued','start_day'], 'my_required'],
            [
                'start_day',
                'compare',
                'compareAttribute'=>'date_issued',
                'operator'=>'>=',
                'message'=>Yii::t('app', '{start day} must be greater than "{date issued}".')
            ],
            [
                'expiry_day',
                'compare',
                'compareAttribute'=>'start_day',
                'operator'=>'>=',
                'message'=>Yii::t('app', '{expiry day} must be greater than "{start day}".')
              ]
        ];
    }
    // public function my_required($attribute_name,$params) 
    // {
    //     if ($this->start_day < $this->date_issued) {
    //         $this->addError($attribute_name, 'start day must great than date issued');
    //     }
    // }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_title' => Yii::t('app', 'Post Title'),
            'post_status' => Yii::t('app', 'Post Status'),
            'post_excerpt' => Yii::t('app', 'Post Excerpt'),
            'post_content' => Yii::t('app', 'Post Content'),
            'attach_file' => Yii::t('app', 'Attach File'),
            'date_issued' => Yii::t('app', 'Date Issued'),
            'start_day' => Yii::t('app', 'Start Day'),
            'expiry_day' => Yii::t('app', 'Expiry Day'),
            'people_signing' => Yii::t('app', 'People Signing'),
            'create_by' => Yii::t('app', 'Create By'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_by' => Yii::t('app', 'Update By'),
            'update_at' => Yii::t('app', 'Update At'),
        ];
    }
    public function getListData(){
        
    }
}
