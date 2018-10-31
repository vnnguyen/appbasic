<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;



for ($i = 1; $i <= 31; $i ++) {
	$dayList[$i] = $i;
}

for ($i = 1; $i <= 12; $i ++) {
	$monthList[$i] = $i.' - '.Yii::t('reg', date('F', strtotime('2015-'.$i)));
}

$thisYear = date('Y');
for ($i = $thisYear; $i >= 1900; $i --) {
	$yearList[$i] = $i;
}
for ($i = 30 + $thisYear; $i >= $thisYear; $i --) {
	$yearListExtended[$i] = $i;
}




?>

<? $form = ActiveForm::begin(); ?>
<p class="text-right">
	<?= Html::a(Yii::t('reg', 'Return to the traveller list'), DIR.URI) ?>
	|
	<?= Html::a(Yii::t('reg', 'Delete this traveller'), DIR.URI.'?action=delete&paxid='.$thePax['id'], ['class'=>'text-danger']) ?>
</p>
<h4><?= Yii::t('reg', 'Name of this traveller') ?></h4>
<div class="row">
	<div class="col-md-6"><?=$form->field($theForm, 'name')->label(false) ?></div>
</div>

<h4><?= Yii::t('reg', 'Passport information') ?></h4>
<p><strong><?= Yii::t('reg', 'Please type each field exactly as appears in the passport') ?></strong>.</p>
<p><?= Yii::t('reg', 'If this person does not have/need a passport, please leave the passport number blank') ?>.</p>
<p><?= Yii::t('reg', 'If you can take a photo or scan of the passport, please also email a copy to your travel consultant') ?>.</p>
<div class="row">
	<div class="col-md-4"><?= $form->field($theForm, 'pp_number') ?></div>
	<div class="col-md-8"><?= $form->field($theForm, 'pp_country_code')->dropdownList(ArrayHelper::map($countryList, 'code', 'name'), ['prompt'=>Yii::t('reg', '- Select -')]); ?></div>
</div>
<div class="row">
	<div class="col-md-6"><?= $form->field($theForm, 'pp_name_1') ?></div>
	<div class="col-md-6"><?= $form->field($theForm, 'pp_name_2') ?></div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="xlabel"><?= Yii::t('reg', 'Date of birth') ?></div>
		<div class="row">
			<div class="col-md-3 col-xs-4"><?=$form->field($theForm, 'pp_bday')->label(false)->dropdownList($dayList, ['prompt'=>yii::t('reg', 'Day')]) ?></div>
			<div class="col-md-5 col-xs-4"><?=$form->field($theForm, 'pp_bmonth')->label(false)->dropdownList($monthList, ['prompt'=>yii::t('reg', 'Month')]) ?></div>
			<div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_byear')->label(false)->dropdownList($yearList, ['prompt'=>yii::t('reg', 'Year')]) ?></div>
		</div>
	</div>
	<div class="col-md-3"><?= $form->field($theForm, 'pp_gender')->dropdownList($genderList, ['prompt'=>Yii::t('reg', '- Select -')]) ?></div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="xlabel"><?= Yii::t('reg', 'Passport issue date') ?></div>
		<div class="row">
			<div class="col-md-3 col-xs-4"><?=$form->field($theForm, 'pp_iday')->label(false)->dropdownList($dayList, ['prompt'=>yii::t('reg', 'Day')]) ?></div>
			<div class="col-md-5 col-xs-4"><?=$form->field($theForm, 'pp_imonth')->label(false)->dropdownList($monthList, ['prompt'=>yii::t('reg', 'Month')]) ?></div>
			<div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_iyear')->label(false)->dropdownList($yearList, ['prompt'=>yii::t('reg', 'Year')]) ?></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="xlabel"><?= Yii::t('reg', 'Passport expiry date') ?></div>
		<div class="row">
			<div class="col-md-3 col-xs-4"><?=$form->field($theForm, 'pp_eday')->label(false)->dropdownList($dayList, ['prompt'=>yii::t('reg', 'Day')]) ?></div>
			<div class="col-md-5 col-xs-4"><?=$form->field($theForm, 'pp_emonth')->label(false)->dropdownList($monthList, ['prompt'=>yii::t('reg', 'Month')]) ?></div>
			<div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_eyear')->label(false)->dropdownList($yearListExtended, ['prompt'=>yii::t('reg', 'Year')]) ?></div>
		</div>
	</div>
