<?php

namespace app\controllers;

use Yii;
use app\models\CpTour;
use app\models\Venues;
use app\models\Dv;
use app\models\Cp;
use app\models\AtCt;
use app\models\Dvc;
use app\models\Dvd;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Tour;
use app\models\AtNgaymau;
use app\models\Tag;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * CptourController implements the CRUD actions for CpTour model.
 */
class CptourController extends Controller
{

    /**
     * Lists all CpTour models.
     * @return mixed
     */
    public function actionIndex()
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
                        $arr_day[date('Y/m/d', strtotime('+'.($cnt - 1).' days', strtotime($start_date)))] =  'day '.$cnt;
                        //echo date('j/n/Y D', strtotime('+'.($cnt - 1).' days', strtotime($start_date))).'<br/>';
                    }
                }
            }
        }
        if ($model->load(Yii::$app->request->post())) {
            $cptour = $_POST['CpTour'];
            if ($cptour['id'] == '') {
                $model->tour_id = $query->id;
                $model->venue_id = $cptour['venue_id'];
                $model->dv_id = $cptour['dv_id'];
                $model->qty = $cptour['qty'];
                $model->currency = $cptour['currency'];
                $model->use_day = $cptour['use_day'];
                $model->payment_dt = $cptour['payment_dt'];
                $model->who_pay = $cptour['who_pay'];
                $model->num_day = $cptour['num_day'];
                $model->book_of = $cptour['book_of'];
                $model->pay_of = $cptour['pay_of'];
                $model->status_book = $cptour['status_book'];
                $model->parent_id = 0;
                $model->use_day = date('Y/m/d', strtotime($cptour['use_day']));
                $model->price = str_replace(',', '', $cptour['price']);
                // check group
                if (isset($_POST['cpt-group'])) {
                    $cpt_group = CpTour::findOne($_POST['cpt-group']);
                    if ($cpt_group != null) {
                        if ($cpt_group->group_id == 0) {
                            $cpt_group->group_id = $cpt_group->id;
                            if ($cpt_group->save()) {
                                $model->group_id = $cpt_group->group_id;
                            }
                        } else {
                            $model->group_id = $cpt_group->group_id;
                        }
                    }
                }
                if ($model->validate() && $model->save()) {
                    Yii::$app->getSession()->setFlash('success', 'Saved cpt success');
                    if (isset($_POST['chk_option'])) {
                        $chk_options = $_POST['chk_option'];
                        foreach ($chk_options as $option) {
                            $option = json_decode($option);
                            $cpt = new CpTour();
                            $cpt->dv_id = $option->dv_id;
                            $cpt->qty = $option->qty;
                            $cpt->price = str_replace(',', '',$option->price);
                            $cpt->currency = $option->currency;
                            $cpt->tour_id = $model->tour_id;
                            $cpt->venue_id = $model->venue_id;
                            $cpt->use_day = $model->use_day;
                            $cpt->payment_dt = $model->payment_dt;
                            $cpt->who_pay = $model->who_pay;
                            $cpt->num_day = $model->num_day;
                            $cpt->book_of = $model->book_of;
                            $cpt->pay_of = $model->pay_of;
                            $cpt->status_book = $model->status_book;
                            $cpt->parent_id = $model->id;
                            if (!$cpt ->save()) {
                                Yii::$app->getSession()->setFlash('err', 'Saved cpt fail');
                            }
                        }
                    }
                }

                return $this->redirect(Yii::$app->request->referrer);
            } else {
                $cpt_updated = CpTour::findOne($cptour['id']);
                if ($cpt_updated == null) {
                    throw new HttpException(404, 'cpt not found.');
                }
                if (isset($cptour['venue_id']) && $cptour['venue_id'] != '') {
                    $cpt_updated->venue_id = $cptour['venue_id'];
                }
                $cpt_updated->dv_id = $cptour['dv_id'];
                $cpt_updated->qty = $cptour['qty'];
                $cpt_updated->currency = $cptour['currency'];
                $cpt_updated->use_day = $cptour['use_day'];
                $cpt_updated->payment_dt = $cptour['payment_dt'];
                $cpt_updated->who_pay = $cptour['who_pay'];
                $cpt_updated->num_day = $cptour['num_day'];
                $cpt_updated->book_of = $cptour['book_of'];
                $cpt_updated->pay_of = $cptour['pay_of'];
                $cpt_updated->status_book = $cptour['status_book'];
                $cpt_updated->use_day = date('Y/m/d', strtotime($cptour['use_day']));
                $cpt_updated->price = str_replace(',', '', $cptour['price']);
                // check group
                $cpt_in_group = CpTour::find()->where('group_id = '.$cpt_updated->group_id.' AND group_id > 0  AND id != '. $cpt_updated->id)->all();
                if ($cpt_in_group != null) {
                    if (isset($_POST['cpt-group']) && $_POST['cpt-group'] != '') {
                        if ($cpt_updated->group_id != $_POST['cpt-group']) {
                            $cpt_grouped = CpTour::findOne($_POST['cpt-group']);
                            if ($cpt_grouped != null) {
                                if ($cpt_grouped->group_id == 0) {
                                    $cpt_grouped->group_id = $cpt_grouped->id;
                                    if ($cpt_grouped->save()) {
                                        $cpt_updated->group_id = $cpt_grouped->group_id;
                                    }
                                } else {
                                    $cpt_updated->group_id = $cpt_grouped->group_id;
                                }
                            }
                            if ($cpt_updated->id == $cpt_in_group[0]->group_id && count($cpt_in_group) > 0) {
                                if (count($cpt_in_group) == 1) {
                                    $cpt_in_group[0]->group_id = 0;
                                    $cpt_in_group[0]->save();
                                } else {
                                    foreach ($cpt_in_group as $cpt_g) {
                                        $cpt_g->group_id = $cpt_in_group[0]->id;
                                        $cpt_g->save();
                                    }
                                }
                            }
                        }
                    } else {
                        if ($cpt_updated->id == $cpt_in_group[0]->group_id && count($cpt_in_group) > 0) {
                            if (count($cpt_in_group) == 1) {
                                $cpt_in_group[0]->group_id = 0;
                                $cpt_in_group[0]->save();
                            } else {
                                foreach ($cpt_in_group as $cpt_g) {
                                    $cpt_g->group_id = $cpt_in_group[0]->id;
                                    $cpt_g->save();
                                }
                            }
                        }
                        $cpt_updated->group_id = 0;
                    }
                } else {
                    if (isset($_POST['cpt-group'])) {
                        $cpt_grouped = CpTour::findOne($_POST['cpt-group']);
                        if ($cpt_grouped != null) {
                            if ($cpt_grouped->group_id == 0) {
                                $cpt_grouped->group_id = $cpt_grouped->id;
                                if ($cpt_grouped->save()) {
                                    $cpt_updated->group_id = $cpt_grouped->group_id;
                                }
                            } else {
                                $cpt_updated->group_id = $cpt_grouped->group_id;
                            }
                        }
                    }
                }
                // save and add or update options
                if ($cpt_updated->validate() && $cpt_updated->save()) {
                    Yii::$app->getSession()->setFlash('success', 'Saved cpt success');
                    $cpt_has_parent = CpTour::find()->select(['id'])->where('parent_id = '.$cpt_updated->id)->indexBy('id')->column();
                    if ($cpt_has_parent != null) {
                        ///check remove or update exist option
                        $arr_diff = [];
                        $arr_updated_ids = [];
                         if (isset($_POST['chk_option'])) {
                            $chk_options = $_POST['chk_option'];
                            foreach ($chk_options as $option) {
                                $option = json_decode($option);
                                if ($option->id != '') {
                                    ///update option
                                    $cpt = CpTour::findOne($option->id);
                                    if ($cpt != null) {
                                        $arr_updated_ids[] = $cpt->id;
                                        $cpt->qty = $option->qty;
                                        $cpt->price = str_replace(',', '', $option->price);
                                        $cpt->currency = $option->currency;
                                        $cpt->tour_id = $cpt_updated->tour_id;
                                        $cpt->venue_id = $cpt_updated->venue_id;
                                        $cpt->use_day = $cpt_updated->use_day;
                                        $cpt->payment_dt = $cpt_updated->payment_dt;
                                        $cpt->who_pay = $cpt_updated->who_pay;
                                        $cpt->num_day = $cpt_updated->num_day;
                                        $cpt->book_of = $cpt_updated->book_of;
                                        $cpt->pay_of = $cpt_updated->pay_of;
                                        $cpt->status_book = $cpt_updated->status_book;
                                        $cpt->parent_id = $cpt_updated->id;
                                        if (!$cpt ->save()) {
                                            Yii::$app->getSession()->setFlash('err', 'Saved cpt option fail');
                                        }
                                    }
                                } else {
                                    ///add new option
                                    $cpt = new CpTour();
                                    $cpt->dv_id = $option->dv_id;
                                    $cpt->qty = $option->qty;
                                    $cpt->price = str_replace(',', '', $option->price);
                                    $cpt->currency = $option->currency;
                                    $cpt->tour_id = $cpt_updated->tour_id;
                                    $cpt->venue_id = $cpt_updated->venue_id;
                                    $cpt->use_day = $cpt_updated->use_day;
                                    $cpt->payment_dt = $cpt_updated->payment_dt;
                                    $cpt->who_pay = $cpt_updated->who_pay;
                                    $cpt->num_day = $cpt_updated->num_day;
                                    $cpt->book_of = $cpt_updated->book_of;
                                    $cpt->pay_of = $cpt_updated->pay_of;
                                    $cpt->status_book = $cpt_updated->status_book;
                                    $cpt->parent_id = $cpt_updated->id;
                                    if (!$cpt ->save()) {
                                        Yii::$app->getSession()->setFlash('err', 'Saved cpt option fail');
                                    }
                                }
                            }
                            $arr_diff = array_diff($cpt_has_parent,$arr_updated_ids);
                         } else {
                            $arr_diff = $cpt_has_parent;
                         }
                         // var_dump($_POST);die();
                         if (count($arr_diff) > 0) {
                             $cpt_removeds = CpTour::find()->where(['id' => $arr_diff])->all();
                             foreach ($cpt_removeds as $cpt_op) {
                                 $cpt_op->delete();
                             }
                         }
                    } else {
                        if (isset($_POST['chk_option'])) {
                            ///insert new options
                            $chk_options = $_POST['chk_option'];
                            foreach ($chk_options as $option) {
                                $option = json_decode($option);
                                $cpt = new CpTour();
                                $cpt->dv_id = $option->dv_id;
                                $cpt->qty = $option->qty;
                                $cpt->price = str_replace(',', '', $option->price);
                                $cpt->currency = $option->currency;
                                $cpt->tour_id = $cpt_updated->tour_id;
                                $cpt->venue_id = $cpt_updated->venue_id;
                                $cpt->use_day = $cpt_updated->use_day;
                                $cpt->payment_dt = $cpt_updated->payment_dt;
                                $cpt->who_pay = $cpt_updated->who_pay;
                                $cpt->num_day = $cpt_updated->num_day;
                                $cpt->book_of = $cpt_updated->book_of;
                                $cpt->pay_of = $cpt_updated->pay_of;
                                $cpt->status_book = $cpt_updated->status_book;
                                $cpt->parent_id = $cpt_updated->id;
                                if (!$cpt ->save()) {
                                    Yii::$app->getSession()->setFlash('err', 'Saved cpt option fail');
                                }
                            }
                        }
                    }
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
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
        // $options_dv = Dv::find()
        $cpts = CpTour::find()
            ->with([
                'venue',
                'dv'
            ])
            ->where('tour_id = '.$query->id);
        $cpts_group = clone $cpts;
        $cpts_group = $cpts_group->andWhere('parent_id = 0')->all();
        // var_dump($cpts);
        return $this->render('cp_tour', [
            'cpts' => $cpts->all(),
            'cpts_group' => $cpts_group,
            'model' => $model,
            'days' => (isset($arr_day))?$arr_day: null,
            'pages' => $provider->getPagination(),
            'dataProvider' => $provider->getModels(),
            'tour_model' => $data,
        ]);
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
    public function actionRemove_cpt($cpt_id)
    {
        $cpt = CpTour::findOne($cpt_id);
        if ($cpt != null) {
            $arr_removed = [];
            if ($cpt->parent_id == 0) {
                $cpt_ops = CpTour::find()->where('parent_id = '.$cpt->id)->all();
                if ($cpt_ops != null) {
                    foreach ($cpt_ops as $cpt_op) {
                        $cpt_op->delete();
                        $arr_removed[] = $cpt_op->id;
                    }
                }
            }
            if ($cpt->delete()) {
                $arr_removed[] = $cpt->id;
                return json_encode(['success' => $arr_removed]);
            }
        }
        return json_encode(['err' => 'delete fail!']);
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
        $query = Dv::find()
            ->with([
                'cp',
            ])
            ->where(['venue_id' => $id_ncc])->andWhere('status != "deleted"');
        $options = clone $query;
        $dv = $query->andWhere(['is_dependent' => 'no'])->asArray()->all();
        $options = $options->andWhere(['is_dependent' => 'yes'])->asArray()->all();
        return json_encode(['dv' => $dv, 'options' => $options]);
    }

    public function actionGet_cpt($cpt_id)
    {
        $cpt = CpTour::findOne($cpt_id);
        if ($cpt == null) {
            return json_encode(['err' => 'cpt null']);
        }
        $cpt_options = CpTour::find()->where('parent_id = '.$cpt->id)->asArray()->all();
        $query_dv = Dv::find()
            ->with([
                'cp',
            ])
            ->where(['venue_id' => $cpt->venue_id])->andWhere('status != "deleted"');
        $options = clone $query_dv;
        $dv = $query_dv->andWhere(['is_dependent' => 'no'])->asArray()->all();
        $options = $options->andWhere(['is_dependent' => 'yes'])->asArray()->all();
        return json_encode([
            'cpt' => ArrayHelper::toArray($cpt),
            'cpt_op' => $cpt_options,
            'dvs' => [
                'dv' => $dv,
                'options' => $options
            ]
        ]);
    }

    public function actionList_cp($dv_id = 0, $date_selected = '')
    {
        if (Yii::$app->request->isAjax) {
            $sl = 1;
            $price = 0;
            $data_dv = Dv::find()->where(['id' => $dv_id])->asArray()->one();

            if ($data_dv == null) {
                return json_encode(['err' => 'Ncc does not exist']);
            }
            // if ($data_dv['maxpax'] != '') {
            //     $sl = ceil(intval($arr_info['num_mem']) / intval($data_dv['maxpax']));
            // }
            if ($date_selected == '') {
                return json_encode(['err' => 'date null']);
            }
            $select_dt_arr = explode('/', $date_selected);
            $date_selected = $select_dt_arr[2].'/'.$select_dt_arr[1].'/'.$select_dt_arr[0];
            $dvc = Dvc::find()
            ->where(['venue_id'=>$data_dv['venue_id']])
            ->with([
                'dvd',
                'venue',
                'venue.dv'=>function($q){
                    return $q->where('status!="deleted"')->orderBy('grouping, sorder, name');
                },
                'venue.dv.cp',
                ])
            ->andWhere('DATE(valid_from_dt) <= "'.date('Y/m/d', strtotime($date_selected)).'" AND DATE(valid_until_dt) >= "'.date('Y/m/d', strtotime($date_selected)).'"')

            ->asArray()
            ->one();
            $currency = '';
            $id_ncc = 0;
            if ($dvc != null) {
                $conditions_change = [];
                foreach ($dvc['dvd'] as $dvd) {
                    if ($dvd['stype'] != 'date') { continue;}
                    $arr_dvds = explode(';', $dvd['def']);
                    foreach ($arr_dvds as $dvd_part) {
                        $arr_parts = explode('-', $dvd_part);
                        if (count($arr_parts) != 2) {continue;}
                        $first_arr = explode('/', $arr_parts[0]);
                        $second_arr = explode('/', $arr_parts[1]);
                        if (count($first_arr) != 3 || count($second_arr) != 3) {continue;}
                        $first_arr = $first_arr[2].'/'.$first_arr[1].'/'.$first_arr[0];
                        $second_arr = $second_arr[2].'/'.$second_arr[1].'/'.$second_arr[0];
                        $date_compair = date('Y/m/d', strtotime($date_selected));
                        if ($date_compair >= date('Y/m/d', strtotime($first_arr))
                            && $date_compair <= date('Y/m/d', strtotime($second_arr))) {
                            $dvc['dvd'] = $dvd;
                            foreach ($dvc['venue']['dv'] as $k_dv => $dv) {
                                $valid_cps = [];
                                if ($dv['id'] == $data_dv['id']) {
                                    $id_ncc = $dvc['venue']['id'];
                                    $dvc['venue']['dv'][$k_dv]['name'] = str_replace(
                                        [
                                            '[', ']', '{', '}', '|',
                                        ], [
                                            '', '', '(<span class="text-light text-pink">', '</span>)', '/',
                                            ], $dv['name']);
                                    foreach ($dv['cp'] as $k_cp => $cp) {
                                        if ($cp['period'] == $dvd['code'] && $dvc['id'] == $cp['dvc_id']) {
                                            $valid_cps[] = $cp;
                                        } else {
                                            if (count($dv['cp']) == 1 && $cp['period'] == '') {
                                                $dvc['venue']['dv'][$k_dv]['cp'][$k_cp] = $cp;
                                            }
                                        }
                                    }
                                    if (count($valid_cps) > 0) {
                                        $dvc['venue']['dv'][$k_dv]['cp'] = $valid_cps;
                                    }
                                }
                            }
                        }
                    }
                }

                echo json_encode([
                    'dvc' => $dvc
                ]);
            } else {
                return json_encode(['err' => 'dvc is null']);
            }
        }
    }

    public function actionView($id)
    {
        return $this->render('view',[]);
    }

    public function actionTimeline()
    {
        if (Yii::$app->request->isAjax) {
            $arr_date = ['hs' => '2016-01-01/2016-04-19', 'ls' => '2016-04-20/2016-12-31', '2016-09-30', '2016-12-31'];

            echo json_encode($arr_date);
        }
    }
}
