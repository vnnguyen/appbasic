<?php

namespace app\controllers;

use Yii;
use app\models\Reserv;
use app\models\Position;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ReservController implements the CRUD actions for Reserv model.
 */
class ReservController extends Controller
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
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Reserv models.
     * @return mixed
     */
    public function actionIndex()
    {
        $listData = Reserv::find()->where(['status'=> 'confirmed'])->orWhere(['status'=> 'commited'])->asArray()->all();
        $select_pos = Position::find()->asArray()->all();
        return $this->render('index', [
            // 'dataProvider' => $dataProvider,
            'select_pos' => $select_pos,
        ]);
    }
    /*
    *return datasource when call ajax
    *
    *
    */
    public function actionData_cal($position = '')
    {
        if (Yii::$app->request->isAjax) {
            $query = Reserv::find();
            if ($position != '') {
                $query = $query->andWhere(['pos_id' => $position]);
            }
            $listData = $query->asArray()->all();
            echo json_encode($listData);
        }
    }

    /**
     * Displays a single Reserv model.
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
     * Creates a new Reserv model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reserv();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = 1;
            $model->created_on = date('Y-m-d H:i:s', strtotime('now'));
            if ($model->validate() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $select_pos = Position::find()->asArray()->all();
        return $this->render('create', [
            'model' => $model,
            'select_pos' => $select_pos,
        ]);
    }

    /**
     * Updates an existing Reserv model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->updated_by = 1;
            $model->updated_on = date('Y-m-d H:i:s', strtotime('now'));
            if ($model->validate() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $select_pos = Position::find()->asArray()->all();
        return $this->render('update', [
            'model' => $model,
            'select_pos' => $select_pos,
        ]);
    }

    /**
     * Deletes an existing Reserv model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    // update status calendar
    function actionUpdate_status($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = Reserv::find()->where(['id' => $id])->one();
            $model->status = ($model->status == 'confirmed')? 'canceled':'confirmed';
            if ($model->status == 'canceled') {
                $model->can_b = 1;
                $model->can_o = date('Y-m-d H:i:s', strtotime('now'));
            }
            if ($model->status == 'confirmed') {
                $model->cfm_b = 1;
                $model->cfm_o = date('Y-m-d H:i:s', strtotime('now'));
            }
            $model->save();
            echo json_encode($model->attributes);
        }
    }

    /**
     * Finds the Reserv model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reserv the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reserv::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
