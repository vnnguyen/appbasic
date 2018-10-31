<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

// use yii\jui\DatePicker;
// var_dump(ArrayHelper::map($bookingPax, 'id', 'name'));die();
// $this->registerJsfile($baseUrl.'/js/jquery.datetimepicker.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'stype')->dropdownList($transportTypeList) ?></div>
		<div class="col-md-6"><?= $form->field($theForm, 'number') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'departure_port', ['inputOptions'=>['class'=>'form-control', 'placeholder'=>Yii::t('reg', 'Eg. "London - Heathrow" or "LHR"')]]) ?></div>
		<div class="col-md-6"><?= $form->field($theForm, 'departure_dt')->widget(DateTimePicker::classname(), [
			'options' => ['placeholder' => 'Enter event time ...'],
			'language' => 'vi',
			'pluginOptions' => [
				'format' => 'dd-mm-yyyy hh:ii'
			]
		]); ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'arrival_port', ['inputOptions'=>['class'=>'form-control', 'placeholder'=>Yii::t('reg', 'Eg. "Hanoi - Noi Bai" or "HAN"')]]) ?></div>
		<div class="col-md-6"><?= $form->field($theForm, 'arrival_dt')->widget(DateTimePicker::classname(), [
			'options' => ['placeholder' => 'Enter event time ...'],
			'language' => 'vi',
			'pluginOptions' => [
				'autoclose' => true,
				'format' => 'dd-mm-yyyy hh:ii',
				
			]
		]);?></div>
	</div>
	<?= $form->field($theForm, 'pax_ids', ['enableClientValidation'=>false, 'inputOptions'=>['class'=>'form-control', 'data-none-selected-text'=>Yii::t('reg', '- Select -')]])->dropdownList(ArrayHelper::map($bookingPax, 'id', 'name'), ['multiple'=>'multiple']) ?>
	<p><?= Html::submitButton(Yii::t('reg', 'Submit'), ['class'=>'btn btn-primary']) ?></p>
<?php
ActiveForm::end();
