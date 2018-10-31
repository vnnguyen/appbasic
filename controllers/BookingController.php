<?php

namespace app\controllers;

use Yii;
use app\models\Payment;
use app\models\Booking;
use app\models\Invoice;
use app\models\AtTours;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UploadedFile;
use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Settings;
use \PHPExcel_Style_Fill;
use \PHPExcel_Writer_IWriter;
use \PHPExcel_Worksheet;
use \PHPExcel_Style;

class BookingController extends Controller
{
	public function actionImport()
	{
		// var_dump(Yii::$app->request);die();
		$arr = [];
		if (isset($_FILES['import'])) {
			//$file = UploadedFile::getInstance($_POST['import']);
			// $file_name = $_FILES['import']['name'];
			$tmp_name = $_FILES["import"]["tmp_name"];


			$objPHPExcel = \PHPExcel_IOFactory::load($tmp_name);
			$arr = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			// print_r($arr);die();
			var_dump($arr); die();
			$booking = Booking::find()->where(['id' => 38873])->with([
				'invoices',
				'payments'
				])->one();
			// var_dump($booking['invoices']);
			// var_dump($booking['payments']);
			// var_dump($arr);die();
			foreach ($arr as $k => $row) {
				if ($k == 1) continue;
				$thePayment = new Payment();
				$thePayment->scenario = 'payments_c';
				if ($row['A'] != '') {
					$tour = AtTours::find()->where(['code' => $row['A']])->with([
						'ct'
					])->asArray()->one();
					if ($tour == null) continue;
					$theBooking = Booking::find()->where(['product_id' => $tour['ct']['id']])->with([
						'invoices',
						'payments'
					])->asArray()->one();
					if ($theBooking == null) continue;

					$thePayment->booking_id = $theBooking['id'];
		            $thePayment->created_at = date('Y-m-d H:i:s', strtotime('now'));//NOW;
		            $thePayment->created_by = 1;//USER_ID;
		            $thePayment->updated_at = '0000-00-00 00:00:00';//NOW;
		            $thePayment->updated_by = 1;//USER_ID;
		            $thePayment->status = 'on';

		            $thePayment->ref = $row['A'].' - '.$row['B'];
		            $thePayment->payment_dt = date('Y-m-d H:i:s', strtotime($row['C']));
		            $thePayment->payer = $row['D'];
		            $thePayment->payee = $row['E'];
		            $thePayment->method = $row['F'];
		            $thePayment->amount = $row['G'];
		            $thePayment->currency = strtoupper($row['H']);
		            if ($row['H'] == 'VND') {
		            	$thePayment->xrate = 1;
		            }
		            else {
		            	$thePayment->xrate = $row['I'];
		            }
		            $thePayment->note = $row['J'];
		            if ($row['B'] != '') {
						$invois = explode('+', $row['B']);
						if (count($invois) > 1) {
							// foreach ($invois as $index => $invoi) {
							// 	foreach ($theBooking['invoices'] as $key => $value) {
							// 		if (intval($invoi) == $key+1) {
							// 			$thePayment->invoice_id = $value['id'];
							// 		}
							// 	}
							// }
							$thePayment->invoice_id = 1;
						} else {
							foreach ($theBooking['invoices'] as $key => $value) {
								$arr_ref = explode('-', $value['ref']);
								if (count($arr_ref) == 2) {
									if (intval($invois[0]) == intval($arr_ref[1])) {
										$thePayment->invoice_id = $value['id'];
									}
								}
							}
						}
					}
					else {
						$thePayment->invoice_id = 0;
					}

					if ($thePayment->validate()) {
						$thePayment->save();
					}
					else
					{
						var_dump($thePayment->errors);die();
					}
				}
			}
		}
		return $this->render('import', [
			'arr' => $arr
		]);
	}
}
