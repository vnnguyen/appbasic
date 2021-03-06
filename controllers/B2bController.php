<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\data\Pagination;
use app\common\models\Note;
use common\models\Person;
use app\common\models\Product;
use common\models\Booking;
use common\models\Kase;
use common\models\Nm;
use common\models\Day;
use common\models\Tour;
use common\models\Venue;
use common\models\Invoice;
use common\models\Payment;

class B2bController extends \app\controllers\MyController
{

    public function actionIndex($language = '', $customer = '', $type = '')
    {
        $getType = Yii::$app->request->get('type', 'all');
        $getMonth = Yii::$app->request->get('month', 'all');
        $getUb = Yii::$app->request->get('ub', 0);
        $getProposal = Yii::$app->request->get('proposal', ['all', 'yes', 'no']);
        $getDays = Yii::$app->request->get('days', 'all');
        $getName = Yii::$app->request->get('name', '');
        $getOrder = Yii::$app->request->get('order', 'uo');
        $getSort = Yii::$app->request->get('sort', 'desc');

        $query = Product::find()->where(['owner'=>'si']);

        if ($type == 'prod' || $type == 'b2b-prod') {
            $query->andWhere(['offer_type'=>'b2b-prod']);
        } else {
            $query->andWhere(['not', ['offer_type'=>'b2b-prod']]);
        }
            // ->andWhere(['not', ['offer_type'=>'b2b-prod']]);
            // $query->andWhere(['owner'=>'si', 'offer_type'=>'b2b-prod']);

        if (in_array($language, ['en', 'fr', 'it', 'vi'])) {
            $query->andWhere(['language'=>$language]);
        }

        if ($getMonth != 'all') {
            $query->andWhere('SUBSTRING(day_from,1,7)=:mo', [':mo'=>$getMonth]);
        }
        if ($getUb != 0) {
            $query->andWhere(['created_by'=>$getUb]);
        }
        if ($getName != '' && strlen($getName) >= 2) {
            $query->andWhere(['or', ['like', 'title', $getName], ['like', 'about', $getName], ['like', 'tags', $getName]]);
        }
        if ($getProposal == 'yes') {
            $query->andWhere('offer_count>0');
        } elseif ($getProposal == 'no') {
            $query->andWhere('offer_count=0');
        }
        if ($getDays == '10') {
            $query->andWhere('day_count<=10');
        } elseif ($getDays == '20') {
            $query->andWhere('day_count>=11')->andWhere('day_count<=20');
        } elseif ($getDays == '30') {
            $query->andWhere('day_count>=21')->andWhere('day_count<=30');
        } elseif ($getDays == '31') {
            $query->andWhere('day_count>30');
        }
        $startDateList = Yii::$app->db->createCommand('SELECT SUBSTRING(day_from,1,7) AS ym FROM at_ct GROUP BY ym ORDER BY ym DESC')
            ->queryAll();
        $ubList = Yii::$app->db->createCommand('SELECT u.id, lname, email FROM persons u, at_ct ct WHERE ct.updated_by=u.id GROUP BY u.id ORDER BY u.lname')
            ->queryAll();

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            'route'=>'/'.URI,
        ]);

        if (!in_array($getOrder, ['updated_at', 'day_from', 'day_count', 'pax', 'title'])) {
            $getOrder = 'updated_at';
        }

        if (!in_array($getSort, ['asc', 'desc'])) {
            $getSort = 'desc';
        }

        $thePrograms = $query
            ->orderBy($getOrder.' '.$getSort)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with([
                'tour'=>function($q) {
                    return $q->select(['id', 'ct_id', 'code', 'name']);
                },
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname', 'image']);
                },
                'days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings',
                'bookings.case'=>function($q) {
                    return $q->select(['id', 'name']);
                }
                ])
            ->asArray()
            ->all();

        return $this->render('program_index', [
            'getOrder'=>$getOrder,
            'getSort'=>$getSort,
            'startDateList'=>$startDateList,
            'ubList'=>$ubList,
            'language'=>$language,
            'getUb'=>$getUb,
            'getProposal'=>$getProposal,
            'getType'=>$getType,
            'getMonth'=>$getMonth,
            'getDays'=>$getDays,
            'getName'=>$getName,
            'thePrograms'=>$thePrograms,
            'pagination'=>$pagination,
        ]);
    }

    public function actionC($type = '') {
        $theProgram = new Product;
        
        $theProgram->created_at = NOW;
        $theProgram->created_by = USER_ID;
        $theProgram->updated_at = NOW;
        $theProgram->updated_by = USER_ID;
        $theProgram->status = 'on';

        $theProgram->owner = 'si';

        $theProgram->day_from = date('Y-m-d');
        $theProgram->price = 0;
        $theProgram->price_until = date('Y-m-d', strtotime('+1 year'));

        $theProgram->scenario = 'products_c';
        if ($type == 'b2b-prod') {
            $theProgram->offer_type = 'b2b-prod';
        }

        if ($theProgram->load(Yii::$app->request->post()) && $theProgram->validate()) {
            if ($theProgram->save(false)) {
                Yii::$app->session->setFlash('success', 'Tour itinerary has been added: '.$theProgram->title);
                // return $this->redirect('@web/b2b/programs/r/'.$theProgram['id']);
            }
        }

        return $this->render('program/program_u', [
            'theProgram'=>$theProgram,
        ]);
    }

    public function actionR($id = 0, $action = '')
    {
        $theProgram = Product::find()
            ->where(['id'=>$id, 'owner'=>'si'])
            ->with([
                'days',
                'bookings',
                'bookings.case',
                'bookings.createdBy',
            ])
            ->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found.');
        }

        $theDays = Day::find()->where(['rid'=>$id])->asArray()->all();

        // Load extra days
        if ($theProgram['offer_type'] == 'combined2016') {
            $extraDays = Day::find()
                ->where(['rid'=>$id])
                ->andWhere('parent_day_id!=0')
                ->asArray()
                ->all();
        }
        // Check and fix day numbers
        $dayIdList = array_filter(explode(',', trim($theProgram['day_ids'], ',')));
        if ($theProgram['day_count'] != count($dayIdList)) {
            $theProgram->day_count = count($dayIdList);
            Yii::$app->db->createCommand()
                ->update('at_ct', ['day_count'=>count($dayIdList)], ['id'=>$id])
                ->execute();
        }

        $theCases = Yii::$app->db
            ->createCommand('SELECT c.id, c.name FROM at_cases c, at_bookings b WHERE b.case_id=c.id AND b.product_id=:id LIMIT 100', [':id'=>$id])
            ->queryAll();

        $theTour = Tour::find()
            ->where(['ct_id'=>$id])
            ->one();

        $theDay = new Day;
        $theDay->scenario = 'day/c';

        if ($action == 'insert-day') {
            $insertFrom = $_POST['from'];
            $insertId = $_POST['id'];
            $insertAt = $_POST['at'];

            if ($insertFrom == 'blank') {
                // Insert blank day
                $newDay = new Day;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->language = $theProgram['language'];
                $newDay->rid = $theProgram['id'];
                $newDay->step = 1;
                $newDay->name = '( blank )';
                $newDay->body = '<p>( blank )</p>';
                $newDay->image = '';
                $newDay->meals = '---';
                $newDay->transport = '';
                $newDay->guides = '';
                $newDay->note = '';
            } elseif ($insertFrom == 'sd') {
                // Sample day
                $sourceDay = Nm::findOne($insertId);
                if (!$sourceDay) {
                    throw new HttpException(404, 'Sample day not found');
                }
                $newDay = new Day;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->language = $sourceDay->language;
                $newDay->rid = $theProgram['id'];
                $newDay->step = 1;
                $newDay->name = $sourceDay->title;
                $newDay->body = $sourceDay->body;
                $newDay->image = $sourceDay->image;
                $newDay->meals = $sourceDay->meals;
                $newDay->transport = $sourceDay->transport;
                $newDay->guides = $sourceDay->guides;
                $newDay->note = $sourceDay->note;
            } else {
                // Program day, previous day
                $sourceDay = Day::findOne($insertId);
                if (!$sourceDay) {
                    throw new HttpException(404, 'Day not found');
                }
                $newDay = (clone $sourceDay);
                unset($newDay->id);
                $newDay->isNewRecord = true;
                $newDay->created_at = NOW;
                $newDay->created_by = USER_ID;
                $newDay->updated_at = NOW;
                $newDay->updated_by = USER_ID;
                $newDay->status = 'on';
                $newDay->rid = $theProgram['id'];
                $newDay->step = 1;
            }

            // Truong hop thong tin ngay cũ dùng textile
            if (substr($newDay->body, 0, 1) != '<') {
                require_once('/var/www/vendor/textile/php-textile/Parser.php');
                $parser = new \Netcarver\Textile\Parser();
                $newDay->body = $parser->parse($newDay->body);
            }

            if ($newDay->save(false)) {
                // Insert new day id
                if ($insertAt == -1) {
                    // Insert at first pos
                    $dayIdList = array_merge($newDay->id, $dayIdList);
                } else {
                    array_splice($dayIdList, (int)$_POST['at'], 0, $newDay->id);
                }
                $theProgram['day_ids'] = implode(',', $dayIdList);
                // Save product
                $theProgram->save(false);
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'id'=>$newDay->id,
                    'meals'=>$newDay->meals,
                    'title'=>$newDay->name,
                    'guides'=>$newDay->guides,
                    'transport'=>$newDay->transport,
                    'body'=>$newDay->body,
                ];
            }
            throw new HttpException(401);
        }

        if ($action == 'json') {
            require_once('/var/www/vendor/textile/php-textile/Parser.php');
            $parser = new \Netcarver\Textile\Parser();

            $searchIn = $_POST['search_in'];
            $searchLang = $_POST['search_lang'];
            $searchName = $_POST['search_name'];
            $searchTags = $_POST['search_tags'];
            $searchB2cb = $_POST['search_b2cb'];
            $searchPage = $_POST['search_page'];
            if ($searchIn == 'sd') {
                $query = \common\models\Nm::find()
                    ->select(['id', 'title', 'meals', 'body', 'guides', 'transport'])
                    ->andWhere(['language'=>$searchLang]);
                if ($searchB2cb == 'b2c') {
                    $query->andWhere(['owner'=>'at']);
                } else {
                    $query->andWhere(['owner'=>'si']);
                }
                if (strlen($searchName) > 2) {
                    $query->andWhere(['like', 'title', $searchName]);
                }
                if (strlen($searchTags) > 2) {
                    $tagArray = explode(',', str_replace([' '], [','], $searchTags));
                    foreach ($tagArray as $tag) {
                        if (trim($tag) != '') {
                            $query->andWhere('LOCATE(:tags, tags) !=0', [':tags'=>$searchTags]);
                        }
                    }
                }

                $countQuery = clone $query;
                $pagination = new Pagination([
                    'page'=>$searchPage,
                    'totalCount' => $countQuery->count(),
                    'pageSize'=>25,
                ]);

                $theDays = $query
                    ->orderBy('title')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->asArray()
                    ->all();

                $prevPage = $pagination->page == 0 ? false : $pagination->page - 1;
                $nextPage = $pagination->page + 1 >= $pagination->pageCount ? false : $pagination->page + 1;

                $result = [
                    'page'=>$pagination->page,
                    'prev_page'=>$prevPage,
                    'next_page'=>$nextPage,
                    'data'=>[],
                ];
                foreach ($theDays as $day) {
                    $result['data'][] = [
                        'is'=>'day',
                        'id'=>$day['id'],
                        'prog_id'=>0,
                        'title'=>$day['title'],
                        'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                        'meals'=>$day['meals'],
                        'meals'=>$day['meals'],
                        'guides'=>$day['guides'],
                        'transport'=>$day['transport'],
                    ];
                }                
            } elseif ($searchIn == 'ap' || $searchIn == 'mp') {
                // All programs
                $query = Product::find()
                    ->select(['id', 'title', 'day_ids', 'day_from', 'updated_at', 'updated_by', 'op_status', 'op_code', 'pax', 'about'])
                    ->andWhere(['language'=>$searchLang]);
                if ($searchIn == 'mp') {
                    $query->andWhere(['updated_by'=>USER_ID]);
                }

                if ($searchB2cb == 'b2c') {
                    $query->andWhere(['offer_type'=>['private', 'combined2016']]);
                } else {
                    $query->andWhere(['offer_type'=>['agent', 'b2b-prod']]);
                }

                if (strlen($searchName) > 2) {
                    $query->andWhere(['or', ['op_code'=>$searchName], ['like', 'title', $searchName], ['like', 'about', $searchName]]);
                }

                if (strlen($searchTags) > 2) {
                    $tagArray = explode(',', str_replace([' '], [','], $searchTags));
                    foreach ($tagArray as $tag) {
                        if (trim($tag) != '') {
                            $query->andWhere('LOCATE(:tags, tags) !=0', [':tags'=>$searchTags]);
                        }
                    }
                }
                
                $countQuery = clone $query;
                $pagination = new Pagination([
                    'page'=>$searchPage,
                    'totalCount' => $countQuery->count(),
                    'pageSize'=>25,
                ]);

                $thePrograms = $query
                    ->orderBy('day_from DESC, updated_at DESC')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->with([
                        'days'=>function($q) {
                            return $q->select(['id', 'rid', 'name', 'body', 'meals', 'guides', 'transport']);
                        },
                        'updatedBy'=>function($q) {
                            return $q->select(['id', 'name'=>'nickname']);
                        },
                    ])
                    ->asArray()
                    ->all();

                $prevPage = $pagination->page == 0 ? false : $pagination->page - 1;
                $nextPage = $pagination->page + 1 >= $pagination->pageCount ? false : $pagination->page + 1;

                $result = [
                    'page'=>$pagination->page,
                    'prev_page'=>$prevPage,
                    'next_page'=>$nextPage,
                    'data'=>[],
                ];

                foreach ($thePrograms as $prog) {
                    $result['data'][] = [
                        'is'=>'prog',
                        'id'=>$prog['id'],
                        'title'=>$prog['title'],
                        'about'=>$prog['about'],
                        'start_date'=>date('j/n/Y', strtotime($prog['day_from'])),
                        'pax_count'=>$prog['pax'],
                        'day_count'=>count(array_filter(explode(',', $prog['day_ids']))),
                        'op_code'=>$prog['op_status'] == 'op' ? $prog['op_code'] : '',
                        'updated_by_name'=>$prog['updatedBy']['name'],
                        'updated_at_time'=>date('j/n/Y', strtotime($prog['updated_at'])),
                    ];

                    $dayIdList = explode(',', $prog['day_ids']);
                    foreach ($dayIdList as $dayId) {
                        foreach ($prog['days'] as $day) {
                            if ($day['id'] == $dayId) {
                                $result['data'][] = [
                                    'is'=>'day',
                                    'id'=>$day['id'],
                                    'prog_id'=>$prog['id'],
                                    'title'=>$day['name'],
                                    'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                                    'meals'=>$day['meals'],
                                    'guides'=>$day['guides'],
                                    'transport'=>$day['transport'],
                                ];
                            }
                        }
                    }
                }                
            } else {
                exit;
                // All programs
                $theDays = \common\models\Day::find()
                    ->select(['id', 'name', 'meals', 'body'])
                    ->andWhere(['like', 'name', $searchFor])
                    ->orderBy('name')
                    ->limit(30)
                    ->asArray()
                    ->all();
                $result = [];
                foreach ($theDays as $day) {
                    $result[] = [
                        'id'=>$day['id'],
                        'title'=>$day['name'],
                        'body'=>substr($day['body'], 0, 1) == '<' ? $day['body'] : $parser->parse($day['body']),
                        'meals'=>$day['meals'],
                    ];
                }
            }
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }

        if ($action == 'edit-day' && Yii::$app->request->isAjax) {
            if (isset($_POST['Day']['id']) && $_POST['Day']['id'] != 0) {
                $theDay = Day::findOne($_POST['Day']['id']);
                $theDay->scenario = 'day/u';
            } else {
                $theDay = new Day;
                $theDay->scenario = 'day/c';
            }
            if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
                // \Yii::$app->response->format = Response::FORMAT_JSON;
                // \fCore::expose($_POST);
                // \fCore::expose($theDay);
                // echo 'ID=', $theDay->id; return false;
                if ($theDay->save(false)) {
                    return 'Success';
                } else {
                    throw new HttpException (401, 'Error saving data');
                }
            }
        }

        if ($action == 'sort-day') {
            if (isset($_POST['ngay']) && in_array(USER_ID, [$theProgram['created_by'], $theProgram['updated_by']])) {
                $sql = 'UPDATE at_ct SET day_ids = :di WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [':di'=>implode(',', $_POST['ngay']), ':id'=>$theProgram['id']])->execute();
                return true;
            } else {
                throw new HttpException(403, 'Access denied.');
            }
        }

        if ($action == 'delete-day' && isset($_POST['day']) && in_array(USER_ID, [$theProgram['created_by'], $theProgram['updated_by']])) {
            // Delete day
            $sql = 'DELETE FROM at_days WHERE rid=:id AND id=:day LIMIT 1';
            Yii::$app->db->createCommand($sql, [':id'=>$theProgram['id'], ':day'=>$dayIdList[$_POST['day']]])->execute();
            // day = cnt thu tu ngay, khong phai ID
            unset($dayIdList[$_POST['day']]);
            // Save product
            $sql = 'UPDATE at_ct SET day_ids=:di WHERE id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [':di'=>implode(',', $dayIdList), ':id'=>$theProgram['id']])->execute();
            return true;   
        }

        // Add blank after
        if ($action == 'add-blank-day' && isset($_POST['at']) && in_array(USER_ID, [$theProgram['created_by'], $theProgram['updated_by']])) {
            $theDay->created_at = NOW;
            $theDay->created_by = USER_ID;
            $theDay->updated_at = NOW;
            $theDay->updated_by = USER_ID;
            $theDay->rid = $theProgram['id'];
            $theDay->meals = '---';
            $theDay->name = '( no title )';
            $theDay->body = '';
            $theDay->note = '';
            $theDay->image = '';
            if ($theDay->save(false)) {
                array_splice($dayIdList, (int)$_POST['at'], 0, $theDay->id);
            
                // Save product
                $sql = 'UPDATE at_ct SET day_ids=:di WHERE id=:id LIMIT 1';
                Yii::$app->db->createCommand($sql, [':di'=>implode(',', $dayIdList), ':id'=>$theProgram['id']])->execute();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['id'=>$theDay->id];
            }

            throw new HttpException(401, 'Could not add day.');
        }

        // Chua co code
        if ($theTour && $theProgram['op_code'] != $theTour['code']) {
            Yii::$app->db->createCommand()
                ->update('at_ct', [
                    'op_status'=>'op',
                    'op_code'=>$theTour['code'],
                    'op_name'=>$theTour['name'],
                    ], ['id'=>$id])
                ->execute();
        }

        $metaData = [];
        $sql = 'SELECT * FROM at_meta WHERE rtype="product" AND rid=:id AND SUBSTRING(k,1,3)="td/" ORDER BY k';
        $metas = Yii::$app->db->createCommand($sql, [':id'=>$theProgram['id']])->queryAll();

        foreach ($metas as $meta) {
            $items = explode('|', $meta['v']);
            if (count($items) == 4) {
                $metaData[] = $items;
            }
        }

        return $this->render($theProgram['offer_type'] == 'b2b-prod' ? 'program_r_b2bprod' : 'program_r', [
            'theProgram'=>$theProgram,
            'theDay'=>$theDay,
            'theDays'=>$theDays,
            'extraDays'=>$extraDays ?? [],
            'theCases'=>$theCases,
            'theTour'=>$theTour,
            'metaData'=>$metaData,
        ]);
    }

    // Mot so thao tac khi lam ct
    public function actionRr($action = '', $ct = 0, $id = 0)
    {
        $theProgram = Product::find()
            ->where(['id'=>$ct])
            ->with(['days'])
            ->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found');
        }

        if (!in_array(USER_ID, [1, $theProgram['created_at']])) {
            throw new HttpException(403, 'Access denied');
        }

        if ($action == 'day-add-blank-after') {
            if ($id == 0) {
                die('ID NOT FOUND');
            }
            $newDay = new Day;
            $newDay->created_at = NOW;
            $newDay->created_by = USER_ID;
            $newDay->updated_at = NOW;
            $newDay->updated_by = USER_ID;
            $newDay->rid = $theProgram['id'];
            $newDay->meals = '---';
            if ($newDay->save(false)) {
                $dayIdNewList = [$newDayId];
                foreach ($dayIdList as $id) {
                    $dayIdNewList[] = $id;
                }
                $dayIdNew = implode(',', $dayIdNewList);

                $theProgram->day_ids = $dayIdNew;
                $theProgram->save(false);


                return $this->redirect('@web/products/r/'.$theProgram['id'].'#ngay-'.$newDay->id);
            }
        }
    }

    public function actionOp($id = 0)
    {
        $theProgram = Product::find()
            ->where(['id'=>$id])
            ->with(['bookings', 'bookings.pax'])
            ->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found');
        }

        // Tim tour neu co
        $theTour = Tour::find()
            ->where(['ct_id'=>$id])
            ->one();

        if (!$theTour) {
            return $this->render('products_op_nop', [
                'theProgram'=>$theProgram,
            ]);
        } else {
            return $this->redirect('@web/tours/r/'.$theTour['id']);
        }
    }

    public function actionSb($id = 0)
    {
        // The product
        $theProgram = Product::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theProgram) {
            throw new HttpException(404, 'Product not found.');
        }

        // The booking
        $theBooking = Booking::find()
            ->where(['product_id'=>$id])
            ->with([
                'createdBy',
                'updatedBy',
                'product',
                'product.tour',
                'product.days',
                'case',
                'case.owner',
                'invoices'=>function($q) {
                    return $q->orderBy('due_dt');
                },
                'payments',
                'people',
            ])
            ->asArray()
            ->one();
        if (!$theBooking) {
            throw new HttpException(404, 'Booking not found');
        }

        return $this->redirect(['booking/r', 'id'=>$theBooking['id']]);

        $bookingOwner = Person::find()
            ->where(['id'=>$theBooking['created_by']])
            ->asArray()
            ->one();

        if (isset($theBooking['product']['tour']['id'])) {
            $tourPeople = Yii::$app->db
                ->createCommand('SELECT u.email, u.fname, u.lname FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id=:id', [':id'=>$theBooking['product']['tour']['id']])
                ->queryAll();
        }

        $theInvoice = new Invoice();

        $thePayment = new Payment;      
        $thePayment->scenario = 'payments_c';

        if ($thePayment->load(Yii::$app->request->post()) && $thePayment->validate()) {

            $thePayment->booking_id = $theBooking['id'];
            $thePayment->created_at = NOW;
            $thePayment->created_by = USER_ID;
            $thePayment->updated_at = NOW;
            $thePayment->updated_by = USER_ID;
            $thePayment->status = 'on';

            if ($thePayment->save(false)) {
                if ($bookingOwner) {
                    $args = [
                        ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                        ['to', $bookingOwner['email'], $bookingOwner['lname'], $bookingOwner['fname']],
                        ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                        // ['attachment', 'filePath', 'fileName'],
                    ];
                    if (isset($tourPeople) && !empty($tourPeople)) {
                        foreach ($tourPeople as $user) {
                            $args[] = ['cc', $user['email'], $user['lname'], $user['fname']];
                        }
                    }
                    $this->mgIt(
                        '[ims] Payment received: '.$thePayment['ref'].' / '.$thePayment['method'].' / '.number_format($thePayment['amount'], 0).' '.$thePayment['currency'],
                        '//payment_received',
                        [
                            'thePayment'=>$thePayment,
                            'theBooking'=>$theBooking,
                        ],
                        $args
                    );
                }

                Yii::$app->session->setFlash('success', 'Payment has been added: '.number_format($thePayment['amount'], 2).' '.$thePayment['currency']);
                return $this->redirect('@web/bookings/r/'.$theBooking['id']);
            }
        }

        // Delete pax from booking
        if (isset($_GET['action']) && $_GET['action'] == 'delete-user-booking' && isset($_GET['user_id'])) {
            // Huan, CSKH
            if (in_array(USER_ID, [1, 7756, 9881, 1351])) {
                Yii::$app->db->createCommand()
                    ->delete('at_booking_user', [
                        'booking_id'=>$theBooking['id'],
                        'user_id'=>$_GET['user_id'],
                    ])
                    ->execute();
                return $this->redirect('@web/bookings/r/'.$theBooking['id']);
            }
        }

        // Cancel pax from booking
        if (isset($_GET['action']) && $_GET['action'] == 'cancel-user-booking' && isset($_GET['user_id'])) {
            // Huan, CSKH
            if (in_array(USER_ID, [1, 7756, 9881, 1351])) {
                Yii::$app->db->createCommand()
                    ->update('at_booking_user',
                        [
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'status'=>'canceled',
                        ], [
                        'booking_id'=>$theBooking['id'],
                        'user_id'=>$_GET['user_id'],
                        ]
                    )
                    ->execute();
                return $this->redirect('@web/bookings/r/'.$theBooking['id']);
            }
        }

        // Add pax
        if (isset($_POST['action']) && $_POST['action'] == 'add-pax' && isset($_POST['name'])) {
            // Yii::$app->session->remove('searchUsers');
            $name = trim($_POST['name']);
            if ((int)$name > 0) {
                $theUsers = Person::find()
                    ->where(['id'=>$name])
                    ->all();
            } elseif (false !== strpos($name, '@')) {
                $theUsers = Person::findBySql('SELECT u.* FROM persons u, at_meta m WHERE m.rtype="user" AND m.rid=u.id AND m.k="email" AND m.v=:email', [':email'=>$name])
                    ->asArray()
                    ->all();
            } else {
                $theUsers = Person::find()
                    ->where(['name'=>$name])
                    ->orWhere('CONCAT(fname, " ", lname)=:name', [':name'=>$name])
                    ->orWhere('CONCAT(lname, " ", fname)=:name', [':name'=>$name])
                    ->asArray()
                    ->all();
            }
            if (!$theUsers) {
                if (strpos($name, ' ') !== false && strlen($name) > 6) {
                    // Add pax if this is a name First Last
                    $newUser = new Person;
                    $newUser->created_at = NOW;
                    $newUser->created_by = USER_ID;
                    $newUser->uo = NOW;
                    $newUser->ub = USER_ID;
                    $newUser->status = 'on';
                    $newUser->name = $name;
                    if ($newUser->save(false)) {
                        Yii::$app->db->createCommand()
                            ->insert('at_booking_user', [
                                'created_at'=>NOW,
                                'created_by'=>USER_ID,
                                'updated_at'=>NOW,
                                'updated_by'=>USER_ID,
                                'booking_id'=>$theBooking['id'],
                                'user_id'=>$newUser['id'],
                                ])
                            ->execute();
                        //return $this->redirect('@web/users/u/'.$newUser['id']);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'User not found: #'.$name. '. A new pax name must be of format "First Last" and longer than 6 characters.');
                }
            } else {
                if (count($theUsers) == 1) {
                    Yii::$app->db->createCommand()
                        ->insert('at_booking_user', [
                            'created_at'=>NOW,
                            'created_by'=>USER_ID,
                            'updated_at'=>NOW,
                            'updated_by'=>USER_ID,
                            'booking_id'=>$theBooking['id'],
                            'user_id'=>$theUsers[0]['id'],
                            ])
                        ->execute();
                } else {
                    $searchUsers = $theUsers;
    if (!empty($searchUsers)) {
        echo '<div class="alert alert-info"><strong>The following users were found with same name / email</strong>';
        foreach ($searchUsers as $user) {
            echo '<br>ID: <a href="/users/r/', $user['id'], '">', $user['id'], '</a> | Name: ', $user['fname'], ' / ', $user['fname'], ' (', $user['name'], ')';
        }
        echo '</div>';
        exit;
        die('Insert one of user IDs above or add new user by adding a plus sign before name, eg. "+Nguyen Van A"');
    }
                    Yii::$app->session->set('searchUsers', $theUsers);
                }
            }
            return $this->redirect('@web/bookings/r/'.$theBooking['id']);
        }

        $thePeople = Yii::$app->db->createCommand('SELECT u.id, u.fname, u.lname, u.byear, u.email, u.gender, u.country_code, u.name, bu.status FROM persons u, at_booking_user bu WHERE bu.user_id=u.id AND bu.booking_id=:id ORDER BY bu.status', [':id'=>$theBooking['id']])
            ->queryAll();

        $methodList = Yii::$app->db->createCommand('SELECT method FROM at_payments GROUP BY method ORDER BY method')
            ->queryAll();

        return $this->render('bookings_r', [
            'theProgram'=>$theProgram,
            'theBooking'=>$theBooking,
            'thePeople'=>$thePeople,
            'theInvoice'=>$theInvoice,
            'thePayment'=>$thePayment,
            'methodList'=>$methodList,
        ]);
    }

    // Update B2b prod
    private function updateB2bProd($id = 0)
    {
        $theProgram = Product::findOne($id);
        if (!in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) {
            throw new HttpException(403, 'Access denied. You are not the owner.');
        }

        $theProgram->scenario = 'product/u/b2bprod';
        if ($theProgram->load(Yii::$app->request->post()) && $theProgram->validate()) {
            $theProgram->updated_at = NOW;
            $theProgram->updated_by = USER_ID;
            if ($theProgram->save(false)) {
                Yii::$app->session->setFlash('success', 'Product has been updated: '.$theProgram['title']);
                return $this->redirect('@web/products/r/'.$theProgram['id']);
            }
        }

        $theDays = [];

        return $this->render('product_u_b2bprod', [
            'theProgram'=>$theProgram,
            'theDays'=>$theDays,
        ]);
    }

    public function actionU($id = 0)
    {
        $theProgram = Product::find()
            ->where(['id'=>$id, 'owner'=>'si'])
            ->with([
                'bookings'=>function($q) {
                    return $q->select(['id', 'product_id', 'case_id']);
                },
                'bookings.case'=>function($q) {
                    return $q->select(['id', 'owner_id']);
                },
                ])
            ->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found.');
        }

        if ($theProgram['offer_type'] == 'b2b-prod') {
            // return $this->updateB2bProd($id);
        }

        // 161003 Allows any of case owners to edit CT
        $editableList = [1, 4432, $theProgram['created_by'], $theProgram['updated_by']];
        foreach ($theProgram['bookings'] as $booking) {
            $editableList[] = $booking['case']['owner_id'];
        }

        if (!in_array(USER_ID, $editableList)) {
            throw new HttpException(403, 'Access denied. You are not the owner.');
        }

        if ($theProgram['offer_count'] > 0) {
            // Since 140412
            // throw new HttpException(403, 'Access denied. Cannot edit proposed itinerary.');
        }

        $theProgram->scenario = 'products_u';

        $days = Day::find()
            ->select(['id', 'name', 'meals'])
            ->where(['rid'=>$theProgram['id']])
            ->all();
        $theDays = [];
        $dayIds = explode(',', $theProgram['day_ids']);
        if (!empty($dayIds)) {
            foreach ($dayIds as $id) {
                foreach ($days as $day) {
                    if ($day['id'] == $id) {
                        $theDays[] = $day;
                    }
                }
            }
        }

        if ($theProgram->load(Yii::$app->request->post()) && $theProgram->validate()) {
            $theProgram->updated_at = NOW;
            $theProgram->updated_by = USER_ID;
            if ($theProgram->save(false)) {
                Yii::$app->session->setFlash('success', 'Product has been updated: '.$theProgram['title']);
                return $this->redirect('@web/b2b/programs/r/'.$theProgram['id']);
            }
        }

        return $this->render('program_u', [
            'theProgram'=>$theProgram,
            'theDays'=>$theDays,
        ]);

        

