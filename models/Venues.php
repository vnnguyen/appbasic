<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "venues".
 *
 * @property string $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $status
 * @property string $stype
 * @property string $parent_venue_id
 * @property string $company_id
 * @property string $supplier_id
 * @property string $destination_id
 * @property string $giao_id
 * @property string $loc_id
 * @property string $latlng
 * @property string $name
 * @property string $about
 * @property string $search
 * @property string $info
 * @property string $info_facilities
 * @property string $info_pricing
 * @property string $exp_id
 * @property string $features
 * @property string $image
 * @property string $images
 * @property string $images_booking
 * @property string $fb_tripadvisor
 * @property string $link_tripadvisor
 * @property string $link_agoda
 * @property string $link_booking
 * @property string $cruise_meta
 * @property string $hotel_meta
 * @property string $ncc_id
 * @property string $abbr
 */
class Venues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'venues';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'status', 'stype', 'parent_venue_id', 'company_id', 'supplier_id', 'destination_id', 'giao_id', 'loc_id', 'latlng', 'name', 'about', 'search', 'info', 'info_facilities', 'info_pricing', 'exp_id', 'features', 'image', 'images', 'images_booking', 'fb_tripadvisor', 'link_tripadvisor', 'link_agoda', 'link_booking', 'cruise_meta', 'hotel_meta', 'abbr'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'parent_venue_id', 'company_id', 'supplier_id', 'destination_id', 'giao_id', 'loc_id', 'exp_id', 'ncc_id'], 'integer'],
            [['status', 'info', 'info_facilities', 'info_pricing', 'features', 'images', 'images_booking', 'fb_tripadvisor', 'hotel_meta'], 'string'],
            [['stype', 'latlng'], 'string', 'max' => 20],
            [['name', 'about', 'search', 'abbr'], 'string', 'max' => 64],
            [['image', 'cruise_meta'], 'string', 'max' => 256],
            [['link_tripadvisor', 'link_agoda', 'link_booking'], 'string', 'max' => 128],
        ];
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
            'status' => Yii::t('app', 'Status'),
            'stype' => Yii::t('app', 'Stype'),
            'parent_venue_id' => Yii::t('app', 'Parent Venue ID'),
            'company_id' => Yii::t('app', 'Company ID'),
            'supplier_id' => Yii::t('app', 'Supplier ID'),
            'destination_id' => Yii::t('app', 'Destination ID'),
            'giao_id' => Yii::t('app', 'Giao ID'),
            'loc_id' => Yii::t('app', 'Loc ID'),
            'latlng' => Yii::t('app', 'Latlng'),
            'name' => Yii::t('app', 'Name'),
            'about' => Yii::t('app', 'About'),
            'search' => Yii::t('app', 'Search'),
            'info' => Yii::t('app', 'Info'),
            'info_facilities' => Yii::t('app', 'Info Facilities'),
            'info_pricing' => Yii::t('app', 'Info Pricing'),
            'exp_id' => Yii::t('app', 'Exp ID'),
            'features' => Yii::t('app', 'Features'),
            'image' => Yii::t('app', 'Image'),
            'images' => Yii::t('app', 'Images'),
            'images_booking' => Yii::t('app', 'Images Booking'),
            'fb_tripadvisor' => Yii::t('app', 'Fb Tripadvisor'),
            'link_tripadvisor' => Yii::t('app', 'Link Tripadvisor'),
            'link_agoda' => Yii::t('app', 'Link Agoda'),
            'link_booking' => Yii::t('app', 'Link Booking'),
            'cruise_meta' => Yii::t('app', 'Cruise Meta'),
            'hotel_meta' => Yii::t('app', 'Hotel Meta'),
            'ncc_id' => Yii::t('app', 'Ncc ID'),
            'abbr' => Yii::t('app', 'Abbr'),
        ];
    }
}
