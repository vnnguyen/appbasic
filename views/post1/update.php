<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Post1 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Post1',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Post1s'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="post1-update">

    <?= $this->render('_form', [
        'model' => $model,
        'status' => $status,
    ]) ?>

</div>
