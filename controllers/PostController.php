<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\postsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\data\sqlDataProvider;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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
    // public function beforeAction($action) {
    //     if (Yii::$app->session->has('language')) {
    //         Yii::$app->language = Yii::$app->session->get('language');
    //     } else {
    //         Yii::$app->language = 'en';
    //     }
    //     return parent::beforeAction($action);
    // }
    public function actionLangen(){
        Yii::$app->session->set('language', 'en'); //or $_GET['lang']
        $returnUrl = Yii::$app->request->referrer;
        $this->redirect($returnUrl);
       
    }

      public function actionLangvi(){
        Yii::$app->session->set('language', 'vi'); //or $_GET['lang']
        $returnUrl = Yii::$app->request->referrer;
        $this->redirect($returnUrl);
    }
    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex() {
        $status = 'on';
        $view = 'index';
        $title = 'List Post';
        return $this->getListData($status, $view, $title);
    }
    /**
     * set language app
     * 
     */
    public function actionLanguage($id){

        if ($id == 1) {
            $language = 'vi';
        }
        if ($id == 2) {
            $language = 'en';
        }
        $session = Yii::$app->session;
        $session->set('language', $language);
        $returnUrl = Yii::$app->request->referrer;
        return $this->redirect($returnUrl);
        // return $this->goBack();
        // $language = $session->get('language');
        // var_dump($language); die();
        // $session->remove('language');
        // if ($session->has('language'))
        // Yii::$app->language = $language;

        // $languageCookie = new Cookie([
        //     'name' => 'language',
        //     'value' => $language,
        //     'expire' => time() + 60 * 60 * 24 * 30, // 30 days
        // ]);
        // Yii::$app->response->cookies->add($languageCookie);
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        // $model->create_at=date('Y-m-d H:i:s',strtotime($model->create_at)+7*3600);
        // $model->update_at=date('Y-m-d H:i:s',strtotime($model->update_at)+7*3600);
        // $model->post_content=strip_tags($model->post_content);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // if (Yii::$app->request->isAjax) {die("ajax");}
        // if (Yii::$app->request->isPost) { die("post");}
        // if (Yii::$app->request->isGet) {die("get");}

        $model = new Post();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->date_issued = date('Y-m-d', strtotime($model->date_issued));
            $model->start_day = date('Y-m-d', strtotime($model->start_day));
            $model->expiry_day = (strtotime($model->expiry_day) != '')? date('Y-m-d', strtotime($model->expiry_day)) :null;
            $model->attach_file = UploadedFile::getInstance($model, 'attach_file');

            $model->create_at = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
            $model->create_by = "1";
            if ($model->attach_file != '') {
                $model->attach_file = $newFileName = Yii::$app->formatter->asDate('now', 'php:Y-m-d-H-i-s').'-'.$model->attach_file->baseName . '.' . $model->attach_file->extension;
            }
            $model->attach_file = UploadedFile::getInstance($model, 'attach_file');
            if ($model->save()) {
                if ($model->attach_file != null) {
                    $model->attach_file->saveAs(Yii::$app->basePath .'/uploads/'. $newFileName);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
            return $this->render('create', [
                'model' => $model,
            ]);
    }
    public function actionUpload(){

        $model = new ResourceManager();
        $uploadPath = Yii::getAlias('@root') .'/uploads/';

        if (isset($_FILES['image'])) {
            $file = \yii\web\UploadedFile::getInstanceByName('image');
          $original_name = $file->baseName;  
          $newFileName = \Yii::$app->security
                            ->generateRandomString().'.'.$file->extension;
           // you can write save code here before uploading.
            if ($file->saveAs($uploadPath . '/' . $newFileName)) {
                $model->image = $newFileName;
                $model->original_name = $original_name;
                if($model->save(false)){
                    echo \yii\helpers\Json::encode($file);
                }
                else{
                    echo \yii\helpers\Json::encode($model->getErrors());
                }

            }
        }
        else {
            return $this->render('upload', [
                'model' => $model,
            ]);
        }

        return false;
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $file = $model->attach_file;
        if ($model->load(Yii::$app->request->post())) {
            $model->date_issued=date('Y-m-d', strtotime($model->date_issued));
            $model->start_day=date('Y-m-d', strtotime($model->start_day));
            $model->expiry_day=(strtotime($model->expiry_day) != '')? date('Y-m-d', strtotime($model->expiry_day)) :null;
            

            $model->update_at=Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
            $model->update_by = "1";
            if($model->attach_file == ''){
                $model->attach_file = $file;
            }else{
                $model->attach_file = date("m-d-Y-h-i-s", time()).'-'.$model->attach_file;

            }
            $model->attach_file = UploadedFile::getInstance($model, 'attach_file');
            if($model->save()){
                if ($model->attach_file != $file) {
                    $model->attach_file->saveAs(Yii::$app->basePath .'/uploads/'. $model->attach_file->baseName . '.' . $model->attach_file->extension);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
            return $this->render('update', [
                'model' => $model,
            ]);

    }
    /**
     * dowload data.
     */
    public function actionDownload($id = null) {
        $model = $this->findModel($id);
        if($model->attach_file != ''){
            $path = Yii::$app->basePath . '/uploads';
            $file = $path . '/'.$model->attach_file;
            if (file_exists($file)) {
               Yii::$app->response->sendFile($file);
            }
        }
        else{
            echo  Yii::t('app',' No such file or directory');
        }
    }

    /**
     * Lists Off Post models.
     * @return mixed
     */
    public function actionOff()
    {
        $title = 'Off Posts';
        return $this->getListData('off', 'index', $title);
    }

    /**
     * Lists draft Post models.
     * @return mixed
     */
    public function actionDraft()
    {
        $title = 'Draft Posts ';
        return $this->getListData('draft', 'index', $title);
    }

    /**
     * Lists recycle bin Post models.
     * @return mixed
     */
    public function actionRecycle()
    {
        $param = Yii::$app->request->queryParams;
        $param['postsearch']['post_status'] = 'deleted';
        $searchModel = new postsearch();
        $dataProvider = $searchModel->search($param);

        return $this->render('recycle', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists restore Post models.
     * @return mixed
     */
    public function actionRestore($id=null)
    {
        $status = 'off';
        return $this->updateStatus($id, $status);
    }

    /**
     * Remove to recycle an existing Post model.
     * If remove is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     */
    public function actionRemove($id = null)
    {
        
        $status = 'deleted';
        $this->updateStatus($id, $status);
    }

    public function actionDelete($id = null)
    {
        $status = 'deleted';
        return $this->updateStatus($id, $status);
    }

    public function actionDelete2($id = null)
    {
        // $returnUrl = Yii::$app->request->referrer;
        $model = $this->findModel($id);
        if ($model->attach_file != null)
        {
            $this->deleteFile($model->attach_file);
        }
        $model->delete();
        // return $this->redirect($returnUrl);
    }
     /**
    * Process deletion of file
    *
    * @return boolean the status of deletion
    */
    public function deleteFile($fileName) {
        $file = Yii::$app->basePath.'/uploads/'.$fileName;
        
        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }
        
        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }
        return true;
    }


    public function updateStatus($id, $status)
    {
        $returnUrl = Yii::$app->request->referrer;
        $model = $this->findModel($id);
        $model->post_status = $status;
        if($model->save()){
            return $this->redirect($returnUrl);
        }
    }
    /**
     * Lists all Post models.
     * @return mixed
     */
    public function getListData($status, $view, $title)
        {
            $model = new Post();
            $listData = $query = Post::find()->where('post_status =:status',[':status' => $status])->orderBy(['id' => SORT_DESC, 'date_issued' => SORT_DESC]);
            // DataSource of select
            $listData = $listData->all();

            $request = Yii::$app->request;

            $post = $request->post('Post');
            //check search
            if ($post != null) {
                if ($post['post_title'] != '') {
                    $query->andwhere(['LIKE', 'post_title', strtr($post['post_title'], ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false]);
                    $model->post_title=$post['post_title'];
                }//['LIKE' ,'name',strtr($city_name,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false]
                if ($post['people_signing'] != '') {
                    $query->andwhere('id=:id', [':id' => $post['people_signing']]);
                    $model->people_signing = $post['people_signing'];
                }
                if ($post['date_issued'] != '') {
                    $query->andwhere('date_issued >=:from_date', [':from_date' => date('Y-m-d', strtotime($post['date_issued']))]);
                    $model->date_issued = $post['date_issued'];
                }
                if ($post['expiry_day'] != '') {
                    if(date('Y-m-d', strtotime($post['expiry_day'])) > date('Y-m-d', strtotime($post['date_issued']))) {
                        $query->andwhere('date_issued <=:to_date', [':to_date' => date('Y-m-d', strtotime($post['expiry_day']))]);
                        $model->expiry_day = $post['expiry_day'];
                    } else {
                        $errors = "ngay bi sai";
                    }
                }
            }
            //pagination
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
            $dataProvider = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
           return $this->render($view, [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'listData' => $listData,
                'pages' => $pages,
                'title' => $title,
            ]);
        }





    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

