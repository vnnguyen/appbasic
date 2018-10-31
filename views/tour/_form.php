<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Tour */
/* @var $form yii\widgets\ActiveForm */

$this->registerCss('
	.ui-datepicker table td.ui-datepicker-current-day .ui-state-active {
	    color: blue;
	}
	.ui-datepicker table td.ui-datepicker-today .ui-state-highlight {color: #363636;}
');
$this->registerJs('
     $("#tour-start_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        showButtonPanel: true, 
        altField: "#actualDate",
        closeText: "Clear",
        yearRange: "c-30:c+30",
        onClose: function () {
                var event = arguments.callee.caller.caller.arguments[0];
                // If "Clear" gets clicked, then really clear it
                if ($(event.delegateTarget).hasClass("ui-datepicker-close")) {
                    $(this).val("");
                }
            }

    });
');
?>
<div class="col-md-8">
	<div class="tour-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'excerpt')->textarea(['rows' => 6]) ?>

		<?= $form->field($model, 'start_date')->widget(DatePicker::className(),['dateFormat' => 'dd-MM-yyyy' ,'options'=>['style'=>'width:100%;', 'class'=>'form-control']])?>

	    <div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>
</div>

