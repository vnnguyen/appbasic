<?php

namespace app\controllers;

use Yii;
use app\models\Post1;
use app\models\post1search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\data\sqlDataProvider;

/**
 * Post1Controller implements the CRUD actions for Post1 model.
 */
class Post1Controller extends Controller
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

    /**
     * Lists all Post1 models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        if ($id == 1) {
            return $this->getListData('pay', 'index', Yii::t('app', 'Listing Payments'));
        }
        if ($id == 0) {
            return $this->getListData('adp', 'index', Yii::t('app', 'Listing Advances'));
        }
        return $this->getListData('adp', 'index', Yii::t('app', 'Listing Advance'));
    }

    /**
     * Displays a single Post1 model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id, $status = null)
    {
        $model = $this->findModel($id);
        if ($model->status == 'pay'){
            if ($model->payment_pay == 1)
                $model->payment_pay = Yii::t('app', 'ready cash');
            else
            {
                $model->payment_pay = Yii::t('app', 'transfer');
            }
        }
        else
        {
            if ($model->payment == 1)
                $model->payment = Yii::t('app', 'ready cash');
            else
            {
                $model->payment =Yii::t('app', 'transfer');
            }
        }
        $a = new \NumberFormatter("it-IT", \NumberFormatter::DECIMAL);
        $model->amount = $a->format($model->amount);
        $model->cost = $a->format($model->cost);
        return $this->render('view', [
            'model' => $model,
            'status2' => ($status == 1)? 'success' : null,
        ]);
    }
    /**
     * Creates a new Post1 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post1();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 'adp';
            $data= explode(',', $model->amount); 
            $model->amount = implode('',$data);
            $model->create_at = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'); 
            $model->deadline = date('Y-m-d', strtotime($model->deadline));
            if ($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post1 model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id = null, $status = null)
    {
        $model = $this->findModel($id);
        if($status == 1) {
            $model->scenario = 'pay';
            if ($model->load(Yii::$app->request->post())) {
                $model->status = 'pay';
                $model->amount = str_replace(',', '', $model->amount);
                $model->cost = str_replace(',', '', $model->cost);
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id, 'status2' => 'success']);
                }
            }
        }
        if ($model->load(Yii::$app->request->post()) && $status !=1) {
            $model->amount = str_replace(',', '', $model->amount);
            if ($model->cost != null) {
                $model->cost = str_replace(',', '', $model->cost);
            }

            $model->deadline = date('Y-m-d', strtotime($model->deadline));
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
                    'model' => $model,
                    'status' => $status
                ]);

    }

    /**
     * Deletes an existing Post1 model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $returnUrl = Yii::$app->request->referrer;
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect($returnUrl);
    }
    public function actionRemove($id)
    {

        $data = $model = $this->findModel($id);
        $model->delete();
        if($data->status == 'pay')
            return $this->redirect(['index', 'id' => 1]);
        if($data->status == 'adp')
            return $this->redirect(['index', 'id' => 0]);
    }

    /**
     * chuyen so thanh chu.
     * 
     * @param number
     * @return string
     */
    public function VndText($amount)
    {
             if($amount <=0)
            {
                return $textnumber="Tiền phải là số nguyên dương lớn hơn số 0";
            }
            $Text=array("Không", "Một", "Hai", "Ba", "Bốn", "Năm", "Sáu", "Bảy", "Tám", "Chín");
            $TextLuythua =array("","nghìn", "triệu", "tỷ", "ngàn tỷ", "triệu tỷ", "tỷ tỷ");
            $textnumber = "";
            $length = strlen($amount);

            for ($i = 0; $i < $length; $i++)
            $unread[$i] = 0;

            for ($i = 0; $i < $length; $i++)
            {
                $so = substr($amount, $length - $i -1 , 1);

                if ( ($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)){
                    for ($j = $i+1 ; $j < $length ; $j ++)
                    {
                        $so1 = substr($amount,$length - $j -1, 1);
                        if ($so1 != 0)
                            break;
                    }
                    if (intval(($j - $i )/3) > 0){
                        for ($k = $i ; $k <intval(($j-$i)/3)*3 + $i; $k++)
                            $unread[$k] =1;
                    }
                }
            }

            for ($i = 0; $i < $length; $i++)
            {
                $so = substr($amount,$length - $i -1, 1);
                if ($unread[$i] ==1)
                continue;

                if ( ($i% 3 == 0) && ($i > 0))
                $textnumber = $TextLuythua[$i/3] ." ". $textnumber;    

                if ($i % 3 == 2 )
                $textnumber = 'trăm ' . $textnumber;

                if ($i % 3 == 1)
                $textnumber = 'mươi ' . $textnumber;


                $textnumber = $Text[$so] ." ". $textnumber;
            }

            //Phai de cac ham replace theo dung thu tu nhu the nay
            $textnumber = str_replace("không mươi", "lẻ", $textnumber);
            $textnumber = str_replace("lẻ không", "", $textnumber);
            $textnumber = str_replace("mươi không", "mươi", $textnumber);
            $textnumber = str_replace("một mươi", "mười", $textnumber);
            $textnumber = str_replace("mươi năm", "mươi lăm", $textnumber);
            $textnumber = str_replace("mươi một", "mươi mốt", $textnumber);
            $textnumber = str_replace("mười năm", "mười lăm", $textnumber);

            return ucfirst($textnumber." đồng");
    }
    /**
     * print cash.
     *
     * @param string $id
     * @return mixed
     */
    public function actionPrint($id = null)
    {
         $this->layout = '@app/themes/mytheme/layouts/main2';
        $model = $this->findModel($id);
        if($model->status == 'adp') {
            $textVnd = $this->VndText($model->amount);
            //return view print advance payment
            return $this->render('prt_adp', [
                        'model' => $model,
                        'textVnd' => $textVnd,
                ]);
        }
        else
        {
            if($model->status == 'pay')
            {
                $cost = '';
                if ($model->cost != '' && $model->cost >0) {
                    $cost = $this->VndText($model->cost);
                    if(intval($model->cost) > intval($model->amount))
                    {
                        $b = intval($model->cost) - intval($model->amount);
                    }
                    else {
                        $c = intval($model->amount) - intval($model->cost);
                    }
                }
                //return view print pay
                return $this->render('prt_pay', [
                        'model' => $model,
                        'textCost' => $cost,
                        'b' => isset($b)? $b: null,
                        'c' =>  isset($c)?$c: null,
                    ]);
            }
        }
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function getListData($status, $view, $title)
        {
            $model = new Post1();
            $listData = $query = Post1::find()->where('status =:status',[':status' => strtolower($status)]);
            // DataSource of select

            $request = Yii::$app->request;

            $post = $request->post('Post1');
            //check search
            if ($post != null) { 
                if ($post['content'] != '') {
                    if ($status == 'pay') {
                        $query->andwhere(['LIKE', 'content_pay', strtr($post['content'], ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false]);
                        $model->content = $post['content'];
                    }
                    if ($status == 'adp') {
                        $query->andwhere(['LIKE', 'content', strtr($post['content'], ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false]);
                    $model->content = $post['content'];
                }

                }//['LIKE' ,'name',strtr($city_name,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false]
                if ($post['offer_by'] != '') {
                    if ($status == 'pay') {
                        $query->andwhere(['LIKE', 'offer_by_pay', strtr($post['offer_by'], ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false]);
                        $model->offer_by = $post['offer_by'];
                    }
                    if ($status == 'adp') {
                        $query->andwhere(['LIKE', 'offer_by', strtr($post['offer_by'], ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false]);
                        $model->offer_by = $post['offer_by'];
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
                'dataProvider' => $dataProvider,
                'pages' => $pages,
                'title' => $title,
                'model' => $model
            ]);
        }


    /**
     * Finds the Post1 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post1 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post1::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
