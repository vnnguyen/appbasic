<?php
namespace common\models;

class Group extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%groups}}';
	}

	public function attributeLabels()
	{
		return [
			'name'=>'Name of group',
			'alias'=>'Short name',
		];
	}

	public function rules()
	{
		return [
			[['name', 'alias', 'info'], 'filter', 'filter'=>'trim'],
			[['name', 'alias'], 'required'],
		];
	}

	public function getUsers()
	{
		return $this->hasMany(User::className(), ['id' => 'user_id'])
			->viaTable('at_role_user', ['role_id'=>'id']);
	}

}
