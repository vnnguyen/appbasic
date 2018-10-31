<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mailqueue".
 *
 * @property string $id
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property string $attachs
 * @property integer $priority
 * @property integer $status
 * @property string $createDate
 * @property string $updateDate
 */
class MailQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailqueue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'subject', 'body', 'priority', 'status', 'createDate', 'updateDate'], 'required'],
            [['body', 'attachs'], 'string'],
            [['priority', 'status'], 'integer'],
            [['createDate', 'updateDate'], 'safe'],
            [['from', 'to', 'subject'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Body'),
            'attachs' => Yii::t('app', 'Attachs'),
            'priority' => Yii::t('app', 'Priority'),
            'status' => Yii::t('app', 'Status'),
            'createDate' => Yii::t('app', 'Create Date'),
            'updateDate' => Yii::t('app', 'Update Date'),
        ];
    }
}
