<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Calen */

$this->title = Yii::t('app', 'Create Calen');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Calens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calen-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
