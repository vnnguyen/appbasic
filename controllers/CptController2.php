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
use common\models\Ltt;
use common\models\Mm;
use common\models\Mtt;
use app\models\Venue;
use app\models\Tour;
use app\models\User;
use app\models\Dv;
use app\models\Cpt1;
use app\models\AtNgaymau;
use app\models\AtNgaymau1;
use app\models\Way;
use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Settings;
use \PHPExcel_Style_Fill;
use \PHPExcel_Writer_IWriter;
use \PHPExcel_Worksheet;

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


                // return $ret;
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
                // return $ret;
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

    public function actionIndex($vat = '', $user = 'all', $tour = '', $dvtour = '', $search = '', $filter = '', $payer = '', $sign = '', $currency = '', $tt = '', $stype = '', $orderby = 'dvtour_day', $limit = 25)
    {
        // if (MY_ID > 4 && !in_array(MY_ID, [1,2,3,4,28431,  11,   17,   16,  20787,29739, 30085, 25457])) {
        //     //throw new HttpException(403, 'Access denied.');
        // }

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
                if (strtolower(substr($search, 0, 4)) == 'ncc:') {
                    $ncc_search = substr($search, 4);
                    $query->innerJoinWith('venue1')
                          // ->join('INNER JOIN', 'venues', 'dv.venue_id = venues.id')
                          ->andWhere(['LIKE', 'venues.abbr', trim($ncc_search)]);
                }
                else if (substr($search, 0, 4) == 'stk:') {
                        $ncc_search = substr($search, 4);
                        $query->andWhere(['dv.so_tk' => trim($ncc_search)]);
                    }
                    else {
                        $query->andFilterWhere(['or', ['like', 'dvtour_name', $search], ['like', 'oppr', $search], ['venue_id'=>$venueIdList], ['via_company_id'=>$companyIdList], ['by_company_id'=>$companyIdList]]);
                    }
            }
        }

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym FROM cpt GROUP BY ym ORDER BY ym DESC')
            ->queryAll();
        // if ($ncc != '') {
        //     // $venue_ids = Venue::find()->select(['id'])->where(['LIKE', 'abbr', $ncc])->indexBy('id')->column();
        //     $query->andWhere('venue_id IN (SELECT id FROM venues WHERE abbr LIKE "%'. $ncc .'%")');
        // }
        if ($stype != '') {
            $query->innerJoinWith('dv')->andWhere(['dv.stype' => $stype]);
        }
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
                'tour'=>function($query) {
                    return $query->select(['id', 'code']);
                },
                'venue'=>function($query) {
                    return $query->select(['id', 'name', 'abbr']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                'viaCompany'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                // 'comments'=>function($q){
                //     return $q->where(['!=', 'status', 'deleted']);
                // },
                'dv'=>function($q){
                    return $q->where(['!=', 'status', 'deleted']);
                },
                'comments.updatedBy'=>function($query) {
                    return $query->select(['id', 'name'=>'nickname']);
                },
                // 'mtt'=>function($q) {
                //     return $q->orderBy('updated_dt');
                // },
            ])
            ->orderBy($orderBy)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $sql = $query->createCommand()->getRawSql();
        // var_dump($theCptx);die();
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
            'stype' => $stype,
            'orderby'=>$orderby,
            'limit'=>$limit,
            'payerList'=>$payerList,
            'theTour'=>$theTour,
            'theTours'=>$theTours,
            'sql'=>$sql,
            'approvedBy'=>$approvedBy,
        ]);
    }
    public function actionEx1(){
///////////////////////////////////////////////////////thêm chặng xe/////////////////////////////////////
        $arr_lx = [
                ["Ăn tối", "at", "20"],
                ["Ăn tối + rối nước", "atrn", "40"],
                ["Đón ga", "dg", "30"],
                ["Tiễn ga", "tg", "30"],
                ["Đón sân bay + city 1 điểm (xe công ty)", "dsbvis", "170"],
                ["Đón sân bay + city 1 điểm (xe lẻ)", "dsbvis", "150"],
                ["Lưu đêm tam cốc (xe lẻ)", "ld", "100"],
                ["Mai Hịch -Thổ Hà -Tam Cốc ", "mhtc", "300"],
                ["Tam Cốc -Bến Bính ", "tcbb", "200"],
                ["Đón ga (trong ngày ko sử dụng xe chỉ có tiễn hoặc đón)", "dg", "50"],
                ["Tiễn ga (trong ngày ko sử dụng xe chỉ có tiễn hoặc đón)", "tg", "50"],
                ["Hà Nội đón sân bay - đưa qua văn phòng", "dsbam", "130"],
                ["Hà Nội đón sân bay - massa", "dsbmassa", "150"],
                ["Đón sân bay - Hạ Long - Tuần Châu", "dsbhl", "310"],
                ["Hạ Long - Tuần Châu - tiễn sân bay", "hlsb", "280"],
                ["Hà Nội đón sân bay", "dsb", "100"],
                ["Hà Nội tiễn sân bay", "tsb", "100"],
                ["Hà Nội city - tiễn ga Lào Cai ", "hntg", "180"],
                ["Hà Nội city - tiễn ga Huế", "hntg", "180"],
                ["Hà Nội city - tiễn sân bay ", "hntsb", "250"],
                ["Hà Nội đón sân bay + đi thêm 1 điểm thăm quan ", "dsb", "170"],
                ["Hà Nội đón sân bay + 1/2 city", "dsbvis", "200"],
                ["Hà Nội 1/2 city tiễn sân bay ", "vistsb", "200"],
                ["Đưa đi học nấu ăn (trong tour)", "na", "50"],
                ["Hà Nội City 1 ngày", "hnvis", "150"],
                ["Hà Nội City 1 ngày ngoài tour (khi tour có 1 ngày duy nhất)", "hanvis", "200"],
                ["Hà Nội City 1/2 + ăn trưa", "hanvis", "120"],
                ["Hà Nội City 1/2 + ăn tối", "hanvis ", "120"],
                ["Hà Nội City 1/2", "hanvis ", "100"],
                ["Massage", "massa", "50"],
                ["Rối nước (chỉ từ 17h trở ra mới tính,)", "rn", "20"],
                ["Hà Nội - Hà Thái - Canh Hoạch - Phú Vinh - Hà Nội (full day)", "hnhtpv", "250"],
                ["Hà Nội - Hạ Thái - Phú Vinh - Hà Nội", "hnhtpv", "200"],
                ["Hà Nội - Hạ Thái - Tam Cốc", "hnhttc", "260"],
                ["Hà Nội - Hạ Thái - Nhị Khê (full day)", "hnnkh", "220"],
                ["Hà Nội - Hạ Thái + city nửa ngày ", "hnhtvis", "220"],
                ["Hà Nội - Nhị Khê - Hà Thái - Hà Nội (half day)", "hnnkhv", "150"],
                ["Hà Nội - Bát Tràng - Bút Tháp - Đông Hồ - Hà Nội", "hnvis", "250"],
                ["Hà Nội - Bát tràng  1/2 ngày trong tour", "hnbt", "100"],
                ["Hà Nội - Vạn Phúc 1/2 ngày trong tour", "hnvp", "100"],
                ["Hà Nội - Bát tràng  cả ngày", "hnbt", "150"],
                ["Hà Nội - Vạn Phúc cả ngày", "hnvp", "150"],
                ["Hà Nội - Bút tháp - Đông Hồ - Đồng Kỵ - Hanoi", "hnvis", "250"],
                ["Hà Nội - Chùa thầy - Chùa Tây Phương + Chùa Trăm Gian - Hà Nội", "hnct", "250"],
                ["Hà Nội - Chùa thầy - Chùa Tây Phương 1 ngày", "hntp", "200"],
                ["Hà Nội - Đường Lâm - Chùa Thầy - Hà Nội", "hndl", "250"],
                ["Hà Nội - Đường Lâm - Chùa Thầy - Tây Phương - Hà Nội", "hndl", "280"],
                ["Hà Nội - Đường Lâm - Hà Nội", "hndl", "200"],
                ["Hà Nội - Làng Nôm - Chùa Nôm ( xã Đại Đồng, Văn Lâm, Hưng Yên)  - City 1/2", "hncm", "250"],
                ["Hà Nội - Chùa Hương - Nộn Khê", "hnchnk", "300"],
                ["Hà Nội - Chùa Hương -  Hồng Phong", "hnchhp", "300"],
                ["Hà Nội - Chùa Hương - Tam Cốc", "hnchtc", "250"],
                ["Hà Nội - Chùa Hương -  Thung Nham ", "hnchtn", "250"],
                ["Hà Nội - Chùa Hương - Vân Long (Ngủ Emeralda Resort )", "hnchvl", "250"],
                ["Hà Nội - Chùa Hương 1 ngày", "hnch", "200"],
                ["Hà Nội - Đường Lâm - Mai Châu", "hndl", "250"],
                ["Hà Nội - Ferme du colvert ", "hnf", "150"],
                ["Hà Nội - Hòa Bình (Bản mường, bản dao) - Hà Nội", "hnhb", "300"],
                ["Hà Nội - Lương Sơn", "hnls", "150"],
                ["Hà Nội - Mai Châu", "hnmc", "200"],
                ["Mai Châu - Hà Nội ", "mchn", "200"],
                ["Hà Nội - Mai Châu - Hà Nội 1 ngày", "hnmc", "380"],
                ["Hà Nội -  Mai Hịch", "hnmh", "250"],
                ["Mai Hịch - Hà Nội ", "mhhn", "250"],
                ["Hà Nội - Mai Châu - Bản Bước - Pù Luông", "hnpl", "300"],
                ["Pù Luông 1 ngày", "plvis", "150"],
                ["Pù Luông - Vân Long - Tam Cốc", "pltc", "300"],
                ["Pù Luông - Tam Cốc ", "pltc", "250"],
                ["Mai Hịch -Tam Cốc", "mhtc", "250"],
                ["Mai Hịch - Hồng Phong ", "mhhp", "250"],
                ["Mai Hịch - Nộn Khê", "mhnk", "250"],
                ["Hà Nội - Mai Châu (Thăm Pà Cò, Xăm Khòe )", "hnmc", "250"],
                ["Hà Nội - Vịt Cổ xanh - Mai Châu", "hnmc", "250"],
                ["Bến Bính - Thung Nham ", "bbtn", "210"],
                ["Bến Bính - Tam Cốc ", "bbtc", "210"],
                ["Hạ Long - Bút Tháp - Phù Lãng - Tiễn sân bay", "hltsb", "380"],
                ["Hạ Long ( Tuần Châu)- Bút Tháp  - Hà Nội", "hlhn", "260"],
                ["Hạ Long (Tuần Châu)- Phù Lẵng - Hà Nội", "hlhn", "260"],
                ["Hạ Long (Tuần Châu)- Côn sơn - Hà Nội", "hlhn", "260"],
                ["Hạ Long (Tuần Châu)- Đông Triều - Hà Nội", "hlhn", "260"],
                ["Hạ Long ( Hòn Gai )- Bút Tháp  - Hà Nội", "hlhn", "285"],
                ["Hạ Long (Hòn Gai )- Phù Lẵng - Hà Nội", "hlhn", "285"],
                ["Hạ Long (Hòn Gai )- Côn sơn - Hà Nội", "hlhn", "285"],
                ["Hạ Long (Hòn Gai )- Đông Triều - Hà Nội", "hlhn", "285"],
                ["Hạ Long (Tuần Châu ) - Bút Tháp - tiễn sân bay - Hà Nội", "hltsb", "340"],
                ["Hạ Long (Tuần Châu) - Bút Tháp - tiễn ga ", "hltg", "290"],
                ["Hạ Long (Tuần Châu ) - Côn Sơn  - tiễn sân bay - Hà Nội", "hltsb", "340"],
                ["Hạ Long (Tuần Châu) - Côn Sơn - tiễn ga ", "hltg", "290"],
                ["Hạ Long (Tuần Châu ) - Phù Lẵng - tiễn sân bay - Hà Nội", "hltsb", "340"],
                ["Hạ Long (Tuần Châu ) - Phù Lẵng - tiễn ga", "hltg", "290"],
                ["Hạ Long (Tuần Châu ) - Đông Triều - tiễn sân bay - Hà Nội", "hltsb", "340"],
                ["Hạ Long (Tuần Châu ) - Đông Triều - tiễn ga", "hltg", "290"],
                ["Hạ Long (Hòn Gai ) - Bút Tháp - tiễn sân bay - Hà Nội", "hltsb", "365"],
                ["Hạ Long (Hòn Gai ) - Bút Tháp - tiễn ga", "hltg", "315"],
                ["Hạ Long (Hòn Gai ) - Côn Sơn  - tiễn sân bay - Hà Nội", "hltsb", "365"],
                ["Hạ Long (Hòn Gai ) - Côn Sơn  - tiễn ga", "hltg", "315"],
                ["Hạ Long (Hòn Gai ) - Phù Lẵng - tiễn sân bay - Hà Nội", "hltsb", "365"],
                ["Hạ Long (Hòn Gai ) - Phù Lẵng - tiễn ga", "hltg", "315"],
                ["Hạ Long (Hòn Gai ) - Đông Triều - tiễn sân bay - Hà Nội", "hltsb", "365"],
                ["Hạ Long (Hòn Gai ) - Đông Triều - tiễn ga", "hltg", "315"],
                ["Hạ Long ( Tuần Châu) - Tiễn Sân Bay - Hà Nội ", "hltsb", "290"],
                ["Hạ Long ( Tuần Châu) - Tiễn ga", "hltg", "240"],
                ["Hạ Long ( Hòn Gai) - Tiễn Sân Bay - Hà Nội ", "hltsb", "315"],
                ["Hạ Long ( Hòn Gai) - Tiễn ga", "hltg", "265"],
                ["Hạ Long lưu xe", "hlld", "100"],
                ["Hạ Long (Tuần Châu) - Hà Nội", "hlhn", "210"],
                ["Hạ Long (Hòn Gai) - Hà Nội", "hlhn", "235"],
                ["Sang bến Hòn Gai", "hg", "25"],
                ["Hà Nội - Hạ Long ( Bãi Cháy )- xe ko tải sang Hải Phòng", "hnhlhp", "300"],
                ["Hà Nội - Hạ Long (Tuần Châu )- xe ko tải sang Hải Phòng", "hnhlhp", "300"],
                ["Hà Nội - Hạ Long (bến Tuần Châu)", "hnhl", "210"],
                ["Hà Nội - Hạ Long (bến bãi cháy)", "hnhl", "210"],
                ["Hà Nội - Hạ Long (bến Hòn Gai)", "hnhl", "235"],
                ["Hà Nội - Vân Long - Hoa Lư - Tam Cốc ", "hntc", "260"],
                ["Hà Nội - Vân Lonng - Tam Cốc ", "hntc", "210"],
                ["Hà Nội - Hoa Lư - Tam Cốc ", "hntc", "210"],
                ["Hà Nội - Hạ Long 1 ngày Bãi cháy", "hnhl", "380"],
                ["Hà Nội - Hạ Long 1 ngày  Tuần châu", "hnhl", "380"],
                ["Cộng thêm km đi cao tốc ", "ct", "10"],
                ["Hà Nội - Hải Phòng (Bến Bính)", "hnhp", "150"],
                ["Hà Nội - Hải Phòng - Thăm Chùa Dư Hằng", "hnhp", "200"],
                ["Hà Nội - Hòn Gai - xe ko tải sang Hải Phòng", "hnhp", "325"],
                ["Hà Nội - Yên Đức - Hạ Long (bến Tuần Châu)", "hnhl", "260"],
                ["Hà Nội - Yên Đức - Hạ Long  (bến Hòn Gai)", "hnhl", "285"],
                ["Hòn Gai - City Hải Phòng - tiễn sân bay Cát Bi - xe chạy không về Hà Nội", "hghpvis", "350"],
                ["Hòn Gai - City trung tâm Hải Phòng - lưu đêm", "hghpld", "225"],
                ["Hòn Gai - Khách sạn trung tâm TP Hải Phòng lưu xe", "hghpld", "175"],
                ["Lưu đêm ở các tỉnh khác", "ld", "130"],
                ["Tam Cốc lưu xe ", "tcld", "130"],
                ["Hạ Long lưu xe", "hlld", "100"],
                ["Sang Hòn Gai 2 lượt", "hg", "50"],
                ["Tuần Châu - Tiễn sân bay Cát Bi HP - Xe chạy không về Hà Nội", "tccbhn", "300"],
                ["Bãi Cháy - tiễn sân bay Cát Bi HP - xe chạy không về Hà Nội", "bccbhn", "300"],
                ["Hòn Gai - tiễn sân bay Cát Bi HP - xe chạy không về Hà Nội", "hgcbhn", "300"],
                ["Xe chạy không từ Hạ Long (Bãi Cháy hoặc Tuần Châu ) ", "hlbb", "100"],
                ["Xe chạy không từ Hạ Long  ( Tuần Châu) - bến Bính", "hlbb", "100"],
                ["Xe chạy không từ Hạ Long (Hòn Gai) - bến Bính", "hlbb", "125"],
                ["Xe chạy không từ Hạ Long (Tuần Châu) - bến Bính (không chương trình)", "hlbb", "150"],
                ["Xe chạy không từ Hạ Long (Bãi Cháy) - bến Bính  (không chương trình)", "hlbb", "150"],
                ["Xe chạy không từ Hạ Long (Hòn Gai) - bến Bính (không chương trình)", "hlbb", "175"],
                ["Xe không tải từ Hạ Long sang đón khách bến Bính - Hà Nội", "hlbbhn", "250"],
                ["Xe không tải từ Hà Nội xuống Bến Bính đón khách về Hà Nội", "hnbb", "270"],
                ["bến Bính - Hà Nội (không khách)", "bbhn", "170"],
                ["bến Bính -  Hải Phòng - Hà Nội ", "bbhn", "210"],
                ["Hà Nội - Hải Phòng - bến Bính ", "hnbb", "210"],
                ["Hà Nội không khách - bến Bính - Hà Nội ", "hnbb", "260"],
                ["Hà Nội không khách - bến Bính - Hải Phòng thăm - Hà Nội ", "hnbbhn", "310"],
                ["Hà Nội - Bái Đính - Tam Cốc", "hntc", "200"],
                ["Hà Nội - Bái Đính -  Thung Nham", "hntn", "200"],
                ["Hà Nội - Bái Đính - Tràng An - Hà Nội 1 ngày", "hnbdhn", "320"],
                ["Hà Nội - Bái Đính - Vân Long", "hnvl", "210"],
                ["Hà Nội - Hoa Lư- Vân Long - Tam Cốc", "hntc", "260"],
                ["Hà Nội - Hoa Lư - Vân Long - Nộn Khê ", "hnvlnk", "260"],
                ["Hà Nội - Hoa Lư - Vân Long - Hồng Phong", "hnvlhp", "260"],
                ["Hà Nội  -Cúc Phương", "hncp", "200"],
                ["Cúc Phương - Hà Nội ", "cphn", "200"],
                ["Hà Nội - Cúc Phương - Hà Nội (Cửa Rừng)", "hncp", "350"],
                ["Hà Nội - Động Thiên Hà - Tam cốc - Nộn Khê", "hnnk", "250"],
                ["Hà Nội - Động Thiên Hà - Tam cốc - Hồng Phong", "hnhp", "250"],
                ["Hà Nội - Hang Múa - Thái Vi - Tam Cốc ", "hntc", "250"],
                ["Hà Nội - Hang Múa - Thái Vi - Thung Nham", "hntn", "250"],
                ["Hà Nội - Hoa Lư - Kênh Gà - Tam Cốc ", "hntc", "250"],
                ["Hà Nội - Hoa Lư - Kênh Gà - Thung Nham", "hntn", "250"],
                ["Hà Nội - Hoa Lư - Kênh Gà - Tràng An - Tam Cốc", "hntc", "300"],
                ["Hà Nội - Hoa Lư - Phát Diệm - Hà Nội 1 ngày", "hnvis", "400"],
                ["Hà Nội - Hoa Lư - Tam Cốc - Hà Nội 1 ngày", "hnvis", "300"],
                ["Hà Nội - Hoa Lư - Tam Cốc ", "hntc", "210"],
                ["Tam Cốc - Hoa Lư - Hà Nội ", "tchn", "210"],
                ["Thung Nham - Hoa Lư - Hà Nội", "tnhn", "210"],
                ["Hà Nội - Hoa Lư - Thung Nham", "hntn", "210"],
                ["Hà Nội - Hoa Lư - Vân Long", "hnvl", "210"],
                ["Vân Long - Hoa Lư - Hà Nội", "vlhn", "210"],
                ["Hà Nội - Hoa Lư - Tràng An - Tam Cốc", "hntc", "260"],
                ["Tam Cốc - Tràng An - Hoa Lư - Hà Nội", "tchn", "260"],
                ["Hà Nội - Hoa Lư - Vân Long", "hnvl", "210"],
                ["Hà Nội - Kênh Gà - Hoa Lư - Thung Nham", "hntn", "260"],
                ["Hà Nội - Kênh Gà - Hoa Lư - Vân Long", "hnvl", "260"],
                ["Hà Nội - Kênh Gà - Tam Cốc - Hà Nội 1 ngày", "hnvis", "300"],
                ["Hà Nội - Kênh Gà - Tam Cốc - Nộn Khê ", "hnnk", "260"],
                ["Nộn Khê - Tam Cốc - Kênh Gà - Hà Nội ", "nkhn", "260"],
                ["Hà Nội - Kênh Gà - Tam Cốc - Hồng Phong ", "hnhp", "260"],
                ["Hồng Phong - Tam Cốc - Kênh Gà - Hà Nội ", "hphn", "260"],
                ["Hà Nội - Kênh Gà - Tam Cốc", "hntc", "210"],
                ["Tam Cốc - Kênh Gà - Hà Nội ", "tchn", "200"],
                ["Hà Nội - Kênh Gà - Thung Nham ", "hntn", "210"],
                ["Thung Nham - Kênh Gà - Hà Nội ", "tnhn", "210"],
                ["Hà Nội - Kênh Gà - Vân Long - Hà Nội 1 ngày", "hnvis", "300"],
                ["Hà Nội - Kênh Gà - Vân Long - Hồng Phong", "hnhp", "260"],
                ["Hà Nội - Kênh Gà - Vân Long - Nộn Khê ", "hnnk", "260"],
                ["Hà Nội - Kênh Gà - Vân Long - Thung Nham", "hntn", "260"],
                ["Hà Nội - Kênh Gà - Vân Long - Tam Cốc", "hntc", "260"],
                ["Hà Nội - Kênh gà - Vân Long ", "hnvl", "210"],
                ["Hà Nội - Kênh gà -  Emeralda Resort", "hnvl", "210"],
                ["Hà Nội - Ninh Bình (ko tải) - Đón khách ở Ga Ninh Bình", "hnnbdg", "160"],
                ["Hồng Phong - Tam Cốc ", "hptc", "150"],
                ["Tan Cốc - Hồng Phong", "tchp", "150"],
                ["Nộn Khê - Tam Cốc", "nktc", "150"],
                ["Tam Cốc - Nộn Khê", "tcnk", "150"],
                [" Nộn Khê - Hà Nội ", "nkhn", "200"],
                ["Hà Nội - Nộn Khê", "hnnk", "210"],
                ["Hồng Phong - Hà Nội ", "hphn", "210"],
                ["Hà Nội - Hồng Phong", "hnhp", "210"],
                ["Hà Nội - Phát Diệm - Hà Nội", "hnvis", "360"],
                ["Hà Nội - Phát Diệm - Nộn Khê - Hà Nội", "hnvis", "360"],
                ["Hà Nội - Phát Diệm - Nộn Khê - Thung Nham", "hntn", "260"],
                ["Hà Nội -Phát Diệm - Nộn Khê - Tam Cốc ", "hntc", "260"],
                ["Hà Nội - Phát Diệm - Nộn Khê ", "hnnk", "210"],
                ["Hà Nội - Phát Diệm - Hồng Phong ", "hnhp", "210"],
                ["Hà Nội - Phát Diệm -  Thung Nham", "hntn", "210"],
                ["Hà Nội - Phát Diệm - Tam Cốc ", "hntc", "210"],
                ["Hà Nội - Tam Cốc - Phát Diệm", "hnpd", "210"],
                ["Hà Nội - Tam Cốc - Phát Diệm - Hà Nội 1 ngày", "hnvis", "400"],
                ["Hà Nội - Tam Cốc - Phát Diệm - Nộn Khê", "hnnk", "260"],
                ["Hà Nội - Tam Cốc - Vân Long", "hnvl", "210"],
                ["Hà Nội - Tam Cốc", "hntc", "160"],
                ["Tam Cốc - Hà Nội ", "tchn", "160"],
                ["Tam cốc lưu xe", "tcld", "130"],
                ["Hà Nội - Tam Cốc", "hntc", "210"],
                ["Hà Nội - Tràng An -  Thung Nham", "hntn", "210"],
                ["Hà Nội - Tràng An - Tam Cốc ", "hntc", "210"],
                ["Hà Nội - Vân Long - Hoa Lư - Tam Cốc ", "hntc", "260"],
                ["Hà Nội - Vân Long - Hoa Lư - Thung Nham ", "hntn", "260"],
                ["Hà Nội - Vân Long - Phát Diệm - Nộn Khê", "hnnk", "260"],
                ["Hà Nội - Vân Long - Phát Diệm - Hồng Phong", "hnhp", "260"],
                ["Hà Nội - Vân Long - Tam Cốc - Nộn Khê", "hnnk", "260"],
                ["Hà Nội - Vân Long - Tam Cốc - Hồng Phong", "hnhp", "260"],
                ["Hà Nội - Vân Long -  Thung Nham", "hntn", "210"],
                ["Hà Nội - Vân Long - Tam Cốc ", "hntc", "210"],
                ["Hà Nội - Vân Long - Tam Cốc -Nộn Khê - Phát Diệm", "hnpd", "260"],
                ["Hà Nội - Vân Long - Tam Cốc - Hồng Phong - Phát Diệm ", "hnpd", "260"],
                ["Nộn Khê - Tam Cốc ", "nktc", "150"],
                ["Nộn Khê - Emeral Ninh Bình", "nkvl", "150"],
                ["Emeral Ninh Bình - Nộn Khê", "vlnk", "150"],
                ["Hồng Phong -Emeral Ninh Bình", "hpvl", "150"],
                ["Emeral Ninh Bình - Hồng Phong", "vlhp", "150"],
                ["Bắc Hà (không thăm quan) Lào Cai - Xe không tải về Hà nội", "bhhn", "350"],
                ["Hà Nội  (Không khách ) Lào Cai - Bắc Hà ", "hnbk", "350"],
                ["Hà Nội - Bắc Hà (có thăm Bắc Hà)", "hnbh", "500"],
                ["Bắc Hà (có thăm ) - Hà Nội", "bhhn", "500"],
                ["Hà Nội - Bắc Hà (không thăm)", "hnbh", "450"],
                ["Bắc Hà - Hà Nội (không thăm)", "bhhn", "450"],
                ["Hà Nội - Điện Biên", "hndb", "500"],
                ["Điện Biên - Hà Nội ", "dbhn", "500"],
                ["Hà Nội - Điện Biên (chạy không tải)", "hndb", "400"],
                ["Điện Biên - Hà Nội  (chạy không tải)", "dbhn", "400"],
                ["Hà Nội - Hải Dương - Xem rối nước ở Bồ Dương - Hà Nội", "hnhd", "250"],
                ["Hà Nội - Lào Cai (đường mới  cao tốc)", "hnlc", "350"],
                ["Lào cai - Hà Nội (đường cao tốc)", "lchn", "350"],
                ["Hà Nội - Lào Cai  (Xe không tải )", "hnlc", "300"],
                ["Lào cai - Hà Nội (không tải)", "lchn", "300"],
                ["Hà Nội - Quảng Xương Thanh Hóa - ksan 257 Trường Thi ( TP Thanh Hóa)", "hnth", "300"],
                ["Hà Nội - Sapa ( cao tốc mới)", "hnsp", "400"],
                ["Sapa - Hà Nội (không thăm)", "hnsp", "400"],
                ["Hà Nội - Sapa -  Thăm xung quanh nửa ngày Sapa", "hnsp", "450"],
                ["Sapa (thăm ) - Hà Nội ", "sphn", "450"],
                ["Sapa (không thăm) -Hà Nội ", "sphn", "400"],
                ["Hà Nội - Tam Đảo - Hà Nội 1 ngày", "hntd", "400"],
                ["Hà Nội - Tam Đảo 2 ngày", "hntd", "500"],
                ["Thành Phố Thanh Hóa - Nộn Khê Ninh Bình", "thnk", "200"],
                ["Hà Nội - Đảo Cò - Hải Dương ( Đi theo đường cũ qua Văn Giang)", "hnhd", "150"],
                ["Hà Nội - Đảo Cò - Hải Dương - Hà Nội", "hndc", "230"],
                ["Hạ Long ( Tuần Châu) Đảo Cò ngủ ở đảo cò", "hlhd", "210"],
                ["Hạ Long  ( Tuần Châu ) - Đảo Cò - Hà Nội", "hldc", "260"],
                ["Hạ Long  ( Hòn gai ) - Đảo Cò - Hà Nội", "hldc", "285"],
                ["Hà Nội - Sơn Tây - Moon garden - Hà Nội (1 ngày)", "hnks", "200"],
                ["Hà Nội - Sơn Tây - Moon garden (ngủ lại Moon Garden)", "hnks", "150"],
                ["Moon garden - Hạ Long ( Tuần Châu) ", "kshl", "290"],
                ["Moon garden - Hạ Long (Hòn Gai)", "kshl", "315"],
                ["Mai Châu - Bản Nhót - Pù Luông", "mcpl", "150"],
                ["Mai Châu - Hoa Lư - Tam Cốc", "mctc", "300"],
                ["Mai Châu - Kênh Gà - Nộn Khê", "mcnk", "300"],
                ["Mai Châu - Kênh Gà - Vân Long", "mcvl", "300"],
                ["Mai Châu - Kênh Gà - Vân Long - Hồng Phong", "mchp", "350"],
                ["Mai Châu - Tam Cốc ", "mctc", "250"],
                ["Mai Châu - Thung Nham ", "mctn", "250"],
                ["Mai Châu - Hồng Phong ", "mchp", "250"],
                ["Mai Châu - Nộn Khê", "mcnk", "250"],
                ["Mai Châu - Thổ Hà (gần Kênh Gà) - Tam Cốc ", "mctc", "300"],
                ["Mai Châu - Thổ Hà (gần Kênh Gà) - Thung Nham", "mctc", "300"],
                ["Mai Châu - Thổ Hà (gần Kênh Gà) - Vân Long", "mcvl", "300"],
                ["Mai Châu - Thổ Hà (gần Kênh Gà) - Hồng Phong", "mchp", "300"],
                ["Mai Châu - Thổ Hà (gần Kênh Gà) - Nộn Khê", "mcnk", "300"],
                ["Mai Hịch - Bản Bước - Hà Nội", "mhhn", "250"],
                ["Mai Hịch - Cúc Phương - Tam Cốc", "mhtc", "300"],
                ["Mai Hịch - Kênh Gà - Tam Cốc", "mhtc", "300"],
                ["Mai Hịch - Tam Cốc", "mhtc", "250"],
                ["Mai Hịch - Pù Luông - Mai Hịch ", "mhvis", "150"],
                ["Mai Hịch - Thung Nắng - Thung Nham", "mhtn", "300"],
                ["Ninh Bình - Hoa Lư - Kênh Gà - Hà Nội", "nbhn", "350"],
                ["Ninh Bình - Phát Diệm", "nbpd", "150"],
                ["Ninh Bình - Thành phố Vinh", "nbv", "250"],
                ["Tam Cốc - Bái Đính - Tràng An - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Bích Động - Mai Châu - Mai  Hịch", "tcmh", "300"],
                ["Tam Cốc - Cúc Phương - Hạ Long", "tccphl", "350"],
                ["Tam Cốc - Hoa Lư - Hạ Long (Bãi Cháy)", "tchl", "300"],
                ["Tam Cốc - Hoa Lư - Hạ Long (Tuần Châu )", "tchl", "300"],
                ["Tam Cốc - Hoa Lư - Vân Long - Hà Nội", "tchn", "260"],
                ["Tam Cốc - Hoa Lư  - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Thái Vi - Tam Cốc ", "tcvis", "150"],
                ["Tam Cốc - Hang Múa - Tam Cốc ", "tcvis", "150"],
                ["Tam Cốc - Nộn Khê- Phát Diệm - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc -  Nộn Khê- Phát Diệm - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Kênh Gà - Vân Long - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Nộn Khê ", "tcnk", "150"],
                ["Nộn Khê - Tam Cốc", "nktc", "150"],
                ["Tam Cốc - Phát Diệm - H. Phong  - Tam Cốc", "tcvis", "180"],
                ["Tam Cốc - Phát Diệm - Nộn Khê- Tam Cốc", "tcvis", "180"],
                ["Tam Cốc - Phát Diệm - Hà Nội", "tchn", "200"],
                ["Tam Cốc - Phát Diệm - Tam Cốc", "tcvis", "150"],
                ["Tam Cốc - Vân Long ", "tcvl", "150"],
                ["Vân Long - Tam Cốc", "vltc", "150"],
                ["Tam Cốc - Vân Long - Nộn Khê", "tcnk", "150"],
                ["Tam Cốc - Hà Nội ", "tchn", "160"],
                ["Hà Nội - Tam Cốc", "hntc", "160"],
                ["Thung Nham - Hà Nội", "tnhn", "160"],
                ["Hà Nội - Thung Nham", "hntn", "160"],
                ["Vân Long - Hà Nội ", "vlhn", "160"],
                ["Hà Nội - Vân Long", "hnvl", "160"],
                ["Tam Cốc -Hạ Long - Tuần Châu", "tchl", "260"],
                ["Tam Cốc -Hạ Long -  Hòn Gai", "tchl", "285"],
                ["Nộn Khê - Hạ Long (Tuần Châu)", "nkhl", "260"],
                ["Hồng Phong - Hạ Long (Tuần Châu)", "hphl", "260"],
                ["Nộn Khê- Hạ Long (Hòn Gai)", "nkhl", "285"],
                ["Hồng Phong - Hạ Long ( Hòn Gai)", "hphl", "285"],
                ["Hạ Long (Hòn Gai) - Hồng Phong ", "hlhp", "285"],
                ["Hạ Long (Hòn Gai) - Nộn Khê", "hlnk", "285"],
                ["Hạ Long (Tuần Châu ) - Hồng Phong", "hlhp", "260"],
                ["Hạ Long (Tuần Châu ) - Nộn Khê", "hlnk", "260"],
                ["Tam Cốc Garden - Cúc Phương - Tam Cốc Garden", "tcvis", "200"],
                ["Tam Cốc Garden - Cúc Phương (ngủ Cúc Phương)", "tccp", "150"],
                ["Thung Nham - Yên Đức - Hòn Gai", "tnhg", "325"],
                ["Vào Giữa rừng (Cộng thêm)", "cp", "50"],
                ["Vân Long - Nộn Khê - Vân Long", "vlvis", "150"],
                ["Vân Long - Nộn Khê ", "vlnk", "150"],
                ["Nộn Khê - Vân Long", "nkvl", "150"],
                ["Nộn Khê - Tam Cốc ", "nktc", "150"],
                ["Hồng Phong - Tam Cốc ", "hptc", "150"],
                ["Vân Long - Tam Cốc - Nộn Khê", "vlnk", "150"],
                ["Vân Long - Tràng An - Bái Đính - Hoa Lư- Vân Long", "vlvis", "200"],
                ["Nộn Khê - Hải Hậu", "nkhh", "150"],
                ["Nộn Khê - Phát Diệm - Nộn Khê", "nkvis", "150"],
                ["Nộn Khê - Phát Diệm - Vân Long", "nkvis", "150"],
                ["Vân Long - Phát Diệm - Nộn Khê", "vlnk", "150"],
                ["Nộn Khê - Tam Cốc - Nộn Khê", "nkvis", "150"],
                ["Nộn Khê tự do (ko dùng xe đi bất cứ đâu)", "nkld", "130"],
                ["Nộn Khê - Ninh Bình - Phủ Lý - Đồng Văn - Đảo Cò ", "nkdc", "150"],
                [" Tam Cốc - Ninh Bình - Phủ Lý - Đồng Văn - Đảo Cò ", "tcdc", "150"],
                [" Tam Cốc - Ninh Bình - Phủ Lý - Đồng Văn - Đảo Cò ", "hpdc", "150"],
                ["Nộn Khê - Ninh Bình - Phủ Lý - Đồng Văn - Rẽ qua Ninh Giang - Đảo Cò  - Hà Nội ", "nkdc", "300"],
                ["Tam Cốc - Ninh Bình - Phủ Lý - Đồng Văn - Rẽ qua Ninh Giang - Đảo Cò  - Hà Nội ", "tcdc", "300"],
                ["Hồng Phong  - Ninh Bình - Phủ Lý - Đồng Văn - Rẽ qua Ninh Giang - Đảo Cò  - Hà Nội ", "hpdc", "300"],
                ["Hải Hậu - Hạ Long - Bãi Cháy", "hhhl", "210"],
                ["Hải Hậu - Hạ Long - Tuần Châu", "hhhl", "210"],
                ["Hải Hậu - Hạ Long (Hòn gai)", "hhhl", "225"],
                ["Hải Hậu - Hà Nội", "hhhn", "200"],
                ["Hà Nội - Lạng Sơn - thăm Tam Thanh", "hnls", "250"],
                ["TP Lạng Sơn - Bến Bính", "lsbb", "250"],
                ["TP Lạng Sơn - Hạ Long ( Hòn Gai hoặc Tuần Châu)", "lshl", "250"],
                ["Ba Bể - Hải Dương", "bbhd", "400"],
                ["Hải Dương - Hạ Long (Tuần Châu)", "hdhl", "210"],
                ["Ăn tối or trưa chez Bảo Tuấn (trong tour)", "bt", "70"],
                ["Ăn tối or ăn trưa chez Bảo Tuấn (chỉ đưa đi ăn, không có chương trình tham quan)", "bt", "100"]
            ];
        foreach ($arr_lx as $key => $lx_way) {
            $way = new Way();
            $way->name = $lx_way[0];
            $way->acro = $lx_way[1];
            $way->sl = $lx_way[2];
            $way->unit = 'km';
            $way->status = 'on';
            $way->note = '';
            $way->created_by = 1;
            $way->created_at = date('Y-m-d H:i:s', strtotime('now'));
            $way->updated_at = date('Y-m-d H:i:s', strtotime('0000-00-00 00:00:00'));
            $way->updated_by = 0;
            if ($way->validate()) {
                if (!$way->save()) {
                    var_dump($way->errors);
                }
            } else {
                var_dump($way->errors);
            }
        }

////////////////////////////////////////////////////////////////// tach ngay mau//////////////////////////////////
        // $sql = "SELECT * FROM at_ngaymau WHERE body LIKE '%Jour 1%' OR body LIKE '%J1%'";
        // $ngaumaus = AtNgaymau::findBySql($sql)->all();

        // foreach ($ngaumaus as $k => $ngaymau) {
        //     $ngaymau->body = str_replace(['J1', 'J2', 'J3', 'J4'], ['Jour 1', 'Jour 2', 'Jour 3', 'Jour 4'], $ngaymau->body);
        //     $ngaymau->body = str_replace(['JOUR'], ['Jour'], $ngaymau->body);
        //     $arr = explode('Jour ', $ngaymau->body);
///////////////////////////////////////////////////////////
            // echo "########################      $ngaymau->id       ############################# <br/>";
            // echo "<b>$ngaymau->title</b><br/>";
            // echo "$ngaymau->body<br/>";
            // var_dump($arr);
            // echo "########################################################### <br/>";
////////////////// /////////////////////////////////////
            // tach body
            // if (count($arr) > 1) {
            //     foreach ($arr as $key => $value) {
            //         if ($key > 0 && $ngaymau->id != 966) {
            //             $content = explode('</p>', $value);
            //             $body = implode('</p>', array_diff($content, [$content[0]]));
            //             $arr_first = ['1.', '2.', '3.', '4.', '5.', '6.', '7.', '8.',
            //                           '1:', '2:', '3:', '4:', '5:', '6:', '7:', '8:',
            //                           '1-', '2-', '3-', '4-'];
            //             $first = substr($content[0], 0 ,2);
            //             if (!in_array($first, $arr_first)) {
            //                 continue;
            //             }
            //             $title = trim(substr($content[0], 2));
            //             if ($title == '') {
            //               die($ngaymau->id);
            //             }
            //             $title = $arr[0].$title."</p>";
            //             $ngaymau1 = new AtNgaymau1();
            //             $ngaymau1->created_dt = '1970-01-01 00:00:00';
            //             $ngaymau1->created_by = 0;
            //             $ngaymau1->updated_dt = '1970-01-01 00:00:00';
            //             $ngaymau1->updated_by = 0;
            //             $ngaymau1->owner = 0;
            //             $ngaymau1->parent_id = 0;
            //             $ngaymau1->sorder = 0;
            //             $ngaymau1->title = $title;
            //             $ngaymau1->body = $body;
            //             $ngaymau1->tags = '';
            //             $ngaymau1->image = '';
            //             $ngaymau1->meals = '---';
            //             $ngaymau1->transport = '';
            //             $ngaymau1->guides = '';
            //             $ngaymau1->note = '';
            //             $ngaymau1->language = 'fr';
            //             $ngaymau1->group_id = $ngaymau->id;
            //             if ($title == '') {
            //               $title = $ngaymau->id;
            //             }
            //             echo "########################      $ngaymau->id       ############################# <br/>";
            //             echo "<b>$title</b><br/>";
            //             echo "$body<br/>";
            //             var_dump($ngaymau1->save());
            //             echo "########################################################### <br/>";

            //         }
            //     }
            // }
        // }
////////////////////        //thống kê nhà hàng////////////////////////////////////////
        // $sql = "SELECT c.tour_id, d.name_vi, v.name, c.venue_id, st.pax_count
        //         FROM `cpt` c inner join `venues` v on c.venue_id = v.id 
        //                      inner join `at_destinations` d on d.id=v.destination_id
        //                      inner join `at_tour_stats` st on c.tour_id = st.tour_old_id
        //         WHERE v.stype = 'restaurant' AND YEAR(c.dvtour_day) = '2016'
        //         ORDER BY c.venue_id, c.tour_id";
        // $cpts = Cpt::findBySql($sql)->asArray()->all();        //var_dump($cpts);die();
        // $list_data = [];
        // $cnt = 1;
        // $pax_count = $cpts[0]['pax_count'];
        // for($i = 1; $i < count($cpts); $i ++) {
        //     if ($cpts[$i]['venue_id'] == $cpts[$i-1]['venue_id']) {
        //         if (trim($cpts[$i]['tour_id']) != trim($cpts[$i-1]['tour_id'])) {
        //             $pax_count += $cpts[$i]['pax_count'];
        //             $cnt++;
        //             if ($i == (count($cpts) - 1)) {
        //                 $list_data[$cpts[$i]['venue_id']][] = $cpts[$i]['venue_id'];
        //                 $list_data[$cpts[$i]['venue_id']][] = $cpts[$i]['name_vi'];
        //                 $list_data[$cpts[$i]['venue_id']][] = $cpts[$i]['name'];
        //                 $list_data[$cpts[$i]['venue_id']][] = $cnt;
        //                 $list_data[$cpts[$i]['venue_id']][] = $pax_count;
        //             }
        //         }
        //     } else {
        //         $list_data[$cpts[$i-1]['venue_id']][] = $cpts[$i-1]['venue_id'];
        //         $list_data[$cpts[$i-1]['venue_id']][] = $cpts[$i-1]['name_vi'];
        //         $list_data[$cpts[$i-1]['venue_id']][] = $cpts[$i-1]['name'];
        //         $list_data[$cpts[$i-1]['venue_id']][] = $cnt;
        //         $list_data[$cpts[$i-1]['venue_id']][] = $pax_count;
        //         $cnt = 1;
        //         $pax_count = $cpts[$i]['pax_count'];
        //         if ($i == (count($cpts) - 1)) {
        //             $list_data[$cpts[$i]['venue_id']][] = $cpts[$i]['venue_id'];
        //             $list_data[$cpts[$i]['venue_id']][] = $cpts[$i]['name_vi'];
        //             $list_data[$cpts[$i]['venue_id']][] = $cpts[$i]['name'];
        //             $list_data[$cpts[$i]['venue_id']][] = $cnt;
        //             $list_data[$cpts[$i]['venue_id']][] = $cpts[$i]['pax_count'];
        //         }
        //     }
        // }
                // var_dump($list_data);die();
        /*SELECT d.name_vi, v.name, count(c.tour_id) AS cnt FROM `cpt` c, `venues` v, at_destinations d WHERE d.id=v.destination_id AND c.venue_id = v.id AND v.stype = 'restaurant' AND YEAR(c.dvtour_day) = '2015' GROUP BY v.name order by destination_id, cnt desc*/
////////////////////////        // thống kê số đêm/////////////////////////////
        // $list_data = [];
        // $list_data_type =[];
        // $venue_ids = Cpt::find()->select(['venue_id'])->distinct()->innerJoinWith('venue')
        //     ->andWhere("venues.stype = 'home' AND YEAR(dvtour_day) = '2016'")->indexBy('venue_id')->column();

        // foreach ($venue_ids as $venue_id) {
        //     $cpts = Cpt::find()->select(['dvtour_id', 'tour_id', 'cpt.venue_id', 'venues.name', 'at_destinations.name_vi', 'dvtour_day', 'unit'])
        //     ->innerJoinWith('venue')
        //     ->join('INNER JOIN', 'at_destinations', 'venues.destination_id = at_destinations.id')
        //         ->andWhere(['cpt.venue_id' => $venue_id])
        //         ->andWhere("venues.stype = 'home' AND YEAR(dvtour_day) = '2016'")
        //         ->orderBy('cpt.tour_id, dvtour_day')
        //         ->asArray()->all();
        //     if ($cpts!= null) {
        //         $cnt = 1;
        //         for ($i = 1; $i < count($cpts); $i ++) {
        //             if (date('Y-m-d', strtotime($cpts[$i]['dvtour_day'])) == date('Y-m-d', strtotime($cpts[$i-1]['dvtour_day'])) && $cpts[$i]['tour_id'] == $cpts[$i-1]['tour_id']) {
        //                 $list_data_type[$cpts[$i]['venue_id']][$cpts[$i]['unit']] = $cpts[$i]['dvtour_id'];

        //             }
        //             else {
        //                 $day = $cpts[$i]['dvtour_id']. ' - '.$cpts[$i]['dvtour_day'] ;
        //                 // var_dump($day);
        //                 $cnt ++;
        //             }
        //         }
        //         $list_data[$cpts[0]['venue_id']][] = $cpts[0]['venue_id'];
        //         $list_data[$cpts[0]['venue_id']][] = $cpts[0]['name'];
        //         $list_data[$cpts[0]['venue_id']][] = $cpts[0]['name_vi'];
        //         $list_data[$cpts[0]['venue_id']][] = $cnt;

        //     }
        // }
/////////////////export to excel     // // Create new PHPExcel object///////////////////////////////////////
        //detail =========>>>>> https://blog.mayflower.de/561-Import-and-export-data-using-PHPExcel.html
            // var_dump($list_data);die();
            // $objPHPExcel = new PHPExcel();

            // // Fill worksheet from values in array
            // $objPHPExcel->getActiveSheet()->fromArray($list_data, null, 'A1');

            // // Rename worksheet
            // $objPHPExcel->getActiveSheet()->setTitle('Members');

            // // Set AutoSize for name and email fields
            // $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            // $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            // // Save Excel 2007 file
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save('MyExcel.xlsx');
            // var_dump($list_data);
/*   INSERT INTO `at_ngaymau1`(`created_dt`, `created_by`, `updated_dt`, `updated_by`, `owner`, `parent_id`, `sorder`, `title`, `body`, `tags`, `image`, `meals`, `transport`, `guides`, `note`, `language`)
VALUES ('1970-01-01 00:00:00',0,'1970-01-01 00:00:00',0,'at',0,0,'HANOI - HA LONG',"",'','','---','','','','fr') */
/*

INSERT INTO `at_ngaymau1`(`created_dt`, `created_by`, `updated_dt`, `updated_by`, `owner`, `parent_id`, `sorder`, `title`, `body`, `tags`, `image`, `meals`, `transport`, `guides`, `note`, `language`)
VALUES ('1970-01-01 00:00:00',0,'1970-01-01 00:00:00',0,'at',0,0,'title','body','','','---','','','','fr')


*/
//////////////////////////////////////////////////////////////////////////////////////////////


        // $tour_ids = Cpt::find()->select(['tour_id'])->distinct()->orderBy('tour_id')->indexBy('tour_id')->column();
        // $like_ids = [];
        // die('done !');
        // foreach ($tour_ids as $tour_id) {
        //     $cpts = Cpt::find()
        //     ->innerJoinWith('dv')
        //     ->andWhere('dv.stype = "a"')
        //     ->andWhere(['tour_id' => $tour_id])
        //     ->andWhere(['>=', 'dvtour_day', '2016-01-01'])
        //     ->orderBy('dvtour_day')
        //     ->asArray()->all();
        //     if ($cpts == null) {
        //        continue;
        //     }
        //     for($i = 0; $i < count($cpts) - 1; $i++) {
        //         if ($cpts[$i]['venue_id'] == $cpts[$i+1]['venue_id']) {
        //             if ($cpts[$i]['dv_id'] == $cpts[$i+1]['dv_id'] && date('Y-m-d', strtotime($cpts[$i+1]['dvtour_day'])) == date('Y-m-d', strtotime('+1 day '.$cpts[$i]['dvtour_day'])) && $cpts[$i]['price'] == $cpts[$i+1]['price'] && $cpts[$i]['paid_full'] == $cpts[$i+1]['paid_full'] && $cpts[$i]['paid_full'] =='yes') {
        //                 $like_ids[$tour_id][$cpts[$i]['dvtour_id']] = $cpts[$i]['unit'].' - '.$cpts[$i]['dvtour_day'].' - '.$cpts[$i]['dv_id'];
        //                 $like_ids[$tour_id][$cpts[$i+1]['dvtour_id']] = $cpts[$i+1]['unit'].' - '.$cpts[$i+1]['dvtour_day'].' - '.$cpts[$i+1]['dv_id'];
        //             }
        //         }
        //     }
        //     if (isset($like_ids[$tour_id])) {
        //         var_dump($like_ids[$tour_id]);
        //         foreach ($like_ids[$tour_id] as $cpt_tours) {
        //             $arr_data = [];
        //             foreach ($cpt_tours as $cpt_id => $cpt_name) {
        //                 $arr_info = explode('-', $cpt_name);
        //                 $arr_data[trim($arr_info[2])][] = $cpt_id;
        //             }
        //             foreach ($arr_data as $data_k => $data_v) {
        //                 foreach($data_v as $key => $id_cpt) {
        //                     if ($key == 0) {
        //                         //update xday
        //                         $cpt = Cpt::find()->where(['dvtour_id' => $id_cpt])->one();
        //                         if ($cpt != null) {
        //                             $cpt->xd = count($data_v);
        //                             $cpt->save();
        //                         }
        //                     }
        //                     else {
        //                         //delete orther
        //                         $cpt = Cpt::find()->where(['dvtour_id' => $id_cpt])->one();
        //                         $cpt->delete();
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        // die('ok');
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
            $theMtt->cpt_id = $theCpt['dvtour_id56'];
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
