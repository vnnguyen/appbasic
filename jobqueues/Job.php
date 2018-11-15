<?php
namespace app\jobqueues;

use Yii;
use yii\base\Model;
use yii\base\BaseObject;


//custorm notification
use app\notifications\MyNotification;
class Job extends BaseObject implements \yii\queue\JobInterface
{
    public $datas;

    public function execute($queue)
    {
        $this->datas->send();
        // $user = User::findOne(34718);
        // MyNotification::create(MyNotification::KEY_NEW_ACCOUNT, [
        //     'datas' => [
        //         'user' => $user,
        //         'channels' => null
        // ]])->send();
        $this->done();
    }
    public function done()
    {
        echo 'execute has done!!!';
    }
}
?>