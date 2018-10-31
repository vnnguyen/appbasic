<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$baseUrl = Yii::$app->request->baseUrl;
// $this->registerCssFile($baseUrl.'/css/jquery-ui.min.css');
$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.css');
//$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.print.css');
// $this->registerCssFile($baseUrl.'/css/scheduler.min.css');
// $this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
// $this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCss('
    .fc-list-table td {
        border-width: 1px 0 0;
        padding: 8px 14px;
    }
    .fc-list-item .wrap_close
    , .fc-list-item .wrap_edit
    , .fc-list-item .wrap_cancel
    , .fc-list-item .wrap_confirm {margin-left: 10px; border-radius: 5px; padding: 5px 8px}
    .cicle{border-radius: 50%; padding: 0 3px; font-size: 12px; margin-right: 7px}
    .fc-ltr .fc-list-item-marker{ padding-right: 20px}

');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
// $this->registerJsFile($baseUrl.'/js/scheduler.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJsFile($baseUrl.'/js/reserv_index.js', ['depends' => \yii\web\JqueryAsset::className()]);


$this->title = Yii::t('app', 'Reservs');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="reserv-index">
    <div class="panel panel-default">
        <div id="filter">
            <div class="col-md-2">
                <div class="form-group">
                    <label><?= Yii::t('app','Position')?></label>
                    <?= Html::dropDownList('position', '', ArrayHelper::map($select_pos, 'id', 'name'), ['class' => 'form-control']) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label><?= Yii::t('app','Date time')?></label>
                    <?php
                            echo DateTimePicker::widget([
                                'name' => 'time_to_meet',
                                'type' => DateTimePicker::TYPE_INPUT,
                                'value' => '',
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'todayBtn' => true,
                                    'daysOfWeekDisabled' => [0],
                                    'hoursDisabled' => [0,1,2,3,4,5,6,7,18,19,20,21,22,23],
                                    'format' => 'yyyy-mm-dd'
                                ]
                            ]);
                    ?>
                </div>

            </div>
        </div>
        <div class="clearfix"></div>
        <div id="calendar"></div>
    </div>
</div>
