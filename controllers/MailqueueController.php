<?php

namespace app\controllers;

use Yii;
use app\models\MailQueue;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yiicod\mailqueue\models\MailQueueModel;

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
        $model = new MailQueueModel;

        // var_dump($model); die();
        // $criteria = [
        //         'where' => 'status=:status',
        //         'params' => ['status' => 0],
        //         'order' => null,
        //         'limit' => 60
        // ];
         $data =[
                // [
                    'from' => 'vannguyen832@gmail.com',
                    'to' => 'nguyenvn099@gmail.com',
                    'subject' => 'abc',
                    'body' => 'abcd',
                    'attachs' => [],
                    'status' => 0,
                    'priority' => 0
                // ],
            ];

        $model->attributes = $data;
        $model->createDate = NOW;
        $model->updateDate = NOW;
        if (!$model->push($data)) {
            var_dump($model->errors);die();
        }

    }
}
