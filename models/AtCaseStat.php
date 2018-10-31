<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "at_case_stats".
 *
 * @property string $case_id
 * @property string $updated_at
 * @property string $updated_by
 * @property string $pax_count_min
 * @property string $pax_count_max
 * @property string $day_count_min
 * @property string $day_count_max
 * @property string $destinations
 * @property string $avail_from_date
 * @property integer $prospect
 * @property string $prospect_updated_dt
 * @property string $prospect_updated_by
 * @property string $pa_destinations
 * @property string $pa_pax
 * @property string $pa_pax_ages
 * @property string $pa_days
 * @property string $pa_start_date
 * @property string $pa_tour_type
 * @property string $pa_group_type
 * @property string $pa_tags
 * @property string $tour_name
 * @property string $country
 * @property string $pa_from_site
 * @property string $request_device
 */
class AtCaseStat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'at_case_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_id', 'updated_at', 'updated_by', 'destinations', 'avail_from_date', 'prospect_updated_dt', 'prospect_updated_by', 'pa_pax', 'pa_pax_ages', 'pa_days', 'pa_start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags', 'tour_name', 'pa_from_site', 'request_device'], 'required'],
            [['case_id', 'updated_by', 'pax_count_min', 'pax_count_max', 'day_count_min', 'day_count_max', 'prospect', 'prospect_updated_by'], 'integer'],
            [['updated_at', 'avail_from_date', 'prospect_updated_dt'], 'safe'],
            [['tour_name', 'request_device'], 'string'],
            [['destinations', 'pa_tour_type', 'pa_group_type'], 'string', 'max' => 64],
            [['pa_pax', 'pa_days', 'pa_start_date'], 'string', 'max' => 10],
            [['pa_tags', 'pa_from_site'], 'string', 'max' => 128],
            [['country'], 'string', 'max' => 2],
            [['case_id'], 'unique'],
            [['pa_days'], 'my_required', 'on' => 'cases/request','skipOnError' => false],
            [['pa_start_date'], 'date', 'format' => 'php:Y-m-d']

        ];
    }
    // regular date dd/mm/yyyy
    //^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$
    public function my_required($attribute_name,$params)
    {
        // var_dump(preg_match('/^([0-9]{1,2}|[0-9]{1,2}-[0-9]{1,2})$/', $this->pa_days)); die();
        if ( ! preg_match('/^(\d{1,2}|\d{1,2}-\d{1,2})$/', $this->pa_days) ) {
             $this->addError($attribute_name, Yii::t('app', 'The number days is not format'));
        }
    }
    public function scenarios()
    {
        return [
            'cases/request'=>['pa_destinations', 'pa_pax', 'pa_pax_ages', 'pa_days', 'pa_start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags', 'tour_name', 'country'],
            ['pa_destinations', 'safe'],
            ['country', 'safe']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'case_id' => Yii::t('app', 'Case ID'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'pax_count_min' => Yii::t('app', 'Pax Count Min'),
            'pax_count_max' => Yii::t('app', 'Pax Count Max'),
            'day_count_min' => Yii::t('app', 'Day Count Min'),
            'day_count_max' => Yii::t('app', 'Day Count Max'),
            'destinations' => Yii::t('app', 'Destinations'),
            'avail_from_date' => Yii::t('app', 'Avail From Date'),
            'prospect' => Yii::t('app', 'Prospect'),
            'prospect_updated_dt' => Yii::t('app', 'Prospect Updated Dt'),
            'prospect_updated_by' => Yii::t('app', 'Prospect Updated By'),
            'pa_destinations' => Yii::t('app', 'Pa Destinations'),
            'pa_pax' => Yii::t('app', 'Pa Pax'),
            'pa_pax_ages' => Yii::t('app', 'Pa Pax Ages'),
            'pa_days' => Yii::t('app', 'Pa Days'),
            'pa_start_date' => Yii::t('app', 'Pa Start Date'),
            'pa_tour_type' => Yii::t('app', 'Pa Tour Type'),
            'pa_group_type' => Yii::t('app', 'Pa Group Type'),
            'pa_tags' => Yii::t('app', 'Pa Tags'),
            'tour_name' => Yii::t('app', 'Tour Name'),
            'country' => Yii::t('app', 'Country'),
            'pa_from_site' => Yii::t('app', 'Pa From Site'),
            'request_device' => Yii::t('app', 'Request Device'),
        ];
    }
}
