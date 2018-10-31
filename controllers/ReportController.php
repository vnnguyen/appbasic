<?
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;
use app\models\AtCt;
use app\models\AtCase;


class ReportController extends Controller
{
	public function actionIndex()
	{
		$sql_conf = "SELECT YEAR(day_from) y, MONTH(day_from) m, COUNT(*) cnt
				FROM at_ct
				WHERE op_status = 'op' AND op_finish != 'cancel' AND year(day_from) = 2017
				GROUP BY m";
		$cts = AtCt::findBySql($sql_conf)->asArray()->all();

		$sql_sale = "SELECT YEAR(avail_from_date) y, MONTH(avail_from_date) m, COUNT(*) cnt
				FROM at_cases c INNER JOIN at_case_stats cs
				WHERE c.id = cs.case_id AND deal_status = 'pending' AND YEAR(avail_from_date) = 2017
				GROUP BY m";
		$cases = AtCase::findBySql($sql_sale)->asArray()->all();
		// var_dump($cts);die();

		return $this->render('index', [
			'cts' => $cts,
			'cases' => $cases
		]);
	}
	public function actionReport()
	{

		return $this->render('report', [
		]);
	}
	public function actionGet_data()
	{
		if (Yii::$app->request->isAjax) {
			$sql_conf = "SELECT YEAR(day_from) y, MONTH(day_from) m, COUNT(*) cnt
						FROM at_ct
						WHERE op_status = 'op' AND op_finish != 'cancel' AND year(day_from) = 2017
						GROUP BY m";
			$cts = AtCt::findBySql($sql_conf)->asArray()->all();

			$sql_sale = "SELECT YEAR(avail_from_date) y, MONTH(avail_from_date) m, COUNT(*) cnt
						FROM at_cases c INNER JOIN at_case_stats cs
						WHERE c.id = cs.case_id AND deal_status = 'pending' AND YEAR(avail_from_date) = 2017
						GROUP BY m";
			$cases = AtCase::findBySql($sql_sale)->asArray()->all();
			echo json_encode([
					'cts' => $cts,
					'cases' => $cases
				]);
		}
	}
}