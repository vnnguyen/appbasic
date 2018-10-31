<?
namespace common\models;

class Nm extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%ngaymau}}';
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'updated_by']);
	}
}
