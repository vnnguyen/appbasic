<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cpt".
 *
 * @property string $dvtour_id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $tour_id
 * @property string $dvtour_day
 * @property string $dvtour_name
 * @property string $cp_id
 * @property string $cpg_id
 * @property string $oppr
 * @property string $user_id
 * @property string $start
 * @property string $length
 * @property string $number
 * @property string $venue_id
 * @property string $by_company_id
 * @property string $via_company_id
 * @property string $prebooking
 * @property string $prepayment
 * @property string $b_owner
 * @property string $b_status
 * @property string $b_status_dt
 * @property string $p_owner
 * @property string $p_status
 * @property string $p_status_dt
 * @property string $qty
 * @property string $unit
 * @property string $price
 * @property string $unitc
 * @property string $vat
 * @property string $booker
 * @property string $payer
 * @property string $adminby
 * @property string $approved
 * @property string $approved_by
 * @property string $paid
 * @property string $confirmed
 * @property string $status
 * @property string $latest
 * @property string $due
 * @property string $vat_ok
 * @property string $vat_by
 * @property string $an_pct
 * @property string $xacnhan_date
 * @property string $xacnhan_by
 * @property string $duyet_date
 * @property string $duyet_by
 * @property string $plusminus
 * @property string $c1
 * @property string $c2
 * @property string $c3
 * @property string $c4
 * @property string $c5
 * @property string $c6
 * @property string $c7
 * @property string $c8
 * @property string $c9
 * @property string $paid_amt
 * @property string $paid_full
 */
class Cpt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cpt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }
    public function getNcc()
    {
        return $this->hasOne(Venues::className(), ['id' => 'venue_id']);
    }
    public function getRout()
    {
        return $this->hasMany(Rout::className(), ['venue_id' => 'venue_id']);
    }
    public function getVenue1()
    {
        return $this->hasOne(Venues::className(), ['id' => 'venue_id'])
                    ->viaTable('dv', ['id' => 'dv_id']);
    }
    public function getCt()
    {
        return $this->hasOne(AtCt::className(), ['id' => 'tour_id']);
    }
    public function getTour()
    {
        return $this->hasOne(AtTours::className(), ['id' => 'tour_id']);
    }
    public function getDv()
    {
        return $this->hasOne(DV::className(), ['id' => 'dv_id']);
    }
    public function getMtt()
    {
        return $this->hasMany(AtMtt::className(), ['cpt_id'=>'dvtour_id']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dvtour_id' => Yii::t('app', 'Dvtour ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'tour_id' => Yii::t('app', 'Tour ID'),
            'dvtour_day' => Yii::t('app', 'Dvtour Day'),
            'dvtour_name' => Yii::t('app', 'Dvtour Name'),
            'cp_id' => Yii::t('app', 'Cp ID'),
            'cpg_id' => Yii::t('app', 'Cpg ID'),
            'oppr' => Yii::t('app', 'Oppr'),
            'user_id' => Yii::t('app', 'User ID'),
            'start' => Yii::t('app', 'Start'),
            'length' => Yii::t('app', 'Length'),
            'number' => Yii::t('app', 'Number'),
            'venue_id' => Yii::t('app', 'Venue ID'),
            'by_company_id' => Yii::t('app', 'By Company ID'),
            'via_company_id' => Yii::t('app', 'Via Company ID'),
            'prebooking' => Yii::t('app', 'Prebooking'),
            'prepayment' => Yii::t('app', 'Prepayment'),
            'b_owner' => Yii::t('app', 'B Owner'),
            'b_status' => Yii::t('app', 'B Status'),
            'b_status_dt' => Yii::t('app', 'B Status Dt'),
            'p_owner' => Yii::t('app', 'P Owner'),
            'p_status' => Yii::t('app', 'P Status'),
            'p_status_dt' => Yii::t('app', 'P Status Dt'),
            'qty' => Yii::t('app', 'Qty'),
            'unit' => Yii::t('app', 'Unit'),
            'price' => Yii::t('app', 'Price'),
            'unitc' => Yii::t('app', 'Unitc'),
            'vat' => Yii::t('app', 'Vat'),
            'booker' => Yii::t('app', 'Booker'),
            'payer' => Yii::t('app', 'Payer'),
            'adminby' => Yii::t('app', 'Adminby'),
            'approved' => Yii::t('app', 'Approved'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'paid' => Yii::t('app', 'Paid'),
            'confirmed' => Yii::t('app', 'Confirmed'),
            'status' => Yii::t('app', 'Status'),
            'latest' => Yii::t('app', 'Latest'),
            'due' => Yii::t('app', 'Due'),
            'vat_ok' => Yii::t('app', 'Vat Ok'),
            'vat_by' => Yii::t('app', 'Vat By'),
            'an_pct' => Yii::t('app', 'An Pct'),
            'xacnhan_date' => Yii::t('app', 'Xacnhan Date'),
            'xacnhan_by' => Yii::t('app', 'Xacnhan By'),
            'duyet_date' => Yii::t('app', 'Duyet Date'),
            'duyet_by' => Yii::t('app', 'Duyet By'),
            'plusminus' => Yii::t('app', 'Plusminus'),
            'c1' => Yii::t('app', 'C1'),
            'c2' => Yii::t('app', 'C2'),
            'c3' => Yii::t('app', 'C3'),
            'c4' => Yii::t('app', 'C4'),
            'c5' => Yii::t('app', 'C5'),
            'c6' => Yii::t('app', 'C6'),
            'c7' => Yii::t('app', 'C7'),
            'c8' => Yii::t('app', 'C8'),
            'c9' => Yii::t('app', 'C9'),
            'paid_amt' => Yii::t('app', 'Paid Amt'),
            'paid_full' => Yii::t('app', 'Paid Full'),
        ];
    }

    public function getCp()
    {
        return $this->hasOne(Cp::className(), ['id'=>'cp_id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venues::className(), ['id'=>'venue_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'by_company_id']);
    }

    public function getViaCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'via_company_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['rid'=>'dvtour_id'])->onCondition(['rtype'=>'cpt'])->orderBy('created_at');
    }
    // public function getMtt()
    // {
    //     return $this->hasMany(Mtt::className(), ['cpt_id'=>'dvtour_id']);
    // }

}
// {

//     public static function tableName()
//     {
//         return 'cpt';
//     }

//     public function attributeLabels() {
//         return [
//         ];
//     }

//     public function rules() {
//         return [];
//     }

    
// }
