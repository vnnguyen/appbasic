<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

//include('_products_inc.php');

$dayIdList = explode(',', $theProduct['day_ids']);

$title = explode(' -', $theProduct['title']);

$this->title = 'List of tour members';
$this->params['small'] = '<br>Confirmed';
$this->params['small'] .= ' | Code: '.$theProduct['op_code'];
$this->params['small'] .= ' | Date: '.date('j/n/Y', strtotime($theProduct['day_from']));

$genderList = [
	'male'=>'Male',
	'female'=>'Female',
];
$allCountries = [
	['code'=>'vn','name_en'=>'Vietnam']
];

for ($i = 1; $i <= 31; $i ++) {
	$dayList[$i] = $i;
}

for ($i = 1; $i <= 12; $i ++) {
	$monthList[$i] = $i.' - '.date('M', strtotime('2015-'.$i));
}

$thisYear = date('Y');
for ($i = $thisYear; $i >= 1900; $i --) {
	$yearList[$i] = $i;
}

?>
<style type="text/css">
input[type="text"], select, option, textarea {background-color:#eee!important;}
.box {padding:8px; border:1px solid #ccc; margin-bottom:2em;}
form h4 {color:brown;}
form label {font-weight:normal!important;}
</style>
<div class="col-md-9">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation"><a href="<?= DIR.SEG1 ?>/group#t-list">Member list</a></li>
		<li role="presentation" class="active"><a href="#t-edit" role="tab" data-toggle="tab">Edit member</a></li>
		<li role="presentation"><a href="<?= DIR.SEG1 ?>/group#t-submit">Confirm and submit</a></li>
	</ul>
	<br>

	<p><strong>Editing member information</strong>. Remember to click <kbd>Save</kbd> when done.</p>
	<? $form = ActiveForm::begin(); ?>

	<h4>Passport</h4>
	<p>Please type each field exactly as appears in your passport</p>
	<div class="box">
	<div class="row">
		<div class="col-md-4"><?=$form->field($theForm, 'pp_number') ?></div>
		<div class="col-md-8"><?=$form->field($theForm, 'pp_country_code')->dropdownList(ArrayHelper::map($allCountries, 'code', 'name_en')); ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'pp_name1') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'pp_name2') ?></div>
	</div>
	<div class="row">
		<div class="col-md-2"><?=$form->field($theForm, 'pp_bday')->dropdownList($dayList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-4"><?=$form->field($theForm, 'pp_bmonth')->dropdownList($monthList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-2"><?=$form->field($theForm, 'pp_byear')->dropdownList($yearList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-4"><?=$form->field($theForm, 'pp_gender')->dropdownList($genderList, ['prompt'=>'- Select -']) ?></div>
	</div>
	<div class="row">
		<div class="col-md-2"><?=$form->field($theForm, 'pp_iday')->label('Expity day')->dropdownList($dayList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-4"><?=$form->field($theForm, 'pp_imonth')->label('month')->dropdownList($monthList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-2"><?=$form->field($theForm, 'pp_iyear')->label('year')->dropdownList($yearList, ['prompt'=>'- Select -']) ?></div>
	</div>
	<div class="row">
		<div class="col-md-2"><?=$form->field($theForm, 'pp_eday')->label(false) ?></div>
		<div class="col-md-2"><?=$form->field($theForm, 'pp_emonth')->label(false) ?></div>
		<div class="col-md-2"><?=$form->field($theForm, 'pp_eyear')->label(false) ?></div>
	</div>
	</div>
	
	<h4>Contact information</h4>
	<div class="box">
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'email') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'website') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'tel') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'mobile') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'profession') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'position') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'place_of_birth') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'address') ?></div>
	</div>
	<?=$form->field($theForm, 'address') ?>
	</div>

	<h4>Entry visa(s)</h4>
	<div class="box">
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'v1_number')->label('Visa number') ?></div>
		<div class="col-md-2"><?= $form->field($theForm, 'v1_eday')->label('Expiry day') ?></div>
		<div class="col-md-2"><?= $form->field($theForm, 'v1_emonth')->label('month') ?></div>
		<div class="col-md-2"><?= $form->field($theForm, 'v1_eyear')->label('year') ?></div>
	</div>
	</div>

	<h4>Flight information</h4>
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'flight_1') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'flight_2') ?></div>
	</div>

	<h4>Travel insurance</h4>
	<div class="box">
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'in_name')->label('Insurance company') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'in_number')->label('Insurance policy number') ?></div>
	</div>
	<?=$form->field($theForm, 'in_address')->label('Contact address') ?>
	</div>

	<h4>The person to contact in case of emergency</h4>
	<div class="box">
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'em_name')->label('Name') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'em_relation')->label('Relationship') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'em_tel')->label('Telephone number') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'em_email')->label('Email address') ?></div>
	</div>
	<?=$form->field($theForm, 'em_address')->label('Contact address') ?>
	</div>

	<h4>Rooming request</h4>
	<div class="row">
		<div class="col-md-6"><?=$form->field($theForm, 'room_1') ?></div>
		<div class="col-md-6"><?=$form->field($theForm, 'room_2') ?></div>
	</div>

	<h4>Other notes about this member</h4>
	<?=$form->field($theForm, 'note')->textArea(['rows'=>5]) ?>

	


	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
<? include('_sb.php');