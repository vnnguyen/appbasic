<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cptmau".
 *
 * @property integer $id
 * @property integer $dv_id
 * @property string $note
 */
class Cptmau extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cptmau';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dv_id'], 'required'],
            [['dv_id'], 'integer'],
            [['note'], 'string', 'max' => 200],
        ];
    }
    public function getDv()
    {
        return $this->hasOne(Dv::className(), ['id' => 'dv_id']);
    }
    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id' => 'venue_id'])->viaTable('dv', ['id' => 'dv_id']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dv_id' => Yii::t('app', 'Dv ID'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
