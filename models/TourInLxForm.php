<?

namespace app\models;

use yii\base\Model;

class TourInLxForm extends Model
{
    public $days;
    public $vp;
    public $chuxe;
    public $laixe;
    public $loaixe;
    public $dieuhanh;
    public $huongdan;
    public $giakm;
    public $giadb;
    public $giatb;
    public $note;

    public function attributeLabels()
    {
        return [
            'days'=>'In các ngày (vd 1-3,4,5-7)',
            'giakm'=>'Giá VND/km',
            'giadb'=>'Giá VND/ ngày Đông Bắc',
            'giatb'=>'Giá VND/ ngày Tây Bắc',
            'note'=>'Ghi chú in kèm',
        ];
    }

    public function rules()
    {
        return [
            [['days', 'vp', 'chuxe', 'laixe', 'loaixe', 'dieuhanh', 'huongdan', 'giakm', 'giadb', 'giatb', 'note'], 'trim'],
            [['giakm', 'giadb', 'giatb'], 'default', 'value'=>0],
            [['vp', 'dieuhanh'], 'required', 'message'=>'Required'],
        ];
    }

}