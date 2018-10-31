<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MailQueue */

$this->title = Yii::t('app', 'Create Mail Queue');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail Queues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-queue-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
