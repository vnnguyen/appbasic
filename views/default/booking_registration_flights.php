<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


include('_reg_inc.php');

$this->title = Yii::t('reg', 'Tour registration').' : '.Yii::t('reg', 'Arrival & Departure');

$msg = <<<'TXT'
<p>If your group travel across borders, please specify your international flights/vessels/vehicles, including any transits.</p>
TXT;

if (Yii::$app->language == 'fr') {
	$msg = <<<'TXT'
<p>Si vous franchissez une ou plusieurs frontières, veuillez spécifier vos vols/navires/véhicules internationaux, y compris les transits.</p>
TXT;
} elseif (Yii::$app->language == 'vi') {
	$msg = <<<'TXT'
<p>Nếu nhóm của quý khách đến và đi qua biên giới, xin hãy cho biết chi tiết các chuyến bay/tàu/xe quý khách sẽ đi, kể cả chuyến transit.</p>
TXT;
}

?>
<div class="col-md-9">
	<?php include('_reg_tabs.php') ?>

	<?php if ($action == 'list') { ?>

	<?= $msg ?>
	<hr>
	<h4><?= Yii::t('reg', 'Current transportation list') ?></h4>
	<?php if (empty($bookingFlights)) { ?>
	<p><?= Yii::t('reg', 'No data found. Start adding flights using the form below.') ?></p>
	<?php } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered" style="border-left:0; border-right:0;">
			<thead>
				<tr>
					<th width="30"></th>
					<th colspan="3"><?= Yii::t('reg', 'Information') ?></th>
					<th width="60"><?= Yii::t('reg', 'Modify') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $cnt = 0; foreach ($bookingFlights as $flight) { ?>
				<tr><td colspan="5" style="border:0;"></td></tr>
				<tr>
					<td class="text-center" rowspan="4"><?= ++$cnt ?></td>
					<td><strong><?php
					if (isset($transportTypeList[$flight['stype']])) {
						echo $transportTypeList[$flight['stype']];
					} else {
						echo $flight['stype'];
					}
					?></strong>
					</td>
					<td colspan="2"><?= $flight['number'] ?></td>
					<td rowspan="4">
						<?= Html::a(Yii::t('reg', 'Modify'), DIR.URI.'?action=edit&flightid='.$flight['id']) ?>
					</td>
				</tr>
				<tr>
					<td><strong><?= Yii::t('reg', 'Departure') ?></strong></td>
					<td><?= $flight['departure_port'] ?></td>
					<td><?= date('j/n/Y H:i', strtotime($flight['departure_dt'])) ?></td>
				</tr>
				<tr>
					<td><strong><?= Yii::t('reg', 'Arrival') ?></strong></td>
					<td><?= $flight['arrival_port'] ?></td>
					<td><?= date('j/n/Y H:i', strtotime($flight['arrival_dt'])) ?></td>
				</tr>
				<tr>
					<td><strong><?= Yii::t('reg', 'Passengers') ?></strong></td>
					<td colspan="2"><?php
					$paxIdList = explode(',', $flight['pax_ids']);
					foreach ($bookingPax as $pax) {
						if (in_array($pax['id'], $paxIdList)) {
							echo '<div>', $pax['name'], '</div>';
						}
					}
					?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php } ?>

	<hr>
	<h4><?= Yii::t('reg', 'Add a flight/vessel/vehicle') ?></h4>
	<?php include('_form_flight.php'); ?>

	<?php } elseif ($action == 'edit') { ?>

	<p class="text-right">
		<?= Html::a(Yii::t('reg', 'Return to the list'), DIR.URI) ?>
		|
		<?= Html::a(Yii::t('reg', 'Delete this information'), DIR.URI.'?action=delete&flightid='.$flightid, ['class'=>'text-danger']) ?>
	</p>
	<h4><?= Yii::t('reg', 'Edit information') ?></h4>
	<?php include('_form_flight.php'); ?>

	<p class="text-right">
		<?= Html::a(Yii::t('reg', 'Return to the list'), DIR.URI) ?>
		|
		<?= Html::a(Yii::t('reg', 'Delete this information'), DIR.URI.'?action=delete&flightid='.$flightid, ['class'=>'text-danger']) ?>
	</p>

	<?php } // if action list ?>
</div>
<?php
include('_reg_sb.php');
$js = <<<'TXT'
 $('#bookingflightform-pax_ids').selectpicker({
  size: 4
});

$('a.text-danger').click(function(){
	if (!confirm('This will be deleted / Cette information sera supprimé / Thông tin này sẽ bị xoá!')) {
		return false;
	}
});
TXT;

// $this->registerCssFile(DIR.'assets/bootstrap-datetimepicker_4.7.14/bootstrap-datetimepicker.css', ['depends'=>'app\assets\MainAsset']);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js', ['depends'=>'app\assets\MainAsset']);
// $this->registerJsFile(DIR.'assets/bootstrap-datetimepicker_4.7.14/bootstrap-datetimepicker.js', ['depends'=>'app\assets\MainAsset']);

$this->registerJs($js);