<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property string $id
 * @property string $name_service
 * @property string $dram
 * @property string $price
 * @property string $conditions
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_service', 'dram', 'price'], 'required'],
            [['name_service', 'conditions'], 'string', 'max' => 100],
            [['dram', 'price'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_service' => 'Name Service',
            'dram' => 'Dram',
            'price' => 'Price',
            'conditions' => 'Conditions',
        ];
    }
}
