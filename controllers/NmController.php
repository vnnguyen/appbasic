<?php

namespace app\controllers;

use common\models\Company;
use app\models\User;
use common\models\Search;
use app\models\Venue;
use app\models\Nm;
use app\models\Cptmau;
use app\models\AtNgaymau;
use app\models\Dv;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;

class NmController extends Controller
{
    // 161116 Mai Phuong
    // 161119 Ngoc Anh
    // 161121 Fleur
    public $allowList = [1, 3, 28722, 17401, 1677, 12952, 26435];

    // Sample tour days
    public function actionIndex($action = '', $to = 0, $at = 0, $orderby = 'updated', $name = '', $tags = '', $show = '', $language = 'fr', $updatedby = 0)
    {
        // Prepare to add day
        if (in_array($action, ['prepare-add-day', 'prepare-add-day-sample']) && $to != 0) {
            Yii::$app->session->set('action', $action);
            Yii::$app->session->set('to', $to);
            Yii::$app->session->set('at', $at);
            return $this->redirect('/nm');
        }

        if (in_array($action, ['cancel-add-day', 'cancel-add-day-sample'])) {
            Yii::$app->session->remove('action');
            Yii::$app->session->remove('to');
            Yii::$app->session->remove('at');
            return $this->redirect('/nm');
        }

        if (Yii::$app->request->isAjax && isset($_POST['action'], $_POST['day'])) {
            if ($_POST['action'] == 'nouse') {
                $nm = Nm::findOne($_POST['day']);
                if (!$nm) {
                    throw new HttpException(404, 'Sample day not found');
                }
                if (strpos($nm->tags, 'nouse') === false) {
                    $nm->tags .= ', nouse';
                    $nm->save(false);
                }
            }
            return true;
        }

        $query = Nm::find();

        if ($show == 'b2b') {
            $query->andWhere(['owner'=>'si']);
        } else {
            $query->andWhere(['owner'=>'at']);
        }

        if ($updatedby != 0) {
            $query->andWhere(['updated_by'=>$updatedby]);
        }

        if (strpos($tags, 'nouse') === false) {
            $query->andWhere('LOCATE("nouse", tags)=0');
        }

        if ($show == '2015') {
            $query->andWhere('LOCATE("2015", tags)!=0');
        }

        if (strlen($name) > 1) {
            $query->andWhere(['like', 'title', $name]);
        }
        if (strlen($tags) > 1) {
            $tagArray = explode(',', $tags);
            $cnt = 0;
            foreach ($tagArray as $tag) {
                $cnt ++;
                $tagStr = trim($tag);
                if ($tagStr != '') {
                    $query->andWhere('LOCATE(:tag'.$cnt.', tags)!=0', [':tag'.$cnt=>$tagStr]);
                }
            }
        }
        if (strlen($language) > 1) {
            $query->andWhere(['language'=>$language]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            'route'=>'/'.URI,
        ]);

        $theDays = $query
            ->orderBy($orderby == 'updated' ? 'updated_dt DESC' : 'title')
            ->with([
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'nickname']);
                }
            ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        if ($show == 'b2b') {
            $updatedByList = Yii::$app->db->createCommand('SELECT u.id, u.nickname AS name FROM at_users u, at_ngaymau nm WHERE owner="si" AND nm.updated_by=u.id GROUP BY u.id ORDER BY lname')->queryAll();
        } else {
            $updatedByList = Yii::$app->db->createCommand('SELECT u.id, u.nickname AS name FROM at_users u, at_ngaymau nm WHERE owner="at" AND nm.updated_by=u.id GROUP BY u.id ORDER BY lname')->queryAll();
        }

