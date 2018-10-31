<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cp".
 *
 * @property string $id
 * @property string $account_id
 * @property string $created_dt
 * @property string $created_by
 * @property string $updated_dt
 * @property string $updated_by
 * @property string $status
 * @property string $dv_id
 * @property string $dvc_id
 * @property string $via_company_id
 * @property string $period
 * @property string $conds
 * @property string $search
 * @property string $price
 * @property string $currency
 * @property string $info
 */
class Cp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'created_dt', 'created_by', 'updated_dt', 'updated_by', 'status', 'dv_id', 'dvc_id', 'via_company_id', 'period', 'conds', 'search', 'price', 'currency', 'info'], 'required'],
            [['account_id', 'created_by', 'updated_by', 'dv_id', 'dvc_id', 'via_company_id'], 'integer'],
            [['created_dt', 'updated_dt'], 'safe'],
            [['status', 'info'], 'string'],
            [['price'], 'number'],
            [['period', 'search'], 'string', 'max' => 32],
            [['conds'], 'string', 'max' => 64],
            [['currency'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'account_id' => Yii::t('app', 'Account ID'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'dv_id' => Yii::t('app', 'Dv ID'),
            'dvc_id' => Yii::t('app', 'Dvc ID'),
            'via_company_id' => Yii::t('app', 'Via Company ID'),
            'period' => Yii::t('app', 'Period'),
            'conds' => Yii::t('app', 'Conds'),
            'search' => Yii::t('app', 'Search'),
            'price' => Yii::t('app', 'Price'),
            'currency' => Yii::t('app', 'Currency'),
            'info' => Yii::t('app', 'Info'),
        ];
    }
}
