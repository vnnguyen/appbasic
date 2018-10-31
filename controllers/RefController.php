<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

use app\models\Venue;
use app\models\Destination;
// use common\models\Venue;
// use common\models\Loc;
// use common\models\Cp;
// use common\models\Cpg;
// use common\models\Cpt;
// use common\models\Destination;

class RefController extends Controller
{
	public function actionHotels($category = '', $destination = 'default', $search = '', $name = '')
	{
		if ($category == '') {
			$category = 're';
		}
		if ($destination == 'default') {
			$destination = Yii::$app->session->get('ref/hotels?destination', 'default');
		}
		if ($destination == 'default') {
			$destination = 1;
		}

		Yii::$app->session->set('ref/hotels?destination', $destination);
		$destinations = Destination::findBySql('SELECT id, name_en, (SELECT name_en FROM at_countries c WHERE c.code=country_code LIMIT 1) AS country_name, (SELECT COUNT(*) FROM venues v WHERE v.stype="hotel" AND v.destination_id=d.id) AS total FROM at_destinations d HAVING total>0 ORDER BY country_code, name_en')->asArray()->all();

		$query = Venue::find()
			->select(['id', 'name', 'stype', 'about', 'search', 'destination_id', 'link_tripadvisor', 'company_id', 'images_booking']);

		// 150811 Hanh lay list de goi dien
		if (Yii::$app->request->get('for') == 'hanh') {
			$query->where(['stype'=>'hotel']);
		} else {
			$query->where([
				'stype'=>'hotel',
				'destination_id'=>$destination,
			]);
		}
		$searchParams = explode(' ', $search);
		$status = false;
		if (count($searchParams) > 0 && $searchParams[0] != '') {
			foreach ($searchParams as $k => $param) {
				$query->andWhere('LOCATE("'.$param.'", search)!=0');
				if ($param == 'not' || $param == 're' || $param == 'sta') {
					$status = true;
				}
			}
			if (!$status) {
				$count = $query;
				if (count($count->all()) > 0) {
					$cnt = $query;
					$cnt->andWhere('LOCATE("'.$category.'", search)!=0');
					if (count($cnt->all()) > 0) {
						$query->andWhere('LOCATE("'.$category.'", search)!=0');
					} else {
						$query->andWhere('LOCATE("not", search)!=0');
					}
				}
			}
		} else {
			$cnt = $query;
			$cnt->andWhere('LOCATE("'.$category.'", search)!=0');
			if (count($cnt->all()) > 0) {
				$query->andWhere('LOCATE("'.$category.'", search)!=0');
			} else {
				$query->andWhere('LOCATE("not", search)!=0');
			}
		}
		if ($name != '') {
			$query->andWhere(['like', 'name', $name]);
		}
		$theVenues = $query
			->orderBy('name')
			->with([
				'metas'=>function($q) {
					return $q->select(['rtype', 'rid', 'k', 'v']);
				}
			])
			->asArray()
			->all();
		return $this->render('ref_hotels', [
			'theVenues'=>$theVenues,
			'destinations'=>$destinations,
			'destination'=>$destination,
			'search'=>$search,
			'name'=>$name,
			'category' => $category,
			'sql'=>	$query->createCommand()->getRawSql()
			]
		);
	}

	public function actionHomes()
	{
		$theVenues = Venue::find()
			->select(['id', 'name', 'stype', 'about', 'search', 'destination_id', 'link_tripadvisor'])
			->where(['stype'=>'home'])
			->orderBy('name')
			->with(['destination', 'metas'])
			->asArray()
			->all();
		return $this->render('ref_homes', [
			'theVenues'=>$theVenues,
		]);
	}

	public function actionHalongcruises($destination = 'default', $search = '', $name = '')
	{
		if ($destination == 'default') {
			$destination = Yii::$app->session->get('ref/halongcruises?destination', 'default');
		}
		if ($destination == 'default') {
			$destination = 2;
		}
		Yii::$app->session->set('ref/halongcruises?destination', $destination);

		$destinations = Destination::findBySql('SELECT id, name_en, (SELECT name_en FROM at_countries c WHERE c.code=country_code LIMIT 1) AS country_name, (SELECT COUNT(*) FROM venues v WHERE v.stype="cruise" AND v.destination_id=d.id) AS total FROM at_destinations d HAVING total>0 ORDER BY country_code, name_en')->asArray()->all();

		$query = Venue::find()
			->select(['id', 'name', 'stype', 'about', 'search', 'destination_id', 'link_tripadvisor', 'supplier_id', 'cruise_meta'])
			->where(['stype'=>'cruise', 'destination_id'=>$destination])
			->orderBy('supplier_id, name')
			->with(['destination', 'company'])
			->asArray();

		$searchParams = explode(' ', $search);
		if (!empty($searchParams)) {
			foreach ($searchParams as $param) {
				$query->andWhere('LOCATE("'.$param.'", search)!=0');
			}
		}

		if ($name != '') {
			$query->andWhere(['like', 'name', $name]);
		}

		$theVenues = $query->all();

		return $this->render('ref_halongcruises', [
			'theVenues'=>$theVenues,
			'destinations'=>$destinations,
			'destination'=>$destination,
			'name'=>$name,
			'search'=>$search,
		]);
	}

	public function actionSsspots()
	{
		$query = Venue::find()->where(['stype'=>'sightseeing']);
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>100,
			]);
		$theVenues = $query
			->select(['id', 'name', 'stype', 'about', 'search', 'destination_id', 'link_tripadvisor'])			
			->orderBy('stype, name')
			->offset($pages->offset)
			->limit($pages->limit)
			->with(['destination'])
			->asArray()
			->all();
		return $this->render('ref_ssspots', [
				'pages'=>$pages,
				'theVenues'=>$theVenues,
			]
		);
	}

	public function actionTables()
	{
		$query = Venue::find()->where(['stype'=>'table']);
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>100,
			]);
		$theVenues = $query
			->select(['id', 'name', 'stype', 'about', 'search', 'destination_id', 'link_tripadvisor'])			
			->orderBy('stype, name')
			->offset($pages->offset)
			->limit($pages->limit)
			->with(['destination'])
			->asArray()
			->all();
		return $this->render('ref_tables', [
				'pages'=>$pages,
				'theVenues'=>$theVenues,
			]
		);
	}

	// Giá vận chuyển đường bộ
	public function actionGiaxe()
	{
		$theCpx = Cp::find()
			->with([
				'cpg',
				'venue',
				'company',
			])
			->where('abbr!=""')
			->limit(100)
			->orderBy('updated_at DESC')
			->asArray()
			->all();
		return $this->render('ref_giaxe', [
			'theCpx'=>$theCpx,
		]);
	}

}
