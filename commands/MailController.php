<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yiicod\mailqueue\commands\WorkerCommand;
use yiicod\mailqueue\MailQueue;

class MailController extends WorkerCommand
{
    public function actionWork()
    {
        // die('ok');
        while (true) {
            Yii::$app->mailqueue::delivery(new $this->mailProvider());

            sleep($this->delay);
        }
    }
}