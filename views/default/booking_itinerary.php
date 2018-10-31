<?
use yii\helpers\Html;
use yii\helpers\Markdown;

//include('_products_inc.php');

$dayIdList = explode(',', $theBooking['product']['day_ids']);
$title = explode(' -', $theBooking['product']['title']);

$this->title = $title[0];
$this->params['small'] = '<br>'.Yii::t('reg', 'Tour code').': '.$theBooking['product']['op_code'];
$this->params['small'] .= ' | '.Yii::t('reg', 'Start date').': '.date('j/n/Y', strtotime($theBooking['product']['day_from']));

?>
<div class="col-md-9">
	<? if ($theBooking['product']['image'] != '') { ?>
	<p><img class="img-responsive img-thumbnail" src="https://my.amicatravel.com/upload/devis-banners/<?= $theBooking['product']['image'] ?>"></p>
	<? } ?>
	<? if (file_exists('/var/www/my.amicatravel.com/upload/devis-pdf/devis-'.$theBooking['product']['id'].'.pdf')) { ?>
	<p class="text-center">
	<?= Html::img('https://ssl.gstatic.com/docs/doclist/images/mediatype/icon_1_pdf_x16.png') ?>
	<?= Html::a('Download PDF program', '@web/'.SEG1.'/itinerary-download') ?>
	</p>
	<? } ?>


	<div role="tabpanel">
		<!-- Nav tabs -->
		<?/*
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#t-overview" aria-controls="home" role="tab" data-toggle="tab">Overview</a></li>
			<li role="presentation"><a href="#t-itinerary" aria-controls="t-itinerary" role="tab" data-toggle="tab">Itinerary</a></li>
			<li role="presentation"><a href="#t-price" aria-controls="t-price" role="tab" data-toggle="tab">Prices</a></li>
			<li role="presentation"><a href="#t-terms" aria-controls="t-terms" role="tab" data-toggle="tab">Terms & Conditions</a></li>
		</ul>
		<br>*/ ?>
		<!-- Tab panes -->
		<? /*
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="t-overview">
				<table class="table table-condensed table-summary">
					<tbody>
						<? if ($theProduct['op_status'] == 'op') { ?>
						<tr>				
							<td class="text-nowrap;"><strong>Operated as:</strong></td><td><?= Html::a($theProduct['op_code'].' - '.$theProduct['op_name'], '@web/tours/r/'.$theProduct['id']) ?></td>
						</tr>
						<? } ?>
						<tr>
							<td><strong>Start date:</strong></td><td><?= date('d-m-Y (D)', strtotime($theProduct['day_from'])) ?> | <?= $theProduct['day_count'] ?> days</td>
						</tr>
						<tr>
							<td>
								<strong>Price:</strong></td><td><?= number_format($theProduct['price'], 0) ?> <?= $theProduct['price_unit'] ?> / <?= $theProduct['price_for'] ?>
								<span class="text-muted">Valid until <?= date('d-m-Y', strtotime($theProduct['price_until'])) ?></span>
							</td>
						</tr>
						<tr>
							<td><strong>About:</strong></td><td><?= $theProduct['about'] ?></td>
						</tr>
						<tr>
							<td><strong>Updated:</strong></td><td><?= $theProduct['updatedBy']['name'] ?>, <?= Yii::$app->formatter->asDatetime($theProduct['updated_at']) ?> (UTC)</td>
						</tr>
					</tbody>
				</table>
				<p><strong>INTRO</strong></p>
				<div class="mb-1em"><?= Markdown::process($theProduct['intro']) ?></div>
				<p><strong>KEY POINTS</strong></p>
				<div class="mb-1em"><?= Markdown::process($theProduct['points']) ?></div>
			</div><!-- #t-overview -->
			<div role="tabpanel" class="tab-pane" id="t-itinerary">
	<p>
		<? if (file_exists('/var/www/my.amicatravel.com/upload/devis-pdf/devis-'.$theProduct['id'].'.pdf')) { ?>
		<strong><?= Html::img('https://ssl.gstatic.com/docs/doclist/images/mediatype/icon_1_pdf_x16.png') ?> PDF</strong>
		<?= Html::a('Download', '@web/products/download/'.$theProduct['id']) ?>
		|
		<? } ?>
		<strong>FULL ITINERARY</strong>
		Days: <? $cnt = 0; foreach ($dayIdList as $id) { $cnt ++; if ($cnt != 1) {echo ' &middot; ';} echo Html::a($cnt, '#ngay-'.$id);} ?>
		&middot; <a href="#" class="text-danger" onclick="$('.day-content').toggle(); return false;">Toggle details</a>
	</p>
	*/?>
			<?
			$cnt = 0;
			$lastId = 0;
			foreach ($dayIdList as $id) {
				foreach ($theBooking['product']['days'] as $li) {
					if ($li['id'] == $id) {
						if ($li['step'] == 0) {
			?>
			<h4><?= Yii::t('reg', 'Day') ?> <?= $cnt ?> (<?= date('j/n/Y', strtotime('+'.($cnt - 1).' days', strtotime($theBooking['product']['day_from']))) ?>) <?= $li['name'] ?></h4>
			<?
						} elseif ($li['step'] > 1) {
							for ($i = 1; $i < $li['step']; $i ++) {
								$cnt ++;
			?>
			<h4><?= Yii::t('reg', 'Day') ?> <?= $cnt ?> (<?= date('j/n/Y', strtotime('+'.($cnt - 1).' days', strtotime($theBooking['product']['day_from']))) ?>) Free day - no services</h4>
			<?
								

							}
						} else {
							$cnt ++;
			?>
			<h4><?= Yii::t('reg', 'Day') ?> <?= $cnt ?> (<?= date('j/n/Y', strtotime('+'.($cnt - 1).' days', strtotime($theBooking['product']['day_from']))) ?>) <?= $li['name'] ?></h4>
			<?
						}

						if ($li['step'] <= 1) {
			?>
			<div>
			<p class="text-danger">
				<? if ($li['guides'] != '') { ?>
				<i class="fa fa-user"></i> <?= $li['guides'] ?>
				<? } ?>
				&nbsp;
				<? if ($li['transport'] != '') { ?>
				<i class="fa fa-truck"></i> <?= $li['transport'] ?>
				<? } ?>
				&nbsp;
				<? if ($li['meals'] != '---') { ?>
				<i class="fa fa-cutlery"></i> <?= $li['meals'] ?>
				<? } ?>
			</p>
			<?= Markdown::process($li['body']) ?>
			</div>
			<hr>
			<?
							$lastId = $li['id'];
						}
					}
				}	
			}
			?>
		</tbody>
	</table>
