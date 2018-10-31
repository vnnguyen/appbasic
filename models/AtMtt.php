<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_mtt".
 *
 * @property integer $id
 * @property string $created_dt
 * @property integer $created_by
 * @property string $updated_dt
 * @property integer $updated_by
 * @property string $status
 * @property integer $cpt_id
 * @property string $payment_dt
 * @property string $tkgn
 * @property string $mp
 * @property double $amount
 * @property string $currency
 * @property double $xrate
 * @property string $paid_in_full
 * @property string $note
 * @property string $check
 */
class AtMtt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_mtt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['created_dt', 'created_by', 'updated_dt', 'updated_by', 'cpt_id', 'payment_dt', 'tkgn', 'mp', 'amount', 'xrate', 'note', 'check'], 'required'],
            // [['created_dt', 'updated_dt', 'payment_dt'], 'safe'],
            // [['created_by', 'updated_by', 'cpt_id'], 'integer'],
            // [['status', 'paid_in_full', 'note'], 'string'],
            // [['amount', 'xrate'], 'number'],
            // [['tkgn', 'mp'], 'string', 'max' => 10],
            // [['currency'], 'string', 'max' => 3],
            // [['check'], 'string', 'max' => 32],
            [['payment_dt', 'tkgn', 'mp', 'amount', 'currency', 'xrate', 'paid_in_full', 'note'], 'trim'],
            [['amount', 'xrate'], 'number', 'message'=>'Không hợp lệ'],
            [['amount', 'xrate', 'currency'], 'required', 'message'=>'Còn thiếu'],
        ];
    }

    public function getCpt()
    {
        return $this->hasOne(Cpt::className(), ['dvtour_id' => 'cpt_id']);
    }
    public function getVenue()
    {
        return $this->hasOne(venue::className(), ['id' => 'venue_id'])->via('cpt');
    }
    public function getLtt()
    {
        return $this->hasOne(AtLtt::className(), ['id' => 'ltt_id']);
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
            'status' => Yii::t('app', 'Status'),
            'cpt_id' => Yii::t('app', 'Cpt ID'),
            'payment_dt' => Yii::t('app', 'Payment Dt'),
            'tkgn' => Yii::t('app', 'Tkgn'),
            'mp' => Yii::t('app', 'Mp'),
            'amount' => Yii::t('app', 'Amount'),
            'currency' => Yii::t('app', 'Currency'),
            'xrate' => Yii::t('app', 'Xrate'),
            'paid_in_full' => Yii::t('app', 'Paid In Full'),
            'note' => Yii::t('app', 'Note'),
            'check' => Yii::t('app', 'Check'),
        ];
    }
}
