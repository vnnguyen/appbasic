<?
use yii\helpers\Html;

$this->title = Yii::t('reg', 'My tours');

?>
<div class="col-md-12">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>View</th>
				<th>Destination</th>
				<th>Time</th>
				<th>Itinerary</th>
				<th>Pax</th>
				<th>Status</th>
				<th>Consultant</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theCases as $case) { ?>
			<tr>
				<td><?= Html::a('View', '@web/tours/'.$case['id']) ?></td>
				<td><?= $case['stats']['pa_destinations'] ?? 'Vietnam' ?></td>
				<td><?= $case['stats']['pa_start_date'] ?? date('Y', strtotime($case['created_at'])) ?></td>
				<td><?= $case['stats']['pa_days'] ?> days</td>
				<td><?= $case['stats']['pa_pax'] ?></td>
				<td><?= $case['deal_status'] == 'won' ? 'CONFIRMED' : '' ?></td>
				<td><?= $case['owner']['name'] ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>
