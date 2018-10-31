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

$userGenderList = [];
$allCountries = [];

?>
<div class="col-md-9">
	<h4>Instruction</h4>
	<p>Please input registration details for all members of your travel group using this page.</p>
	<ul>
		<li>To add a new traveller, use the form <em>Add a new person</em> below. Names of people you have added are shown in summary table below.</li>
		<li>To modify somebody's details, click their name in the summary table.</li>
		<li>To delete a person from the list, click their name in the summary table and select <em>Delete</em> on the next page.</li>
	</ul>

	<? if ($action == 'edit') { ?>
	<hr>
	<h4>Edit registration information for: <?= $thePax['name'] ?></h4>
	<? include('_pax_form.php') ?>
	<? } ?>
	<hr>
	<h4>Summary table</h4>
	<? if (empty($thePaxList)) { ?>
	<p>No data found.</p>
	<? } else { ?>
	<p>This is the list of all travellers in this tour. You can add, edit or delete any of them.</p>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="30"></th>
					<th>Name of traveller (click to modify)</th>
					<th>Group</th>
					<th>Room</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			<? $cnt = 0;
			foreach ($thePaxList as $pax) { ?>
			<tr>
				<td><?= ++$cnt ?></td>
				<td><strong><?= Html::a($pax['name'], '@web/'.SEG1.'/group?action=edit&paxid='.$pax['id']) ?></strong></td>
				<td><?= $pax['group'] ?></td>
				<td><?= $pax['room'] ?></td>
				<td></td>
			</tr>
			<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>

	<hr>
	<h4>Add a new person</h4>
	<p>Enter the name of that person below and press <em>Add person</em> button</p>
	<form method="post" action="" class="form-inline">
		<input type="text" class="form-control" name="name" value="">
		<?= Html::submitButton('Add person', ['class'=>'btn btn-primary']) ?>
	</form>
</div>
<? include('_sb.php');