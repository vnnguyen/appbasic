<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\postsearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'post_title') ?>

    <?= $form->field($model, 'post_status') ?>

    <?= $form->field($model, 'post_excerpt') ?>

    <?= $form->field($model, 'post_content') ?>

    <?php // echo $form->field($model, 'attach_file') ?>

    <?php // echo $form->field($model, 'date_issued') ?>

    <?php // echo $form->field($model, 'start_day') ?>

    <?php // echo $form->field($model, 'expiry_day') ?>

    <?php // echo $form->field($model, 'people_signing') ?>

    <?php // echo $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_by') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
