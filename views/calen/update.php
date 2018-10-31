<?php

use yii\helpers\Html;
///sdádf

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Calen',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Calens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="calen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
