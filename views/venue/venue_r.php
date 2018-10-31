<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\FileHelper;

// include('_venue_inc.php');
$this->registerCss('
    #wrap_dvd .dvd-a { display: inline-block; margin-left: 15px;}
    .my_tooltip {
    display:none; position:absolute; border:1px solid #333; background-color:#161616; border-radius:5px; padding:5px; color:#fff; font-size:12px "Arial"; 
    }
');
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = $theVenue['name'];
define('USER_ID',1);
// Stars
if ($theVenue['stype'] == 'hotel') {
    $stars = '';
    for ($i = 3; $i <= 5; $i ++) {
        if (strpos($theVenue['search'], $i.'s') !== false) {
            $stars = str_repeat('<i class="fa fa-star text-orange-300"></i>', $i);
        }
    }
    if ($stars != '') {
        Yii::$app->params['page_small_title'] = $stars.' ';
    }
}

// if (strpos($theVenue['search'], 'str ') !== false || substr($theVenue['search'], -3) == 'str') {
//     Yii::$app->params['page_small_title'] .= ' <em>strategic</em>';
// }
// if (strpos($theVenue['search'], 're ') !== false || substr($theVenue['search'], -2) == 're') {
//     Yii::$app->params['page_small_title'] .= ' <em>recommended</em>';
// }

Yii::$app->params['page_small_title'] .= $theVenue['destination']['name_en'];
// Price dates
$fromDTArray = [];
// foreach ($theVenue['dvo'] as $dvo) {
//     foreach ($dvo['cpo'] as $cpo) {
//         if (!in_array($cpo['from_dt'], $fromDTArray)) {
//             $fromDTArray[] = $cpo['from_dt'];
//         }
//     }
// }
rsort($fromDTArray);

$range = [];
// foreach ($theVenue['dvc'] as $dvc) {
//     foreach ($dvc['dvd'] as $dvd) {
//         if ($dvd['stype'] == 'date') {
//             $subRange = explode(';', $dvd['def']);
//             foreach ($subRange as $sr) {
//                 $arr = [
//                     'range'=>$sr,
//                     'code'=>$dvd['code'],
//                     'name'=>$dvd['desc'],
//                     'group'=>$dvc['name'],
//                 ];
//                 $range[] = $arr;
//             }
//         }
//     }
// }
// \fCore::expose($range);

$cnt = 0;
$data_set = '';
$range_set = '';
foreach ($range as $i=>$rg) {
    $cnt ++;
    $r = explode('-', $rg['range']);
    if (!isset($r[1])) {
        $r[1] = $r[0];
    }

    $bg = 9;
    foreach ($theVenue['dvc'] as $dvc) {
        foreach ($dvc['dvd'] as $j=>$dvd) {
            if ($dvd['stype'] == 'date' && strpos($dvd['def'], $rg['range']) !== false) {
                $bg = $j + 1;
            }
        }
    }

    $from = \DateTime::createFromFormat('j/n/Y', $r[0])->format('Y-m-d');
    $until = \DateTime::createFromFormat('j/n/Y', $r[1])->format('Y-m-d');
    $data_set .= "{id: $cnt, group: '{$rg['group']}', content: '{$rg['code']}', start: '$from 00:00:00', end: '$until 23:59:59', type: 'background', className: 'bg$bg'}";
    $range_set .= "ranges.push ( moment.range(moment('{$from} 00:00:00'), moment('{$until} 23:59:59')));";
    if ($cnt < count($range)) {
        $data_set .= ",\n";
    }
}

$js = <<<'TXT'
// function showPriceOn(date)
// {
//     $('#view-price-on').html(moment(date).format('D/M/Y dddd'));
//     $('.range').hide();
//     cnt = 0;
//     ranges.forEach(function(element) {
//         if (element.contains(date)) {
//             $('.range.range'+cnt).show();
//         }
//         cnt ++;
//     });
// }

// var container = document.getElementById('price-periods');

// var items = new vis.DataSet([
//     // {id:999, content: 'Promo XYZ', editable: false, start: '2017-08-26', end: '2017-09-02'},
//     {$DATASET}
// ]);

// var options = {
//     // type: 'background',
//     stack: false,
//     zoomMin: 1000000000,
//     zoomMax: 32000000000,
// };

// var timeline = new vis.Timeline(container, items, options);
// timeline.addCustomTime(moment().format('YYYY-MM-DD 12:00:00'));

// var ranges = [];

// {$RANGES}

// timeline.on('click', function (event) {
//     timeline.setCustomTime(event.time);
//     showPriceOn(event.time);
// });

// timeline.on('timechange', function (event) {
//     showPriceOn(event.time);
// });
TXT;

// $this->registerJs(str_replace(['{$DATASET}', '{$RANGES}'], [$data_set, $range_set], $js));

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
if (Yii::$app->language != 'en') {
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/locale/'.Yii::$app->language.'.js', ['depends'=>'yii\web\JqueryAsset']);
}
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.css', ['depends'=>'yii\web\JqueryAsset']);

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= $theVenue['name'] ?></h6>
            <div class="heading-elements panel-nav">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="active"><a href="#t-prices" data-toggle="tab">Prices</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="" id="t-prices">
                    <div class="row">
                        <div class="col-md-8">
                            <? if (!empty($theVenue['dvo'])) { ?>
                            <ul class="nav nav-tabs nav-tabs-bottom">
                                <li class="active"><a href="#t-oldprices" data-toggle="tab" aria-expanded="true">Current</a></li>
                                <li class=""><a href="#t-newprices" data-toggle="tab" aria-expanded="false">New price table <span class="label label-warning">testing</span></a></li>
                            </ul>
                            <? } ?>
                            <? if (!empty($theVenue['dvo'])) { ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="t-oldprices">
                                    <p>Xem giá từ: <?
                                        foreach ($fromDTArray as $item) {
                                            echo Html::a($item, '#', ['class'=>(strtotime($item) > strtotime('now') ? 'from-future' : 'from-past').' from-dt', 'id'=>'from-'.$item]);
                                            echo ' - ';
                                        }
                                        ?></p>
                                        <?
                                        $dvGrouping = 'Không phân nhóm';
                                        $dvType = 'xxx';
                                        foreach ($theVenue['dvo'] as $dvo) {
                                            if ($dvType != $dvo['stype']) {
                                                $dvType = $dvo['stype'];
                                                ?>
                            <table class="table table-bordered table-xxs">
                                <thead>
                                    <tr>
                                        <th width="50%">Loại dịch vụ: <?=isset($dvTypes[$dvType]) ? $dvTypes[$dvType] : '-'?></th>
                                        <th width="13%">Đơn vị</th>
                                        <th width="37%" class="ta-r">Áp dụng từ <span id="quote-from-dt">-</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? } // if sC != s[]?>
                                    <? if ($dvo['grouping'] != $dvGrouping) { 
                                        $dvGrouping = $dvo['grouping']; ?>
                                        <tr class="info"><td colspan="4"><?=$dvo['grouping'] == '' ? '(Tất cả)' : $dvo['grouping']?></td></tr>
                                        <? } ?>
                                        <tr>
                                            <td>
                                                <?//$s['requires_dvc_id'] == 0 ? '' : '&mdash;'?>
                                                <?= Html::a($dvo['name'], '@web/dvo/r/'.$dvo['id'], ['title'=>$dvo['search']]) ?>
                                                <? if (in_array(Yii::$app->user->id, [1, 1118, 1119198])) { ?>
                                                <?= Html::a('e', '@web/dvo/u/'.$dvo['id'], ['class'=>'text-muted', 'title'=>'Sửa'])?>
                                                <?= Html::a('d', '@web/dvo/d/'.$dvo['id'], ['class'=>'text-danger', 'title'=>'Xoá'])?>
                                                <?= Html::a('p', '@web/cpo/c?cp_id='.$dvo['id'], ['class'=>'text-muted', 'title'=>'Giá'])?>
                                                <? } ?>
                                            </td>
                                            <td>
                                                <?
                                                if (trim($dvo['note']) != '') {
                                                    echo '<i class="fa fa-info-circle" rel="popover" data-title="'.$dvo['name'].'" data-content="'.str_replace('"', '', $dvo['note']).'"></i> ';
                                                }
                                                ?>
                                                <?=$dvo['unit']?>
                                            </td>
                                            <td class="text-right">
                                                <?
                                                $cnt = 0;
                                                foreach ($dvo['cpo'] as $cpo) {
                                    //if ($cpo['cp_id'] == $dvo['id']) {
                                                    $cnt++; 
                                                    echo '<div class="hide from-dt from-'.$cpo['from_dt'].'">';
                                                    echo Html::a($cpo['name'], '@web/cpo/u/'.$cpo['id'], ['title'=>$cpo['search'], 'style'=>'float:left']);
                                                    if (trim($cpo['info']) != '') echo ' <i data-content="'.$cpo['info'].'" data-title="'.$cpo['name'].' : '.number_format($cpo['price'], 2).' '.$cpo['currency'].'" rel="popover" class="fa fa-info-circle"></i>';
                                                    ?>
                                                    <?= number_format($cpo['price'], intval($cpo['price']) == $cpo['price'] ? 0 : 2) ?>
                                                    <span class="text-muted"><?=$cpo['currency']?></span><?
                                                    echo '</div>';
                                    //}
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <? } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="t-newprices">
                                <div>
                                    <? } ?>
                                    <p><span class="text-uppercase text-bold">CONTRACTS:</span>
                                        <? foreach ($theVenue['dvc'] as $dvc) { ?>
                                        <?= Html::a($dvc['name'], '/dvc/r/'.$dvc['id'], ['title'=>$dvc['description']]) ?>,
                                        <? } ?>
                                        <? if (in_array(USER_ID, [1, 8, 9198, 11134718])) { ?>
                                        <?= Html::a('+New contract', '/dvc/c?venue_id='.$theVenue['id'], ['class'=>'pull-right']) ?>
                                        <? } ?>
                                    </p>
                                    <hr>

                                    <? if (in_array(USER_ID, [1, 8, 9198, 11134718])) { ?>
                                    <?= Html::a('+New service', '/dv/c?venue_id='.$theVenue['id'], ['class'=>'pull-right']) ?>
                                    <? } ?>

                                    <p class="text-uppercase text-bold"><?= Yii::t('dv', 'Price table') ?></p>
                                    <? if (USER_ID == 1) { ?>
                                    <p id="wrap_dvd"class="dvds">
                                        <?php foreach ($theVenue['dvc'] as $dvc): ?>
                                            <?php foreach ($dvc['dvd'] as $dvd): ?>
                                                <?php if ($dvd['stype'] == 'date'): ?>
                                                    <a class="masterTooltip dvd-a" title="<?= $dvc['name'].'====>'. $dvd['def']?>"><?= $dvd['code']?></a>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        <?php endforeach ?>
                                    </p>
                                    <div class="row col-md-4">
                                        <input type='text' class="form-control datepicker-here" id="selme" data-language="en" data-position="top left" data-today-button="<?= NOW ?>" placeholder="Date select" readonly/>
                                    </div>

                                    <div class="clearfix"></div><br />
                    <!-- <div id="selme" class="datepicker-here" data-language="en" data-today-button="<?= NOW ?>"></div> -->
                    <style type="text/css">
                    .datepicker>div{display:block;}
                    .datepicker--button {display:inline-block; width:50%; margin:auto; text-align:center; line-height:32px;}
                    .gd1 {background-color:#fffff0;}
                    .dp-note {background:blue; width: 4px; height: 4px; border-radius: 50%; left: 50%; bottom: 1px; -webkit-transform: translateX(-50%); transform: translateX(-50%); position:absolute;}
                    .-selected- .dp-note {bottom: 2px; background: #fff; opacity: .5;}
                    </style>
<?
$js = <<<'TXT'
$('#viewprice-submit').on('click', function(){
    $(this).addClass('disabled').html('<i class="fa fa-refresh fa-spin"></i> Searching...')
    $.ajax({
        method: 'POST',
        url: '?ajax',
        data: { name: "John", location: "Boston" }
    })
    .done(function(){
        
    })
    .fail(function(){
        
    })
    .always(function(){
        $('#viewprice-submit').removeClass('disabled').html('Search');
    })
})
TXT;
$this->registerJs($js);
?>
                    <? } ?>
<? if (0): ?>
                    <p><?= Yii::t('dv', 'Price as at') ?>: <span id="view-price-on"><?= Yii::$app->formatter->asDate(NOW, 'php:j/n/Y l') ?></span></p>
                    <div id="price-periods" class="mb-20"></div>
                    <style>
                    #price-periods * {font-size:12px; font-family:Roboto;}
                    .vis-item.vis-background.bg1 {background-color:#E6BABA}
                    .vis-item.vis-background.bg2 {background-color:#C6BAE6}
                    .vis-item.vis-background.bg3 {background-color:#BAD4E6}
                    .vis-item.vis-background.bg4 {background-color:#BAE6E3}
                    .vis-item.vis-background.bg5 {background-color:#E0BAE6}
                    .vis-item.vis-background.bg6 {background-color:#BAE6CA}
                    .vis-item.vis-background.bg7 {background-color:#E4E6BA}
                    .vis-item.vis-background.bg8 {background-color:#E6CEBA}
                    .vis-item.vis-background.bg9 {background-color:#E6BCBA}
                    </style>
<? endif; ?>
                    <? if (!empty($theVenue['dv'])) { ?>
                    <div class="table-responsive mb-20">
                        <table class="table table-bordered table-xxs" id="table_dv" data-venue-id="<?= $theVenue['id']?>">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Code</th>
                                    <th class="text-right">Period & prices</th>
                                </tr>
                            </thead>
                            <tbody id="list_dv">
                            </tbody>
                        </table>
                    </div>
                    <? } ?>
                    <? if (!empty($theVenue['dvo'])) { ?>
                </div>
            </div>
        </div>
        <? } ?>
    </div>
    <div class="col-md-4 note_display"></div>
</div>
</div>
<div class="tab-pane" id="t-promo">
    <?
    $promoInfo = explode('**Promotion**', $theVenue['info_pricing']);
    if (isset($promoInfo[1])) {
        echo Markdown::process($promoInfo[1]);
    } else {
        echo 'NO PROMOTIONS AVAILABLE';
    }
    ?>
</div>
</div>
</div>
</div>
</div>

<style type="text/css">
    .fancybox-overlay {z-index:1000!important}
    a.from-past {color:#c00;}
</style>
<?
$js = <<<TXT
// $('a.fancybox').fancybox();
$('a.from-dt').on('click', function(){
    $('a.from-dt').removeClass('fw-b');
    $(this).addClass('fw-b');
    var id = $(this).attr('id');
    var txt = $(this).text();
    $('div.from-dt').addClass('hide');
    $('div.'+id).removeClass('hide');
    $('span#quote-from-dt').text(txt);
    return false;
});
$('a.from-past:first').click();
$('[rel="popover"]').popover({
    'trigger':'hover'
});

$('#t-prices').on('click', 'a.dv_d', function(event){
    event.preventDefault();
    var id = $(this).data('id');
    var jqxhr = $.post('/dv/d/' + id, {x:'x'})
    .done(function(data) {
        $('tr#tr_dv_' + id).remove();
    }, 'text')
    .fail(function() {
        alert('Error deleting DV!');
    });
});


// Mousetrap.bind('p', function() {
//     $('a[href="#t-prices"]').tab('show');
//     $('a[href="#t-newprices"]').tab('show');
// });


TXT;
$this->registerJs($js);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/venue_r.js', ['depends'=>'yii\web\JqueryAsset']);