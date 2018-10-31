<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\selectize\SelectizeTextInput;

/* @var $this yii\web\View */
/* @var $model app\models\AtNgaymau */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="at-ngaymau-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uo')->textInput() ?>

    <?= $form->field($model, 'ub')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ngaymau_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ngaymau_body')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ngaymau_tags')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ngaymau_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ngaymau_meals')->dropDownList([ '---' => '---', 'B--' => 'B--', '-L-' => '-L-', '--D' => '--D', 'BL-' => 'BL-', 'B-D' => 'B-D', '-LD' => '-LD', 'BLD' => 'BLD', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'ngaymau_transport')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ngaymau_hotels')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ngaymau_guides')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ngaymau_services')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'language')->dropDownList([ 'fr' => 'Fr', 'en' => 'En', 'vi' => 'Vi', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'tagValues')->widget(SelectizeTextInput::className(), [
    // calls an action that returns a JSON object with matched
    // tags
        'loadUrl' => ['tag/list'],
        'options' => ['class' => 'form-control'],
        'clientOptions' => [
        'plugins' => ['remove_button'],
        'valueField' => 'name',
        'labelField' => 'name',
        'searchField' => ['name'],
        'create' => true,
        ],
        ])->hint('Use commas to separate tags') ?>
        
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
