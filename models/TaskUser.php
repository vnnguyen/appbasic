<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_task_user".
 *
 * @property string $task_id
 * @property string $user_id
 * @property string $assigned_dt
 * @property string $completed_dt
 */
class TaskUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_task_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id', 'assigned_dt', 'completed_dt'], 'required'],
            [['task_id', 'user_id'], 'integer'],
            [['assigned_dt', 'completed_dt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => Yii::t('app', 'Task ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'assigned_dt' => Yii::t('app', 'Assigned Dt'),
            'completed_dt' => Yii::t('app', 'Completed Dt'),
        ];
    }
}
