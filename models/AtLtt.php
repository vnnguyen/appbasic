<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_ltt".
 *
 * @property integer $id
 * @property string $created_dt
 * @property integer $created_by
 * @property string $updated_dt
 * @property integer $updated_by
 * @property string $status
 * @property string $status_detail
 * @property string $payment_ref
 * @property string $payment_dt
 * @property string $tkgn
 * @property string $mp
 * @property string $currency
 * @property string $xrate
 * @property string $note
 */
class AtLtt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_ltt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['created_dt', 'created_by', 'updated_dt', 'updated_by', 'status_detail', 'payment_ref', 'payment_dt', 'tkgn', 'mp', 'xrate', 'note'], 'required'],
            [['created_dt', 'updated_dt', 'payment_dt', 'status_detail', 'tkgn', 'mp', 'xrate', 'note'], 'safe'],
            // [['created_by', 'updated_by'], 'integer'],
            // [['status', 'note'], 'string'],
            // [['xrate'], 'number'],
            // [['status_detail'], 'string', 'max' => 128],
            // [['payment_ref'], 'string', 'max' => 32],
            // [['tkgn', 'mp'], 'string', 'max' => 10],
            // [['currency'], 'string', 'max' => 3],
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
            'status' => Yii::t('app', 'Status'),
            'status_detail' => Yii::t('app', 'Status Detail'),
            'payment_ref' => Yii::t('app', 'Payment Ref'),
            'payment_dt' => Yii::t('app', 'Payment Dt'),
            'tkgn' => Yii::t('app', 'Tkgn'),
            'mp' => Yii::t('app', 'Mp'),
            'currency' => Yii::t('app', 'Currency'),
            'xrate' => Yii::t('app', 'Xrate'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
