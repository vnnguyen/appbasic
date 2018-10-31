<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_tours".
 *
 * @property string $id
 * @property string $created_dt
 * @property string $created_by
 * @property string $uo
 * @property string $ub
 * @property string $status
 * @property string $status_date
 * @property string $canceled_from_day
 * @property string $owner
 * @property string $ct_id
 * @property string $tour_type
 * @property string $code
 * @property string $name
 * @property string $tour_tags
 * @property string $opened
 * @property string $closed
 * @property string $se
 * @property string $op
 * @property string $g1
 * @property string $g2
 * @property string $g3
 * @property string $x1
 * @property string $x2
 * @property string $x3
 * @property string $gprint
 * @property string $days
 * @property integer $pax_ratings
 */
class AtTours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_tours';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_dt', 'created_by', 'uo', 'ub', 'status', 'status_date', 'canceled_from_day', 'owner', 'ct_id', 'tour_type', 'code', 'name', 'tour_tags', 'opened', 'closed', 'g1', 'g2', 'g3', 'x1', 'x2', 'x3', 'gprint', 'days', 'pax_ratings'], 'required'],
            [['created_dt', 'uo', 'status_date', 'canceled_from_day', 'opened', 'closed'], 'safe'],
            [['created_by', 'ub', 'owner', 'ct_id', 'se', 'op', 'pax_ratings'], 'integer'],
            [['status', 'tour_type', 'gprint', 'days'], 'string'],
            [['code', 'g1', 'g2', 'g3', 'x1', 'x2', 'x3'], 'string', 'max' => 64],
            [['name', 'tour_tags'], 'string', 'max' => 128],
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
            'uo' => Yii::t('app', 'Uo'),
            'ub' => Yii::t('app', 'Ub'),
            'status' => Yii::t('app', 'Status'),
            'status_date' => Yii::t('app', 'Status Date'),
            'canceled_from_day' => Yii::t('app', 'Canceled From Day'),
            'owner' => Yii::t('app', 'Owner'),
            'ct_id' => Yii::t('app', 'Ct ID'),
            'tour_type' => Yii::t('app', 'Tour Type'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'tour_tags' => Yii::t('app', 'Tour Tags'),
            'opened' => Yii::t('app', 'Opened'),
            'closed' => Yii::t('app', 'Closed'),
            'se' => Yii::t('app', 'Se'),
            'op' => Yii::t('app', 'Op'),
            'g1' => Yii::t('app', 'G1'),
            'g2' => Yii::t('app', 'G2'),
            'g3' => Yii::t('app', 'G3'),
            'x1' => Yii::t('app', 'X1'),
            'x2' => Yii::t('app', 'X2'),
            'x3' => Yii::t('app', 'X3'),
            'gprint' => Yii::t('app', 'Gprint'),
            'days' => Yii::t('app', 'Days'),
            'pax_ratings' => Yii::t('app', 'Pax Ratings'),
        ];
    }
    public function getOperators()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('at_tour_user', ['tour_id'=>'id']);
            //->andWhere(['role'=>'operator']);
            //->onCondition(['at_tour_user.role'=>'operator']);
    }

    public function getCt()
    {
        return $this->hasOne(AtCt::className(), ['id'=>'ct_id']);
    }
}
