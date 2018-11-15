<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class DemoController extends Controller
{
    public function actionRun()
    {
        Yii::$app->queue->run(2,3);
    }
}