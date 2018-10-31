<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CpTour */

$baseUrl = Yii::$app->request->baseUrl;
$this->registerCss('
    .vis-item.HS{ background: #ffaa99;}
    .vis-item.LS{background: #faebd7}
    .vis-item.s{color: #666}
');
$this->registerCssFile($baseUrl.'/css/timeline/vis.css');

$this->registerJsFile($baseUrl.'/js/timeline/vis.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile($baseUrl.'/js/range.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="cp-tour-view">
<div class="col-md-12">
    <div id='visualization'></div><br><br>

</div>

</div>

