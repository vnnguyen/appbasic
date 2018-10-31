<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

include('_reg_inc.php');

if ($action == 'edit') {
	$this->title = Yii::t('reg', 'Tour registration').' : '.$thePax['name'];
} elseif ($action == 'list') {
	$this->title = Yii::t('reg', 'Tour registration').' : '.Yii::t('reg', 'Travellers');
}

$msg = <<<'TXT'
<p>Please enter the names and details for all members of your travel group.</p>
<ul>
	<li>Step 1: Input the name of each person of your group in the form below and click "Add traveller". Repeat this for all travellers.</li>
	<li>Step 2: Click the name of each person in the list to edit or delete his/her details.</li>
</ul>
TXT;

if (Yii::$app->language == 'fr') {
	$msg = <<<'TXT'
<p>Veuillez entrer les noms et détails concernant chaque membre de votre groupe.</p>
<ul>
	<li>1re étape : saisissez le nom de chaque personne de votre groupe dans le formulaire ci-dessous et cliquez sur « Ajouter ». Répétez l’opération pour chaque voyageur.</li>
	<li>2e étape : cliquez sur le nom de chaque personne de la liste pour entrer ou supprimer les détails les concernant.</li>
</ul>
TXT;
} elseif (Yii::$app->language == 'vi') {
	$msg = <<<'TXT'
<p>Hãy nhập tên và các thông tin khác cho mọi người trong nhóm tour của quý khách.</p>
<ul>
	<li>Bước 1: Nhập tên của từng người trong nhóm vào form dưới đây và click "Thêm khách". Lặp lại bước này cho tất cả mọi người.</li>
	<li>Bước 2: Click tên của từng người trong danh sách mới nhập để sửa hoặc xoá thông tin.</li>
</ul>
TXT;
}

?>
<div class="col-md-9">
	<?php include('_reg_tabs.php') ?>

	<?php if ($action == 'edit') { ?>

	<?php include('_form_traveller.php') ?>

	<?php } elseif ($action == 'list') { ?>

	<?= $msg ?>

	<hr>
	<h4><?= Yii::t('reg', 'Current traveller list') ?></h4>
	<?php if (empty($bookingPax)) { ?>
	<p><?= Yii::t('reg', 'No data found.') ?></p>
	<?php } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="30"></th>
					<th><?= Yii::t('reg', 'Name') ?> (<?= Yii::t('reg', 'Click to modify') ?>)</th>
					<th><?= Yii::t('reg', 'Gender') ?></th>
					<th><?= Yii::t('reg', 'Date of birth') ?></th>
					<th><?= Yii::t('reg', 'Nationality') ?></th>
					<th><?= Yii::t('reg', 'Passport number') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php $cnt = 0;
			foreach ($bookingPax as $pax) { ?>
			<tr>
				<td class="text-center"><?= ++$cnt ?></td>
				<td>
					<? if ($pax['passport_file'] != '') { ?>
					<?= Html::a(Html::img($pax['passport_file'] .'-/resize/20x20/'), $pax['passport_file']) ?>
					<? } ?>
					<strong><?= Html::a($pax['name'], DIR.URI.'?action=edit&paxid='.$pax['id']) ?></strong></td>
				<td><?= isset($pax['vars']['pp_gender']) ? Yii::t('reg', ucfirst($pax['vars']['pp_gender'])) : '' ?></td>
				<td><?= isset($pax['vars']['pp_bday']) ? $pax['vars']['pp_bday'] : '' ?>/<?= isset($pax['vars']['pp_bmonth']) ? $pax['vars']['pp_bmonth'] : '' ?>/<?= isset($pax['vars']['pp_byear']) ? $pax['vars']['pp_byear'] : '' ?></td>
				<td><?php
				if (isset($pax['vars']['pp_country_code'])) {
					foreach ($countryList as $country) {
						if ($country['code'] == $pax['vars']['pp_country_code']) {
							echo $country['name'];
						}
					}
				}
				?>
				</td>
				<td><?= isset($pax['vars']['pp_number']) ? $pax['vars']['pp_number'] : '' ?></td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php } ?>

	<h4><?= Yii::t('reg', 'Add a traveller') ?></h4>
	<form method="post" action="" class="form-inline">
		<input type="text" class="form-control" name="name" value="" placeholder="<?= Yii::t('reg', 'Enter name here') ?>">
		<?= Html::submitButton(Yii::t('reg', 'Add'), ['class'=>'btn btn-primary']) ?>
	</form>
	<?php } // if action edit ?>
</div>
<?php
include('_reg_sb.php');

$js = <<<'TXT'
$('a.text-danger').click(function(){
	if (!confirm('This will be deleted / Cette information sera supprimé / Thông tin này sẽ bị xoá!')) {
		return false;
	}
});
TXT;

$this->registerJs($js);