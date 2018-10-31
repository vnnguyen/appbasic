<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
     public function actionImsprint($id = 0, $code = 0) {//die('ok');
        $key = 'hu4n12bb';
        if ($id == 0) {
            die('NOT OK');
        }
        $handle = fopen('https://my.amicatravel.com/products/x/' . $id, 'r');
        //$handle = fopen('http://www.etourhome.com/products/x/' . $id, 'r');
        //$handle = fopen('http://www.w3schools.com/php/func_filesystem_fopen.asp', 'r');
        $data = stream_get_contents($handle);
        //$data = Security::decrypt($rawData, $key);

        $theProduct = unserialize($data);
        if ($code != md5($theProduct['created_at'])) {
            die('NOT OK');
        }
        $theProduct['createdBy']['fname'] = $this->vn_str_filter($theProduct['createdBy']['fname']);
        $theProduct['createdBy']['lname'] = $this->vn_str_filter($theProduct['createdBy']['lname']);
        $data = unserialize($data);
        
        
      //  var_dump($data['days'][0]['body']);exit;
       // echo "<pre>";
       // print_r($data['days'][0]['body']);
       // exit;

        return $this->renderPartial('imsPrint', [
                    'theProduct' => $theProduct
        ]);
    }
    
    public function actionImsPrintEn($id = 0, $code = 0) {
        $key = 'hu4n12bb';
        if ($id == 0) {
            die('NOT OK');
        }

        //$handle = fopen('http://www.etourhome.com/products/x/' . $id, 'r');
        $handle = fopen('https://my.amicatravel.com/products/x/' . $id, 'r');
        //$handle = fopen('http://www.w3schools.com/php/func_filesystem_fopen.asp', 'r');
        $data = stream_get_contents($handle);
        //$data = Security::decrypt($rawData, $key);

        $theProduct = unserialize($data);
        if ($code != md5($theProduct['created_at'])) {
            die('NOT OK');
        }
        $theProduct['createdBy']['fname'] = $this->vn_str_filter($theProduct['createdBy']['fname']);
        $theProduct['createdBy']['lname'] = $this->vn_str_filter($theProduct['createdBy']['lname']);
        $data = unserialize($data);
//        echo "<pre>";
//        print_r($theProduct);
//        exit;

        return $this->renderPartial('//imsPrintEn', [
                    'theProduct' => $theProduct
        ]);
    }
    public function actionImsprintB2b($id = 0, $code = 0) {
        $key = 'hu4n12bb';
        if ($id == 0) {
            die('NOT OK');
        }

        $handle = fopen('https://my.amicatravel.com/products/x/' . $id, 'r');
        $data = stream_get_contents($handle);

        $theProduct = unserialize($data);
        if ($code != md5($theProduct['created_at'])) {
            die('NOT OK');
        }
        $theProduct['createdBy']['fname'] = $this->vn_str_filter($theProduct['createdBy']['fname']);
        $theProduct['createdBy']['lname'] = $this->vn_str_filter($theProduct['createdBy']['lname']);
        $data = unserialize($data);
       // print_r($data['conditions']);exit;
       //var_dump($data);exit;
        return $this->renderPartial('//imsPrintB2b', [
                    'theProduct' => $theProduct
        ]);
    }
    public function actionImsprintB2bEn($id = 0, $code = 0) {
        $key = 'hu4n12bb';
        if ($id == 0) {
            die('NOT OK');
        }

        $handle = fopen('https://my.amicatravel.com/products/x/' . $id, 'r');
        $data = stream_get_contents($handle);

        $theProduct = unserialize($data);
        if ($code != md5($theProduct['created_at'])) {
            die('NOT OK');
        }
        $theProduct['createdBy']['fname'] = $this->vn_str_filter($theProduct['createdBy']['fname']);
        $theProduct['createdBy']['lname'] = $this->vn_str_filter($theProduct['createdBy']['lname']);
        $data = unserialize($data);
       // print_r($data['conditions']);exit;
       //var_dump($data);exit;
        return $this->renderPartial('//imsPrintB2b_en', [
                    'theProduct' => $theProduct
        ]);
    }

    function vn_str_filter($str) {

        $unicode = array(
            'a' => 'á|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|�?|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|�?|õ|�?|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|�?|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => '�?|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => '�?',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => '�?|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|�?|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => '�?|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

        return $str;
    }
}
