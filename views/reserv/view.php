<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$baseUrl = Yii::$app->request->baseUrl;
$this->registerCssFile($baseUrl.'/css/jquery-ui.min.css');
$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.css');
//$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.print.css');
$this->registerCssFile($baseUrl.'/css/scheduler.min.css');
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCss('
    .fc-scroller{ min-height: 10px}
    .turn_right {
        float: left;
        display:inline-block;
    }
    .badge, .btn-xs .badge {
        padding: 2px 3px 0;
        color: #fff
        font-weight: bold
    }
    .fc-v-event{ padding: 1px!important}
    .stack-custom { max-width: 500px !important; }
    .brighttheme-notice{background: #fff; border: none;}
    .commited{ background-color:#EF5350;border-color:#EF5350;}
    .draft{background-color:#26A69A; border-color:#26A69A;}
    .canceled{background-color:#546E7A; border-color:#546E7A;}
    .remove{position: absolute; top: 5px; right: 52px; font-size: 14px}
');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJsFile($baseUrl.'/js/scheduler.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJsFile($baseUrl.'/js/reserv_view.js', ['depends' => \yii\web\JqueryAsset::className()]);


$this->title = Yii::t('app', 'Reservs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reservs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View')];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="reserv-index">
    <div class="panel panel-default">
        <div id="calendar"></div>
    </div>
</div>
