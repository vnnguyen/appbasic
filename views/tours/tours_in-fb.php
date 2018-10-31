<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

require_once('_tours_inc.php');

$this->title = Yii::t('tours_in-fb', 'Print tour feedback form').' - '.$theTour['op_code'];

if ($theCompany) {
	$logoOptionList = [
		'them'=>'Logo and name of '. $theCompany['name'],
		'both'=>'Logo and name of Amica Travel as a receptive agency for '. $theCompany['name'],
		'us'=>'Logo and name of Amica Travel',
		'none'=>'No logo, only name of '. $theCompany['name'],
		'voyages-villegia'=>'SPECIAL: Name and logo of Voyages Villegia (for Plani-Corpo)',
	];
}

$languageList = [
	'en'=>'English',
	'fr'=>'Francais',
	'vi'=>'Tiếng Việt',
];

$dayIdList = explode(',', $theTour['day_ids']);
$dayCnt = [];
$cnt = 0;
foreach ($dayIdList as $id) {
	$cnt ++;
	$dayCnt[$cnt] = $cnt;
}

?>
<div class="col-md-8">
	<div class="alert alert-info">
		<strong><?= Yii::t('tours_in-fb', 'CHÚ Ý');?>:</strong><br>
		- <?= Yii::t('tours_in-fb', 'Nếu có nhiều tên guide / lái xe thì điền các tên cách nhau bằng dấu phẩy');?><br>
		- <?= Yii::t('tours_in-fb', 'Nếu để trống phần tên guide / lái xe thì form sẽ không in ra câu hỏi cho phần đó');?><br>
		- <?= Yii::t('tours_in-fb', 'Nếu không biết tên guide / lái xe mà vẫn muốn in câu hỏi phần đó ra form thì phải điền tên là');?> <kbd>yes</kbd><br>
	</div>
	<? $form = ActiveForm::begin(); ?>

	<? if ($theCompany) { ?>
	<?= $form->field($theForm, 'logoName')->dropdownList($logoOptionList)->label(Yii::t('tours_in-fb', 'This tour is from ').$theCompany['name'].'. '.Yii::t('tours_in-fb', 'Select a logo option')) ?>
	<? } ?>

	<div class="row">
		<div class="col-md-3"><?= $form->field($theForm, 'language')->dropDownList($languageList) ?></div>
		<div class="col-md-5"><?= $form->field($theForm, 'paxName') ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'regionName') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'guideNames') ?></div>
		<div class="col-md-6"><?= $form->field($theForm, 'driverNames') ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton('Print form', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
	<p><strong><?= Yii::t('tours_in-fb', 'TOUR ITINERARY');?></strong></p>
	<ul class="list-unstyled">
<?
$cnt = 0;
foreach ($dayIdList as $id) {
	foreach ($theTour['days'] as $day) {
		if ($id == $day['id']) {
			$cnt ++;
?>
		<li><strong><?= $cnt ?></strong> - <?= $day['name'] ?></li>
<?
		}
	}
}
?>
	</ul>
</div>