<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_tasks".
 *
 * @property string $id
 * @property string $co
 * @property string $cb
 * @property string $uo
 * @property string $ub
 * @property string $status
 * @property string $description
 * @property string $mins
 * @property string $due_dt
 * @property string $is_priority
 * @property string $fuzzy
 * @property string $is_all
 * @property integer $assignee_count
 * @property string $rtype
 * @property string $rid
 * @property string $n_id
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['co', 'cb', 'uo', 'status', 'description', 'mins', 'due_dt', 'rtype', 'rid', 'n_id'], 'required'],
            [['co', 'uo', 'due_dt'], 'safe'],
            [['cb', 'ub', 'mins', 'assignee_count', 'rid', 'n_id'], 'integer'],
            [['status', 'is_priority', 'fuzzy', 'is_all', 'rtype'], 'string'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'co' => Yii::t('app', 'Co'),
            'cb' => Yii::t('app', 'Cb'),
            'uo' => Yii::t('app', 'Uo'),
            'ub' => Yii::t('app', 'Ub'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
            'mins' => Yii::t('app', 'Mins'),
            'due_dt' => Yii::t('app', 'Due Dt'),
            'is_priority' => Yii::t('app', 'Is Priority'),
            'fuzzy' => Yii::t('app', 'Fuzzy'),
            'is_all' => Yii::t('app', 'Is All'),
            'assignee_count' => Yii::t('app', 'Assignee Count'),
            'rtype' => Yii::t('app', 'Rtype'),
            'rid' => Yii::t('app', 'Rid'),
            'n_id' => Yii::t('app', 'N ID'),
        ];
    }
}
