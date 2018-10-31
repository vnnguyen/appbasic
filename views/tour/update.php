<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tour */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tour',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tours'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tour-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
