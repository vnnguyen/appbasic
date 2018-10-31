<?php

namespace app\controllers;

use Yii;
use app\models\Way;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * WayController implements the CRUD actions for Way model.
 */
class WayController extends Controller
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
     * Lists all Way models.
     * @return mixed
     */
    public function actionIndex($name = '', $status = '')
    {
        $model = new Way();
        $listData = $query = Way::find();
        if ($name != '') {
            if (substr($name, 0, 1) == '#') {
                $query->andWhere(['LIKE', 'acro', substr($name, 1)]);
            } else {
                $query->andWhere(['LIKE', 'name', $name]);
            }
        }
        if ($status != '') {
            $query->andWhere(['status' => $status]);
        }
        if (isset($_POST['save']) && $_POST['action'] != '') {
            if ($_POST['action'] == 'create') {
                $way = new Way();
                $way->name = $_POST['name'];
                $way->acro = $_POST['acro'];
                $way->sl = $_POST['sl'];
                $way->unit = $_POST['unit'];
                $way->status = $_POST['status'];
                $way->note = $_POST['note'];
                $way->created_at = date('Y-m-d H:i:s', strtotime('now'));
                $way->created_by = 1;
                $way->updated_at = date('Y-m-d H:i:s', strtotime(0));
                $way->updated_by = 0;
                if ($way->save()) {
                    Yii::$app->getSession()->setFlash('created', Yii::t('way', 'Create success !'));
                    //return $this->redirect(Yii::$app->request->getReferrer());
                }
            } else {
                $id = $_POST['action'];
                $way = Way::find()->where(['id' => $id])->one();
                $way->name = $_POST['name'];
                $way->acro = $_POST['acro'];
                $way->sl = $_POST['sl'];
                $way->unit = $_POST['unit'];
                $way->status = $_POST['status'];
                $way->note = $_POST['note'];
                $way->updated_at = date('Y-m-d', strtotime('now'));
                $way->updated_by = 1;
                if ($way->save()) {
                    Yii::$app->getSession()->setFlash('updated', Yii::t('way', 'Update success !'));
                    //return $this->redirect(Yii::$app->request->getReferrer());
                }
            }
            $_POST['action'] = '';
        }
            //pagination
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $dataProvider = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->orderBy('id DESC')
        ->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'pages' => $pages,
            'model' => $model,
            'name' => $name,
            'status' => $status
            ]);
    }
    public function actionGet_way($id)
    {
        if (Yii::$app->request->isAjax) {
            $way = Way::find()->where(['id' => $id])->asArray()->one();
            return json_encode($way);
        }
    }
    /**
     * Deletes an existing Way model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete_way($id)
    {
        if (Yii::$app->request->isAjax) {
            if ($this->findModel($id)->delete()) {
                return 1;
            }
        }
    }

    /**
     * Finds the Way model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Way the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Way::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
