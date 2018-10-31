<?php

namespace app\controllers;

use Yii;
use app\models\Tour;
use app\models\AtNgaymau;
use app\models\Tag;
use app\models\AtCt;
use app\models\TourNote;
use app\models\TranslateCt;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * NgaymauController implements the CRUD actions for AtNgaymau model.
 */
class NgaymauController extends Controller
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
     * Lists all AtNgaymau models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AtNgaymau::find(),
            ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single AtNgaymau model.
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
     * Creates a new AtNgaymau model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AtNgaymau();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                ]);
        }
    }

    /**
     * Updates an existing AtNgaymau model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            ]);

    }

    /**
     * Deletes an existing AtNgaymau model.
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
     * Finds the AtNgaymau model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AtNgaymau the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AtNgaymau::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionDetail($id)
    {
        $query = null;
        $data = Tour::find()->where('id=:id', [':id' => $id])->one();
        // var_dump($data);die();
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
                $query = $arr;
            }
            else {
                // do something
            }
        }
        $translate_data = TranslateCt::find()->where(['ct_id' => $data->id])->all();
        // $tour_notes = TourNote::find()->where(['ct_id' => $data->id])->asArray()->all();
        $provider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
            'pageSize' => 50,
            ],
            // 'sort' => [
            //     'attributes' => ['id', 'name'],
            // ],
            ]);


        return $this->render('detail',[
            'pages' => $provider->getPagination(),
            'dataProvider' => $provider->getModels(),
            'tour_model' => $data,
            'translate_data' => $translate_data,
            // 'tour_notes' => $tour_notes
            ]);
    }
    public function actionS_note()
    {
        if (Yii::$app->request->isAjax) {
            $data = $_POST['data'];
            if ($data['action'] == 'edit') {
                $tourNote = TourNote::find()->where(['id' => $data['id']])->one();
                if ($tourNote != null) {
                    $tourNote->icon = $data['icon'];
                    $tourNote->color = $data['color'];
                    $tourNote->content = $data['content'];

                    $tourNote->updated_by = 1;
                    $tourNote->updated_at = date('Y/m/d H:i:s', strtotime('now'));
                    if (!$tourNote->save()) {
                        echo json_encode(['error' => $tourNote->errors]);
                    }else {
                        echo json_encode($tourNote->attributes);
                    }
                }
            } else {
                $tourNote = new TourNote();
                $tourNote->icon = $data['icon'];
                $tourNote->color = $data['color'];
                $tourNote->content = $data['content'];
                $tourNote->ct_id = $data['ct_id'];
                $tourNote->day_id = $data['day_id'];

                $tourNote->updated_by = 0;
                $tourNote->updated_at = '0000/00/00 00:00:00';
                $tourNote->created_by = 1;
                $tourNote->created_at = date('Y/m/d H:i:s', strtotime('now'));
                if (!$tourNote->save()) {
                    echo json_encode(['error' => $tourNote->errors]);
                }else {
                    echo json_encode($tourNote->attributes);
                }
            }
        }
    }
    public function actionRemove_note()
    {
        if (Yii::$app->request->isAjax) {
            $id = $_POST['id'];
            $tourNote = TourNote::find()->where(['id' => $id])->one();
            if ($tourNote->delete()) {
                return 1;
            } else {
                return 2;
            }
        }
    }
    public function actionConvert_note1()
    {
        $query = new Query();
        $at_tour_notes  = $query->select('*')->from('at_tour_notes')->createCommand()->queryAll();
        foreach ($at_tour_notes as $key => $notes) {
            //$t_notes = $notes['body'];
            $lines = explode(PHP_EOL, $notes['body']);
            foreach ($lines as $line) {
                $parts = explode('>>>', $line);
                if (isset($parts[1])) {
                    $parts[0] = trim($parts[0]);
                    $parts[1] = trim($parts[1]);
                    $color = 'blue';
                    $icon = '';
                    if (strpos($parts[1], '(red)') !== false) {
                        $color = 'red';
                    }
                    if (strpos($parts[1], '(green)') !== false) {
                        $color = 'green';
                    }
                    if (strpos($parts[1], '(purple)') !== false) {
                        $color = 'purple';
                    }
                    if (strpos($parts[1], '(car)') !== false) {
                        $icon = 'car';
                    }
                    if (strpos($parts[1], '(plane)') !== false) {
                        $icon = 'plane';
                    }
                    if (strpos($parts[1], '(air)') !== false) {
                        $icon = 'plane';
                    }
                    if (strpos($parts[1], '(flight)') !== false) {
                        $icon = 'plane';
                    }
                    if (strpos($parts[1], '(phone)') !== false) {
                        $icon = 'phone';
                    }
                    if (strpos($parts[1], '(tel)') !== false) {
                        $icon = 'phone';
                    }
                    if (strpos($parts[1], '(train)') !== false) {
                        $icon = 'train';
                    }
                    if (strpos($parts[1], '(guide)') !== false) {
                        $icon = 'user';
                    }
                    if (strpos($parts[1], '(hdv)') !== false) {
                        $icon = 'user';
                    }
                    if (strpos($parts[1], '(time)') !== false) {
                        $icon = 'clock-o';
                    }
                    $parts[1] = str_replace(['(red)', '(green)', '(blue)', '(purple)'], ['', '', '', ''], $parts[1]);
                    $parts[1] = str_replace(['(car)', '(train)', '(phone)', '(tel)', '(time)', '(plane)', '(flight)', '(air)', '(guide)', '(hdv)'], ['', '', '', '', '', '', '', '', '', ''], $parts[1]);
                    $tourNote = new TourNote();
                    $tourNote->created_at = $notes['created_at'];
                    $tourNote->created_by = $notes['created_by'];
                    $tourNote->updated_at = $notes['updated_at'];
                    $tourNote->updated_by = $notes['updated_by'];
                    $tourNote->ct_id = $notes['product_id'];
                    $tourNote->day_id = 0;
                    $tourNote->text = $parts[0];
                    $tourNote->icon = $icon;
                    $tourNote->color = $color;
                    $tourNote->content = $parts[1];
                    if (!$tourNote->validate()) {
                        var_dump($line);
                        var_dump($tourNote->errors);die();
                    }else{
                        if (!$tourNote->save()) {
                            var_dump($line);
                            var_dump($tourNote->errors);die();
                        }
                    }
                }
            }
        }
        var_dump($at_tour_notes);
    }
    public function actionConvert_note()
    {
        $query = new Query();
        $at_tour_notes  = $query->select('*')->from('at_tour_notes')->createCommand()->queryAll();
        foreach ($at_tour_notes as $key => $notes) {
            //$t_notes = $notes['body'];
            $lines = explode(PHP_EOL, $notes['body']);
            foreach ($lines as $line) {
                $parts = explode('>>>', $line);
                if (isset($parts[1])) {
                    $parts[0] = trim($parts[0]);
                    $parts[1] = trim($parts[1]);
                    $color = 'blue';
                    $icon = '';
                    if (strpos($parts[1], '(red)') !== false) {
                        $color = 'red';
                    }
                    if (strpos($parts[1], '(green)') !== false) {
                        $color = 'green';
                    }
                    if (strpos($parts[1], '(purple)') !== false) {
                        $color = 'purple';
                    }
                    if (strpos($parts[1], '(car)') !== false) {
                        $icon = 'car';
                    }
                    if (strpos($parts[1], '(plane)') !== false) {
                        $icon = 'plane';
                    }
                    if (strpos($parts[1], '(air)') !== false) {
                        $icon = 'plane';
                    }
                    if (strpos($parts[1], '(flight)') !== false) {
                        $icon = 'plane';
                    }
                    if (strpos($parts[1], '(phone)') !== false) {
                        $icon = 'phone';
                    }
                    if (strpos($parts[1], '(tel)') !== false) {
                        $icon = 'phone';
                    }
                    if (strpos($parts[1], '(train)') !== false) {
                        $icon = 'train';
                    }
                    if (strpos($parts[1], '(guide)') !== false) {
                        $icon = 'user';
                    }
                    if (strpos($parts[1], '(hdv)') !== false) {
                        $icon = 'user';
                    }
                    if (strpos($parts[1], '(time)') !== false) {
                        $icon = 'clock-o';
                    }
                    $parts[1] = str_replace(['(red)', '(green)', '(blue)', '(purple)'], ['', '', '', ''], $parts[1]);
                    $parts[1] = str_replace(['(car)', '(train)', '(phone)', '(tel)', '(time)', '(plane)', '(flight)', '(air)', '(guide)', '(hdv)'], ['', '', '', '', '', '', '', '', '', ''], $parts[1]);
                    $ct = AtCt::find()->where(['id' => $notes['product_id']])->with(['days'])->one();
                    $arr_day_ids = explode(',', $ct->day_ids);
                    $start_date = $ct->day_from;
                    $cnt = 0;
                    $d_id = 0;
                    $ex = $parts[0];
                    $current_date = $current_date;
                    // for($i = 0; $i< count($arr_day_ids); $i++){
                    //     $ex = date('Y/m/d', strtotime('+'.$i.' days', strtotime($start_date)));
                    //     $arr_dt = explode('/', $parts[0]);
                    //     $ex1 = date('Y/m/d',strtotime(date('Y', strtotime($ex)).'/'.$arr_dt[1].'/'.$arr_dt[0]));
                    //     if ($ex1 == $ex) {
                    //         $current_date = $ex;
                    //         $d_id = $arr_day_ids[$i];
                    //     }
                    // }
                    foreach ($arr_day_ids as $day_id) {
                        foreach ($ct->days as $day) {
                            if ($day['id'] == $day_id) {
                                $cnt ++;
                                $ex = date('Y/m/d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)));
                                $arr_dt = explode('/', $current_date);
                                if (count($arr_dt) >= 2) {
                                    $ex1 = date('Y/m/d',strtotime(date('Y', strtotime($ex)).'/'.$arr_dt[1].'/'.$arr_dt[0]));
                                    if ($ex1 == $ex) {
                                        $current_date = $ex;
                                        $d_id = $day_id;
                                    }
                                }
                            }
                        }
                     }
                    $tourNote = new TourNote();
                    $tourNote->created_at = $notes['created_at'];
                    $tourNote->created_by = $notes['created_by'];
                    $tourNote->updated_at = $notes['updated_at'];
                    $tourNote->updated_by = $notes['updated_by'];
                    $tourNote->ct_id = $notes['product_id'];
                    $tourNote->day_id = $d_id;
                    $tourNote->text = $current_date;
                    $tourNote->icon = $icon;
                    $tourNote->color = $color;
                    $tourNote->content = $parts[1];
                    if (!$tourNote->validate()) {
                        var_dump($line);
                        var_dump($tourNote->errors);die();
                    }
                    else {
                        if (!$tourNote->save()) {
                            var_dump($line);
                            var_dump($tourNote->errors);die();
                        }
                    }
                }
            }
        }
        var_dump($at_tour_notes);
    }
    public function actionTranslated()
    {
        if (Yii::$app->request->isAjax) {
            $data = $_GET['data'];
            $trans = TranslateCt::find()->where(['ct_id' => $data['ct_id'], 'day_id' => $data['day_id']])->asArray()->one();
            echo json_encode($trans);
        }
    }
    public function actionTranslate_data()
    {
        if (Yii::$app->request->isAjax) {
            $data = $_GET['data'];
            $trans = TranslateCt::find()->where(['ct_id' => $data['ct_id'], 'day_id' => $data['day_id']])->one();
            if ($trans != null) {
                $trans->title_t = $data['title'];
                $trans->content_t = $data['content'];
                $trans->updated_by = 1;
                $trans->updated_on = date('Y/m/d H:i:s', strtotime('now'));
                $trans->save();
            } else {
                $trans = new TranslateCt();
                $trans->created_by = 1;
                $trans->created_on = date('Y/m/d H:i:s', strtotime('now'));
                $trans->updated_by = 0;
                $trans->updated_on = "0000/00/00 00:00:00";
                $trans->ct_id = $data['ct_id'];
                $trans->day_id = $data['day_id'];
                $trans->title_t = $data['title'];
                $trans->content_t = $data['content'];
                if (!$trans->save()) {
                    var_dump($trans->errors);die();
                }
            }
            $trans_data = TranslateCt::find()->asArray()->all();
            echo json_encode($trans_data);
        }
    }
    public function actionDelete_translate($id, $ct_id)
    {
        if (Yii::$app->request->isAjax) {
            $trans = TranslateCt::find()->where(['id' => $id, 'ct_id' => $ct_id])->one();
            if ($trans != null) {
                $trans->delete();
            }
            return 1;
        }
    }
    /**
    * this function add new a tour
    *
    */


    public function actionAdd_new_daytour()
    {

        if(Yii::$app->request->isAjax)
        {
            $model = new AtNgaymau();
            $model->tour_id = $_POST['tour_id'];
            $model->ngaymau_title = $_POST['title'];
            $model->ngaymau_body = $_POST['content'];
            $model->uo = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
            $model->ub = 1;
            $model->ngaymau_tags = 'demo';
            $model->ngaymau_image = 'none';
            $model->ngaymau_meals = '---';
            $model->ngaymau_transport = 'none';
            $model->ngaymau_hotels = 'none';
            $model->ngaymau_guides = 'none';
            $model->ngaymau_services = 'none';
            $model->language = 'en';
            if ($model->validate() && $model->save()) {
                return $model->id;
            }
            else {
                return 'error';
            }
        }
    }
    /**
    * this function get list data source
    *
    */
    public function actionGet_data()
    {
        if (Yii::$app->request->isAjax) {//print_r(1); die();
            if (isset($_POST['tour_id'])) {
                $listData = AtNgaymau::find()->select('id, ngaymau_title, ngaymau_body')->where('tour_id =0')->asArray()->all();
                if ($listData !== null) { //print_r(json_encode($listData)); die();
                    echo json_encode($listData);
                }
            }
        }
    }

    /**
    * this function get list tag
    *
    */
    public function actionGet_tag()
    {//$connection = \Yii::$app->db;
        if (Yii::$app->request->isAjax) {//print_r(1); die();
            if (isset($_POST['tour_id'])) {
                $query = new Query;
                $query  ->select('*')
                ->from('tag')
                ->leftJoin('post_tag_assn', 'tag.id =post_tag_assn.tag_id')->where('post_tag_assn.parent = 2');
                $command = $query->createCommand();
                $data = $command->queryAll();
                if ($data !== null) { //print_r(json_encode($listData)); die();
                    echo json_encode($data);
                }
            }
        }
    }
    /**
    * this function get list data when search ajax
    *
    */
    public function actionSearch()
    {
        if (Yii::$app->request->isAjax) {
            if(isset($_POST['title']) && $_POST['title'] != '') {
                $listData = AtNgaymau::find()->select('id, ngaymau_title, ngaymau_body')->where('tour_id=0')->andWhere(['LIKE', 'ngaymau_title', strtr($_POST['title'], ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false])->asArray()->all();
            }
            else {
                $listData = AtNgaymau::find()->select('id, ngaymau_title, ngaymau_body')->where('tour_id=0')->asArray()->all();
            }
            if ($listData !== null) { //print_r(json_encode($listData)); die();
                echo json_encode($listData);
            } else {
                return "empty";
            }
        }
    }
    /**
    * this function edit a tour
    *
    */
    /**
    * this function get list data when search ajax
    *
    */
    public function actionSearch_tag()
    {
        if (Yii::$app->request->isAjax) {
            if(isset($_POST['name'])) {
                if ($_POST['name'] != '') {
                     // print_r($_POST['name']);die('ok');
                    $listData = AtNgaymau::find()->allTagValues($_POST['name'])->asArray()->all();
                    if ($listData != null) { //print_r(json_encode($listData)); die();
                        echo json_encode($listData);
                    }
                }else {
                    $listData = AtNgaymau::find()->select('id, ngaymau_title, ngaymau_body')->where('tour_id=0')->asArray()->all();
                    echo json_encode($listData);
                }
            }
        }
    }
    /**
    * this function edit a tour
    *
    */
    public function actionEdit_daytour()
    {
        if(Yii::$app->request->isAjax)
        {
            $model = AtNgaymau::find()->where(['id' => $_POST['id']])->one();
            $model->ngaymau_title = $_POST['title'];
            $model->ngaymau_body = $_POST['content'];
            if ($model->save()) {
                return $model->id;
            }
            else {
                return 'error';
            }
        }
    }
    /**
    * this function remove a daytour
    *
    */
    public function actionDelete_daytour()
    {
        if (Yii::$app->request->isAjax)
        {
            if(isset($_POST['id']) > 0) {
                $model = AtNgaymau::find()->where(['id' => $_POST['id']])->one();
                if($model == null){
                    return 'error';
                }
                if($model->delete()) {
                    return 1;
                }
                else {
                    return 'error';
                }
            }
        }
    }

}
