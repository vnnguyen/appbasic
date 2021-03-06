<?
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\web\Response;
use common\models\Comment;
use app\models\Company;
use app\models\Cpt;
use app\models\Cp;
use app\models\Rout;
use common\models\Cpg;
use app\models\AtLtt;
use common\models\Mm;
use app\models\AtMtt;
use app\models\Venue;
use app\models\Tour;
use app\models\User;
use app\models\Dv;
use yii\helpers\ArrayHelper;

class CptController extends Controller
{
    // 160916 Mark as not paid: deleted mtt & empty checks
    public function actionMu($id = 0)
    {
        if (USER_ID != 1) {
            throw new HttpException(403, 'Access denied');
        }
        $sql = 'DELETE FROM at_mtt WHERE cpt_id=:id';
        Yii::$app->db->createCommand($sql, [':id'=>$id])->execute();
        $sql = 'UPDATE cpt SET c1="", c2="", c3="", c4="", c5="", c6="", c7="", c8="", c9="", paid_full="" WHERE dvtour_id=:id LIMIT 1';
        Yii::$app->db->createCommand($sql, [':id'=>$id])->execute();
        return $this->redirect('/cpt/r/'.$id);
    }

    public function actionAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $action = Yii::$app->request->post('action', '');
        $tour_id = Yii::$app->request->post('tour_id', 0);
        $dvtour_id = Yii::$app->request->post('dvtour_id', 0);
        $mtt_id = Yii::$app->request->post('mtt_id', 0);

        $theCpt = Cpt::find()
            ->where(['tour_id'=>$tour_id, 'dvtour_id'=>$dvtour_id])
            ->asArray()
            ->one();

        if (!$theCpt || $theCpt['tour_id'] != $tour_id) {
            throw new HttpException(404, 'Cpt not found');
        }

        // Danh dau da thanh toan 100%
        if ($action == 'mark-paid') {
            if (substr($theCpt['c3'], 0, 2) == 'on' || substr($theCpt['c4'], 0, 2) == 'on'){
                throw new HttpException(403, 'Already marked as paid: #'.$dvtour_id);
            }

            // 161116 Minh Minh Laos
            if (!in_array(USER_ID, [30554, 1, 28431, 11, 17, 16, 29739, 30085, 32206, 34743, 34717, 36871, 37159])) {
                throw new HttpException(403);
            }
            // Kiem tra xem da co mtt nay trong basket chua
            $mtt = Mtt::find()
                ->where(['status'=>'draft', 'created_by'=>USER_ID, 'cpt_id'=>$dvtour_id])
                ->one();
            if ($mtt) {
                throw new HttpException(403, 'In basket');
            }
            $theMtt = new Mtt;
            $theMtt->created_dt = NOW;
            $theMtt->created_by = USER_ID;
            $theMtt->updated_dt = NOW;
            $theMtt->updated_by = USER_ID;
            $theMtt->cpt_id = $theCpt['dvtour_id'];
            $theMtt->status = 'on';
            $theMtt->payment_dt = NOW;
            $theMtt->amount = $theCpt['qty'] * $theCpt['price'];
            $theMtt->currency = $theCpt['unitc'];
            $theMtt->xrate = 1;
            if ($theMtt->save(false)) {
                // Success, mark C3
                $sql = 'UPDATE cpt SET c3=:c3 WHERE dvtour_id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [
                    ':c3'=>'on,'.USER_ID.','.NOW,
                    ':id'=>$theCpt['dvtour_id'],
                    ])->execute();
                // Return
                // $ret = new class()
                // {
                //     public $code = 200;
                //     public $status = 'ok';
                //     public $message = 'Đã thêm mục thanh toán.';
                // };

                // Theo doi MM
                if (USER_ID == 30554) {
                    $this->mgIt(
                        'ims | Minh Minh checked out cpt "'.$theCpt['dvtour_name'].' - '.$theMtt->amount.'"',
                        '//mg/cpt_mark-paid',
                        [
                            'theCpt'=>$theCpt,
                            'theMtt'=>$theMtt,
                        ],
                        [
                            ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                            ['to', 'hn.huan@gmail.com', 'Huân', 'H.'],
                        ]
                    );
                }