</div>
<?/*
			</div><!-- #t-itinerary -->
			<div role="tabpanel" class="tab-pane" id="t-price">
				<p><strong>PRICE TABLE</strong></p>
				<div class="table-responsive">
					<table class="table table-bordered table-condensed">
<? // Gia va cac options
$ctpx = $theProduct['prices'];
$ctpx = explode(chr(10), $ctpx);
$unitp = '';
$minp = 99999;
$maxp = 0;
$optcnt = 0;
foreach ($ctpx as $ctp) {
if (substr($ctp, 0, 7) == 'OPTION:') {
$optcnt ++;
echo '<tr class="b-ffc"><th colspan="4">Option '.$optcnt.' : '.trim(substr($ctp, 7)).'</th></tr>';
echo '<tr><th>Ville</th><th>Hotel</th><th>Categorie chambre</th><th>Ref</th></tr>';
}
if (substr($ctp, 0, 2) == '+ ') {
$line = trim(substr($ctp, 2));
$line = explode(':', $line);
for ($i = 0; $i < 4; $i ++) if (!isset($line[$i])) $line[$i] = '';
echo '<tr><td style="white-space:nowrap;">'.$line[0].'</td><td>'.$line[1].'</td><td>'.$line[2].'</td><td>'.Html::a('<i class="text-muted fa fa-external-link"></i>', 'http://'.str_replace('http://', '', trim($line[3])), ['rel'=>'external']).'</td></tr>';
}
if (substr($ctp, 0, 2) == '- ') {
$line = trim(substr($ctp, 2));
$line = explode(':', $line);
for ($i = 0; $i < 3; $i ++) if (!isset($line[$i])) $line[$i] = '';
$line[1] = (int)trim($line[1]);
if ($minp > $line[1]) $minp = $line[1];
if ($maxp < $line[1]) $maxp = $line[1];
$unitp = $line[2];
echo '<tr><td colspan="4" class="text-right">'.$line[0].' <strong>'.number_format($line[1]).' '.$theProduct['price_unit'].'</strong></td></tr>';
}
}
if (empty($ctpx)) $minp = 0;
if ($minp > $maxp) $minp = 0;
?>
					</table>
				</div>
			</div><!-- #t-price -->
			<div role="tabpanel" class="tab-pane" id="t-terms">
				<p><strong>TERMS AND CONDITIONS</strong></p>
				<div class="mb-1em"><?= Markdown::process(str_replace(['h3. '], ['### '], $theProduct['conditions'])) ?></div>
				<p><strong>MORE INFORMATION</strong></p>
				<div class="mb-1em"><?= Markdown::process(str_replace(['h3. '], ['### '], $theProduct['others'])) ?></div>
			</div><!-- #t-terms -->
		</div>
	</div>*/ ?>
</div>
<?
include('_reg_sb.php');

