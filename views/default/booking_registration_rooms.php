<?
use \Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

include('_reg_inc.php');

$this->title = Yii::t('reg', 'Tour registration').' : '.Yii::t('reg', 'Hotel rooms');

$paxWithRoom = [];
$ids = '';
foreach ($bookingRooms as $room) {
	$ids .= ','.$room['pax_ids'];
}
$paxWithRoom = explode(',', $ids);

$paxWithoutRoom = [];
foreach ($bookingPax as $pax) {
	if (!in_array($pax['id'], $paxWithRoom)) {
		$paxWithoutRoom[] = $pax;
	}
}

if ($action == 'edit') {
	foreach ($bookingRooms as $room) {
		if ($room['id'] == $roomid) {
			$paxWithoutRoomIds = explode(',', $room['pax_ids']);
		}
	}
	$paxWithoutRoomEdit = $paxWithoutRoom;
	foreach ($bookingPax as $pax) {
		if (in_array($pax['id'], $paxWithoutRoomIds)) {
			$paxWithoutRoomEdit[] = $pax;
		}
	}
}

?>
<div class="col-md-9">
	<? include('_reg_tabs.php') ?>

	<? if ($action == 'list') { ?>
	<p><?= Yii::t('reg', 'Please specify how many rooms and of what types your group will need, and who will stay in each of the rooms.') ?></p>

	<hr>
	<h4><?= Yii::t('reg', 'Current room list') ?></h4>
	<? if (empty($bookingRooms)) { ?>
	<p><?= Yii::t('reg', 'No information found. Please add rooms using the form below.') ?></p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered" style="border-left:0; border-right:0;">
			<thead>
				<tr>
					<th width="50"><?= Yii::t('reg', 'Room') ?></th>
					<th><?= Yii::t('reg', 'Room type') ?></th>
					<th><?= Yii::t('reg', 'Person(s)') ?></th>
					<th><?= Yii::t('reg', 'Modify') ?></th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($bookingRooms as $room) { ?>
				<tr><td colspan="5" style="border:0;"></td></tr>
				<tr>
					<td class="text-center"><?= ++$cnt ?></td>
					<td><?
					if (isset($roomTypeList[$room['room_type']])) {
						echo $roomTypeList[$room['room_type']];
					} else {
						echo $room['room_type'];
					}
					?></td>
					<td><?
					$paxIdList = explode(',', $room['pax_ids']);
					foreach ($bookingPax as $pax) {
						if (in_array($pax['id'], $paxIdList)) {
							echo '<div>', $pax['name'], '</div>';
						}
					}
					?></td>
					<td><?= $theUser['id'] == $room['created_by'] ? Html::a(Yii::t('reg', 'Modify'), DIR.URI.'?action=edit&roomid='.$room['id']) : '' ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>

	<? } ?>


	<? if ($action == 'edit') { ?>
	<p class="text-right">
		<?= Html::a(Yii::t('reg', 'Return to the list'), DIR.URI) ?>
		|
		<?= Html::a(Yii::t('reg', 'Delete this information'), DIR.URI.'?action=delete&roomid='.$roomid, ['class'=>'text-danger']) ?>
	</p>
	<h4><?= Yii::t('reg', 'Edit information') ?></h4>
	<? include('_form_room.php'); ?>

	<p class="text-right">
		<?= Html::a(Yii::t('reg', 'Return to the list'), DIR.URI) ?>
		|
		<?= Html::a(Yii::t('reg', 'Delete this information'), DIR.URI.'?action=delete&roomid='.$roomid, ['class'=>'text-danger']) ?>
	</p>

	<? } else { ?>

	<? if (!empty($paxWithoutRoom)) { ?>
	<h4><?= Yii::t('reg', 'You haven\'t specify a room for the following person(s)') ?></h4>
	<ul>
		<? foreach ($paxWithoutRoom as $pax) { ?>
		<li><?= $pax['name'] ?></li>
		<? } ?>
	</ul>

	<hr>
	<h4><?= Yii::t('reg', 'Add a room') ?></h4>
	<? include('_form_room.php'); ?>

	<? } // if !empty woRoom ?>


	<? } // if action edit ?>
</div>
<?
include('_reg_sb.php');
$js = <<<'TXT'
$('#bookingroomform-pax_ids').selectpicker();
$('a.text-danger').click(function(){
	if (!confirm('This will be deleted / Cette information sera supprimé / Thông tin này sẽ bị xoá!')) {
		return false;
	}
});
TXT;
$this->registerJs($js);

