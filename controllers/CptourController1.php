<?php

namespace app\controllers;

use Yii;
use app\models\CpTour;
use app\models\Venues;
use app\models\Dv;
use app\models\Cp;
use app\models\AtCt;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Tour;
use app\models\AtNgaymau;
use app\models\Tag;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * CptourController implements the CRUD actions for CpTour model.
 */
class CptourController extends Controller
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
     * Lists all CpTour models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CpTour::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CpTour model.
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
     * Creates a new CpTour model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CpTour();
        $query = AtCt::find()->where(['id' => 12537])->with(['days'])->one();

        if ($query != null && $query->day_ids) {
            $dayIdList = explode(',', $query->day_ids);
            $start_date = $query->day_from;
            $arr_day = [];
            $cnt = 0;
            $lastId = 0;
            foreach ($dayIdList as $id) {
                foreach ($query->days as $day) {
                    if ($day['id'] == $id) {
                        $cnt ++;
                        $arr_day[date('Y/m/d D', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] =  'day '.$cnt;
                        //echo date('j/n/Y D', strtotime('+'.($cnt - 1).' days', strtotime($start_date))).'<br/>';
                    }
                }
            }
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->tour_id = $query->id;
            if ($model->validate() && $model->save()) {
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                var_dump($model->errors);die();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $query1 = null;
        $id = 58;
        $data = Tour::find()->where('id=:id', [':id' => $id])->one();

        if ($data != null) {
            if ($data->days_id != null) {
                $days_id = explode(",", $data->days_id);
                $arr = [];
                $ngaymau = AtNgaymau::find()->where('tour_id=:id', [':id' => $data->id])->all();
                foreach ($days_id as $id) {
                    foreach ($ngaymau as $ngay_mau) {
                        if ($ngay_mau->id == $id) {
                            $arr[] = $ngay_mau;
                        }
                    }
                }
                $query1 = $arr;
            }
            else {
                // do something
            }
        }
        $provider = new ArrayDataProvider([
            'allModels' => $query1,
            'pagination' => [
            'pageSize' => 50,
            ],
            // 'sort' => [
            //     'attributes' => ['id', 'name'],
            // ],
            ]);

        // var_dump($provider->getPagination());die();
        // return $this->render('detail',[
        //     'pages' => $provider->getPagination(),
        //     'dataProvider' => $provider->getModels(),
        //     'tour_model' => $data,
        //     ]);
        
        return $this->render('_form', [
            'model' => $model,
            'days' => (isset($arr_day))?$arr_day: null,
            'pages' => $provider->getPagination(),
            'dataProvider' => $provider->getModels(),
            'tour_model' => $data,
        ]);
    }

    /**
     * Updates an existing CpTour model.
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
     * Deletes an existing CpTour model.
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
     * Finds the CpTour model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CpTour the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CpTour::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionSearch_ncc($q,$page){
        if (Yii::$app->request->isAjax) {
            $data_ncc = $query = Venues::find()->andWhere(['LIKE', 'name', $q]);
            $resultCount = 200;
            $offset = ($page - 1) * $resultCount;
            $data_ncc = $data_ncc->offset($offset)->limit($resultCount)->asArray()->all();
            $count = count($query->all());
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;
            $results = [
              "items" => $data_ncc,
              'total_count' => $count
            ];
            echo json_encode($results);
        }
    }

    public function actionList_dv($id_ncc){
        if (Yii::$app->request->isAjax) {
            $data_dv = Dv::find()->where(['venue_id' => $id_ncc])->asArray()->all();
            echo json_encode($data_dv);
        }
    }
    public function actionList_cp($dv_id){
        if (Yii::$app->request->isAjax) {
            $sl = 0;
            $data_dv = Dv::find()->where(['id' => $dv_id])->asArray()->one();
            if ($data_dv == null) {
                return json_encode(['error' => 'Ncc do not exist']);
            }
            $conditions = [
                '2016-01-01/2016-03-01;2016-03-02/2016-04-28' => '1000',
                '2016-04-29/2016-08-30;2016-09-01/2016-12-31' => '5000'
            ];
            $arr_info = [
                'date_book' =>'2016/11/24',
                'date_use' => '2016/9/10',
                'num_mem' => '2',
                // 'age' => 20,
                // 'h_mem' => '',
                // 'num_use' => '1',
                // 'room_type'=> 'Superior'
            ];
            if ($data_dv['maxpax'] != '') {
                $sl = ceil(intval($arr_info['num_mem']) / intval($data_dv['maxpax']));
            }
            $arr_price = [];
            $data_cp = Cp::find()->where(['dv_id' => 737])->asArray()->all();
            if ($data_cp == null) {
                return json_encode(['error' => 'service do not exist']);
            }
            foreach ($conditions as $k => $v) {
                $arr_cons = explode(';', $k);
                foreach ($arr_cons as $key => $value) {
                    $arr_con = explode('/', $value);
                    if (count($arr_con) == 2) {
                        if (date('Y-m-d', strtotime($arr_info['date_use'])) >= date('Y-m-d', strtotime($arr_con[0])) && date('Y-m-d', strtotime($arr_info['date_use'])) <= date('Y-m-d', strtotime($arr_con[1]))) {
                            $arr_price[] = $v;
                            break;
                        }
                    }
                }
            }
            echo json_encode([
                    'price' => $arr_price[0],
                    'sl' => $sl,
                    'currency' => $data_cp[0]['currency'],
                ]);
        }
    }
    public function actionTimeline()
    {
        if (Yii::$app->request->isAjax) {
            $arr_date = ['hs' => '2016-01-01/2016-04-19', 'ls' => '2016-04-20/2016-12-31', '2016-09-30', '2016-12-31'];

            echo json_encode($arr_date);
        }
    }
}
