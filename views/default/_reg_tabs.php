	<ul class="nav nav-tabs">
		<li role="presentation" class="<?= SEG4 == '' ? 'active' : '' ?>"><a href="<?= DIR.SEG1.DIR.SEG2.DIR.SEG3 ?>/registration"><?= Yii::t('reg', 'Start') ?></a></li>
		<li role="presentation" class="<?= SEG4 == 'travellers' ? 'active' : '' ?>"><a href="<?= DIR.SEG1.DIR.SEG2.DIR.SEG3?>/registration/travellers"><?= Yii::t('reg', 'Travellers') ?></a></li>
		<li role="presentation" class="<?= SEG4 == 'flights' ? 'active' : '' ?>"><a href="<?= DIR.SEG1.DIR.SEG2.DIR.SEG3 ?>/registration/flights"><?= Yii::t('reg', 'Arrival & Departure') ?></a></li>
		<li role="presentation" class="<?= SEG4 == 'rooms' ? 'active' : '' ?>"><a href="<?= DIR.SEG1.DIR.SEG2.DIR.SEG3 ?>/registration/rooms"><?= Yii::t('reg', 'Hotel rooms') ?></a></li>
		<li role="presentation" class="<?= SEG4 == 'submit' ? 'active' : '' ?>"><a href="<?= DIR.SEG1.DIR.SEG2.DIR.SEG3 ?>/registration/submit"><?= Yii::t('reg', 'Finish & Submit') ?></a></li>
	</ul>
	<br>