        return $this->render('nm', [
            'pagination'=>$pagination,
            'theDays'=>$theDays,
            'language'=>$language,
            'name'=>$name,
            'tags'=>$tags,
            'show'=>$show,
            'orderby'=>$orderby,
            'updatedby'=>$updatedby,
            'updatedByList'=>$updatedByList,
        ]);
    }

    public function actionList_dv($vid = 0){
        if (Yii::$app->request->isAjax) {
            if ($vid == 0) {
                $dv = Dv::find()->where('status != "deleted"')->asArray()->all();
            } else {
                $dv = Dv::find()->where('venue_id = '.$vid.' AND status != "deleted"')->asArray()->all();
            }
            echo json_encode($dv);
        }
    }

    public function actionDelete_cpt($ids, $day_id) {
        if (Yii::$app->request->isAjax) {
            if ($ids != '' && $day_id != '') {
                $nm = AtNgaymau::findOne($day_id);
                if ($nm != null) {
                    $arr_ids = array_diff(explode(',', $ids), ['']);
                    $cpt_maus = Cptmau::findAll($arr_ids);
                    foreach ($cpt_maus as $cpt_mau) {
                        $cpt_mau->delete();
                    }
                    if (count($arr_ids) > 0) {
                        $after_diff = explode(',', $nm->cpt_nm_ids);
                        foreach ($arr_ids as $id) {
                            if (in_array($id, explode(',', $nm->cpt_nm_ids))) {
                                $after_diff = array_diff($after_diff, [$id]);
                            }
                        }
                        if (count($after_diff) > 0) {
                            $nm->cpt_nm_ids = implode(',', $after_diff);
                        } else {
                            $nm->cpt_nm_ids = '';
                        }
                    }
                    if ($nm->save()) {
                        return ($nm->cpt_nm_ids != '')?$nm->cpt_nm_ids:'empty';
                    } else {
                        echo json_encode(['error' => 'nm save unsuccess']);
                    }
                } else {
                     echo json_encode(['error' => 'nm null']);
                }
            } else {
                 echo json_encode(['error' => 'id null']);
            }
        }
    }

    public function actionList_cpt($arr) {
        if (Yii::$app->request->isAjax) {
            if ($arr != '') {
                $cpt_ids = array_diff(explode(',', $arr), ['']);
                $cpts = Cptmau::find()->where(['id' => $cpt_ids])
                    ->with([
                        'dv',
                        'venue' => function($q){
                            return $q->select(['id', 'name']);
                        },
                    ])->asArray()->all();
                if ($cpts != []) {
                    echo json_encode($cpts);
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }
    }

    public function actionCpt_nm() {
        if (Yii::$app->request->isAjax) {
            $id = 0;
            if ($_POST['cpt_id'] == '') {
                $cpt_nm = new Cptmau();
                $cpt_nm->day_id = $_POST['day_id'];
                $cpt_nm->dv_id = $_POST['dv_id'];
                $cpt_nm->note = $_POST['note'];
                $cpt_nm->created_by = 1;
                $cpt_nm->created_at = date('Y-m-d H:i:s', strtotime('now'));
                if ($cpt_nm->save()) {
                    $id = $cpt_nm->id;
                } else {
                    echo json_encode(['error' => 'cpt add not save']);
                }
            } else {
                $cpt_nm = Cptmau::find()->with([
                                'dv',
                                'venue' => function($q){
                                    return $q->select(['id', 'name']);
                                }])->where(['id' => $_POST['cpt_id']])->one();
                if ($cpt_nm != null) {
                    $cpt_nm->dv_id = $_POST['dv_id'];
                    $cpt_nm->day_id = $_POST['day_id'];
                    $cpt_nm->note = $_POST['note'];
                    $cpt_nm->updated_by = 1;
                    $cpt_nm->updated_at = date('Y-m-d H:i:s', strtotime('now'));
                    if ($cpt_nm->save()) {
                        $id = $cpt_nm->id;
                    } else {
                        echo json_encode(['error' => 'cpt update not save']);
                    }
                }
            }
            if ($id > 0) {
                $day = AtNgaymau::findOne($_POST['day_id']);
                if ($day != null) {
                    if ($day->cpt_nm_ids == '') {
                        $day->cpt_nm_ids = $id;
                    } else {
                        if (!in_array($id, explode(',', $day->cpt_nm_ids))) {
                            $day->cpt_nm_ids = $day->cpt_nm_ids.','.$id;
                        }
                    }
                    $str_ids = $day->cpt_nm_ids;
                    if ($day->save()) {
                        return json_encode(['cpt' => ArrayHelper::toArray($cpt_nm), 'nm_ids' => $str_ids]);
                    } else {
                        echo json_encode(['error' => 'day not save']);
                    }
                }
            }
        }
    }
    public function actionCpt_save_paste()
    {
        if (Yii::$app->request->isAjax) {
            $ids = [];
            if (count($_POST['objs']) > 0 && $_POST['day_id'] != '') {
                $objs = $_POST['objs'];
                $day_id = $_POST['day_id'];
                foreach ($objs as $obj) {
                    if ($obj['cpt_id'] != '') {
                        //$ids[] = $obj['cpt_id'];
                        continue;
                    }
                    $cpt_nm = new Cptmau();
                    $cpt_nm->day_id = $day_id;
                    $cpt_nm->dv_id = $obj['dv_id'];
                    $cpt_nm->note = $obj['note'];
                    $cpt_nm->created_by = 1;
                $cpt_nm->created_at = date('Y-m-d H:i:s', strtotime('now'));
                    if ($cpt_nm->save()) {
                        $ids[] = $cpt_nm->id;
                    } else {
                        echo json_encode(['error' => 'cpt add not save']); 
                        break;
                    }
                }
            }
            if (count($ids) > 0) {
                $day = AtNgaymau::findOne($_POST['day_id']);
                if ($day != null) {
                        $day->cpt_nm_ids = $day->cpt_nm_ids.','.implode(',', $ids);
                    if ($day->save()) {
                        return json_encode(explode(',', $day->cpt_nm_ids));
                    } else {
                        echo json_encode(['error' => 'day not save']);
                    }
                }
            } else {
                return 0;
            }
        }
    }

    public function actionSearch_cpt($s_txt = '', $day_id)
    {
        if (Yii::$app->request->isAjax) {
            $cpt_nm = Cptmau::find()
                    ->with([
                        'dv',
                        'venue'
                        ])
                    ->where('day_id = '.$day_id);
            if ($s_txt != '') {
                $arr_s = explode('/', $s_txt);
                if (count($arr_s) > 1) {
                    $name_ncc = str_replace(' ', '%', $arr_s[0]);
                    $name_dv = str_replace(' ', '%', $arr_s[1]);
                    $cpt_nm->innerJoinWith('venue')
                          ->andWhere('venues.name LIKE "%'.$name_ncc.'%"')
                          ->andWhere('dv.name LIKE "%'.$name_dv.'%"');
                } else {
                    $name_ncc = str_replace(' ', '%', $arr_s[0]);
                    $cpt_nm->innerJoinWith('venue')
                          ->andWhere('venues.name LIKE "%'.$name_ncc.'%"');
                          // $sql = $cpt_nm->createCommand()->getRawSql();
                          //   var_dump($sql);die();
                }
                $list = $cpt_nm->asArray()->all();
                if ($list != null) {
                    echo json_encode($list);
                } else {
                    return 0;
                }
            }
        }
    }

    public function actionC($id = 0) {
        if (!in_array(USER_ID, $this->allowList)) { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/nm');
        }

        $theDay = new Nm;
        $theDay->scenario = 'nm/c';
        $theDay->language = 'fr';
        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
            $theDay->owner = 'at';
            $theDay->created_dt = NOW;
            $theDay->created_by = USER_ID;
            $theDay->updated_dt = NOW;
            $theDay->updated_by = USER_ID;
            $theDay->save(false);
            Yii::$app->session->setFlash('success', 'Sample day has been created');
            return $this->redirect('/nm');
        }
        return $this->render('nm_u', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionR($id = 0)
    {
        $theDay = Nm::find()
            ->where(['id'=>$id])
            ->with([
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                }
            ])
            ->asArray()
            ->one();

        $parentId = $theDay['parent_id'];

        $theDays = $parentId == 0 ? [] : Nm::find()
            ->select(['id', 'meals', 'title'])
            ->where(['parent_id'=>$parentId])
            ->orderBy('sorder')
            ->asArray()
            ->all();
                
        return $this->render('nm_r', [
            'theDay'=>$theDay,
            'theDays'=>$theDays,
        ]);
    }

    public function actionU($id = 0) {

        $theDay = Nm::findOne($id);
        if (!$theDay) {
            throw new HttpException(404, 'Sample day not found.'); 
        }

        // if (!in_array(USER_ID, $this->allowList) || $theDay->owner == 'si') { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
        //     return $this->redirect('/nm/r/'.$id);
        // }

        $theDay->scenario = 'nm/u';
        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
            $theDay->updated_dt = NOW;
            $theDay->updated_by = 1; // USER_ID;
            $theDay->save(false);
            Yii::$app->session->setFlash('success', 'Sample day has been updated.');
            return $this->redirect('/nm');
        }
                
        return $this->render('nm_u', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionD($id = 0) {
        $theDay = Nm::findOne($id);
        if (!$theDay) {
            throw new HttpException(404, 'Sample day not found.'); 
        }

        if (!in_array(USER_ID, $this->allowList) || $theDay->owner == 'si') { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/nm/r/'.$id);
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'delete') {
            $theDay->delete();
            Yii::$app->session->setFlash('success', 'Sample day has been deleted.');
            return $this->redirect('/nm');
        }
                
        return $this->render('nm_d', [
            'theDay'=>$theDay,
        ]);
    }
}
