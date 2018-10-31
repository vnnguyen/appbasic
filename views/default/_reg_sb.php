<?php
use yii\helpers\Html;
?>
<div class="col-lg-3 hidden-print">
	<?php /*
	<div class="panel panel-default">
		<div class="panel-heading text-center"><?= Yii::t('reg', 'The tour') ?></div>
		<div class="list-group">
			<a href="<?= DIR.SEG1 ?>/itinerary" class="list-group-item<?=SEG2 == 'itinerary' ? ' active' : '' ?>"><?= Yii::t('reg', 'Tour itinerary') ?></a>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading text-center"><?= Yii::t('reg', 'The travellers') ?></div>
		<div class="list-group">
			<a href="<?= DIR.SEG1 ?>/group" class="list-group-item<?=SEG2 == 'group' ? ' active' : '' ?>"><?= Yii::t('reg', 'List of travellers') ?></a>
			<a href="<?= DIR.SEG1 ?>/flights" class="list-group-item<?=SEG2 == 'flights' ? ' active' : '' ?>"><?= Yii::t('reg', 'Incoming & outgoing flights') ?></a>
			<a href="<?= DIR.SEG1 ?>/rooms" class="list-group-item<?=SEG2 == 'rooms' ? ' active' : '' ?>"><?= Yii::t('reg', 'Groups and rooming list') ?></a>
			<a href="<?= DIR.SEG1 ?>/payments" class="list-group-item<?=SEG2 == 'payments' ? ' active' : '' ?>"><?= Yii::t('reg', 'Payment information') ?></a>
		</div>
	</div>
*/?>
	<?php if (isset($theUser)) { ?>
	<div class="panel panel-default">
		<div class="panel-heading text-center"><?= Yii::t('reg', 'Your information'); ?></div>
		<div class="panel-body text-center">
			<h4><?= $theUser['name']; ?></h4>
		</div>
	</div>
	<?php } ?>

	<div class="panel panel-default">
		<div class="panel-heading text-center"><?= Yii::t('reg', 'Your travel consultant') ?></div>
		<div class="panel-body text-center">
			<div><img class="img-circle" style="xmargin-top:-45px; border:1px solid #ccc; padding:4px; width:50%;" src="https://my.amicatravel.com/timthumb.php?src=<?= isset($theBooking['case']['owner']['image']) ?$theBooking['case']['owner']['image']:'' ?>&w=100&h=100"></div>
			<h4><?= $theBooking['case']['owner']['fname']; ?> <?= $theBooking['case']['owner']['lname']; ?></h4>
			<div><i class="fa fa-comment-o"></i> <?= Html::a(Yii::t('reg', 'Contact her'), '@web/'.SEG1); ?></div>
		</div>
	</div>
</div>