                return $ret;
            } else {
                // $ret = new class()
                // {
                //     public $code = 401;
                //     public $status = 'nok';
                //     public $message = 'Không thêm được mục thanh toán';
                // };
                // return $ret;
            }
        }

        // Check/Uncheck anything
        if ($action == 'check') {
                // $ret = new class()
                // {
                //     public $code = 401;
                //     public $status = 'nok';
                //     public $message = 'Không thêm được mục thanh toán';
                // };
                // return $ret;
        }

        // Check/Uncheck mtt
        if ($action == 'check-mtt') {
            // Tu Phuong
            // 160305 Thu Hien
            // 160914 Thu Huyen
            // 161024 Kim Mong
            if (!in_array(USER_ID, [1, 11, 34717, 32206])) {
                throw new HttpException(403);
            }
            // Kiem tra co mtt nay khong
            $theMtt = Mtt::find()
                ->where(['cpt_id'=>$dvtour_id, 'id'=>$mtt_id])
                ->one();
            if (!$theMtt) {
                throw new HttpException(404, 'Not found');
            }

            if ($theMtt['check'] == '') {
                // Chua check, now check
                $check = 'ok,'.USER_ID.','.NOW;
                $returnClass = 'label-success';
            } else {
                // Da check, now un-check
                $check = '';
                $returnClass = 'label-default';
            }
            $theMtt->check = $check;
            if ($theMtt->save(false)) {
                // Return
                // $ret = new class()
                // {
                //     public $code = 200;
                //     public $status = 'ok';
                //     //public $class = $returnClass;
                // };
                // return $ret;
            } else {
                // $ret = new class()
                // {
                //     public $code = 401;
                //     public $status = 'nok';
                //     //public $class = 'label-danger';
                // };
                // return $ret;
            }
        }
        // Them hoac bo muc thanh toan nay
        if ($action == 'add-to-b') {
            if (USER_ID != 1) {
                // throw new HttpException(403, 'Access denied.');
            }
            /*
            $ret = new class()
                {
                    public $code = 403;
                    public $status = 'nok';
                    public $message = 'This function is under development. Please try again later.';
                    public $count = 0;
                    public $class = 'label-danger';
                };

            $classList = ['label-danger', 'label-default', 'label-info', 'label-success', 'bg-pink'];
            $ret->count = random_int(0, 100);
            $ret->class = $classList[random_int(0, count($classList) - 1)];
            return $ret;
            exit;
            */
            if (substr($theCpt['c3'], 0, 2) == 'on' || substr($theCpt['c4'], 0, 2) == 'on'){
                throw new HttpException(403, 'Already marked as paid: #'.$dvtour_id);
            }

            // Them: them Mtt voi ltt_id = 0
            // Bo: xoa Mtt co ltt_id = 0

            // Kiem tra xem da co mtt nay trong basket chua
            $mtt = Mtt::find()
                ->where(['status'=>'draft', 'created_by'=>USER_ID, 'cpt_id'=>$dvtour_id])
                ->one();
            // Chua co: them vao basket
            if (!$mtt) {
                $theMtt = new Mtt;
                $theMtt->created_dt = NOW;
                $theMtt->created_by = USER_ID;
                $theMtt->updated_dt = NOW;
                $theMtt->updated_by = USER_ID;
                $theMtt->cpt_id = $theCpt['dvtour_id'];
                $theMtt->status = 'draft';
                $theMtt->paid_in_full = '';
                $theMtt->currency = $theCpt['unitc'];
                $theMtt->xrate = 1;
                $theMtt->amount = $theCpt['qty'] * $theCpt['price'];
                if ($theMtt->save(false)) {
                    // Success
                    // $ret = new class()
                    // {
                    //     public $code = 200;
                    //     public $status = 'ok';
                    //     public $message = 'Đã thêm mục thanh toán.';
                    //     public $class = 'label-info';
                    // };
                    // return $ret;
                }
            } else {
                $mtt->delete();
                // $ret = new class()
                // {
                //     public $code = 200;
                //     public $status = 'ok';
                //     public $message = 'Đã xoá mục thanh toán.';
                //     public $class = 'label-default';
                // };
                // return $ret;
            }
        }

        // Danh dau da lay hd VAT
        // Them hoac bo muc thanh toan nay
        if ($action == 'vat-ok') {
            if (!in_array(USER_ID, [1, 11, 17, 29739, 30085, 32206, 34743, 34717, 37159])) {
                throw new HttpException(403, 'Access denied: #'.$dvtour_id);
            }

            $theCpt = Cpt::find()
                ->where(['dvtour_id'=>$dvtour_id])
                ->one();

            if (!$theCpt) {
                throw new HttpException(403, 'CPT not found: #'.$dvtour_id);
            }

            if ($theCpt['vat_by'] != 0 && $theCpt['vat_by'] != USER_ID) {
                throw new HttpException(403, 'CPT was updated by another user.');
            }

            if ($theCpt['vat_ok'] == 'ok') {
                $newValue = '';
            } else {
                $newValue = 'ok';
            }

            $theCpt->vat_ok = $newValue;
            $theCpt->vat_by = USER_ID;

            if ($theCpt->save(false)) {
                // Success
                if ($newValue == '') {
                    // $ret = new class()
                    // {
                    //     public $code = 200;
                    //     public $status = 'ok';
                    //     public $message = 'Đã bỏ đánh dấu VAT.';
                    //     public $class = 'label-default';
                    // };

                } else {
                    // $ret = new class()
                    // {
                    //     public $code = 200;
                    //     public $status = 'ok';
                    //     public $message = 'Đã đánh dấu VAT.';
                    //     public $class = 'label-success';
                    // };
                }
                return $ret;
            }

            throw new HttpException(404, 'Error updating HĐ');
        }

        //throw new HttpException(401, 'Invalid request');
        // $ret = new class()
        // {
        //     public $code = 401;
        //     public $status = 'nok';
        //     public $message = 'Invalid request.';
        // };
        // return $ret;
    }
    public function actionCheck_ltt($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $ltt = AtLtt::findOne($id);
            $USER_ID = 1;
            if ($ltt) {
                $status = $ltt->status;
                if ($status == 'request' && $USER_ID = 1) {
                    $ltt->status = 'check1';
                } elseif ($status == 'check1' && $USER_ID = 1) {
                    $ltt->status = 'check2';
                } elseif ($status == 'check2' && $USER_ID = 1) {
                    $ltt->status = 'check3';
                } elseif ($status == 'check3') {
                    echo json_encode(['warn' => 'This Ltt Checked']);
                }
                $ltt->updated_by = $USER_ID;
                $ltt->updated_dt = date('Y-m-d H:i:s', strtotime('now'));
                $ltt->actions .= ';'.$USER_ID.','.$ltt->status.','.$ltt->updated_dt;
                if ($ltt->save()) {
                    echo json_encode(ArrayHelper::toArray($ltt));
                } else {
                    echo json_encode(['err' => 'ltt not save']);
                }
            } else {
                echo json_encode(['err' => 'ltt not found']);
            }
        }
    }
    public function actionStop_ltt($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $ltt = AtLtt::findOne($id);
            $USER_ID = 1;
            if ($ltt) {
                $status = $ltt->status;
                if ($status == 'request' && $USER_ID = 1) {
                    $ltt->status = 'deleted';
                } elseif ($status == 'check1' && $USER_ID = 1) {
                    $ltt->status = 'deleted';
                } elseif ($status == 'check2' && $USER_ID = 1) {
                    $ltt->status = 'deleted';
                } elseif ($status == 'check3') {
                    $ltt->status = 'deleted';
                }
                $ltt->updated_by = $USER_ID;
                $ltt->updated_dt = date('Y-m-d H:i:s', strtotime('now'));
                $ltt->actions .= ';'.$USER_ID.','.$ltt->status.','.$ltt->updated_dt;
                if ($ltt->save()) {
                    $mtts = AtMtt::find()->where(['id' => explode(',', $ltt->mtt_ids)])->all();
                    if ($mtts) {
                        foreach ($mtts as $mtt) {
                            $mtt->ltt_id = 0;
                            $mtt->updated_by = $USER_ID;
                            $mtt->updated_dt = date('Y-m-d H:i:s', strtotime('now'));
                            if (!$mtt->save()) {
                                return json_encode(['err' => 'mtt not save']);
                                break;
                            }
                        }
                    }
                    echo json_encode(ArrayHelper::toArray($ltt));
                } else {
                    echo json_encode(['err' => 'ltt not save']);
                }
            } else {
                echo json_encode(['err' => 'ltt not found']);
            }
        }
    }
    public function actionStop_mtt($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $mtt = AtMtt::findOne($id);
            $USER_ID = 1;
            if ($mtt) {
                $ltt = AtLtt::findOne($mtt->ltt_id);
                if ($ltt) {
                    if (in_array($mtt->id, explode(',', $ltt->mtt_ids))) {
                        $status = $ltt->status;
                        if ($status == 'request' && $USER_ID == $ltt->created_by) {
                            $ltt->mtt_ids = implode(',',array_diff(explode(',', $ltt->mtt_ids), [$mtt->id]));
                            $mtt->ltt_id = 0;
                        } elseif ($status == 'check1' && $USER_ID = 1) {
                            $ltt->mtt_ids = implode(',',array_diff(explode(',', $ltt->mtt_ids), [$mtt->id]));
                            $mtt->ltt_id = 0;
                        } elseif ($status == 'check2' && $USER_ID = 1) {
                            $ltt->mtt_ids = implode(',',array_diff(explode(',', $ltt->mtt_ids), [$mtt->id]));
                            $mtt->ltt_id = 0;
                        } elseif ($status == 'check3') {
                            $ltt->mtt_ids = implode(',',array_diff(explode(',', $ltt->mtt_ids), [$mtt->id]));
                            $mtt->ltt_id = 0;
                        }
                        //tinh lai tien
                        $dntts = AtMtt::find()->where(['id' => explode(',', $ltt->mtt_ids),'created_by' => 1, 'status' => 'draft'])->all();
                        $t_amount = [];
                        $money_group = [];
                        $moneys = [];

                        foreach ($dntts as $dntt) {
                            $money_group[$dntt->currency][] = $dntt->amount;
                            $ids[] = $dntt->id;
                        }
                        foreach ($money_group as $key => $prices) {
                            $moneys[$key] = 0;
                            foreach ($prices as $price) {
                                if (isset($moneys[$key])) {
                                    $moneys[$key] += $price;
                                }
                            }
                        }
                        foreach ($moneys as $k => $v) {
                            $t_amount[] = $v.'-'.$k;
                        }
                        //
                        $ltt->updated_by = $USER_ID;
                        $ltt->updated_dt = date('Y-m-d H:i:s', strtotime('now'));
                        $ltt->t_amount = implode(';', $t_amount);
                        $ltt->actions .= ';'.$USER_ID.','.$mtt->status."-deleted-$id,".$mtt->updated_dt;
                        if ($ltt->save()) {
                            $mtt->updated_by = $USER_ID;
                            $mtt->updated_dt = date('Y-m-d H:i:s', strtotime('now'));
                            if ($mtt->save()) {
                                echo json_encode(ArrayHelper::toArray($mtt));
                            } else {
                                echo json_encode(['err' => 'mtt not save']);
                            }
                        } else {
                            echo json_encode(['err' => 'ltt not save']);
                        }
                    }
                }
            } else {
                echo json_encode(['err' => 'ltt not found']);
            }
        }
    }
    public function actionCreate_ltt()
    {
        if (Yii::$app->request->isAjax) {
            $note = '';
            if ($_POST['note_ltt'] != '') {
                $note = $_POST['note_ltt'];
            }
            $USER_ID = 1;
            $dntts = AtMtt::find()
                ->where(['created_by' => 1, 'status' => 'draft', 'ltt_id' => 0])->all();
            if ($dntts != null) {
                $ids = [];
                //phan loai tien
                $t_amount = [];
                $money_group = [];
                $moneys = [];

                foreach ($dntts as $dntt) {
                    $money_group[$dntt->currency][] = $dntt->amount;
                    $ids[] = $dntt->id;
                }
                foreach ($money_group as $key => $prices) {
                    $moneys[$key] = 0;
                    foreach ($prices as $price) {
                        if (isset($moneys[$key])) {
                            $moneys[$key] += $price;
                        }
                    }
                }
                foreach ($moneys as $k => $v) {
                    $t_amount[] = $v.'-'.$k;
                }
                // tao ltt
                $ltt = new AtLtt();
                $ltt->created_by = $USER_ID;
                $ltt->created_dt = date('Y-m-d H:i:s', strtotime('now'));
                $ltt->updated_by = 0;
                $ltt->updated_dt = date('Y-m-d H:i:s', strtotime(0));

                $ltt->status = 'request';
                $ltt->mtt_ids = implode(',', $ids);
                $ltt->note = $note;
                $ltt->t_amount = implode(';', $t_amount);
                $ltt->actions = $ltt->created_by.','.$ltt->status.','.$ltt->created_dt;
                if ($ltt->save()) {
                    foreach ($dntts as $dntt) {
                        $dntt->ltt_id = $ltt->id;
                        if (!$dntt->save()) {
                            echo json_encode($dntt->errors);
                        }
                    }
                    echo json_encode(ArrayHelper::toArray($ltt));
                } else {
                    echo json_encode(['error' => $ltt->errors]);
                }
            } else return 0;
        }
    }
    public function actionList_ltt()
    {
        $query = AtLtt::find()->where('status != "deleted"');
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);
        $ltts = $query->orderBy('id DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return $this->render('ltt', [
                'pagination' => $pagination,
                'ltts' => $ltts,
            ]);
    }
    public function actionList_detail_ltt($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $ltt = AtLtt::findOne($id);
            if ($ltt != null) {
                $mtts = AtMtt::find()
                            ->with([
                                'cpt',
                                'venue' => function($q){
                                    return $q->select(['id', 'name']);
                                },
                            ])
                            ->where(['id' => explode(',', $ltt->mtt_ids)])
                            ->orderBy('id DESC')->asArray()->all();
                if ($mtts != null) {
                    echo json_encode($mtts);
                } else {
                    echo json_encode(['error' => 'mtt null']);
                }
            } else {
                echo json_encode(['error' => 'ltt null']);
            }
        }
    }
    public function actionEdit_dntt()
    {
        if (Yii::$app->request->isAjax) {
            if ($_POST['mtt_id'] != '' && $_POST['amount'] != '') {
                $dntt = AtMtt::findOne($_POST['mtt_id']);
                $dntt->amount = $_POST['amount'];
                $dntt->note = $_POST['note'];
                $dntt->updated_dt = date('Y-m-d H:i:s', strtotime('now'));
                $dntt->updated_by = 1;
                if ($dntt->save()) {
                    return json_encode(ArrayHelper::toArray($dntt));
                } else {
                    return json_encode($dntt->errors);
                }
            }
        }
    }
    public function actionRemove_dntt($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $dntt = AtMtt::findOne($id);
            $undo = clone $dntt;
            if ($dntt != null) {
                if ($dntt->delete()) {
                    echo json_encode(ArrayHelper::toArray($undo));
                } else {
                    echo json_encode(['error' => 'delete error']);
                }
            }
        }
    }
    public function actionCpt_percen($ids = '', $percen = 0)
    {
        if (Yii::$app->request->isAjax) {
            $USER_ID = 1;
            if ($ids != '' && $percen > 0) {
                $ids = array_diff(explode(',', $ids),['']);
                $mtts = AtMtt::find()
                        ->with([
                            'cpt',
                            'venue' => function($q){
                                return $q->select(['id', 'name']);
                            }])
                        ->where(['id' => $ids, 'created_by' => 1, 'status' => 'draft', 'ltt_id' => 0])->all();
                if ($mtts != null) {
                    foreach ($mtts as $mtt) {
                        $mtt->amount = $percen * $mtt->rem_amount / 100;
                        if (!$mtt->save()) {
                            return json_encode(['error' => 'mtt is not save']);
                        }
                    }
                    $mtt_lasts = AtMtt::find()
                        ->with([
                            'cpt',
                            'venue' => function($q){
                                return $q->select(['id', 'name']);
                            }])
                        ->where(['created_by' => $USER_ID, 'status' => 'draft', 'ltt_id' => 0])->asArray()->all();
                    echo json_encode($mtt_lasts);
                } else {
                    echo json_encode(['error' => 'mtts is null']);
                }
            }
        }
    }
    public function actionList_dntt()
    {
        if (Yii::$app->request->isAjax) {
            $USER_ID = 1;
            $dntts = AtMtt::find()
                ->with([
                    'cpt',
                    'venue' => function($q){
                        return $q->select(['id', 'name']);
                    },
                ])->where(['created_by' => $USER_ID, 'status' => 'draft', 'ltt_id' => 0])->asArray()->all();
            if ($dntts != null) {
                echo json_encode($dntts);
            } else return 0;
        }
    }
    public function actionCpt_dntt()
    {
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['dvtour_id']) && isset($_POST['tour_id'])) {
                $tour_id = $_POST['tour_id'];
                $dvtour_id = $_POST['dvtour_id'];
                $cpt = Cpt::findOne($dvtour_id);
                $USER_ID = 1;
                if ($cpt != null && $cpt->tour_id == $tour_id) {
                    $query = AtMtt::find()
                            ->where(['cpt_id' => $dvtour_id]);
                    $chk_mtt = clone $query;
                    $chk_mtt = $chk_mtt->where(['cpt_id' => $dvtour_id])
                            ->andWhere('ltt_id != 0')
                            ->All();
                    $amount = 0;
                    if ($chk_mtt != null) {
                        $total = 0;
                        foreach ($chk_mtt as $item) {
                            $total += $item->amount;
                        }
                        $amount =  $cpt['qty'] * $cpt['price'] - $total;
                    } else {
                        $amount = $cpt['qty'] * $cpt['price'];
                    }
                    $mtt = clone $query;
                    $mtt = $mtt->where(['status' => 'draft', 'cpt_id' => $dvtour_id, 'ltt_id' => 0])->one();
                    if (!$mtt) {
                        if ($amount <= 0) {
                            return json_encode(['limit' => 'limited']);
                        }
                        $theMtt = new AtMtt();
                        $theMtt->created_dt = date('Y-m-d H:i:s', strtotime('now'));
                        $theMtt->created_by = $USER_ID;
                        $theMtt->updated_dt = date('Y-m-d H:i:s', strtotime('now'));
                        $theMtt->updated_by = $USER_ID;
                        $theMtt->cpt_id = $dvtour_id;
                        $theMtt->status = 'draft';
                        $theMtt->paid_in_full = 0;
                        $theMtt->currency = $cpt['unitc'];
                        $theMtt->xrate = $USER_ID;
                        $theMtt->rem_amount = $amount;
                        $theMtt->amount = $amount;
                        $theMtt->ltt_id = 0;
                        if ($theMtt->save()) {
                            return json_encode(['success' => 'Saved']);
                        } else {
                            return json_encode(['err' => 'error']);
                        }
                    } else {
                        if ($mtt->created_by == $USER_ID) {
                            $mtt->delete();
                            return json_encode(['success' => 'deleted']);
                        } else {
                            return json_encode(['limit' => 'This cpt created by another']);
                        }
                    }
                }

            }
        }
    }



    public function actionIndex($vat = '', $user = 'all', $tour = '', $dvtour = '', $search = '', $filter = '', $payer = '', $sign = '', $currency = '', $tt = '', $orderby = 'dvtour_day', $unit = '', $link = 'all', $limit = 25)
    {
        // if (MY_ID > 4 && !in_array(MY_ID, [1,2,3,4,28431,  11,   17,   16,  20787,29739, 30085, 25457])) {
        //     //throw new HttpException(403, 'Access denied.');
        // }
        $USER_ID = 1;
        if (isset($_POST['active_link'])) {
            if (isset($_POST['chk']) && count($_POST['chk']) > 0) {
                foreach ($_POST['chk'] as $k => $v) {
                    $cpt = Cpt::find()->where(['venue_id' => $_POST['venue_id'],'dvtour_id' => $v])->one();

                    if ($cpt == null) {
                        Yii::$app->getSession()->setFlash("error[$v]", 'links nok');
                        continue;
                    }
                    $cpt->dv_id = ($_POST['active_link'] != '')?$_POST['active_link']: 0;
                    $cpt->linked_at = date('Y-m-d H:i:s', strtotime('now'));
                    $cpt->linked_by = $USER_ID;
                    $cpt->updated_at = date('Y-m-d', strtotime('now'));
                    $cpt->updated_by = $USER_ID;

                    $routs = Rout::find()->where(['venue_id' => $_POST['venue_id'], 'dv_id' => $cpt->dv_id])->all();
                    if ($routs != null) {
                        foreach ($routs as $rout) {
                            if (isset($_POST['unknow']) && $_POST['unknow'] == 1) {
                                if ($rout->unknow == '') {
                                    $rout->unknow = $cpt->unit;
                                } else {
                                    if (!in_array(strtolower(trim($cpt->unit)), explode(';', strtolower(trim($rout->unknow))))) {
                                        $rout->unknow = $rout->unknow.'; '.$cpt->unit;
                                        $rout->unknow = implode(';', array_unique(explode(';', strtolower(trim($rout->unknow)))));
                                    }
                                }
                            } else {
                                if ($rout->content == '') {
                                    $rout->content = $cpt->unit;
                                }
                                else {
                                    if (!in_array(strtolower(trim($cpt->unit)), explode(';', strtolower($rout->content)))) {
                                        $rout->content = $rout->content.'; '.$cpt->unit;
                                        $rout->content = implode(';', array_unique(explode(';', strtolower($rout->content))));
                                    }
                                }
                            }
                        }
                    } else {
                        $rout = new rout();
                        $rout->dv_id = $cpt->dv_id;
                        $rout->venue_id = $_POST['venue_id'];
                        if (isset($_POST['unknow']) && $_POST['unknow'] == 1) {
                            $rout->unknow = $cpt->unit;
                            $rout->content = '';
                        } else {
                            $rout->unknow = '';
                            $rout->content = $cpt->unit;
                        }
                    }
                    $rout->link_cpt_id = 0;
                    $rout->save();

                    if ($cpt->save()) {
                        Yii::$app->getSession()->setFlash("success[$v]", 'links ok');
                    } else {
                        Yii::$app->getSession()->setFlash("error[$v]", 'links nok');
                    }
                }
            }

        }
        else {
            if (isset($_POST['save'])) {
                $cpt_l = Cpt::find()->where('dvtour_id = '.$_POST['cpt_id'])->one();
                if ($cpt_l != null) {
                    $cpt_l->dv_id = $_POST['dv'];
                    $cpt_l->linked_at = date('Y-m-d H:i:s', strtotime('now'));
                    $cpt_l->linked_by = $USER_ID;
                    if ($cpt_l->save()) {
                        Yii::$app->getSession()->setFlash("success", 'links ok');
                    } else {
                        Yii::$app->getSession()->setFlash("error", 'links nok');
                    }
                }
            }
        }
        if (!in_array($limit, [25, 50, 100, 500])) {
            $limit = 25;
        }

        $query = Cpt::find();

        if ($user == 'me') {
            $query->andWhere(['updated_by'=>USER_ID]);
        } elseif ((int)$user != 0) {
            $query->andWhere(['updated_by'=>$user]);
        }

        if ($tt == 'no') {
            $query->andWhere('SUBSTRING(c3,1,2)!="on"');
        } elseif ($tt == 'yes') {
            $query->andWhere('SUBSTRING(c3,1,2)="on"');
        } elseif ($tt == 'c3') {
            $query->andWhere('SUBSTRING(c3,1,2)="on"');
            $query->andWhere('SUBSTRING(c4,1,2)!="on"');
        } elseif ($tt == 'c4') {
            $query->andWhere('SUBSTRING(c3,1,2)="on"');
            $query->andWhere('SUBSTRING(c4,1,2)="on"');
        } elseif ($tt == 'overdue') {
            $query->andWhere('SUBSTRING(c3,1,2)!="on"');
            $query->andWhere('SUBSTRING(c4,1,2)!="on"');
            $query->andWhere('due!=0');
            $query->andWhere('due<=:due', ['due'=>date('Y-m-d')]);
        }

        // Search for tour with code
        $theTour = false;
        $theTours = [];
        $tourIdList = [];
        if (strlen($tour) > 2) {
            // yyyy-mm Thang khoi hanh tour
            if (preg_match("/(\d{4})-(\d{2})/", $tour) || preg_match("/(\d{4})-(\d{2})-(\d{2})/", $tour)) {
                $theTours = Tour::findBySql('SELECT t.id, day_from FROM at_tours t, at_ct p WHERE p.id=t.ct_id AND SUBSTRING(day_from,1,'.strlen($tour).')=:ym', [':ym'=>$tour])
                    ->indexBy('id')
                    ->asArray()
                    ->all();
            } else {
                $theTours = Tour::find()
                    ->select(['id'])
                    ->where(['or', ['like', 'code', $tour], ['id'=>$tour]])
                    ->indexBy('id')
                    ->asArray()
                    ->all();
            }
            if (!empty($theTours)) {
                $tourIdList = array_keys($theTours);
                $query->andWhere(['tour_id'=>$tourIdList]);
                if (count($theTours) == 1) {
                    $theTour = Tour::find()
                        ->where(['id'=>key($theTours)])
                        ->with([
                            'product',
                            'product.days',
                            'product.bookings',
                        ])
                        ->asArray()
                        ->one();
                }
            }
        }

        if (preg_match("/(\d{4})-(\d{2})/", $dvtour) || preg_match("/(\d{4})-(\d{2})-(\d{2})/", $dvtour)) {
            $query->andWhere('SUBSTRING(dvtour_day,1,'.strlen($dvtour).')=:ym', [':ym'=>$dvtour]);
        }

        if (strlen($search) > 2) {
            $supplierOnly = false;
            if (substr($search, 0, 1) == '@') {
                $search = substr($search, 1);
                $supplierOnly = true;
            }
            // Tim venue
            $theVenues = Venue::find()->select(['id'])->where(['like', 'name', $search])->indexBy('id')->asArray()->all();
            $venueIdList = null;
            if (!empty($theVenues)) {
                $venueIdList = array_keys($theVenues);
            }
            $theCompanies = Company::find()->select(['id'])->where(['like', 'name', $search])->indexBy('id')->asArray()->all();
            $companyIdList = null;
            if (!empty($theCompanies)) {
                $companyIdList = array_keys($theCompanies);
            }
            if ($supplierOnly) {
                $query->andFilterWhere(['or', ['like', 'oppr', $search], ['venue_id'=>$venueIdList], ['via_company_id'=>$companyIdList], ['by_company_id'=>$companyIdList]]);
            } else {
                $query->andFilterWhere(['or', ['like', 'dvtour_name', $search], ['like', 'oppr', $search], ['venue_id'=>$venueIdList], ['via_company_id'=>$companyIdList], ['by_company_id'=>$companyIdList]]);
            }
        }

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym FROM cpt GROUP BY ym ORDER BY ym DESC')
            ->queryAll();

        if (in_array($currency, ['eur', 'usd', 'vnd', 'lak', 'khr'])) {
            $query->andWhere(['unitc'=>strtoupper($currency)]);
        }
        if (in_array($sign, ['plus', 'minus'])) {
            $query->andWhere(['plusminus'=>$sign]);
        }
        if ($payer != '' && $payer != 'miennam' && !$theTour) {
            $query->andWhere(['payer'=>$payer]);
        }
        if ($payer == 'miennam' && !$theTour) {
            $query->andWhere(['payer'=>['Amica Saigon', 'Hướng dẫn MN 1', 'Hướng dẫn MN 2', 'Hướng dẫn MN 3']]);
        }
        if ($vat == 'ok') {
            $query->andWhere(['vat_ok'=>'ok']);
        } elseif ($vat == 'nok') {
            $query->andWhere(['vat_ok'=>'']);
        }
        if ($link != '' && $link == 'on') {
            $query->andWhere('dv_id > 0');
        } elseif ($link == 'off') {
            $query->andWhere('dv_id = 0');
        }

        if ($unit != '') {
            $query->andWhere(['LIKE', 'unit', $unit]);
        }
        $payerList = Yii::$app->db
            ->createCommand('SELECT payer FROM cpt GROUP BY payer ORDER BY payer')
            ->queryAll();

        // Thay đổi điều kiện tìm kiếm nếu chỉ có 1 tour
        $orderBy = $orderby == 'updated_at' ? 'updated_at DESC' : 'dvtour_day DESC';
        if ($theTour) {
            $limit = 1000;
            $orderBy = 'dvtour_day';
        }

        $countQuery = clone $query;

        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>$limit,
        ]);

        $theCptx = $query
            ->with([
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                // 'cp'=>function($query) {
                //     return $query->select(['id', 'name', 'venue_id', 'unit'])
                //         ->with(['venue'=>function($query){
                //             return $query->select(['id', 'name']);
                //             }
                //         ]);
                // },

                'rout',
                'tour'=>function($query) {
                    return $query->select(['id', 'code']);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'viaCompany'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'dv'=>function($q){
                    return $q->where(['!=', 'status', 'deleted']);
                },
                // 'comments'=>function($q){
                //     return $q->where(['!=', 'status', 'deleted']);
                // },
                'comments.updatedBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname']);
                },
                'mtt'=>function($q) {
                    return $q->andWhere('at_mtt.created_by = 1')->orderBy('updated_dt')
                    ->with(['ltt'
                        // =>function($query){
                        // return $query->select(['id', 'name']);
                        // }
                    ]);
                },
            ])
            ->orderBy($orderBy)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $sql = $query->createCommand()->getRawSql();
        // Aprroved by
        $approvedByIdList = [];
        foreach ($theCptx as $cpt) {
            if ($cpt['approved_by'] != '') {
                $cpt['approved_by'] = trim($cpt['approved_by'], '[');
                $cpt['approved_by'] = trim($cpt['approved_by'], ']');

                $ids = explode(':][', $cpt['approved_by']);
                foreach ($ids as $id2) {
                    $approvedByIdList[] = (int)$id2;
                }
            }
        }
        $approvedBy = User::find()->select(['id', 'name'])->where(['id'=>$approvedByIdList])->asArray()->all();

        if (SEG2 == 'x') {
            $viewFile = 'cpt_x';
        } else {
            $viewFile = 'cpt';
        }

        $countMtt = AtMtt::find()
                ->with([
                    'cpt',
                    'venue' => function($q){
                        return $q->select(['id', 'name']);
                    },
                ])->where(['created_by' => $USER_ID, 'status' => 'draft', 'ltt_id' => 0])->count();
        return $this->render($viewFile, [
            'pagination'=>$pagination,
            'theCptx'=>$theCptx,
            'filter'=>$filter,
            'tour'=>$tour,
            'dvtour'=>$dvtour,
            'search'=>$search,
            'tt'=>$tt,
            'currency'=>$currency,
            'sign'=>$sign,
            'payer'=>$payer,
            'vat'=>$vat,
            'orderby'=>$orderby,
            'unit' => $unit,
            'link' => $link,
            'limit'=>$limit,
            'payerList'=>$payerList,
            'theTour'=>$theTour,
            'theTours'=>$theTours,
            'sql'=>$sql,
            'approvedBy'=>$approvedBy,
            'cnt_mtt' => $countMtt,
        ]);
    }

    // Lịch thời hạn Amica phải thanh toán cho đối tác dịch vụ
    public function actionLichThanhToan($day1 = false, $day2 = false, $c3 = 'off')
    {
        if (!$day1) {
            $day1 = date('Y-m-d', strtotime('this week Monday'));
        }
        if (!$day2) {
            $day2 = date('Y-m-d', strtotime('+6 days', strtotime($day1)));
        }
        // Hanh muon xem ca nhung muc da thanh toan, 151127
        if ($c3 == 'on') {
            $andC3 = '';
        } else {
            $andC3 = 'AND (SUBSTRING(s.c3,1,2)!="on" AND paid_full="yes")';
        }
        // Các hoá đơn thanh toán
        $sql = 'SELECT t.code, t.status AS tour_status, s.*,
            IF(s.venue_id=0,"",(SELECT name FROM venues v WHERE v.id=s.venue_id LIMIT 1)) AS venue_name,
            IF(s.via_company_id=0,"",(SELECT name FROM at_companies c WHERE c.id=s.via_company_id LIMIT 1)) AS via_company_name,
            IF(s.by_company_id=0,"",(SELECT name FROM at_companies c WHERE c.id=s.by_company_id LIMIT 1)) AS by_company_name,
            (select name from at_users u where u.id=op limit 1) AS op_name FROM cpt s, at_tours t
            WHERE t.id=s.tour_id AND s.due>=:day1 AND s.due<=:day2 AND s.due != 0 '.$andC3.' ORDER BY due ASC LIMIT 1000';
        $theCptx = Yii::$app->db->createCommand($sql, [':day1'=>$day1, ':day2'=>$day2])->queryAll();

        //$theMttx = Yii::$app->db->createCommand($sql, [':day1'=>$day1, ':day2'=>$day2])->queryAll();

        $xRates = [
            'USD'=>21500,
            'EUR'=>23500,
            'VND'=>1,
        ];

        $sql = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<=NOW() ORDER BY rate_dt DESC LIMIT 1';
        $theXRate = Yii::$app->db->createCommand($sql)->queryScalar();
        if ($theXRate) {
            $xRates['USD'] = $theXRate;
        }

        $sql = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="EUR" AND rate_dt<=NOW() ORDER BY rate_dt DESC LIMIT 1';
        $theXRate = Yii::$app->db->createCommand($sql)->queryScalar();
        if ($theXRate) {
            $xRates['EUR'] = $theXRate;
        }

        $result = [];
        $total = [];
        $html = '';

        foreach ($theCptx as $cpt) {
            if ($cpt['venue_id'] != 0) {
                $payableTo = $cpt['venue_name'];
            } else {
                if ($cpt['via_company_id'] != 0) {
                    $payableTo = $cpt['via_company_name'];
                } else {
                    if ($cpt['by_company_id'] != 0) {
                        $payableTo = $cpt['by_company_name'];
                    } else {
                        $payableTo = $cpt['oppr'];
                    }
                }
            }

            $item = [
                'payableto'=>$payableTo,
                'id'=>$cpt['dvtour_id'],
                'name'=>$cpt['dvtour_name'],
                'quantity'=>$cpt['qty'],
                'unit'=>$cpt['unit'],
                'price'=>$cpt['price'],
                'currency'=>$cpt['unitc'],
                'tour_id'=>$cpt['tour_id'],
                'tour_code'=>$cpt['code'],
                'tour_status'=>$cpt['tour_status'],
                'total'=>0,
            ];

            $sub = $cpt['qty']*$cpt['price']*$xRates[$cpt['unitc']]*(1+$cpt['vat']/100);
            if ($cpt['plusminus'] == 'minus') {
                $sub = -$sub;
            }
            $item['total'] = $sub;

            if (isset($result[$payableTo])) {
                $result[$payableTo][] = $item;
            } else {
                $result[$payableTo] = [$item];
            }

            if (isset($total[$payableTo])) {
                $total[$payableTo] += $sub;
            } else {
                $total[$payableTo] = $sub;
            }
        }

        ksort($result);

        return $this->render('cpt_lich-thanh-toan', [
            'theCptx'=>$theCptx,
            'xRates'=>$xRates,
            'day1'=>$day1,
            'day2'=>$day2,
            'result'=>$result,
            'c3'=>$c3,
            'total'=>$total,
        ]);
    }

    // Thanh toan cac muc da chon
    public function actionThanhToan($cpt = null)
    {
        $theMttx = Mtt::find()
            ->where(['status'=>'draft', 'created_by'=>USER_ID])
            ->with([
                'cpt',
                'cpt.tour'=>function($q) {
                    return $q->select(['id', 'code']);
                },
                'cpt.venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cpt.company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cpt.viaCompany'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                ])
            ->orderBy('created_dt')
            ->asArray()
            ->all();
        if (empty($theMttx)) {
            Yii::$app->session->setFlash('danger', 'Chưa chọn mục nào để thanh toán');
            return $this->redirect('/cpt');
        }

        $theMtt = new Mtt;
        $theMtt->payment_dt = date('Y-m-d');
        $theMtt->paid_in_full = 'yes';
        $theMtt->status = 'on';
        if ($theMtt->load(Yii::$app->request->post()) && $theMtt->validate()) {
            foreach ($theMttx as $mtt) {
                if ($mtt['payment_dt'] == '0000-00-00 00:00:00') {
                    $mtt['payment_dt'] = $theMtt['payment_dt'];
                }
                if ($mtt['tkgn'] == '') {
                    $mtt['tkgn'] = $theMtt['tkgn'];
                }
                if ($mtt['mp'] == '') {
                    $mtt['mp'] = $theMtt['mp'];
                }
                if ($theMtt['amount'] != '100') {
                    $mtt['amount'] = $mtt['amount'] * ($theMtt['amount'] / 100);
                }

                // Loai tien va ti gia bat buoc giong nhau
                if ($mtt['currency'] != $theMtt['currency']) {
                    $mtt['currency'] = $theMtt['currency'];
                    $mtt['xrate'] = $theMtt['xrate'];
                }
                if ($mtt['paid_in_full'] == '') {
                    $mtt['paid_in_full'] = $theMtt['paid_in_full'];
                }
                if ($mtt['note'] == '') {
                    $mtt['note'] = $theMtt['note'];
                }
                $sql = 'UPDATE at_mtt SET updated_by=:me, updated_dt=:now, status="on", payment_dt=:dt, amount=:amt, currency=:currency, xrate=:xrate, tkgn=:tk, mp=:mp, paid_in_full=:pif WHERE status="draft" AND created_by=:me AND id=:id';
                Yii::$app->db->createCommand($sql, [
                    ':now'=>NOW,
                    ':id'=>$mtt['id'],
                    ':tk'=>$mtt['tkgn'],
                    ':mp'=>$mtt['mp'],
                    ':dt'=>$mtt['payment_dt'],
                    ':currency'=>$mtt['currency'],
                    ':pif'=>$mtt['paid_in_full'],
                    ':amt'=>$mtt['amount'],
                    ':xrate'=>$mtt['xrate'],
                    ':me'=>USER_ID,
                    ])->execute();
                if ($mtt['paid_in_full'] == 'yes') {
                    $sql = 'UPDATE cpt SET c3=:c3 WHERE dvtour_id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [
                        ':c3'=>'on,'.USER_ID.','.NOW,
                        ':id'=>$mtt['cpt_id'],
                    ])->execute();
                } else {
                    $sql = 'UPDATE cpt SET c1=:c1 WHERE dvtour_id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [
                        ':c1'=>'on,'.USER_ID.','.NOW,
                        ':id'=>$mtt['cpt_id'],
                    ])->execute();
                }
            }

            return $this->redirect('@web/cpt');
        }
        return $this->render('cpt_thanh-toan', [
            'theMtt'=>$theMtt,
            'theMttx'=>$theMttx,
        ]);
    }

    // Cpt da thanh toan
    public function actionDaThanhToan($date = '', $tour = '', $search = '', $updatedby = 0, $unitc = '', $currency = '', $tkgn ='', $mp = '', $check = 'all')
    {
        $query = Mtt::find()
            ->andWhere(['at_mtt.status'=>'on']);

        $joinConditions = [];

        if ($date != '') {
            $query->andWhere('LOCATE(:date, payment_dt)!=0', [':date'=>$date]);
        }

        if ($tour != '') {
            $tourIdList = [];
            $theTours = Tour::find()
                ->select('id')
                ->where(['like', 'code', $tour])
                ->orWhere(['id'=>$tour])
                ->asArray()
                ->all();
            if (!empty($theTours)) {
                foreach ($theTours as $tourx) {
                    $tourIdList[] = $tourx['id'];
                }
            }

            if (empty($tourIdList)) {
                $joinConditions[] = ['like', 'dvtour_name', $tour];
            } else {
                $joinConditions[] = ['tour_id'=>$tourIdList];
            }
        }

        if ($search != '') {
            $joinConditions[] = ['like', 'dvtour_name', $search];
        }

        if ($unitc != '') {
            $joinConditions[] = ['unitc'=>$unitc];
        }

        if (!empty($joinConditions)) {
            $query
                ->innerJoinWith('cpt');
            foreach ($joinConditions as $joinCondition) {
                $query->andOnCondition($joinCondition);
            } 
                
        }

        if ((int)$updatedby != 0) {
            $query->andWhere(['at_mtt.updated_by'=>(int)$updatedby]);
        }

        if (strlen($currency) == 3) {
            $query->andWhere(['currency'=>$currency]);
        }

        if ($tkgn != '') {
            $query->andWhere(['like', 'tkgn', $tkgn]);
        }

        if ($mp != '') {
            $query->andWhere(['like', 'mp', $mp]);
        }

        if ($check == 'yes') {
            $query->andWhere('`check`!=""');
        } elseif ($check == 'no') {
            $query->andWhere('`check`=""');
        }

        $query
            ->with([
                'cpt',
                'cpt.tour'=>function($q) {
                    return $q->select(['id', 'code']);
                },
                'cpt.venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cpt.company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cpt.viaCompany'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname']);
                },
            ]);
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
        ]);
        $theMttx = $query
            ->orderBy('payment_dt DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $sql = 'SELECT u.id, u.nickname AS name FROM at_users u, at_mtt m WHERE m.status="on" AND m.created_by=u.id GROUP BY u.id ORDER BY u.lname';
        $updatedbyList = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'SELECT m.currency FROM at_mtt m WHERE m.status="on" GROUP BY m.currency ORDER BY m.currency';
        $currencyList = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('cpt_da-thanh-toan', [
            'pagination'=>$pagination,
            'theMttx'=>$theMttx,
            'date'=>$date,
            'tour'=>$tour,
            'unitc'=>$unitc,
            'currency'=>$currency,
            'updatedby'=>$updatedby,
            'tkgn'=>$tkgn,
            'mp'=>$mp,
            'check'=>$check,
            'updatedbyList'=>$updatedbyList,
            'currencyList'=>$currencyList,
        ]);
    }

    public function actionGet_data_link($id)
    {
        if (Yii::$app->request->isAjax) {
            $dv = Dv::find()->where('venue_id = '.$id)->asArray()->all();
            return json_encode($dv);
        }
    }
    public function actionCheck_link($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $cpt = Cpt::findOne($id);
            if ($cpt != null) {
                $cpt->status_check = ($cpt->status_check == "not ok")? 'ok':'not ok';
                $cpt->checked_by = 1;
                $cpt->checked_at = date('Y-m-d H:i:s',strtotime('now'));
                if ($cpt->save()) {
                    return 1;
                } else {
                    return 'error';
                }
            } else return 'error';
        }
    }
    public function actionList_ncc($query)
    {
        if (Yii::$app->request->isAjax) {
            $query = str_replace(' ', '%', $query);
            $nccs = Venue::find()->where("name LIKE '%$query%'")->asArray()->all();

            // $sql = $dv->createCommand()->getRawSql();
            //var_dump($dv);die();
            $suggestions =[];
            foreach ($nccs as $ncc) {
                $obj = ['value' => trim($ncc['name']), 'data' => trim($ncc['id'])];
                $suggestions[] = $obj;
            }
            $result = [
                'suggestions' => $suggestions
            ];
                echo json_encode($result);
        }
    }
    public function actionEx()
    {
        $routs = Rout::find()->where('link_cpt_id = 0')->all();
        // var_dump($routs);die();
        $cnt = 0;
        foreach ($routs as $rout) {
            $cpts = Cpt::find()->where('dv_id = 0 AND venue_id = ' .$rout->venue_id)->all();
            foreach ($cpts as $cpt) {
                $chars = explode(';', $rout->content);
                foreach ($chars as $c) {
                    if ($cpt->dv_id == 0 && strtolower(trim($cpt->unit)) == strtolower(trim($c))) {
                        $cpt->dv_id = $rout->dv_id;
                        $cpt->linked_at = date('Y-m-d H:i:s', strtotime('now'));
                        $cpt->linked_by = 1;
                        if ($cpt->save()) {
                            var_dump($cpt->unit.' - '.$cpt->price);
                            $cnt++;
                            break;
                        }
                        else {
                            var_dump('not ok - '.$cpt->dvtour_id.' - '.$cpt->price);
                        }
                    }
                }
            }
            $rout->link_cpt_id = 1;
            $rout->save();
        }
        Yii::$app->getSession()->setFlash("success", 'links '.$cnt.' ok');
        return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
    }
    public function actionList_dv($vid = 0, $query){
        if (Yii::$app->request->isAjax) {
            $query = str_replace(' ', '%', $query);
            if ($vid == 0) {
                $dv = Dv::find()->where('status != "deleted" AND name LIKE "%'.$query.'%"')->asArray()->all();
            } else {
                $dv = Dv::find()->where('venue_id = '.$vid.' AND status != "deleted" AND name LIKE "%'.$query.'%"')->asArray()->all();
            }

            // $sql = $dv->createCommand()->getRawSql();
            //var_dump($dv);die();
            $venu = Venue::find()->where(['id' => $vid])->one();
            $suggestions =[];
            foreach ($dv as $v) {
                $cp = Cp::find()->where(['dv_id' => $v['id']])->one();
                $prefix = '';
                if ($v['stype'] == 'a') {
                    $prefix = 'Phòng';
                }
                // if ($v['stype'] == 'f') {
                //     $prefix = 'Chuyến bay';
                // }
                // if ($v['stype'] == 'v') {
                //     $prefix = 'Thăm quan';
                // }
                // if ($v['stype'] == 'p') {
                //     $prefix = 'Gói';
                // }
                // if ($v['stype'] == 't') {
                //     $prefix = 'Tàu';
                // }
                $v['name'] = str_replace(['[',']'], ['', ''], $v['name']);
                if (strpos(strtolower($v['name']), strtolower($prefix)) === false) {
                    $v['name'] = $prefix.' '.$v['name'];
                }
                if ($venu != null) {
                    $v['name'] = $v['name'] .' '.$cp['price'];
                }
                // $cps = Cp::find()->where(['dv_id' => $v['id']])->asArray()->all();
                // var_dump($cps);die();
                //if (strpos(strtolower($v['name']), strtolower($query)) !== false) {
                $obj = ['value' => trim($v['name']), 'data' => trim($v['id'])];
                $suggestions[] = $obj;
                //}
            }
            $result = [
                'suggestions' => $suggestions
            ];
                echo json_encode($result);
        }
    }
    public function actionX($vat = '', $user = 'all', $tour = '', $dvtour = '', $search = '', $filter = '', $payer = '', $sign = '', $currency = '', $tt = '', $orderby = 'dvtour_day', $limit = 25)
    {
        return $this->actionIndex($vat, $user, $tour, $dvtour, $search, $filter, $payer, $sign, $currency, $tt, $orderby, $limit);
    }

    // Search lam thanh toan
    public function actionSearch($search = '', $tour = '', $currency = '', $day = '', $limit = 25)
    {
        if (MY_ID > 4 && !in_array(MY_ID, [1,28431,  11,   17,   16,  20787,29739, 30085, 25457])) {
            throw new HttpException(403, 'Access denied.');
        }

        if (!in_array($limit, [25, 50, 100, 500])) {
            $limit = 25;
        }

        $query = Cpt::find();

        if (trim($search) != '') {
            // Tim venue
            $theVenues = Venue::find()->select(['id'])->where(['like', 'name', $search])->indexBy('id')->asArray()->all();
            $venueIdList = [-1];
            if (!empty($theVenues)) {
                $venueIdList = array_keys($theVenues);
            }
            $theCompanies = Company::find()->select(['id'])->where(['like', 'name', $search])->indexBy('id')->asArray()->all();
            $companyIdList = [-1];
            if (!empty($theCompanies)) {
                $companyIdList = array_keys($theCompanies);
            }
            $query->filterWhere(['or', ['like', 'oppr', $search], ['venue_id'=>$venueIdList], ['via_company_id'=>$companyIdList], ['by_company_id'=>$companyIdList]]);
        }

        $theTours = [];
        $tourIdList = [];
        if (trim($tour) != '') {
            if (preg_match("/(\d{4})-(\d{2})/", $tour)) {
                $theTours = Tour::findBySql('SELECT t.id, day_from FROM at_tours t, at_ct p WHERE p.id=t.ct_id AND SUBSTRING(day_from,1,7)=:ym', [':ym'=>$tour])
                    ->indexBy('id')
                    ->asArray()
                    ->all();
            } else {
                $theTours = Tour::find()
                    ->select(['id'])
                    ->where(['or', ['like', 'code', $tour], ['id'=>$tour]])
                    ->indexBy('id')
                    ->asArray()
                    ->all();
            }
            if (!empty($theTours)) {
                $tourIdList = array_keys($theTours);
                $query->andWhere(['tour_id'=>$tourIdList]);
            }
        }

        // Ngay
        if (trim($day) != '') {
            
        }

        $theCptx = $query
            ->with([
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'tour'=>function($query) {
                    return $query->select(['id', 'code']);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'comments',
                'comments.updatedBy',
            ])
            //->orderBy($orderBy)
            ->orderBy('dvtour_day DESC')
            //->offset($pagination->offset)
            //->limit($pagination->limit)
            ->limit(100)
            ->asArray()
            ->all();

        $sql = $query->createCommand()->getRawSql();

        // Aprroved by
        $approvedByIdList = [];
        foreach ($theCptx as $cpt) {
            if ($cpt['approved_by'] != '') {
                $cpt['approved_by'] = trim($cpt['approved_by'], '[');
                $cpt['approved_by'] = trim($cpt['approved_by'], ']');

                $ids = explode(':][', $cpt['approved_by']);
                foreach ($ids as $id2) {
                    $approvedByIdList[] = (int)$id2;
                }
            }
        }
        $approvedBy = User::find()->select(['id', 'name'])->where(['id'=>$approvedByIdList])->asArray()->all();

        return $this->render('cpt_search', [
            //'pagination'=>$pagination,
            'theCptx'=>$theCptx,
            //'filter'=>$filter,
            //'view'=>$view,
            'tour'=>$tour,
            'search'=>$search,
            //'currency'=>$currency,
            //'sign'=>$sign,
            //'payer'=>$payer,
            //'orderby'=>$orderby,
            //'limit'=>$limit,
            'theTours'=>$theTours,
            'sql'=>$sql,
            'approvedBy'=>$approvedBy,
        ]);
    }

    public function actionExport() {
        if (MY_ID > 4 && !in_array(MY_ID, [11, 17, 16, 4065, 20787, 4432, 4125])) {
            throw new HttpException(403, 'Access denied.');
        }

        $getTour = Yii::$app->request->get('tour', '');

        // Search for tour with code
        if ($getTour != '') {
            $theTour = Tour::find()
                ->where(['or', ['like', 'code', $getTour], ['id'=>$getTour]])
                ->with([
                    'product',
                    'product.days',
                ])
                ->asArray()
                ->one();
        }

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $query = Cpt::find()->andWhere(['tour_id'=>$theTour['id']]);

        $theCptx = $query
            ->with([
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'cp'=>function($query) {
                    return $query->select(['id', 'name', 'venue_id', 'unit'])
                        ->with(['venue'=>function($query){
                            return $query->select(['id', 'name']);
                            }
                        ]);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'comments',
                'comments.updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
            ])
            ->orderBy('venue_id', 'by_company_id', 'via_company_id')
            ->asArray()
            ->all();

        return $this->render('cpt_export', [
            'theCptx'=>$theCptx,
            'getTour'=>$getTour,
            'theTour'=>$theTour,
        ]);
    }

    public function actionC()
    {
        $theCpt = new Cpt;

        $theCpt->scenario = 'dvt_c';

        return $this->render('dvt_c', [
            'theCpt'=>$theCpt,
        ]);
    }

    public function actionR($id = 0, $action = '')
    {
        $theCpt = Cpt::find()
            ->where(['dvtour_id'=>$id])
            ->with([
                'mtt'=>function($q) {
                    // Do not get draft
                    return $q->andWhere(['status'=>['on', 'deleted']]);
                },
                'mtt.updatedBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tour'=>function($q) {
                    return $q->select(['id', 'code']);
                },
                'venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'company'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'viaCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'comments',
                'edits',
                'comments.updatedBy'=>function($q) {
                    return $q->select(['id', 'name', 'image']);
                },
                ])
            ->asArray()
            ->one();

        if (!$theCpt) {
            throw new HttpException(404, 'Cpt: Not found.');
        }

        if ($action == 'mark-unpaid') {

            if (strpos($theCpt['c3'], 'on,'.USER_ID) !== 0) {
                throw new HttpException(403, 'Access denied.');
            }

            $sql = 'DELETE FROM at_mtt WHERE cpt_id=:cpt_id';
            Yii::$app->db->createCommand($sql, [':cpt_id'=>$theCpt['dvtour_id']])->execute();
            $sql = 'UPDATE cpt SET paid_full="no", c3=:c3 WHERE dvtour_id=:cpt_id LIMIT 1';
            Yii::$app->db->createCommand($sql, [':c3'=>str_replace('on,', 'off,', $theCpt['c3']), ':cpt_id'=>$theCpt['dvtour_id']])->execute();

            return $this->redirect(DIR.URI);
        }

        // $action = $_GET['action'] ?? '';
        // $mttid = $_GET['mtt-id'] ?? '';
        // $cmtid = $_GET['cmt-id'] ?? '';

        $theMtt = false;

        if ($action == 'delete-mtt' && (int)$mttid != 0) {
            $theMtt = Mtt::find()
                ->where(['id'=>$mttid, 'cpt_id'=>$theCpt['dvtour_id']])
                ->one();
            if (!$theMtt) {
                Yii::$app->session->setFlash('danger', 'Not found');
                return $this->redirect(DIR.URI);
            }
            if ($theMtt['check'] != '') {
                Yii::$app->session->setFlash('danger', 'Already checked');
                return $this->redirect(DIR.URI);
            }
            if ($theMtt['status'] != 'on' || !in_array(USER_ID, [1, $theMtt['created_by'], $theMtt['updated_by']])) {
                throw new HttpException(403, 'Access denied');  
            }
            $theMtt->status = 'deleted';
            $theMtt->updated_dt = NOW;
            $theMtt->updated_by = USER_ID;
            $theMtt->save(false);
            return $this->redirect('/cpt/r/'.$theCpt['dvtour_id']);
        }

        if ($action == 'edit-mtt' && (int)$mttid != 0) {
            $theMtt = Mtt::find()
                ->where(['id'=>$mttid, 'cpt_id'=>$theCpt['dvtour_id']])
                ->one();
            if (!$theMtt) {
                Yii::$app->session->setFlash('danger', 'Not found');
                return $this->redirect(DIR.URI);
            }
            if ($theMtt['check'] != '') {
                Yii::$app->session->setFlash('danger', 'Already checked');
                return $this->redirect(DIR.URI);
            }
            if ($theMtt['status'] != 'on' || !in_array(USER_ID, [1, $theMtt['created_by'], $theMtt['updated_by']])) {
                Yii::$app->session->setFlash('danger', 'Access denied');    
                return $this->redirect(DIR.URI);
            }
            if ($theMtt->load(Yii::$app->request->post() && $theMtt->validate())) {
                $theMtt->updated_dt = NOW;
                $theMtt->updated_by = USER_ID;
                $theMtt->save(false);
                return $this->redirect('/cpt/r/'.$theCpt['dvtour_id']);
            }
        }

        if ($action == 'new-mtt') {
            $theMtt = new Mtt;
            $theMtt->status = 'on';
            $theMtt->cpt_id = $theCpt['dvtour_id'];
            $theMtt->payment_dt = NOW;
            $theMtt->xrate = 1;
            $theMtt->amount = $theCpt['qty'] * $theCpt['price'];
            $theMtt->currency = $theCpt['unitc'];
            if ($theMtt->load(Yii::$app->request->post()) && $theMtt->validate()) {
                $theMtt->created_dt = NOW;
                $theMtt->created_by = USER_ID;
                $theMtt->updated_dt = NOW;
                $theMtt->updated_by = USER_ID;
                $theMtt->save(false);
                return $this->redirect('/cpt/r/'.$theCpt['dvtour_id']);
            }
        }

        if ($action == 'delete-cmt' && (int)$cmtid != 0) {
            $theComment = Comment::find()
                ->where(['id'=>$cmtid, 'rtype'=>'cpt', 'rid'=>$theCpt['dvtour_id']])
                ->one();
            if (!$theComment) {
                throw new HttpException(404, 'Not found');
            }
            if ($theComment['status'] != 'on' || !in_array(USER_ID, [1, $theComment['created_by'], $theComment['updated_by']])) {
                throw new HttpException(403, 'Access denied');  
            }
            $theComment->status = 'deleted';
            $theComment->updated_at = NOW;
            $theComment->updated_by = USER_ID;
            $theComment->save(false);
            return $this->redirect('/cpt/r/'.$theCpt['dvtour_id']);
        }

        if ($action == 'edit-cmt' && (int)$cmtid != 0) {
            $theComment = Comment::find()
                ->where(['id'=>$cmtid, 'rtype'=>'cpt', 'rid'=>$theCpt['dvtour_id']])
                ->one();
            if (!$theComment) {
                throw new HttpException(404, 'Not found');
            }
            if ($theComment['status'] != 'on' || !in_array(USER_ID, [1, $theComment['created_by'], $theComment['updated_by']])) {
                throw new HttpException(403, 'Access denied');  
            }
            $theComment->scenario = 'any/c';
        } else {
            $theComment = new Comment;
            $theComment->scenario = 'any/c';
        }

        if ($theComment->load(Yii::$app->request->post()) && $theComment->validate()) {
            $theComment->updated_at = NOW;
            $theComment->updated_by = USER_ID;
            if ($theComment->isNewRecord) {
                $theComment->created_at = NOW;
                $theComment->created_by = USER_ID;
                $theComment->rtype = 'cpt';
                $theComment->rid = $theCpt['dvtour_id'];
                $theComment->pid = $theCpt['tour_id'];
            }
            $theComment->save(false);
            return $this->redirect('@web/cpt/r/'.$theCpt['dvtour_id']);
        }

/*
        $newName = trim(Yii::$app->request->post('name', ''));
        if ($newName != '' && Yii::$app->user->id == 1) {
            $theCp = Cp::find()
                ->where(['id'=>$newName])
                ->Orwhere(['like', 'name', $newName])
                ->orWhere(['like', 'abbr', $newName])
                ->asArray()
                ->limit(2)
                ->all();
            if ($theCp && count($theCp) == 1) {
                //die('OK='.$theCp['id']. ' '.$theCp['name']);
                $theCpt->cp_id = $theCp[0]['id'];
                $theCpt->save();
                return $this->redirect('@web/cpt/r/'.$id);
            }
        }
*/
        return $this->render('cpt_r', [
            'theCpt'=>$theCpt,
            'theMtt'=>$theMtt,
            'theComment'=>$theComment,
        ]);
    }

    public function actionU($id = 0)
    {
        $theCpt = Cpt::findOne($id);

        if (!$theCpt) {
            throw new HttpException(404, 'Not found.');
        }

        $theCpt->scenario = 'dvt_u';

        if ($theCpt->load(Yii::$app->request->post()) && $theCpt->validate()) {
            $theCpt->save();
        }

        return $this->render('dvt_u', [
            'theCpt'=>$theCpt,
        ]);
    }

    public function actionD($id = 0)
    {
        $theCpt = Cpt::findOne($id);

        if (!$theCpt) {
            throw new HttpException(404, 'Not found.');
        }

        return $this->render('dvt_d', [
            'theCpt'=>$theCpt,
        ]);
    }

    public function actionTour($id = 0)
    {
        if (Yii::$app->user->id == 1 && isset($_GET['dvt']) && isset($_GET['dv'])) {
            Yii::$app->db->createCommand('update cpt SET cp_id=:dv WHERE cp_id=0 AND dvtour_id=:dvt LIMIT 1',
                [':dv'=>(int)$_GET['dv'], ':dvt'=>(int)$_GET['dvt']]
                )
                ->execute();
            return $this->redirect(DIR.URI);
        }

        $theTour = Tour::find()
            ->where(['id'=>$id])
            ->with([
                'ct',
                'ct.days',
                ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');         
        }

        $query = Cpt::find()
            ->where(['tour_id'=>$id]);

        $theCptx = $query
            ->with([
                'updatedBy'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'dv'=>function($query) {
                    return $query->select(['id', 'name', 'venue_id', 'unit'])
                        ->with(['venue'=>function($query){
                            return $query->select(['id', 'name']);
                            }
                        ]);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'venue.dv',
                'mm',
                'mm.updatedBy',
            ])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        return $this->render('dvt_tour', [
            'theTour'=>$theTour,
            'theCptx'=>$theCptx,
        ]);
    }

    // TESTING: auto convert posted text to DVT table
    public function actionTest()
    {
        $theVenue = null;
        $theCp = null;

        $postedText = Yii::$app->request->post('text');
        $text = implode("\n", array_map('trim', explode("\n", $postedText)));
        if ($text != '') {
            $lines = explode("\n", $text);
            foreach ($lines as $i=>$line) {
                $line = trim($line);
                $segs = explode(' ', $line);
                $theVenue = Yii::$app->db->createCommand('SELECT v.* FROM venues v, at_search s WHERE s.rtype="venue" AND s.rid=v.id AND s.search LIKE :search LIMIT 1', [':search'=>'%'.$segs[0].'%'])
                    ->queryOne();
                if ($theVenue) {
                    $theCp = Cp::find()
                        ->where(['venue_id'=>$theVenue['id']])
                        ->andWhere(['like', 'search', $segs[1]])
                        ->one();
                }
                break;
            }

        }

        return $this->render('dvt_test', [
            'text'=>$text,
            'theVenue'=>$theVenue,
            'theCp'=>$theCp,
        ]);
    }

    // Đánh cmd để nhập và xử lý chi phí tour
    public function actionCmd()
    {
        $cmdList = [
            ''=>'(Không có)',
            'a'=>'Thêm chi phí',
            'b'=>'Chuyển chi phí',
            'c'=>'Copy chi phí',
            'd'=>'Xoá chi phí',
            'e'=>'Sửa chi phí',
            'f'=>'Mặc định',
        ];
        $getCmd = Yii::$app->request->post('cmd');
        $getCmd = trim($getCmd);

        $theCmd = $getCmd;
        $theParams = [];
        if ($getCmd != '') {
            $params = explode(' ', $getCmd);
            $theCmd = $params[0];
            foreach ($params as $i=>$param) {
                if ($i != 0) {
                    $segs = explode('-', $param);
                    if (isset($segs[1])) {
                        $theParams[] = [
                            'name'=>strtolower($segs[0]),
                            'value'=>$segs[1],
                        ];
                    } else {
                        $theParams[] = [
                            'name'=>strtolower($param),
                            'value'=>'',
                        ];
                    }
                }
            }
        }

        if (!in_array($theCmd, array_keys($cmdList))) {
            $theCmd = 'a';
        }

        return $this->render('cpt_cmd', [
            'theCmd'=>$theCmd,
            'theParams'=>$theParams,
        ]);
    }
}
