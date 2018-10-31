<?php

namespace app\controllers;

use Yii;
use app\models\Service;
use app\models\Condition;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class ServiceController extends Controller
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
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Service::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Service model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Service model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Service();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing Service model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionShow_price()
    {
        $services = Service::find()->one();
        
        $arr = explode(';', $services->conditions);
        // foreach ($arr as $v) {
        //     $ds = 
        // }
        var_dump($arr);die();







        //////////////////////////////////
        $status = true;
        $tour_info = [
            'date_book' =>'20/7/2016',
            'date_use' => '',
            'num_mem' => 15,
            'age' => 20,
        ];
        foreach ($services as $service) {
            $arr_cons = explode(',', $service->conditions);
            foreach ($arr_cons as $code) {
                if (!$this->validateCondition($tour_info, $code)) {
                    $status = false;
                    break;
                }
            }
            if ($status == true && $service->price) {
                $price = $service->price;
            }
        }
        echo $price;
    }
    public function validateCondition($tour_info, $code)
    {
        $status = false;
        $condition = Condition::find()->where('code=:code', [':code' => $code])->one();
        if ($condition != null) {
            switch ($condition->category) {
                case 'date_book':
                    switch ($condition->operator) {
                        case 'before':
                            if (strtotime($tour_info['date_book']) < strtotime($condition->to)) {
                                $status = true;
                            }
                            break;
                        case 'after':
                            if (strtotime($tour_info['date_book']) > strtotime($condition->from)) {
                                $status = true;
                            }
                            break;
                        case 'from':
                            if (strtotime($tour_info['date_book']) >= strtotime($condition->from)) {
                                $status = true;
                            }
                            break;
                        case 'to':
                            if (strtotime($tour_info['date_book']) <= strtotime($condition->from)) {
                                $status = true;
                            }
                            break;
                        case 'equal':
                            if (strtotime($tour_info['date_book']) == strtotime($condition->from)) {
                                $status = true;
                            }
                            break;
                        case 'beetween':
                            if (strtotime($tour_info['date_book']) >= strtotime($condition->from) && strtotime($tour_info['date_book']) <= strtotime($condition->to)) {
                                $status = true;
                            }
                            break;
                    }
                    break;
                case 'date_use':
                    switch ($condition->operator) {
                        case 'before':
                            if (strtotime($tour_info['date_use']) < strtotime($condition->to)) {
                                $status = true;
                            }
                            break;
                        case 'after':
                            if (strtotime($tour_info['date_use']) > strtotime($condition->from)) {
                                $status = true;
                            }
                            break;
                        case 'from':
                            if (strtotime($tour_info['date_use']) >= strtotime($condition->from)) {
                                $status = true;
                            }
                            break;
                        case 'to':
                            if (strtotime($tour_info['date_use']) <= strtotime($condition->from)) {
                                $status = true;
                            }
                            break;
                        case 'equal':
                            if (strtotime($tour_info['date_use']) == strtotime($condition->from)) {
                                $status = true;
                            }
                            break;
                        case 'beetween':
                            if (strtotime($tour_info['date_use']) >= strtotime($condition->from) && strtotime($tour_info['date_use']) <= strtotime($condition->to)) {
                                $status = true;
                            }
                            break;
                    }
                    break;
                case 'num_mem':
                    switch ($condition->operator) {
                        case 'before':
                            if ($tour_info['num_mem'] < $condition->to) {
                                $status = true;
                            }
                            break;
                        case 'after':
                            if ($tour_info['num_mem'] > $condition->from) {
                                $status = true;
                            }
                            break;
                        case 'from':
                            if ($tour_info['num_mem'] >= $condition->from) {
                                $status = true;
                            }
                            break;
                        case 'to':
                            if ($tour_info['num_mem'] <= $condition->to) {
                                $status = true;
                            }
                            break;
                        case 'equal':
                            if ($tour_info['num_mem'] == $condition->equal) {
                                $status = true;
                            }
                            break;
                        case 'beetween':
                            if ($tour_info['num_mem'] >= $condition->from && $tour_info['num_mem'] <= $condition->to) {
                                $status = true;
                            }
                            break;
                    }
                    break;
                case 'age':
                    switch ($condition->operator) {
                        case 'before':
                            if ($tour_info['age'] < $condition->to) {
                                $status = true;
                            }
                            break;
                        case 'after':
                            if ($tour_info['age'] > $condition->from) {
                                $status = true;
                            }
                            break;
                        case 'from':
                            if ($tour_info['age'] >= $condition->from) {
                                $status = true;
                            }
                            break;
                        case 'to':
                            if ($tour_info['age'] <= $condition->to) {
                                $status = true;
                            }
                            break;
                        case 'equal':
                            if ($tour_info['age'] == $condition->equal) {
                                $status = true;
                            }
                            break;
                        case 'equal':
                            if ($tour_info['age'] == $condition->equal) {
                                $status = true;
                            }
                            break;
                        case 'beetween':
                            if ($tour_info['age'] >= $condition->from && $tour_info['age'] <= $condition->to) {
                                $status = true;
                            }
                            break;
                    }
                    break;
                case 'or_condition':
                    switch ($condition->operator) {
                        case 'or':
                            $from = explode(',', $condition->from); 
                            $to = explode(',', $condition->to);
                            foreach ($from as $con) {
                                if ($this->validateCondition($tour_info, $con)) {
                                    $status = true;
                                }else{
                                    $status = false;
                                    foreach ($to as $con_to) {
                                        if ($this->validateCondition($tour_info, $con_to)) {
                                            $status = true;
                                        }else{
                                            $status = false;
                                            break;
                                        }
                                    }
                                }
                            }
                            break;
                    }
                    break;
            }
        }
        return $status;
    }
}
