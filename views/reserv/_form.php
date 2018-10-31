<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Reserv */
/* @var $form yii\widgets\ActiveForm */
$status = ['draft' => Yii::t('app', 'draft'), 'commited' => Yii::t('app', 'commit')];
?>

<div class="reserv-form">
    <div class="col-md-6 panel">


        <?php $form = ActiveForm::begin(); ?>

        
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'book_dt')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Enter event time ...'],
            'readonly' => true,
            'pluginOptions' => [
                'autoclose'=>true,
                'startDate' => date('Y-m-d H:i', strtotime('now')),
                'todayBtn' => true,
                'daysOfWeekDisabled' => [0],
                'minuteStep' => 15,
                'hoursDisabled' => [0,1,2,3,4,5,6,7,18,19,20,21,22,23],
                'format' => 'yyyy-mm-dd hh:ii'
            ],
            // 'pluginEvents' => [
            //     "show" => "function(e) {  # `e` here contains the extra attributes }",
            //     "hide" => "function(e) {  # `e` here contains the extra attributes }",
            //     "clearDate" => "function(e) {  # `e` here contains the extra attributes }",
            //     "changeDate" => "function(e) {  # `e` here contains the extra attributes }",
            //     "changeYear" => "function(e) {  # `e` here contains the extra attributes }",
            //     "changeMonth" => "function(e) {  # `e` here contains the extra attributes }",
            // ]
        ]); ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'mins')->textInput(['type' => 'number']) ?>

            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'num_people')->textInput(['type' => 'number']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'pos_id')->dropDownList(ArrayHelper::map($select_pos, 'id', 'name')); ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'status')->dropDownList($status) ?>
            </div>
        </div>

        <?= $form->field($model, 'content')->textarea(['rows' => 3]) ?>

        <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