/*
        Yii::$app->session->set('ckfinder_authorized', true);
        Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@web').'/upload/web/'.substr($model->created_at, 0, 7));
        Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').'/upload/web/'.substr($model->created_at, 0, 7));
        Yii::$app->session->set('ckfinder_role', 'user');
        Yii::$app->session->set('ckfinder_thumbs_dir', 'web/'.substr($model->created_at, 0, 7));
        Yii::$app->session->set('ckfinder_resource_name', 'web');
*/
    }

    public function actionUOp($id = 0) {
        $theProgram = Product::findOne($id);
        if (!$theProgram) {
            throw new HttpException(404, 'Product not found');
        }

        $theProgram->scenario = 'products_u-op';

        if ($theProgram->load(Yii::$app->request->post())) {
            if ($theProgram->save()) {
                Yii::$app->session->setFlash('success', 'Product has been updated: '.$theProgram['name']);
                return $this->redirect('@web/products/op/'.$theProgram['id']);
            }
        }

        return $this->render('products_u-op', [
            'theProgram'=>$theProgram,
        ]);
    }

    // Delete a product
    public function actionD($id = 0) {
        $theProgram = Product::find()
            ->where(['id'=>$id])
            ->with(['bookings'=>function($q){
                return $q->select(['id', 'product_id']);
                }
            ])
            ->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found.');
        }

        if ($theProgram['created_by'] != USER_ID) {
            throw new HttpException(403, 'Access denied. You are not the owner.');
        }

        if (!empty($theProgram['bookings'])) {
            throw new HttpException(403, 'Access denied. There are existing bookings for this product. You have to delete those bookings first.');
        }

        if (!$theProgram['op_status'] == 'op') {
            throw new HttpException(403, 'Access denied. This product is operational. You can only cancel its operation.');
        }

        if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
            // Delete days
            Day::deleteAll(['rid'=>$theProgram['id']]);
            // Delete pdf file
            @unlink(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$rId.'.pdf');
            // Delete new upload files
            if (file_exists(Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'])) {
                \yii\helpers\FileHelper::removeDirectory(Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id']);
            }
            // Delete product
            $theProgram->delete();

            Yii::$app->session->setFlash('success', 'Product has been deleted: '.$theProgram['title']);
            return $this->redirect('@web/products?ub='.USER_ID);
        }

        return $this->render('products_d', [
            'theProgram'=>$theProgram
        ]);
    }

    public function actionDownload($id = 0, $type = '', $file = '')
    {
        // Download PDF file
        $theProgram = Product::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found.');
        }

        if ($type == 'pdf' && $file != '') {
            $filePath = Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'].'/pdf/'.$file;
            $fileName = $file;
            if (!file_exists($filePath)) {
                throw new HttpException(404, 'File not found.');
            }
            return Yii::$app->response->sendFile($filePath, $fileName);
        }

        if ($type == 'image' && $file != '') {
            $filePath = Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'].'/image/'.$file;
            $fileName = $file;
            if (!file_exists($filePath)) {
                throw new HttpException(404, 'File not found.');
            }
            return Yii::$app->response->sendFile($filePath, $fileName);
        }

        if ($type == 'excel' && $file != '') {
            $filePath = Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'].'/excel/'.$file;
            $fileName = $file;
            if (!file_exists($filePath)) {
                throw new HttpException(404, 'File not found.');
            }
            return Yii::$app->response->sendFile($filePath, $fileName);
        }

        $filePath = Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$theProgram['id'].'.pdf';
        if (!file_exists($filePath)) {
            throw new HttpException(404, 'Product file (PDF) not found.');
        }

        $fileName = $theProgram['title'].' '.date('Ymd-Hi', strtotime($theProgram['created_at'])).'.pdf';
        return Yii::$app->response->sendFile($filePath, $fileName);
    }

    public function actionCopy($id = 0)
    {
        $theProgram = Product::find()
            ->where(['id'=>$id])
            ->with(['days'])
            ->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found.');
        }

        $newProduct = new Product;
        $newProduct->scenario = 'products_copy';

        if ($newProduct->load(Yii::$app->request->post()) && $newProduct->validate()) {
            $newProduct->offer_type = $theProgram->offer_type == 'b2b-prod' ? 'agent' : $theProgram->offer_type;
            $newProduct->about = $theProgram->about;
            $newProduct->day_count = 0;//$theProgram->day_count;
            $newProduct->day_from = $theProgram->day_from;
            $newProduct->pax = $theProgram->pax;
            $newProduct->intro = $theProgram->intro;
            $newProduct->esprit = $theProgram->esprit;
            $newProduct->points = $theProgram->points;
            $newProduct->conditions = $theProgram->conditions;
            $newProduct->others = $theProgram->others;
            $newProduct->tags = $theProgram->tags;
            $newProduct->promo = $theProgram->promo;
            $newProduct->price = $theProgram->price;
            $newProduct->price_unit = $theProgram->price_unit;
            $newProduct->price_for = $theProgram->price_for;
            $newProduct->price_until = $theProgram->price_until;
            $newProduct->prices = $theProgram->prices;
            $newProduct->image = $theProgram->image;
            $newProduct->language = $theProgram->language;
            $newProduct->owner = $theProgram->owner;

            $newProduct->created_at = NOW;
            $newProduct->created_by = USER_ID;
            $newProduct->updated_at = NOW;
            $newProduct->updated_by = USER_ID;
            $newProduct->status = 'on';
            $newProduct->op_status = 'nop';
            $newProduct->op_code = '';
            $newProduct->op_name = '';
            $newProduct->uid = Yii::$app->security->generateRandomString();
            $newProduct->offer_count = 0;
            $newProduct->day_ids = '';

            if ($newProduct->save(false)) {
                // Save days
                $dayIdList = explode(',', $theProgram['day_ids']);
                if (!$dayIdList) {
                    $dayIdList = [];
                }

                $newDayIds = ',';
                $newDayCount = 0;
                foreach ($dayIdList as $id) {
                    foreach ($theProgram['days'] as $day) {
                        if ($day['id'] == $id) {
                            $newDay = new Day;
                            $newDay->scenario = 'products_copy';

                            $newDay->created_at = NOW;
                            $newDay->created_by = USER_ID;
                            $newDay->updated_at = NOW;
                            $newDay->updated_by = USER_ID;
                            $newDay->status = 'on';
                            $newDay->rid = $newProduct['id'];

                            // TODO: day as option

                            $newDay->name = $day['name'];
                            $newDay->step = $day['step'];
                            $newDay->day = $day['day'];
                            $newDay->body = $day['body'];
                            $newDay->image = $day['image'];
                            $newDay->meals = $day['meals'];
                            $newDay->guides = $day['guides'];
                            $newDay->transport = $day['transport'];
                            $newDay->note = $day['note'];

                            if ($newDay->save(false)) {
                                $newDayIds .= ','.$newDay['id'];
                                $newDayCount ++;
                            }
                        }
                    }
                }

                $newProduct->day_ids = trim($newDayIds, ',');
                $newProduct->day_count = $newDayCount;
                $newProduct->save(false);

                $metaData = [];
                $sql = 'SELECT * FROM at_meta WHERE rtype="product" AND rid=:id AND SUBSTRING(k,1,3)="td/" ORDER BY k';
                $metas = Yii::$app->db->createCommand($sql, [':id'=>$theProgram['id']])->queryAll();

                foreach ($metas as $meta) {
                    Yii::$app->db->createCommand()->insert('at_meta', [
                        'uo'=>NOW,
                        'ub'=>USER_ID,
                        'rtype'=>'product',
                        'rid'=>$newProduct['id'],
                        'k'=>$meta['k'],
                        'v'=>$meta['v'],
                    ])->execute();
                }

                return $this->redirect('@web/products/r/'.$newProduct['id']);
            }
        }

        return $this->render('products_copy', [
            'theProgram'=>$theProgram,
            'newProduct'=>$newProduct,
        ]);
    }

    public function actionUpload($id = 0, $action = '', $type = '', $file = '')
    {
        // Upload PDF file
        $theProgram = Product::find()->where(['id'=>$id])->asArray()->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found.');
        }

        if ($action == 'delete' && $type == 'oldpdf') {
            if (!in_array(USER_ID, [1, $theProgram['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }
            unlink(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$theProgram['id'].'.pdf');
            return $this->redirect(DIR.URI);
        }

        if ($action == 'delete' && $type == 'pdf' && $file != '') {
            if (!in_array(USER_ID, [1, $theProgram['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }
            unlink(Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'].'/pdf/'.$file);
            return $this->redirect(DIR.URI);
        }

        if ($action == 'delete' && $type == 'image' && $file != '') {
            if (!in_array(USER_ID, [1, $theProgram['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }
            unlink(Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'].'/image/'.$file);
            return $this->redirect(DIR.URI);
        }

        if ($action == 'delete' && $type == 'excel' && $file != '') {
            if (!in_array(USER_ID, [1, $theProgram['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }
            unlink(Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'].'/excel/'.$file);
            return $this->redirect(DIR.URI);
        }

        $model = new \app\models\ProductUploadForm;
        $model->productId = $theProgram['id'];
        if (Yii::$app->request->isPost) {
            $model->pdfFiles = UploadedFile::getInstances($model, 'pdfFiles');
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            $model->excelFiles = UploadedFile::getInstances($model, 'excelFiles');
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->redirect('/products/r/'.$theProgram['id']);
            }
        }

        return $this->render('products_upload', [
            'theProgram'=>$theProgram,
            'model'=>$model,
        ]);
    }

    public function actionPrint($id = 0)
    {
        // Make print version for Word/Pdf
        $theProgram = Product::find()->where(['id'=>$id])->with(['days', 'createdBy'])->asArray()->one();

        if (!$theProgram) {
            throw new HttpException(404, 'Product not found.');
        }

        return $this->renderPartial('products_print', [
            'theProgram'=>$theProgram,
        ]);
    }
}
