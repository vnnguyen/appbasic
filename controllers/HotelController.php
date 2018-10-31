<?php

namespace app\controllers;

use Yii;
use app\models\Cpt;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConditionController implements the CRUD actions for Condition model.
 */
class HotelController extends Controller
{
    public function actionCreate()
    {
        $query = Cpt::find();
        $cpts = $query->where(['tour_id' => 13527, 'venue_id' => 288, 'dvtour_name' => 'Khách sạn'])
            ->with([
                'ncc',
                'ct',
                'tour'
                ])->orderBy('dvtour_day')->asArray()->all();
        $cpts[2]['dvtour_day'] = '2017-11-09';
        if ($cpts) {
            $check_in = [date('D d-m-Y', strtotime($cpts[0]['dvtour_day']))];
            $check_out = [];
            for($i = 1; $i < count($cpts); $i++){
                $next_day = date('d-m-Y', strtotime($cpts[$i-1]['dvtour_day'].' +1 day'));
                if ($next_day !== date('d-m-Y', strtotime($cpts[$i]['dvtour_day']))) {
                    $check_out[] = date('D d-m-Y', strtotime($cpts[$i-1]['dvtour_day'].' +1 day'));
                    $check_in[] = date('D d-m-Y', strtotime($cpts[$i]['dvtour_day']));
                }
            }
            $check_out[] = date('D d-m-Y', strtotime($cpts[count($cpts)-1]['dvtour_day'].' +1 day'));
            $cpts['check_in'] = $check_in;
            $cpts['check_out'] = $check_out;
        }
        // var_dump($cpts);die();
        Yii::$app->mail->compose('bookhotel', ['cpt' => $cpts])
            ->setFrom('nguyen.nv@amica-travel.com')
            ->setTo('nguyenvn099@gmail.com')
            ->setSubject('Message subject test')
            ->send();
            return $this->render('bookhotel',[
                'cpt' => $cpts
            ]);
    }

}
