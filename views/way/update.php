<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Way */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Way',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ways'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="way-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
