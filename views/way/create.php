<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Way */

$this->title = Yii::t('app', 'Create Way');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ways'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="way-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
