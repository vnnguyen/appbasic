<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AtNgaymau */

$this->title = Yii::t('app', 'Create At Ngaymau');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'At Ngaymaus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="at-ngaymau-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
