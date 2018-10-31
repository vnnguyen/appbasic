<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Reserv */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Reserv',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reservs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="reserv-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'select_pos' => $select_pos,
    ]) ?>

</div>
