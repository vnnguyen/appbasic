<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class CalenController extends Controller
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
    public function actionIndex()
    {
        $theTasks = Task::find()->all();
        foreach ($theTasks as $task) {
            // var_dump($task);die();
            if ($task->description != '') {
                $arr_des = explode('#', $task->description);
                if (!is_numeric(intval($arr_des[0]))) {
                    throw new HttpException(404, "the description not correct format");
                }
            }
        }
        return $this->render('index', [
            'theCalens' => $theTasks,
        ]);
    }
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionCal_info($id)
    {
        if (Yii::$app->request->isAjax) {
            $task = Task::find()->where(['id' => $id])->one();
            if ($task->description != '') {
                $arr_des = explode('#', $task->description);
                if (!is_numeric(intval($arr_des[0]))) {
                    echo json_encode(['error' => 'the description not correct format']); exit;
                }
                $md = array_diff($arr_des, [$arr_des[0],$arr_des[1], $arr_des[count($arr_des)-1]]);
                $meet_place = $arr_des[1];
                $note = $arr_des[count($arr_des)-1];
                echo json_encode([
                        'id' => $task->id,
                        'time' => date('H:i:s', strtotime($task->due_dt)),
                        'mins' => $task->mins,
                        'md' => explode(',', implode(',', $md)),
                        'note' => $note,
                        'fuzzy' => $task->fuzzy,
                        'meet_place' => $meet_place
                        ]);

            }
            else{
                echo json_encode(['error' => 'the description not correct format']); exit;
            }
        }

    }
    public function actionUpdate_info()
    {
        if (Yii::$app->request->isAjax) {//var_dump(implode('#', $_POST['md']));die();
            $model = Task::find()->where(['id' => $_POST['id']])->one();
            $date = date('Y-m-d',strtotime($model->due_dt)).' '.date('H:i:s', strtotime($_POST['time']));
            $description = '#'.$_POST['meet_place'].'#'.implode('#', $_POST['md']).'#'.$_POST['note'];
            if ($_POST['s_time'] == 'none') {
                $model->fuzzy = 'none';
            }
            else {
                $model->fuzzy = 'time';
            }
            $model->due_dt = $date;
            $model->description = $description;
            $model->mins = $_POST['mins'];
            if ($model->validate() && $model->save()) {
                return json_encode($_POST);
            }
            else
            {
                return json_encode($model->errors);
            }
            // $cal_info = Calen::find()->where(['id' => $id])->asArray()->one();
            // echo json_encode($cal_info);
        }

    }
}
