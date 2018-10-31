<?php

namespace app\controllers;

use Yii;
use app\models\Condition;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConditionController implements the CRUD actions for Condition model.
 */
class ConditionController extends Controller
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
     * Lists all Condition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Condition::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Condition model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Condition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Condition();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Condition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Condition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Condition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Condition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Condition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionList_condition()
    {
        if (Yii::$app->request->isAjax) {
            $listCondition = Condition::find()->asArray()->all();
            echo json_encode($listCondition);
        }
    }
    public function actionAdd_condition()
    {
        if (Yii::$app->request->isAjax) {
            $arr_datas = $_POST['arr_data'];
            $arr_ids = [];
            // var_dump($_POST);die();
            foreach ($arr_datas as $data) {
                $condition = new Condition();
                $condition->code = $data['code'];
                $condition->category = $data['category'];
                $condition->operator = $data['operator'];
                $condition->from = $data['from'];
                $condition->to = $data['to'];
                $condition->equal = $data['equal'];
                $condition->description = $data['description'];
                if ($condition->validate() && $condition->save()) {
                    $arr_ids[] = $condition->id;
                } else {
                    echo json_encode(['error' => $condition->code.' is duplicated']);
                }
            }
        }
        if (count($arr_ids) > 0) {
            return json_encode($arr_ids);
        }
    }
    public function actionSearch_condition()
    {
        if (Yii::$app->request->isAjax) {
            $listData = $con = Condition::find();
            if ($_POST['code'] != '') {
                $con->andWhere(['LIKE', 'code', $_POST['code']]);
            }
            if ($_POST['category'] != '') {
                $con->andWhere(['LIKE', 'category', $_POST['category']]);
            }
            if ($_POST['description'] != '') {
                $con->andWhere(['LIKE', 'description', $_POST['description']]);
            }
            echo json_encode($con->asArray()->all());
        }
    }
    
}
