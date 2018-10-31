<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Priority */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Priority',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Priorities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="priority-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
