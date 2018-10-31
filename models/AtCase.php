<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_cases".
 *
 * @property string $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $status
 * @property string $status_date
 * @property string $market_id
 * @property string $is_ok_new
 * @property string $owner_id
 * @property string $info
 * @property string $lead_source
 * @property string $lead_status
 * @property string $lead_status_date
 * @property string $how_contacted
 * @property string $how_found
 * @property string $web_keyword
 * @property string $web_referral
 * @property string $name
 * @property string $deal_tags
 * @property string $deal_status
 * @property string $deal_status_date
 * @property string $opened
 * @property string $closed
 * @property string $closed_note
 * @property string $why_closed
 * @property string $ao
 * @property string $cofr
 * @property string $ep
 * @property string $at_who
 * @property string $at_opened
 * @property string $at_closed
 * @property string $buyer_is
 * @property string $seller_is
 * @property string $contact_type
 * @property string $customer_type
 * @property string $company_id
 * @property string $ref
 * @property string $is_priority
 * @property string $dup
 * @property string $vespa2013
 * @property string $campaign_id
 * @property string $opened_dt
 * @property string $owner_asssigned_dt
 * @property string $cofr_asssigned_dt
 * @property string $sale_status_dt
 * @property string $sale_status
 * @property string $language
 * @property string $is_b2b
 */
class AtCase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_cases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'status_date', 'market_id', 'is_ok_new', 'owner_id', 'info', 'lead_source', 'lead_status_date', 'how_contacted', 'how_found', 'web_keyword', 'web_referral', 'name', 'deal_tags', 'deal_status', 'deal_status_date', 'opened', 'closed', 'closed_note', 'why_closed', 'ao', 'cofr', 'at_opened', 'at_closed', 'buyer_is', 'seller_is', 'company_id', 'dup', 'opened_dt', 'owner_asssigned_dt', 'cofr_asssigned_dt', 'sale_status_dt'], 'required'],
            [['created_at', 'updated_at', 'deleted_at', 'status_date', 'lead_status_date', 'deal_status_date', 'opened', 'closed', 'ao', 'at_opened', 'at_closed', 'opened_dt', 'owner_asssigned_dt', 'cofr_asssigned_dt', 'sale_status_dt'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'market_id', 'owner_id', 'cofr', 'ep', 'at_who', 'company_id', 'ref', 'dup', 'campaign_id'], 'integer'],
            [['status', 'is_ok_new', 'info', 'lead_status', 'deal_status', 'buyer_is', 'seller_is', 'contact_type', 'customer_type', 'is_priority', 'vespa2013', 'sale_status', 'language', 'is_b2b'], 'string'],
            [['lead_source', 'how_contacted', 'how_found', 'web_referral'], 'string', 'max' => 32],
            [['web_keyword'], 'string', 'max' => 64],
            [['name', 'deal_tags'], 'string', 'max' => 128],
            [['closed_note', 'why_closed'], 'string', 'max' => 256],
        ];
    }
    public function scenarios()
    {
        return [
            'cases_c'=>['name', 'language', 'is_b2b', 'is_priority', 'owner_id', 'cofr', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword', 'emails'],
            'cases_u'=>['name', 'language', 'is_b2b', 'is_priority', 'owner_id', 'cofr', 'info', 'how_found', 'how_contacted', 'company_id', 'campaign_id', 'ref', 'web_referral', 'web_keyword', 'emails'],
            'cases/upa'=>['how_contacted', 'web_referral', 'web_keyword', 'campaign_id', 'company_id', 'how_found', 'ref', 'info'],
            'inquiries_r'=>['name'],
            'update'=>['name', 'is_priority', 'owner_id'],
            'cases_close'=>['why_closed', 'closed_note'],
            'cases_reopen'=>[],
        ];
    }
    public function getStats() {
        return $this->hasOne(AtCaseStat::className(), ['case_id' => 'id']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'status' => Yii::t('app', 'Status'),
            'status_date' => Yii::t('app', 'Status Date'),
            'market_id' => Yii::t('app', 'Market ID'),
            'is_ok_new' => Yii::t('app', 'Is Ok New'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'info' => Yii::t('app', 'Info'),
            'lead_source' => Yii::t('app', 'Lead Source'),
            'lead_status' => Yii::t('app', 'Lead Status'),
            'lead_status_date' => Yii::t('app', 'Lead Status Date'),
            'how_contacted' => Yii::t('app', 'How Contacted'),
            'how_found' => Yii::t('app', 'How Found'),
            'web_keyword' => Yii::t('app', 'Web Keyword'),
            'web_referral' => Yii::t('app', 'Web Referral'),
            'name' => Yii::t('app', 'Name'),
            'deal_tags' => Yii::t('app', 'Deal Tags'),
            'deal_status' => Yii::t('app', 'Deal Status'),
            'deal_status_date' => Yii::t('app', 'Deal Status Date'),
            'opened' => Yii::t('app', 'Opened'),
            'closed' => Yii::t('app', 'Closed'),
            'closed_note' => Yii::t('app', 'Closed Note'),
            'why_closed' => Yii::t('app', 'Why Closed'),
            'ao' => Yii::t('app', 'Ao'),
            'cofr' => Yii::t('app', 'Cofr'),
            'ep' => Yii::t('app', 'Ep'),
            'at_who' => Yii::t('app', 'At Who'),
            'at_opened' => Yii::t('app', 'At Opened'),
            'at_closed' => Yii::t('app', 'At Closed'),
            'buyer_is' => Yii::t('app', 'Buyer Is'),
            'seller_is' => Yii::t('app', 'Seller Is'),
            'contact_type' => Yii::t('app', 'Contact Type'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'company_id' => Yii::t('app', 'Company ID'),
            'ref' => Yii::t('app', 'Ref'),
            'is_priority' => Yii::t('app', 'Is Priority'),
            'dup' => Yii::t('app', 'Dup'),
            'vespa2013' => Yii::t('app', 'Vespa2013'),
            'campaign_id' => Yii::t('app', 'Campaign ID'),
            'opened_dt' => Yii::t('app', 'Opened Dt'),
            'owner_asssigned_dt' => Yii::t('app', 'Owner Asssigned Dt'),
            'cofr_asssigned_dt' => Yii::t('app', 'Cofr Asssigned Dt'),
            'sale_status_dt' => Yii::t('app', 'Sale Status Dt'),
            'sale_status' => Yii::t('app', 'Sale Status'),
            'language' => Yii::t('app', 'Language'),
            'is_b2b' => Yii::t('app', 'Is B2b'),
        ];
    }
    public function getOwner() {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }
}
