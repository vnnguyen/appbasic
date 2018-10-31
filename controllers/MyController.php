<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use common\models\Message;
use Mailgun\Mailgun;

class MyController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        // Active Language
        // Yii::$app->params['active_languages'] = ['en', 'fr', 'vi'];
        
        // if (Yii::$app->user->isGuest) {
        //     $activeLanguage = Yii::$app->session->get('active_language', 'en');
        // } else {
        //     $activeLanguage = Yii::$app->user->identity->language;
        // }
        // if (!in_array($activeLanguage, Yii::$app->params['active_languages'])) {
        //     $activeLanguage = Yii::$app->params['active_languages'][0];
        // }
        // Yii::$app->language = $activeLanguage;

        // if (!defined('USER_ID')) {
        //     if (Yii::$app->user->isGuest) {
        //         define('USER_ID', 0);
        //     } else {
        //         define('USER_ID', Yii::$app->user->identity->id);
        //     }
        // }

        // if (!defined('USER_IP')) {
        //     define('USER_IP', isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : Yii::$app->request->getUserIP());
        // }

        // Prevent accidental upload
        // Yii::$app->session->set('ckfinder_authorized', false);

        parent::__construct($id, $module, $config);

    }

    // public function actions() {
    //     return [
    //         'error' => [
    //             'class' => 'yii\web\ErrorAction',
    //         ],
    //         'captcha' => [
    //             'class' => 'yii\captcha\CaptchaAction',
    //             'width'=>100,
    //             'height'=>34,
    //             'foreColor'=>0xBD499B,
    //             'minLength'=>4,
    //             'maxLength'=>4,
    //             'offset'=>2,
    //             'transparent'=>true,
    //         ],
    //     ];
    // }

    // public function behaviors() {
    //     return [
    //         'AccessControl' => [
    //             'class' => \yii\filters\AccessControl::className(),
    //             'rules' => [
    //                 [
    //                     'allow'=>true,
    //                     'roles'=>array('@'),
    //                 ], [
    //                     'allow'=>false,
    //                 ],
    //             ]
    //         ]
    //     ];
    // }

    // Send email using Mailgun's HTTP API
    // public function mgIt($subject, $body, $vars = [], $args = [])
    // {
    //     $mg = new Mailgun(MAILGUN_API_KEY);
    //     $mb = $mg->MessageBuilder();

    //     $setFrom = false;
    //     $setTo = false;

    //     $files['attachment'] = [];

    //     foreach ($args as $arg) {
    //         if (is_array($arg)) {
    //             if ($arg[0] == 'from') {
    //                 $setFrom = true;
    //                 $mb->setFromAddress($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
    //             } elseif ($arg[0] == 'to') {
    //                 $setTo = true;
    //                 $mb->addToRecipient($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
    //             } elseif ($arg[0] == 'cc') {
    //                 $mb->addCcRecipient($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
    //             } elseif ($arg[0] == 'bcc') {
    //                 $mb->addBccRecipient($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
    //             } elseif ($arg[0] == 'reply-to') {
    //                 $mb->setReplyToAddress($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
    //             } elseif ($arg[0] == 'attachment') {
    //                 $files['attachment'][] = '/var/www/my.amicatravel.com/'.$arg[1];
    //             }
    //         }
    //     }

    //     if (!$setFrom) {
    //         $mb->setFromAddress('noreply-ims@amicatravel.com', ['first'=>'Amica Travel', 'last'=>'IMS']);
    //     }
    //     if (!$setTo) {
    //         $mb->addToRecipient('hn.huan@gmail.com', ['first'=>'Huân', 'last'=>'H.']);
    //     }
    //     //if (isset($args))

    //     $mb->setSubject($subject);
    //     // $mb->setTextBody($body, $vars);
    //     $mb->setHtmlBody($this->renderPartial($body, $vars));

    //     # Other Optional Parameters.
    //     //$mb->addCampaignId("My-Awesome-Campaign");
    //     //$mb->addCustomHeader("Customer-Id", "12345");
    //     //$mb->addAttachment('@@/var/www/my.amicatravel.com/120303-help.pdf');
    //     //$files['attachment'] = [];
    //     //$files['attachment'][] = '/var/www/my.amicatravel.com/120303-help.pdf';
        
    //     //$mb->addAttachment('@@/var/www/my.amicatravel.com/120303-help.pdf');
    //     //$mb->setDeliveryTime("tomorrow 8:00AM", "PST");
    //     //$mb->setClickTracking(true);

    //     # Finally, send the message.
    //     // $mg->post(MAILGUN_API_DOMAIN.'/messages', $mb->getMessage(), $files);
    //     $mg->post(MAILGUN_API_DOMAIN.'/messages', $mb->getMessage(), $files);
    //     return true;
    // }
}
