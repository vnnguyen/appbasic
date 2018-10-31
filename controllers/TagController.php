<?php
namespace app\controllers;
use yii;
use app\models\Tag;
class TagController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList($query)
    {
        $models = Tag::find()->where("name='".$query."'")->all();
        $items = [];

        foreach ($models as $model) {
            $items[] = ['name' => $model->name];
        }
        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $items;
    }

}
