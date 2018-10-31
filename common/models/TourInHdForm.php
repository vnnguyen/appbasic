<?

namespace common\models;

use yii\base\Model;

class TourInHdForm extends Model
{
	public $days;
	public $until_day;
	public $payer;
	public $tourguide;

	public function attributeLabels()
	{
		return [
			'days'=>'In các ngày (vd 1-3,4,5-7)',
			'payer'=>'Người thanh toán',
			'tourguide'=>'In cho tour guide',
		];
	}

	public function rules()
	{
		return [
			[['days', 'payer', 'tourguide'], 'trim'],
			[['days', 'payer', 'tourguide'], 'required', 'message'=>'Required'],
		];
	}

}