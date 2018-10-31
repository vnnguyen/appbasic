<?php
namespace app\controllers;

use Yii;
use app\models\Priority;
use app\models\Tour;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PriorityController implements the CRUD actions for Priority model.
 */
class PriorityController extends Controller
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
                    // 'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($this->action->id == 'viewpriority') {
            Yii::$app->controller->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Priority models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Priority::find()->orderBy('location, category'),
            'sort' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Priority model.
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
     * Creates a new Priority model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Priority();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Priority model.
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
     * Deletes an existing Priority model.
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
     * Finds the Priority model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Priority the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Priority::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionViewpriority($id) {
        $tour = Tour::find()->where('id=:id', [':id' => $id])->one();
        $listData = null;
        if ($tour != null) {
            if ($tour->priority != null) {
                $listData = unserialize($tour->priority);
            }
        }
        return $this->render('create_diff', [
            'id' =>$id,
            'listData' => $listData,
        ]);
    }

    // public function actionList_customer() { 
    //     if (Yii::$app->request->isAjax) {
    //         $arr = [];
    //         $query = Priority::find();
    //         $arr['customer_request'] = $query->select(['content'])->where('category = 3')->distinct()->asArray()->all();
    //         echo json_encode($arr);
    //     }
    // }
    public function actionList_location() {

        if (Yii::$app->request->isAjax) {
            $arr = [];
            $query = Priority::find();
            $arr['location'] = $query->select(['location'])->distinct()->asArray()->all();
            $arr['customer_request'] = $query->select(['content'])->where('category = 3')->distinct()->asArray()->all();
            
            echo json_encode($arr);
        }
    }
    // public function actionList_location() {

    //     if (Yii::$app->request->isAjax) {
    //         $arr = [];
    //         $query = Priority::find();
    //         $arr['location'] = $query->select(['location'])->where('content =:content', [':content' => $_POST['str_cust']])->distinct()->asArray()->all();
    //         echo json_encode($arr);
    //     }
    // }
    public function actionList_other() {
        if (Yii::$app->request->isAjax) {
            $arr = [];
            $query = Priority::find();
            $arr['other_company'] = $query->select(['content'])->where('location =:location and category=1', [':location' => $_GET['str_pos']])->distinct()->asArray()->all();
            $arr['our_company'] = $query->select(['content'])->where('location =:location and category=2', [':location' => $_GET['str_pos']])->distinct()->asArray()->all();
            echo json_encode($arr);
        }
    }

    public function actionUpdateajax() {
        if (Yii::$app->request->isAjax) {

            //var_dump(unserialize(serialize($_POST['str_priority'])));exit;
            if (isset($_POST['str_priority']) && isset($_POST['tour_id'])) {
                $tour = Tour::find()->where('id=:id', [':id' => $_POST['tour_id']])->one();
                $tour->priority = serialize($_POST['str_priority']);
                if ($tour->save()){
                    return 1;
                }
            }
        }
    }

}
