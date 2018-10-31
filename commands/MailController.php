<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yiicod\mailqueue\commands\MailQueueCommand;
use app\components\EmailQueue;

class MailController extends MailQueueCommand
{
    public function actionIndex()
    {
        // echo 'ok';
        $criteria = [];
        $criteria['where'] = $this->condition;
        $criteria['params'] = $this->params;
        $criteria['order'] = $this->order;
        $criteria['limit'] = $this->limit;
        $mailQueue = new EmailQueue();
        $mailQueue->afterSendDelete = true;
        try {
            $mailQueue->delivery($criteria);
        } catch (Exception $e) {
            if (YII_DEBUG) {
                Yii::error("MailQueueCommand: " . $e->getMessage(), 'system.mailqueue');
            }
        }
        // $mails = EmailQueue::find()->where('success=0')->all();
        // foreach($mails as $mail)
        // {
        //    if($mail->success==1)
        //    {
        //        if($mail->attempts<=$mail->max_attempts)
        //        {
        //             //send mail here
        //             $message =\Yii::$app->mailer->compose();
        //             $message->setHtmlBody($mail->message,'text/html')
        //             ->setFrom($mail->from_email)
        //             ->setTo($mail->to_email)
        //             ->setSubject($mail->subject);

        //             if($message->send())
        //             {
        //                  $mail->success = 1;//set status to 0 to avoid resending of emails.
        //                  $mail->date_sent=date("Y-m-d H:i:s");
        //             }
        //             $mail->attempts=$mail->attempts + 1;
        //             $mail->last_attempt= date("Y-m-d H:i:s");
        //             $mail->save();
        //         }
        //     }
        // }
    }
}