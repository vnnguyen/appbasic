<?
use yii\helpers\Html;

$this->title = Yii::t('reg', 'Welcome');

//$this->params['small'] = '<br>'.Yii::t('reg', 'Tour code').': '.$theProduct['op_code'];
//$this->params['small'] .= ' | '.Yii::t('reg', 'Start date').': '.date('j/n/Y', strtotime($theProduct['day_from']));

?>
<div class="col-md-9">
	<h4><?= Yii::t('reg', 'Your upcoming tour') ?></h4>
	<ul>
		<li><?= Yii::t('reg', 'Tour code') ?>: <?= $theProduct['op_code'] ?></li>
		<li><?= Yii::t('reg', 'Tour name') ?>: <?= $theProduct['title'] ?></li>
		<li><?= Yii::t('reg', 'Start date') ?>: <?= date('j/n/Y', strtotime($theProduct['day_from'])) ?></li>
	</ul>
	<p><?= Html::a(Yii::t('reg', 'Click here to view the itinerary'), DIR.SEG1.'/itinerary') ?></p>

	<br>

	<h4><?= Yii::t('reg', 'Tour registration') ?></h4>
	<p><?= Html::a(Yii::t('reg', 'Click here'), DIR.SEG1.'/registration') ?></p>
	<br>

	<h4><?= Yii::t('reg', 'Your travel consultant') ?>: <?= $theBooking['case']['owner']['fname'] ?> <?= $theBooking['case']['owner']['lname'] ?></h4>
	<p>Office: +84 4 6273 4455</p>
	<p>Mobile: <?= $theBooking['case']['owner']['phone'] ?></p>
	<p>Email: <?= $theBooking['case']['owner']['email'] ?></p>
</div>
<?
include('_reg_sb.php');