</div>



<h4><?= Yii::t('reg', 'Contact information') ?></h4>
<div class="row">
	<div class="col-md-6"><?=$form->field($theForm, 'tel_1') ?></div>
	<div class="col-md-6"><?=$form->field($theForm, 'tel_2') ?></div>
</div>
<div class="row">
	<div class="col-md-6"><?=$form->field($theForm, 'email') ?></div>
	<div class="col-md-6"><?=$form->field($theForm, 'website') ?></div>
</div>
<div class="row">
	<div class="col-md-6"><?=$form->field($theForm, 'profession') ?></div>
	<div class="col-md-6"><?=$form->field($theForm, 'place_of_birth') ?></div>
</div>
<?=$form->field($theForm, 'address') ?>

<h4><?= Yii::t('reg', 'Entry visa for Vietnam') ?></h4>
<?= $form->field($theForm, 'visa_vn_arrival')->dropdownList($visaYesNoList, ['prompt'=>Yii::t('reg', '- Select -')]) ?>

<h4><?= Yii::t('reg', 'Method of payment') ?></h4>
<div class="row">
	<div class="col-md-6"><?= $form->field($theForm, 'pay_deposit')->dropdownList($paymentMethodList, ['prompt'=>Yii::t('reg', '- Select -')]) ?></div>
	<div class="col-md-6"><?= $form->field($theForm, 'pay_balance')->dropdownList($paymentMethodList, ['prompt'=>Yii::t('reg', '- Select -')]) ?></div>
</div>
<p><?= Yii::t('reg', 'NOTE: In case traveller pays via bank transfer or credit card, a small processing fee will apply. Contact us for detail.') ?></p>

<h4><?= Yii::t('reg', 'Travel insurance') ?></h4>
<div class="row">
	<div class="col-md-6"><?=$form->field($theForm, 'in_name') ?></div>
	<div class="col-md-6"><?=$form->field($theForm, 'in_number') ?></div>
</div>
<div class="row">
	<div class="col-md-6"><?=$form->field($theForm, 'in_tel') ?></div>
	<div class="col-md-6"><?=$form->field($theForm, 'in_email') ?></div>
</div>

<h4><?= Yii::t('reg', 'The person to contact in case of emergency' ) ?></h4>
<div class="row">
	<div class="col-md-6"><?=$form->field($theForm, 'em_name') ?></div>
	<div class="col-md-6"><?=$form->field($theForm, 'em_relation') ?></div>
</div>
<div class="row">
	<div class="col-md-6"><?=$form->field($theForm, 'em_tel') ?></div>
	<div class="col-md-6"><?=$form->field($theForm, 'em_email') ?></div>
</div>

<h4><?= Yii::t('reg', 'Other notes about this person' ) ?> (<?= $thePax['name'] ?>)</h4>
<?= $form->field($theForm, 'note')->textArea(['rows'=>10])->label(Yii::t('reg', 'Health conditions, meals and other special requests')) ?>

<div>
	<?=Html::submitButton(Yii::t('reg', 'Submit'), ['class' => 'btn btn-primary']); ?>
</div>
<p class="text-right">
	<?= Html::a(Yii::t('reg', 'Return to the traveller list'), DIR.URI) ?>
	|
	<?= Html::a(Yii::t('reg', 'Delete this traveller'), DIR.URI.'?action=delete&paxid='.$thePax['id'], ['class'=>'text-danger']) ?>
</p>

<? ActiveForm::end(); ?>
