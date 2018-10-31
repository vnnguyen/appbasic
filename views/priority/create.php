<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Priority */

$this->title = Yii::t('app', 'Create Priority');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Priorities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="priority-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
