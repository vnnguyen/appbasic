<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Inflector;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\validators\EmailValidator;

use app\models\AtCase;
use app\models\AtCaseStat;
use app\models\AtCountries;

class CaseController extends Controller
{
	// Edit customer request
    public function actionRequest($id = 0)
    {
        $theCase = AtCase::find()
            ->where(['id'=>$id])
            ->with(['stats'])
            ->asArray()
            ->one();


        if ($theCase['status'] != 'open') {
            throw new HttpException(403, 'Case is not open.');
        }

        if (!$theCase['stats'] || $theCase['stats'] == null) {
            Yii::$app->db->createCommand()
                ->insert('at_case_stats', [
                    'case_id'=>$theCase['id'],
                    'updated_at'=>date('Y/m/d', strtotime('now')),
                    'updated_by'=>1,
                ])->execute();
            return $this->redirect('@web/case/request/'.$theCase['id']);
        }

        $caseStats = AtCaseStat::find()->where(['case_id'=>$theCase['id']])->one();
        $caseStats->pa_start_date = date('d-m-Y', strtotime($caseStats->pa_start_date));
        if ($caseStats->pa_destinations != '') {
            $caseStats->pa_destinations = explode('/', $caseStats->pa_destinations);
        }
        // var_dump($theCase);die();
        if ($caseStats->pa_pax_ages != null) {
        	$caseStats->pa_pax_ages = explode(';',$caseStats->pa_pax_ages);
        }
        $caseStats->scenario = 'cases/request';
        $Countries = AtCountries::find()->select(['code', 'name_en'])->all();
        $listCountries =[];
        foreach ($Countries as $country) {
        	$listCountries[$country->code] = $country->name_en;
        }
        if ($caseStats->load(Yii::$app->request->post())) {
                if ($caseStats->pa_destinations != '') {
                    $caseStats->pa_destinations = implode('/', $caseStats->pa_destinations);
                }
        		
                $caseStats->pa_start_date = date('Y-m-d', strtotime($caseStats->pa_start_date));
        	if (count($_POST['list']) > 0) {
        		$arr_pax = [];
        		$total_pax = 0;
        		for($i = 0; $i < count($_POST['list']); $i++){
        			if ($_POST['num_pax'][$i] < 0 || !is_numeric($_POST['num_pax'][$i])) {
        				$_POST['num_pax'][$i] = 0;
        			}
        			$arr_pax[] = $_POST['list'][$i].':'.$_POST['num_pax'][$i];
        			$total_pax = $total_pax + $_POST['num_pax'][$i];
        		}
        	}
        	$caseStats->pa_pax = (string)$total_pax;
        	$caseStats->pa_pax_ages = implode(';',$arr_pax);
        	if ($caseStats->validate()) {
        		$caseStats->save(false);
        		return $this->redirect('@web/case/request/'.$theCase['id']);
        	}
            else{
                $caseStats->pa_pax_ages = explode(';',$caseStats->pa_pax_ages);
            }
        }//var_dump($caseStats);die();
        return $this->render('case_request', [
            'theCase'=>$theCase,
            'caseStats'=>$caseStats,
            'listCountries' => $listCountries,
            'num_pax' => (isset($num_pax)) ? $num_pax : null,
        ]);

    }
}