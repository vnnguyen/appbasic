<?php
namespace app\components;

use CDbCriteria;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseJson;
use yii\console\Application;
use yiicod\mailqueue\components\MailQueue;

class EmailQueue extends MailQueue
{
	public function test(){
		return 'ok';
	}
	public function delivery($criteria)
    {
        $criteria = ArrayHelper::merge([
                    'where' => [],
                    'params' => [],
                    'order' => null,
                    'limit' => 0
                        ], $criteria);
        $table = Yii::$app->get('mailqueue')->modelMap['MailQueue']['class'];
        $deliveringCount = $criteria['limit'];
        $item = null;
        $ids = [];
        $failedIds = [];
        $statusSended = Yii::$app->get('mailqueue')->modelMap['MailQueue']['status']['sended'];
        $statusUnsended = Yii::$app->get('mailqueue')->modelMap['MailQueue']['status']['unsended'];
        $statusFailed = Yii::$app->get('mailqueue')->modelMap['MailQueue']['status']['failed'];
        $fieldStatus = Yii::$app->get('mailqueue')->modelMap['MailQueue']['fieldStatus'];
        $mailer = Yii::$app->{Yii::$app->get('mailqueue')->mailer};

        while ($deliveringCount > 0) {
            $criteria['limit'] = min($this->partSize, $deliveringCount);
            $models = $table::find()
                    ->where($criteria['where'])
                    ->params($criteria['params'])
                    ->orderBy($criteria['order'])
                    ->limit($criteria['limit'])
                    ->all();

            if (method_exists($mailer, 'deliveryBegin')) {
                $mailer->deliveryBegin($models);
            }
            foreach ($models as $item) {
                $attachs = $item->getAttachs();
                $mailer = Yii::$app->{Yii::$app->get('mailqueue')->mailer};
                $message = $mailer->compose();
                $message->setTo($item->to)
                        ->setSubject($item->subject)
                        ->setHtmlBody($item->body);
                if ($item->from) {
                    $message->setFrom($item->from);
                }
                if (is_array($attachs)) {
                    foreach ($attachs as $attach) {
                        $message->attach($attach);
                    }
                }
                
                if ($message->send()) {
                    $ids[] = $item->id;
                } else {
                    if (YII_DEBUG && Yii::$app instanceof Application) {
                        echo "MailQueue send failed to - $item->to, subject - $item->subject \n";
                    }
                    Yii::error("MailQueue send failed to - $item->to, subject - $item->subject \n", 'system.mailqueue');
                    $failedIds[] = $item->id;
                }
            }

            if (method_exists($mailer, 'deliveryEnd')) {
                $mailer->deliveryEnd($ids, $failedIds);
            }

            if (count($ids)) {
                if ($this->afterSendDelete) {
                    $table::deleteAll(['id' => $ids]);
                } else if (in_array($fieldStatus, $item->attributes())) {
                    $status = $statusSended;
                    $this->updateMailQueue($ids, $status);
                }
            }
            if (count($failedIds) && in_array($fieldStatus, $item->attributes())) {
                $status = $statusUnsended;
                if ($statusFailed != $statusUnsended) {
                    $status = $statusFailed;
                }
                $this->updateMailQueue($failedIds, $status);
            }

            $deliveringCount = $deliveringCount - $this->partSize;
        }
    }
}
