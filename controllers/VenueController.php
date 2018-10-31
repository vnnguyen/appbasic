<?php

namespace app\controllers;
use \yii;
use \yii\web\Controller;
use yii\web\HttpException;
use app\models\Venue;
use app\models\Dvc;
use app\models\Dvd;

class VenueController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionR($id = 0)
    {
        // Han che mot so view vi khong dung chuan
        $restrictedList = [2202];

        if (in_array($id, $restrictedList)) {
            //throw new HttpException(403, 'Chức năng tạm bị hạn chế.');
        }

        $theVenue = Venue::find()
            ->where(['id'=>$id])
            ->with([
                'dvc',
                'dvc.dvd',
                'dv'=>function($q){
                    return $q->where('status!="deleted"')->orderBy('grouping, sorder, name');
                },
                'dv.cp',
                // 'dvo'=>function($q){
                //     return $q->orderBy('grouping, name');
                // },
                // 'dvo.cpo',
                'destination',
                ])
            ->asArray()
            ->one();
        if (!$theVenue) {
            throw new HttpException(404, 'Venue not found');
        }

        // Update SGL,DBL,TWN rooms
        if ($theVenue['stype'] == 'hotel') {
            $redir = false;
            foreach ($theVenue['dv'] as $dv) {
                if (strtoupper(substr($dv['name'], -4)) == ' SGL') {
                    $redir = true;
                    $roomtype = trim(substr($dv['name'], 0, strlen($dv['name']) - 4));
                    $name = '';
                    $trail = ' {*SGL';
                    $dbl = false;
                    $twn = false;
                    foreach ($theVenue['dv'] as $dv2) {
                        if ($dv2['name'] == $roomtype.' DBL' || $dv2['name'] == $roomtype.' dbl') {
                            $dbl = true;
                            Yii::$app->db
                                ->createCommand('UPDATE dv SET status="deleted" WHERE id=:id LIMIT 1', ['id'=>$dv2['id']])
                                ->execute();
                        }
                        if ($dv2['name'] == $roomtype.' TWN') {
                            $twn = true;
                            Yii::$app->db
                                ->createCommand('UPDATE dv SET status="deleted" WHERE id=:id LIMIT 1', ['id'=>$dv2['id']])
                                ->execute();
                        }
                    }
                    if ($dbl) {
                        $trail .= '|DBL';
                    }
                    if ($twn) {
                        $trail .= '|TWN';
                    }
                    $trail .= '}';
                    if ($dbl || $twn) {
                        $name = $roomtype.$trail;
                        Yii::$app->db
                            ->createCommand('UPDATE dv SET name=:n WHERE id=:id LIMIT 1', [':n'=>$name, 'id'=>$dv['id']])
                            ->execute();
                    }
                }
            }
            if ($redir) {
                //return $this->redirect(DIR.URI);
            }
        }
/*
            if (isset($_POST['html']) && $_POST['html'] != '') {
                $html = trim($_POST['html']);
                $pos = strpos($html, 'slideshow_photos');
                if ($pos !== false) {
                    $pos2 = strpos($html, '</script>');
                    if (false !== $pos2) {
                        $code = substr($html, $pos, $pos2 - $pos);
                        $code = str_replace([chr(10), chr(13)], ['', ''], $code);
                        $code = str_replace(['slideshow_photos = [', ',', '];'], ['<img src=', '><img src=', '>'], $code);
                        $code = str_replace(['= '], ['='], $code);
                        Yii::$app->db->createCommand()->update('venues', ['images_booking'=>$code], ['id'=>$theVenue['id']])->execute();
                        return $this->redirect(DIR.URI);
                    } else {
                        die('POS2 NF');
                    }
                } else {
                    die('POS1 NF');
                }
            }

            // Trip Advisor feedback
            $fbTripadvisor = $theVenue['fb_tripadvisor'];
            if ((Yii::$app->user->id == 111 && isset($_GET['get']) && $_GET['get'] == 'tripadvisor') || $theVenue['fb_tripadvisor'] == '') {
                if ($theVenue['link_tripadvisor'] != '') {
                    $html = ''; //file_get_contents($theVenue['link_tripadvisor']);
                    $pos = strpos($html, '<div id="REVIEWS" class="deckB review_collection">');
                    if (false !== $pos) {
                        $pos2 = strpos($html, '<div id="HSCS">');
                        if (false !== $pos2) {
                            $code = substr($html, $pos, $pos2 - $pos - 1);
                            $code = str_replace(['/ShowUserReviews', 'Review collected in partnership with this hotel '], ['http://www.tripadvisor.com/ShowUserReviews', ''], $code);
                            Yii::$app->db->createCommand()->update('venues', ['fb_tripadvisor'=>$code], ['id'=>$theVenue['id']])->execute();
                            $fbTripadvisor = $code;
                        } else {
                            $fbTripadvisor = 'POS2 NF!';
                        }
                    } else {
                        $fbTripadvisor = 'POS1 NF!';
                    }
                }
            }
*/
        // $venueMetas = Meta::find()
        //     ->where(['rtype'=>'venue', 'rid'=>$id])
        //     ->asArray()->all();

        // $venueNotes = Note::find()
        //     ->where(['rtype'=>'venue', 'rid'=>$id])
        //     ->with(['updatedBy', 'files'])
        //     ->orderBy('uo DESC')
        //     ->asArray()->all();

        $sql = 'SELECT f.*, t.day_from, t.op_code, t.op_name FROM at_tour_feedbacks f, at_ct t WHERE f.tour_id=t.id AND f.rtype="venue" AND f.rid=:id ORDER BY t.day_from DESC';
        // $venueFeedbacks = Yii::$app->db->createCommand($sql, [':id'=>$theVenue['id']])->queryAll();

        $venueTours = Yii::$app->db
            ->createCommand('SELECT t.id, t.code, t.name, c.price, c.unitc, c.dvtour_name, c.qty, c.unit, c.dvtour_day, c.dvtour_id FROM at_tours t, cpt c WHERE c.tour_id=t.id AND c.venue_id=:id GROUP BY t.id ORDER BY c.dvtour_day DESC LIMIT 100', [':id'=>$id])
            ->queryAll();

        $venueSupplier = null;
        // if ($theVenue['supplier_id'] != 0) {
        //     $venueSupplier = Supplier::find()
        //         ->where(['id'=>$theVenue['supplier_id']])
        //         ->with([
        //             'venues'=>function($q) {
        //                 return $q->select(['id', 'name', 'image', 'supplier_id']);
        //             }
        //             ])
        //         ->asArray()
        //         ->one();
        // }

        return $this->render('venue_r', [
            'theVenue'=>$theVenue,
            // 'venueMetas'=>$venueMetas,
            // 'venueNotes'=>$venueNotes,
            'venueFeedbacks'=> null, //$venueFeedbacks,
            'venueTours'=>$venueTours,
            'venueSupplier'=>$venueSupplier,
            'fbTripadvisor'=>'',//$fbTripadvisor,
        ]);
    }
    public function actionValidate()
    {
    	$cond = 'a<17';
    	if (preg_match('/<=|>=|!=/', $cond, $matches)) {
    		print_r($matches);
    	} else {
    		if (preg_match('/<|>|=/', $cond, $matches)) {
    			print_r($matches);
    		}
    	}

    }
    public function actionList_dv($venue_id = 0, $date_selected = '')
    {
    	if (Yii::$app->request->isAjax) {
    		if ($date_selected == '') {
    			return json_encode(['err' => 'date null']);
    		}
    		$select_dt_arr = explode('/', $date_selected);
    		$date_selected = $select_dt_arr[2].'/'.$select_dt_arr[1].'/'.$select_dt_arr[0];
    		$dvc = Dvc::find()
            ->where(['venue_id'=>$venue_id])
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
                                $valid_cp = [];
		            			$dvc['venue']['dv'][$k_dv]['name'] = str_replace(
	                                    [
	                                        '[', ']', '{', '}', '|',
	                                    ], [
	                                        '', '', '(<span class="text-light text-pink">', '</span>)', '/',
	                                        ], $dv['name']);
		            			foreach ($dv['cp'] as $k_cp => $cp) {
				            		if ($cp['period'] == $dvd['code'] && $dvc['id'] == $cp['dvc_id']) {
										$valid_cp[] = $cp;
				            		} else {
				            			if (count($dv['cp']) == 1 && $cp['period'] == '') {
				            				$dvc['venue']['dv'][$k_dv]['cp'][$k_cp] = $cp;
				            			}
				            		}
				            	}
                                if (count($valid_cp) > 0) {
                                    $dvc['venue']['dv'][$k_dv]['cp'] = $valid_cp;
                                }
							}
	        			}
            		}
            	}
            	echo json_encode(['dvc' => $dvc]);
            } else {
            	return json_encode(['err' => 'dvc is null']);
            }
    	}
    }
}
