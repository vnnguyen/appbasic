<?php

namespace app\controllers;

use Yii;
use app\models\MailQueue;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MailqueueController implements the CRUD actions for MailQueue model.
 */
class MailqueueController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new MailQueue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // var_dump(Yii::$app->mailqueue->components['mailQueue']['class']);
        $table = Yii::$app->get('mailqueue')->components['mailQueue']['class'];
        // var_dump($table); die();
        $model = new $table();
        var_dump($model); die();
        // $criteria = [
        //         'where' => 'status=:status',
        //         'params' => ['status' => 0],
        //         'order' => null,
        //         'limit' => 60
        // ];
         $data =[
                [
                    'from' => 'vannguyen832@gmail.com',
                    'to' => 'nguyenvn099@gmail.com',
                    'subject' => 'abc',
                    'body' => 'abcd',
                    'attachs' => [],
                    'status' => 0
                ],
                [
                    'from' =>'vannguyen832@gmail.com',
                    'to' =>'nguyen.nv@amicatravel.com',
                    'subject'=> 'test',
                    'body'=> 'test content',
                    'attachs' => [],
                    'status' => 0
                ]
            ];
        $model->pushMass($data);
    }
}
