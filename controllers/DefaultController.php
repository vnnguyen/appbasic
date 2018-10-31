<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

use app\models\BookingPaxForm;
use app\models\BookingRoomForm;
// use app\models\BookingVisaForm;
use app\models\BookingFlightForm;
// use app\models\BookingSubmitForm;

use app\models\Booking;
use app\common\models\AtCase;
// use app\common\models\Product;
use app\models\User;
use app\models\AtCountries;

use app\models\UploadForm;
use yii\web\UploadedFile;

// use Mailgun\Mailgun;

class DefaultController extends Controller
{
    public $bookingId = 0;
    public $userId = 0;

    public function behaviors() {
        return [
            'AccessControl' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions'=>['index', 'select-lang'],
                        'allow'=>true,
                    ], [
                        'allow'=>true,
                        // 'roles'=>['@'],
                    ],
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'width' => 100,
                'height' => 34,
                'foreColor' => 0xC74CA3,
                'minLength' => 4,
                'maxLength' => 4,
                'offset' => 2,
                'transparent' => true,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('home', [
        ]);
    }

    public function actionAccount()
    {
        return $this->render('//undercon');
    }

    public function actionTours()
    {
        $myCaseIdList = [];

        // Cac booking (cfm hoac cxl)
        $sql = 'SELECT case_id FROM at_cases k, at_bookings b, at_booking_user bu WHERE k.id=b.case_id AND b.id=bu.booking_id AND user_id=:id';
        $results = Yii::$app->db->createCommand($sql, [':id'=>USER_ID])->queryAll();
        foreach ($results as $result) {
            $myCaseIdList[] = $result['case_id'];
        }

        // Cac ho so (pnd/cfm)
        $sql = 'SELECT case_id FROM at_case_user WHERE role="contact" AND user_id=:id';
        $results = Yii::$app->db->createCommand($sql, [':id'=>USER_ID])->queryAll();
        foreach ($results as $result) {
            $myCaseIdList[] = $result['case_id'];
        }

        $myCaseIdList = array_unique($myCaseIdList);

        $theCases = Kase::find()
            ->where(['id'=>$myCaseIdList])
            ->with([
                'owner',
                'stats',
                ])
            ->orderBy('created_at DESC')
            ->asArray()
            ->all();

        return $this->render('tours', [
            'theCases'=>$theCases,
        ]);
    }
    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            var_dump($model->attributes = Yii::$app->request->post('UploadForm'));die('ok');
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }

        return $this->render('abc', ['model' => $model]);
    }

    public function actionToursView($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        return $this->render('tours_view', [
            'theCase'=>$theCase,
        ]);
    }

    public function actionBookingRegistrationVisas($token = '', $action = 'list', $roomid = 0)
    {
        $result = $this->checkAccess($token);

        $theBooking = Booking::find()
            ->with(['createdBy'])
            ->with(['people'])
            ->where(['id'=>$result['booking_id']])
            ->asArray()
            ->one();

        if (!$theBooking) {
            throw new HttpException(404);
        }

        if (substr(md5($theBooking['created_at']), -4) != $result['booking_md5']) {
            throw new HttpException(404);
        }

        $theUser = User::find()
            ->where(['id'=>$result['user_id']])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404);
        }

        if (substr(md5($theUser['created_at']), -4) != $result['user_md5']) {
            throw new HttpException(404);
        }

        $theProduct = Product::find()
            ->where(['id'=>$theBooking['product_id']])
            ->with([
                'bookings',
                'bookings.people',
                'bookings.case',
                'days',
                'updatedBy',
            ])
            ->asArray()
            ->one();

        if (!$theProduct) {
            throw new HttpException(404);
        }

        return $this->render('booking_registration_visas', [
            'theBooking'=>$theBooking,
            'theProduct'=>$theProduct,
            /*'theForm'=>$theForm,
            'action'=>$action,
            'roomid'=>$roomid,
            'theRooms'=>$theRooms,
            'theTravellers'=>$theTravellers,*/
        ]);
    }

    public function actionBookingRegistrationFlights($token = '', $action = 'list', $flightid = 0)
    {
        $result = $this->checkAccess($token);

        $theBooking = Booking::find()
            ->with([
                'createdBy',
                'people',
                'product',
                'case',
                'case.owner',
                ])
            ->where(['id'=>$result['booking_id']])
            ->asArray()
            ->one();

        if (!$theBooking) {
            throw new HttpException(404);
        }

        if (substr(md5($theBooking['created_at']), -4) != $result['booking_md5']) {
            //throw new HttpException(404);
        }

        $theUser = User::find()
            ->where(['id'=>$result['user_id']])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404);
        }

        if (substr(md5($theUser['created_at']), -4) != $result['user_md5']) {
            //throw new HttpException(404);
        }

        $sql = 'SELECT id, name FROM at_booking_pax WHERE booking_id=:booking_id';
        $bookingPax = Yii::$app->db->createCommand($sql, [':booking_id'=>$theBooking['id']])->queryAll();

        $sql = 'SELECT * FROM at_booking_flights WHERE booking_id=:booking_id ORDER BY departure_dt';
        $bookingFlights = Yii::$app->db->createCommand($sql, [':booking_id'=>$theBooking['id']])->queryAll();

        if ($action == 'delete') {
            Yii::$app->db->createCommand()->delete('at_booking_flights', [
                'id'=>$flightid, 'booking_id'=>$theBooking['id'], 'created_by'=>$theUser['id'],
            ])->execute();
            Yii::$app->session->setFlash('success', Yii::t('reg', 'Information has been deleted'));
            return $this->redirect(DIR.URI);
        }

        $theForm = new BookingFlightForm;

        if ($action == 'edit') {
            $sql = 'SELECT * FROM at_booking_flights WHERE booking_id=:booking_id AND id=:id LIMIT 1';
            $theFlight = Yii::$app->db->createCommand($sql, [
                ':booking_id'=>$theBooking['id'],
                ':id'=>$flightid,
                ])->queryOne();
            if (!$theFlight) {
                throw new HttpException(404);
            }
            if (!in_array($theUser['id'], [1, $theFlight['created_by']])) {
                throw new HttpException(403);
            }
            $theForm->attributes = $theFlight;
            $theForm->departure_dt = date('d/m/Y H:i', strtotime($theFlight['departure_dt']));
            $theForm->arrival_dt = date('d/m/Y H:i', strtotime($theFlight['arrival_dt']));
            $theForm->pax_ids = explode(',', $theFlight['pax_ids']);
        }

        if ($theForm->load(Yii::$app->request->post())) {//var_dump($theForm);die();
            $theForm['departure_dt'] = date_format(date_create_from_format('d-m-Y H:i', $theForm['departure_dt']), 'Y-m-d H:i');
            $theForm['arrival_dt'] = date_format(date_create_from_format('d-m-Y H:i', $theForm['arrival_dt']), 'Y-m-d H:i');

            if ($action == 'list') {
                // Add new flight
                Yii::$app->db->createCommand()->insert('at_booking_flights', [
                    'created_dt'=>NOW,
                    'created_by'=>$theUser['id'],
                    'booking_id'=>$theBooking['id'],
                    'stype'=>$theForm['stype'],
                    'number'=>$theForm['number'],
                    'departure_port'=>$theForm['departure_port'],
                    'departure_dt'=>$theForm['departure_dt'],
                    'arrival_port'=>$theForm['arrival_port'],
                    'arrival_dt'=>$theForm['arrival_dt'],
                    'pax_ids'=>isset($theForm['pax_ids']) && is_array($theForm['pax_ids']) ? implode(',', $theForm['pax_ids']) : '',
                    'note'=>$theForm['note'],
                    ])->execute();
            } elseif ($action == 'edit') {
                // Edit flight
                Yii::$app->db->createCommand()->update('at_booking_flights', [
                    'updated_dt'=>NOW,
                    'updated_by'=>$theUser['id'],
                    'stype'=>$theForm['stype'],
                    'number'=>$theForm['number'],
                    'departure_port'=>$theForm['departure_port'],
                    'departure_dt'=>$theForm['departure_dt'],
                    'arrival_port'=>$theForm['arrival_port'],
                    'arrival_dt'=>$theForm['arrival_dt'],
                    'pax_ids'=>isset($theForm['pax_ids']) && is_array($theForm['pax_ids']) ? implode(',', $theForm['pax_ids']) : '',
                    'note'=>$theForm['note'],
                    ], [
                    'id'=>$flightid
                    ])->execute();
            }
            return $this->redirect(DIR.URI);
        }

        return $this->render('booking_registration_flights', [
            'theBooking'=>$theBooking,
            'theUser'=>$theUser,
            'bookingPax'=>$bookingPax,
            'bookingFlights'=>$bookingFlights,
            'theForm'=>$theForm,
            'action'=>$action,
            'flightid'=>$flightid,
        ]);
    }

    public function actionBookingRegistrationRooms($token = '', $action = 'list', $roomid = 0)
    {
        $result = $this->checkAccess($token);

        $theBooking = Booking::find()
            ->with([
                'createdBy',
                'people',
                'product',
                'case',
                'case.owner',
                ])
            ->where(['id'=>$result['booking_id']])
            ->asArray()
            ->one();

        if (!$theBooking) {
            throw new HttpException(404);
        }

        if (substr(md5($theBooking['created_at']), -4) != $result['booking_md5']) {
            //throw new HttpException(404);
        }

        $theUser = User::find()
            ->where(['id'=>$result['user_id']])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404);
        }

        if (substr(md5($theUser['created_at']), -4) != $result['user_md5']) {
            //throw new HttpException(404);
        }

        $sql = 'SELECT * FROM at_booking_rooms WHERE booking_id=:booking_id';
        $bookingRooms = Yii::$app->db->createCommand($sql, [':booking_id'=>$theBooking['id']])->queryAll();

        $sql = 'SELECT * FROM at_booking_pax WHERE booking_id=:booking_id';
        $bookingPax = Yii::$app->db->createCommand($sql, [':booking_id'=>$theBooking['id']])->queryAll();

        if ($action == 'delete') {
            Yii::$app->db->createCommand()->delete('at_booking_rooms', [
                'id'=>$roomid, 'booking_id'=>$theBooking['id'], 'created_by'=>$theUser['id'],
            ])->execute();
            Yii::$app->session->setFlash('success', Yii::t('reg', 'Information has been deleted'));
            return $this->redirect(DIR.URI);
        }

        $theForm = new BookingRoomForm;
        if ($action == 'list') {
            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                Yii::$app->db->createCommand()->insert('at_booking_rooms', [
                    'created_dt'=>NOW,
                    'created_by'=>$theUser['id'],
                    'booking_id'=>$theBooking['id'],
                    'room_type'=>$theForm['room_type'],
                    'pax_ids'=>isset($theForm['pax_ids']) && is_array($theForm['pax_ids']) ? implode(',', $theForm['pax_ids']) : '',
                    'note'=>$theForm['note'],
                ])->execute();
                // Flash
                // Redir
                return $this->redirect(DIR.URI);
            }
        } elseif ($action == 'edit') {
            $sql = 'select * from at_booking_rooms where booking_id=:booking_id AND created_by=:created_by AND id=:room_id limit 1';
            $theRoom = Yii::$app->db->createCommand($sql, [
                ':booking_id'=>$theBooking['id'],
                ':created_by'=>$theUser['id'], 
                ':room_id'=>$roomid
                ])->queryOne();
            if (!$theRoom) {
                throw new HttpException(404);
            }
            $theForm['room_type'] = $theRoom['room_type'];
            $theForm['pax_ids'] = explode(',', $theRoom['pax_ids']);
            $theForm['note'] = $theRoom['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                Yii::$app->db->createCommand()->update('at_booking_rooms', [
                    'updated_dt'=>NOW,
                    'updated_by'=>$theUser['id'],
                    'room_type'=>$theForm['room_type'],
                    'pax_ids'=>isset($theForm['pax_ids']) && is_array($theForm['pax_ids']) ? implode(',', $theForm['pax_ids']) : '',
                    'note'=>$theForm['note'],
                ], [
                    'id'=>$roomid,
                    'created_by'=>$theUser['id'],
                ])->execute();
                // Flash
                // Redir
                return $this->redirect(DIR.URI);
            }
        }

        return $this->render('booking_registration_rooms', [
            'theBooking'=>$theBooking,
            'theUser'=>$theUser,
            'theForm'=>$theForm,
            'action'=>$action,
            'roomid'=>$roomid,
            'bookingRooms'=>$bookingRooms,
            'bookingPax'=>$bookingPax,
        ]);
    }

    public function actionBookingRegistration($token = '')
    {
        $result = $this->checkAccess($token);

        $theBooking = Booking::find()
            ->with([
                'createdBy',
                'people',
                'case',
                'case.owner',
                'product',
                ])
            ->where(['id'=>$result['booking_id']])
            ->asArray()
            ->one();
            // var_dump($theBooking);die('ok');
        if (!$theBooking) {
            throw new HttpException(404);
        }

        if (substr(md5($theBooking['created_at']), -4) != $result['booking_md5']) {
            //throw new HttpException(404);
        }

        $theUser = User::find()
            ->where(['id'=>$result['user_id']])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404);
        }

        if (substr(md5($theUser['created_at']), -4) != $result['user_md5']) {
            //throw new HttpException(404);
        }

        // Client page link
        $sql = 'SELECT * FROM at_client_page_links WHERE booking_id=:booking_id AND user_id=:user_id ORDER BY link_sent_dt DESC LIMIT 1';
        $theClientPageLink = Yii::$app->db->createCommand($sql, [
            ':booking_id'=>$theBooking['id'],
            ':user_id'=>$theUser['id'],
        ])->queryOne();

        return $this->render('booking_registration', [
            'theBooking'=>$theBooking,
            'theUser'=>$theUser,
            'theClientPageLink'=>$theClientPageLink,
        ]);
    }

    public function actionBookingRegistrationTravellers($token = '', $action = 'list', $paxid = 0)
    {
        $result = $this->checkAccess($token);

        $theBooking = Booking::find()
            ->with([
                'createdBy',
                'people',
                'product',
                'case',
                'case.owner',
                ])
            ->where(['id'=>$result['booking_id']])
            ->asArray()
            ->one();

        if (!$theBooking) {
            //throw new HttpException(404, 'Not found 1');
        }

        if (substr(md5($theBooking['created_at']), -4) != $result['booking_md5']) {
            //throw new HttpException(404, 'Not found 2');
        }

        $theUser = User::find()
            ->where(['id'=>$result['user_id']])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404, 'Not found 3');
        }

        if (substr(md5($theUser['created_at']), -4) != $result['user_md5']) {
            // throw new HttpException(404, 'Not found 4');
        }

        // The pax
        $sql = 'SELECT id, name, status, data, created_by, is_repeating, passport_file FROM at_booking_pax WHERE booking_id=:booking_id ORDER BY id LIMIT 100';
        $bookingPax = Yii::$app->db->createCommand($sql, [':booking_id'=>$theBooking['id']])->queryAll();

        foreach ($bookingPax as &$pax) {
            $vars = $this->getVarsFrom($pax['data']);
            $pax['vars'] = $vars;
        }

        // All countries
        $countryList = AtCountries::find()->select(['code', 'name'=>'name_'.Yii::$app->language])->orderBy('name_'.Yii::$app->language)->asArray()->all();

        // Edit pax
        $thePax = false;
        if ((int)$paxid != 0 && in_array($action, ['edit', 'delete'])) {
            foreach ($bookingPax as $paxx) {
                if ($paxx['id'] == (int)$paxid) {
                    $thePax = $paxx;
                    break;
                }
            }

            if (!$thePax) {
                throw new HttpException(404, 'Not found 5');
            }

            if ($action == 'delete') {
                if ($thePax['created_by'] == $theUser['id']) {
                    Yii::$app->db->createCommand()->delete('at_booking_pax', ['id'=>$paxid])->execute();
                    Yii::$app->session->setFlash('success', Yii::t('reg', 'Information has been deleted').': '.$thePax['name']);
                    return $this->redirect(DIR.URI);
                }
            }

            $theForm = new BookingPaxForm;

            $theVars = $this->getVarsFrom($thePax['data']);

            $theForm->attributes = $theVars;

            $theForm['name'] = $thePax['name'];
            $theForm['is_repeating'] = $thePax['is_repeating'];
            $theForm['passport_file'] = $thePax['passport_file'];


$text = <<<'TXT'
{{ pp_number | $pp_number }}
{{ pp_country_code | $pp_country_code }}
{{ pp_name_1 | $pp_name_1 }}
{{ pp_name_2 | $pp_name_2 }}
{{ pp_gender | $pp_gender }}
{{ pp_bday | $pp_bday }} / {{ pp_bmonth | $pp_bmonth }} / {{ pp_byear | $pp_byear }}
{{ pp_iday | $pp_iday }} / {{ pp_imonth | $pp_imonth }} / {{ pp_iyear | $pp_iyear }}
{{ pp_eday | $pp_eday }} / {{ pp_emonth | $pp_emonth }} / {{ pp_eyear | $pp_eyear }}

{{ tel_1 | $tel_1 }}
{{ tel_2 | $tel_2 }}
{{ email | $email }}
{{ website | $website }}
{{ profession | $profession }}
{{ place_of_birth | $place_of_birth }}
{{ address | $address }}

{{ visa_vn_arrival | $visa_vn_arrival }},

{{ pay_deposit | $pay_deposit }},
{{ pay_balance | $pay_balance }},

{{ in_name | $in_name }}
{{ in_number | $in_number }}
{{ in_tel | $in_tel }}
{{ in_email | $in_email }}

{{ em_name | $em_name }}
{{ em_relation | $em_relation }}
{{ em_tel | $em_tel }}
{{ em_email | $em_email }}

{{ note | $note }}

TXT;

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                $data = str_replace([
                    '$pp_number',
                    '$pp_country_code',
                    '$pp_name_1',
                    '$pp_name_2',
                    '$pp_gender',
                    '$pp_bday',
                    '$pp_bmonth',
                    '$pp_byear',
                    '$pp_iday',
                    '$pp_imonth',
                    '$pp_iyear',
                    '$pp_eday',
                    '$pp_emonth',
                    '$pp_eyear',
                    '$tel_1',
                    '$tel_2',
                    '$email',
                    '$website',
                    '$profession',
                    '$place_of_birth',
                    '$address',

                    '$visa_vn_arrival',

                    '$pay_deposit',
                    '$pay_balance',

                    '$in_name',
                    '$in_number',
                    '$in_tel',
                    '$in_email',

                    '$em_name',
                    '$em_relation',
                    '$em_tel',
                    '$em_email',
                    '$note',
                    ], [
                    $theForm['pp_number'],
                    $theForm['pp_country_code'],
                    $theForm['pp_name_1'],
                    $theForm['pp_name_2'],
                    $theForm['pp_gender'],
                    $theForm['pp_bday'],
                    $theForm['pp_bmonth'],
                    $theForm['pp_byear'],
                    $theForm['pp_iday'],
                    $theForm['pp_imonth'],
                    $theForm['pp_iyear'],
                    $theForm['pp_eday'],
                    $theForm['pp_emonth'],
                    $theForm['pp_eyear'],
                    $theForm['tel_1'],
                    $theForm['tel_2'],
                    $theForm['email'],
                    $theForm['website'],
                    $theForm['profession'],
                    $theForm['place_of_birth'],
                    $theForm['address'],

                    $theForm['visa_vn_arrival'],

                    $theForm['pay_deposit'],
                    $theForm['pay_balance'],

                    $theForm['in_name'],
                    $theForm['in_number'],
                    $theForm['in_tel'],
                    $theForm['in_email'],

                    $theForm['em_name'],
                    $theForm['em_relation'],
                    $theForm['em_tel'],
                    $theForm['em_email'],
                    $theForm['note'],
                    ], $text);

                Yii::$app->db->createCommand()->update('at_booking_pax', [
                    'updated_dt'=>NOW,
                    'updated_by'=>$theUser['id'],
                    'name'=>$theForm['name'],
                    'is_repeating'=>$theForm['is_repeating'],
                    'passport_file'=>$theForm['passport_file'],
                    'data'=>$data,
                ], [
                    'id'=>$paxid,
                ])->execute();
                return $this->redirect(DIR.URI);
            }
        }

        // New pax
        $postName = Yii::$app->request->post('name', '');
        $postName = trim($postName);
        if ($postName != '' && $action != 'edit') {
            Yii::$app->db->createCommand()->insert('at_booking_pax', [
                'created_dt'=>NOW,
                'created_by'=>$theUser['id'],
                'status'=>'',
                'booking_id'=>$theBooking['id'],
                'user_id'=>0,
                'data'=>'',
                'name'=>$postName,
            ])->execute();
            return $this->redirect(DIR.URI);
        }

        return $this->render('booking_registration_travellers', [
            'theBooking'=>$theBooking,
            'theUser'=>$theUser,
            'bookingPax'=>$bookingPax,
            'thePax'=>$thePax,
            'theForm'=>isset($theForm) ? $theForm : false,
            'countryList'=>$countryList,
            'action'=>$action,
            'paxid'=>$paxid,
        ]);
    }

    public function actionBookingHome($token = '')
    {
        $result = $this->checkAccess($token);

        $theBooking = Booking::find()
            ->with([
                'createdBy',
                'people',
                'product',
                'case',
                'case.owner',
                ])
            //->where(['id'=>$result['booking_id']])
            ->where(['id'=>39144])
            ->asArray()
            ->one();

        if (!$theBooking) {
            throw new HttpException(404, 'None found');
        }

        if (substr(md5($theBooking['created_at']), -4) != $result['booking_md5']) {
            //throw new HttpException(404);
        }

        $theUser = User::find()
            ->where(['id'=>$result['user_id']])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404);
        }

        if (substr(md5($theUser['created_at']), -4) != $result['user_md5']) {
            //throw new HttpException(404);
        }

        $theProduct = Product::find()
            ->where(['id'=>$theBooking['product_id']])
            ->with([
                'bookings',
                'bookings.people',
                'bookings.case',
                'days',
                'updatedBy',
            ])
            ->asArray()
            ->one();
        return $this->render('booking_home', [
            'theBooking'=>$theBooking,
            'theProduct'=>$theProduct,
            'theUser'=>$theUser,
        ]);
    }

    public function actionBookingRegistrationSubmit($token = '')
    {
        $result = $this->checkAccess($token);

        $theBooking = Booking::find()
            ->with([
                'case',
                'case.owner',
                'people',
                'product',
                ])
            ->where(['id'=>$result['booking_id']])
            ->asArray()
            ->one();

        if (!$theBooking) {
            throw new HttpException(404);
        }

        if (substr(md5($theBooking['created_at']), -4) != $result['booking_md5']) {
            //throw new HttpException(404);
        }

        $theUser = User::find()
            ->where(['id'=>$result['user_id']])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404);
        }

        if (substr(md5($theUser['created_at']), -4) != $result['user_md5']) {
            //throw new HttpException(404);
        }

        $sql = 'SELECT id, name FROM at_booking_pax WHERE booking_id=:booking_id';
        $bookingPax = Yii::$app->db->createCommand($sql, [':booking_id'=>$theBooking['id']])->queryAll();

        $sql = 'SELECT * FROM at_booking_flights WHERE booking_id=:booking_id ORDER BY departure_dt';
        $bookingFlights = Yii::$app->db->createCommand($sql, [':booking_id'=>$theBooking['id']])->queryAll();

        $sql = 'SELECT * FROM at_booking_rooms WHERE booking_id=:booking_id ORDER BY room_type';
        $bookingRooms = Yii::$app->db->createCommand($sql, [':booking_id'=>$theBooking['id']])->queryAll();

        $theForm = new BookingSubmitForm;
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            // Change booking reg status to FROZEN
            // Email Amica
            // Say thank you
            Yii::$app->session->setFlash('success', 'Your data has been sent to Amica Travel. Thank you for your time.');
            // Redirect
            return $this->redirect(DIR.SEG1);
        }

        return $this->render('booking_registration_submit', [
            'theBooking'=>$theBooking,
            'theUser'=>$theUser,
            'bookingPax'=>$bookingPax,
            'bookingFlights'=>$bookingFlights,
            'bookingRooms'=>$bookingFlights,
            'theForm'=>$theForm,
        ]);
    }

    public function actionBookingItinerary($token = '')
    {
        $result = $this->checkAccess($token);

        $theBooking = Booking::find()
            ->with([
                'createdBy',
                'people',
                'product',
                'product.days',
                'case',
                'case.owner',
                ])
            ->where(['id'=>$result['booking_id']])
            ->asArray()
            ->one();

        if (!$theBooking) {
            throw new HttpException(404);
        }

        if (substr(md5($theBooking['created_at']), -4) != $result['booking_md5']) {
            throw new HttpException(404);
        }

        $theUser = User::find()
            ->where(['id'=>$result['user_id']])
            ->asArray()
            ->one();

        if (!$theUser) {
            throw new HttpException(404);
        }

        if (substr(md5($theUser['created_at']), -4) != $result['user_md5']) {
            throw new HttpException(404);
        }

        return $this->render('booking_itinerary', [
            'theBooking'=>$theBooking,
            'theUser'=>$theUser,
        ]);
    }

    public function actionSelectLang($lang, $redir = '')
    {
        if (in_array($lang, ['en', 'fr', 'vi']) && $lang != Yii::$app->language) {
            Yii::$app->session->set('active_language', $lang);
            Yii::$app->language = $lang;
        }
        
        return $this->redirect('@web/'.$redir);
    }

    public function actionHelp()
    {
        return $this->render('help');
    }

    public function actionMe()
    {
        return $this->render('me');
    }

    // Get vars from string
    private function getVarsFrom($txt = '')
    {
        $ok = '';
        $fields = [];
        $parts = explode(' }}', $txt);
        foreach ($parts as $part) {
            $qa = explode('{{ ', $part);
            if (isset($qa[1])) {
                $a = explode(' | ', $qa[1]);
                if (isset($a[1])) {
                    $fields[trim($a[0])] = trim($a[1]);
                    $ok .= $qa[0];
                    $ok .= '<span style="color:brown">'.$a[1].'</span>';
                }
                else {
                    $ok .= $part.' }}';
                }
            } else {
                $ok .= $part;
            }
        }
        return $fields;
    }

    // Check xem token (SEG1) co OK khoong
    // OK = L-BID-BMD5-L-UID-UMD5
    // VD: b-530973417f11abcd-uid/itinerary

    private function checkAccess($token)
    {
        $bidlen = 0;
        $bid = 0;
        $bmd5 = 0;
        $uidlen = 0;
        $uid = 0;
        $umd5 = 0;

        $bidLen = (int)substr($token, 0, 1);
        $bid = substr($token, 1, $bidLen);
        $bmd5 = substr($token, $bidLen + 1, 4);

        $uidLen = (int)substr($token, $bidLen + 5, 1);
        $uid = substr($token, $bidLen + 6, $uidLen);
        $umd5 = substr($token, $bidLen + $uidLen + 6, 4);

        $uname = substr($token, $bidLen + $uidLen + 11);

        $result = [
            'booking_id'=>$bid,
            'booking_md5'=>$bmd5,
            'user_id'=>$uid,
            'user_md5'=>$umd5,
            'user_name'=>$uname,
        ];

        $result = [
            'booking_id'=>39144,
            'booking_md5'=>md5(39144),
            'user_id'=>26679,
            'user_md5'=>md5(26679),
            'user_name'=>'Marie Vuaillat',
        ];

        return $result;

    }
}
