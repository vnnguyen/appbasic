<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_icon'] = 'car';

if ($month == 'next30days') {
    $monthText = 'trong 30 ngày tới';
} elseif ($month == 'last30days') {
    $monthText = 'trong 30 vừa qua';
} else {
    $monthText = 'trong tháng '.date('n/Y', strtotime($month));
}

if ($orderby == 'startdate') {
    $selectText = 'Tour khởi hành ';
} elseif ($orderby == 'enddate') {
    $selectText = 'Tour kết thúc ';
} else {
    $selectText = 'Tour được mở ';
}

Yii::$app->params['page_title'] = $selectText.$monthText.' ('.number_format(count($theTours)).' tour)';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tours', '@web/tours'],
    [$month, '@web/tours?month='.$month],
];

$newMonthList = [''=>'Tháng này'];
$newMonthList['next30days'] = '30 ngày tới';
$newMonthList['last30days'] = '30 ngày qua';
foreach ($monthList as $mo) {
    $newMonthList[$mo['ym']] = $mo['ym'].' ('.$mo['total'].')';
    $newMonthList[$mo['ym']] = $mo['ym'];
}

$statusList = [
    'active'=>'Active',
    'canceled'=>'Canceled',
];

$goto = Yii::$app->request->get('goto');
$gotoList = [
    'vn'=>'Vietnam',
    'la'=>'Laos',
    'kh'=>'Cambodia',
    'mm'=>'Myanmar',
    'th'=>'Thailand',
    'cn'=>'China',
    'id'=>'Indonesia',
    'my'=>'Malaysia',
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('orderby', $orderby, ['startdate'=>'Start in', 'enddate'=>'End in', 'created'=>'Created in'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('month', $month, $newMonthList, ['class'=>'form-control']) ?>
                <?= Html::dropdownList('fg', $fg, ['f'=>'F tours', 'g'=>'G tours'], ['class'=>'form-control', 'prompt'=>'F/G tours']) ?>
                <?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control', 'prompt'=>'Status']) ?>
                <?= Html::dropdownList('goto', $goto, $gotoList, ['class'=>'form-control', 'prompt'=>'Countries']) ?>
                <?= Html::dropdownList('seller', $seller, $sellerList, ['class'=>'form-control', 'prompt'=>'Sellers']) ?>
                <?= Html::dropdownList('operator', $operator, ArrayHelper::map($operatorList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Operators']) ?>
                <?= Html::dropdownList('cservice', $cservice, ArrayHelper::map($cserviceList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Customer care']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search in name']) ?>
                <?= Html::textInput('dayname', $dayname, ['class'=>'form-control', 'placeholder'=>'Search in days']) ?>
                <?= Html::dropdownList('view', $view, [''=>'Hide ratings', 'pts'=>'View ratings'], ['class'=>'form-control']) ?>
                <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), '@web/tours') ?>
            </form>
        </div>
    <div class="table-responsive">
        <table id="tourlist" class="table table-xxs xtable-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= YiiL::t('tour_index', 'Vào')?></th>
                    <th><?= YiiL::t('tour_index', 'Ra')?></th>
                    <th style="min-width:280px"><?= YiiL::t('tour_index', 'Code')?> - <?= YiiL::t('tour_index', 'Tên tour')?> - <!--a class="fw-n" href="#" onclick="$('tr.paxLine').toggleClass('hide'); return false;">Ẩn / hiện danh sách khách</a--></th>
                    <th><?= YiiL::t('tour_index', 'P')?></th>
                    <th><?= YiiL::t('tour_index', 'D')?></th>
                    <th><?= YiiL::t('tour_index', 'To')?></th>
                    <th><?= YiiL::t('tour_index', 'Bán hàng')?></th>
                    <th><?= YiiL::t('tour_index', 'Điều hành')?></th>
                    <th><?= YiiL::t('tour_index', 'QHKH')?></th>
                    <th><?= YiiL::t('tour_index', 'Guide')?></th>
                    <th><?= YiiL::t('tour_index', 'Lái xe')?></th>
                </tr>
            </thead>
            <tbody>
<?
$dayIn = '';
$cnt = 0;

foreach ($theTours as $tour) {
    $gotoOK = true;
    if (array_key_exists($goto, $gotoList) && strpos($tour['tourStats']['countries'], $goto) === false) {
        $gotoOK = false;
    }

    $sellerOK = true;
    if ($seller != 0) {
        $sellerOK = false;
        foreach ($tour['bookings'] as $booking) {
            if ($booking['createdBy']['id'] == $seller) {
                $sellerOK = true;
            }
        }
    }

    $operatorOK = true;
    if ($operator != 0 && $tour['tour']['id'] != 0 && !in_array($operator, $staffList[$tour['tour']['id']]['op'])) {
        $operatorOK = false;
    }

    $cserviceOK = true;
    if ($cservice != 0 && $tour['tour']['id'] != 0 && !in_array($cservice, $staffList[$tour['tour']['id']]['cs'])) {
        $cserviceOK = false;
    }

    $dayOK = true;
    if (strlen(trim($dayname)) > 2) {
        $dayOK = false;
        foreach ($tour['days'] as $day) {
            if (strpos(\fURL::makeFriendly($day['name'], '-'), \fURL::makeFriendly($dayname, '-')) !== false) {
                $dayOK = true;
                break;
            }
        }
    }

    // FG
    $fgOK = true;
    if (in_array($fg, ['f', 'g']) && substr($tour['tour']['code'], 0, 1) != strtoupper($fg)) {
        $fgOK = false;
    }

    // Status
    $statusOK = true;
    if (($status == 'active' && $tour['tour']['status'] == 'deleted') || ($status == 'canceled' && $tour['tour']['status'] != 'deleted')) {
        $statusOK = false;
    }

    if ($gotoOK &&  $sellerOK && $operatorOK && $cserviceOK && $dayOK && $fgOK && $statusOK) {
?>
                <tr class="<? foreach ($tour['bookings'] as $booking) echo 'bh-',$booking['created_by']; ?>
                    tour <?= $tour['tour']['status'] == 'deleted' ? 'danger' : '' ?>">
                    <td class="text-center text-muted"><?= ++ $cnt ?></td>
                    <td class="text-center"><strong><?
    if ($dayIn != $tour['day_from']) {
        $dayIn = $tour['day_from'];
        $jOrjn = 'j/n';
        if ($orderby == 'startdate' && $month == date('Y-m')) {
            $jOrjn = 'j';
        }
        echo date($jOrjn, strtotime($dayIn));
    }
?></strong>
                    </td>
                    <td class="text-center">
                        <?
                        $jOrjn = 'j/n';
if ($orderby == 'enddate' && $month == date('Y-m')) {
    $jOrjn = 'j';
}
                        ?>
                        <?= date($jOrjn, strtotime($tour['day_from'].' + '.($tour['day_count'] - 1).'days')) ?>
                    </td>
                    <td>
                        <i class="fa fa-info-circle popovers pull-right text-muted"
                            data-trigger="hover"
                            data-title="<?= $tour['title'] ?>"
                            data-placement="right"
                            data-html="true"
                            data-content="
<?
            $dayIds = explode(',', $tour['day_ids']);
            if (count($dayIds) > 0) {
                $cnt2 = 0;
                echo '<ol>';
                foreach ($dayIds as $id) {
                    foreach ($tour['days'] as $day) {
                        if ($day['id'] == $id) {
                            $dd = date('j/n', strtotime('+ '.$cnt2.' days', strtotime($tour['day_from'])));
                            $cnt2 ++;
                            echo '<li><strong>', $dd, '</strong> ', Html::encode($day['name']), ' <em>', $day['meals'], '</em></li>';
                        }
                    }
                }
                echo '</ol>';
            }
?>
                        "></i>
<?
                        $flag = $tour['language'];
                        if ($tour['language'] == 'en') $flag = 'us';
                        if ($tour['language'] == 'vi') $flag = 'vn';
                        echo '<span class="flag-icon flag-icon-', $flag,'"></span>';
?>
                        <?= $tour['offer_type'] == 'combined2016' ? '<span class="text-uppercase text-light" style="background-color:#cff; color:#148040; padding:0 3px" title="Combined">C</span> ' : ''?>
                        <?= $tour['tour']['status'] == 'deleted' ? '<strong style="color:#c00;">(CXL)</strong> ' : ''?>
                        <?= Html::a($tour['tour']['code'].' - '.$tour['tour']['name'], '@web/tours/r/'.$tour['tour']['id']) ?>
                        
                    </td>
                    <td class="text-nowrap text-center">
                        <?= Html::a($tour['pax'], '@web/tours/pax/'.$tour['id']) ?>
                    </td>
                    <td>
                        <?= Html::a($tour['day_count'], '@web/tours/services/'.$tour['tour']['id']) ?>
                    </td>
                    <td class="text-nowrap">
                        <?
                        if ($tour['tourStats']['countries'] != '') {
                            $countries = explode(',', $tour['tourStats']['countries']);
                            foreach ($countries as $country) {
                        ?><span title="<?= strtoupper($country) ?>" class="flag-icon flag-icon-<?= $country ?>"></span> <?
                            }
                        }
                        ?>
                    <td class="text-nowrap">
<?
    $nameList = [];
    foreach ($tour['bookings'] as $booking) {
        $nameList[] = '<span class="cursor-pointer" onclick="$(\'.tour\').removeClass(\'info\');$(\'.tour.bh-'.$booking['created_by'].'\').toggleClass(\'info\');">'.$booking['createdBy']['name'].'</span>';
    }
    echo implode(', ', $nameList);
?>
                    </td>
                    <td>
<?
    $nameList = [];
    foreach ($tourOperators as $user) {
        if ($user['tour_id'] == $tour['tour']['id']) {
            $nameList[] = $user['name'];
        }
    }
    echo implode(', ', $nameList);
?>
                    </td>
                    <td class="text-nowrap">
<?
    $nameList = [];
    foreach ($tourCCStaff as $user) {
        if ($user['tour_id'] == $tour['tour']['id']) {
            $nameList[] = $user['name'];
        }
    }
    echo implode(', ', $nameList);
?>
                    </td>
                    <td>
<?
    $nameList = [];
    if (!empty($tourGuides)) {
        foreach ($tourGuides as $guide) {
            if ($guide['tour_id'] == $tour['id']) {
                if ($view == 'pts') {
                    $nameList[] = ($guide['points'] == 0 ? '<span title="<?= Yii::t('tour_index', 'Chưa có điểm HD')?>" class="label label-warning">?</span> ' : '<span title="<?= Yii::t('tour_index', 'Điểm HD')?>" class="label label-info">'.$guide['points'].'</span> ').$guide['namephone'];
                } else {
                    $nameList[] = $guide['namephone'];
                }
            }
        }
    }
    if ($view == 'pts') {
        echo implode('<br>', $nameList);
    } else {
        echo implode(', ', $nameList);
    }
?>
                    </td>
                    <td>
<?
    $nameList = [];
    if (!empty($tourDrivers)) {
        foreach ($tourDrivers as $driver) {
            if ($driver['tour_id'] == $tour['id']) {
                if ($view == 'pts') {
                    $nameList[] = ($driver['points'] == 0 ? '<span title="<?= Yii::t('tour_index', 'Chưa có điểm LX')?>" class="label label-warning">?</span> ' : '<span title="<?= Yii::t('tour_index', 'Điểm LX')?>" class="label label-info">'.$driver['points'].'</span> ').$driver['namephone'];
                } else {
                    $nameList[] = $driver['namephone'];
                }
            }
        }
    }
    if ($view == 'pts') {
        echo implode('<br>', $nameList);
    } else {
        echo implode(', ', $nameList);
    }
?>
                    </td>
                </tr>
<?
    } // if hidden
} // foreach
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
.popover {min-width:500px;}
.fa-male {color:blue;}
.fa-female {color:purple;}
.form-control.w-auto {width:auto; display:inline;}
</style>
<?
if (USER_ID == 1) {
    $js = <<<'JS'
$('a.editable-name').editable({});
JS;
    $this->registerCssFile(DIR.'assets/x-editable_1.5.1/css/bootstrap-editable.css', ['depends'=>'app\assets\MainAsset']);
    $this->registerJsFile(DIR.'assets/x-editable_1.5.1/js/bootstrap-editable.min.js', ['depends'=>'app\assets\MainAsset']);
    $this->registerJs($js);
}