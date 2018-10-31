<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'co')->textInput() ?>

    <?= $form->field($model, 'cb')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uo')->textInput() ?>

    <?= $form->field($model, 'ub')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'on' => 'On', 'off' => 'Off', 'draft' => 'Draft', 'deleted' => 'Deleted', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mins')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'due_dt')->textInput() ?>

    <?= $form->field($model, 'is_priority')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'fuzzy')->dropDownList([ 'none' => 'None', 'time' => 'Time', 'date' => 'Date', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'is_all')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'assignee_count')->textInput() ?>

    <?= $form->field($model, 'rtype')->dropDownList([ 'none' => 'None', 'case' => 'Case', 'tour' => 'Tour', 'user' => 'User', 'venue' => 'Venue', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'rid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'n_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
