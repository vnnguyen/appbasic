<?
use yii\helpers\Html;
use yii\helpers\Markdown;

$thus = ['-', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy', 'Chủ nhật'];
$total = 0;

$this->title = 'Hồ sơ tour gốc '.$theTour['code'];
$this->params['breadcrumb'] = [
	['Tours', '@web/tours'],
	['View', '@web/tours/r/'.$theTour['id']],
];

$se = [];
$tourOperators = [];
$cpx = [];

$dayIdList = explode(',', $theProduct['day_ids']);

$xRates['EUR'] = 24100;
$xRates['USD'] = 21400;
$theXRate = false;

?>
<div class="col-md-12">
	<div class="row">
		<div class="col-xs-4">
			<table class="table table-condensed table-bordered">
				<tr><th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Code');?></th><td><strong><?= $theTour['code'] ?></strong></td></tr>
				<tr><th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Tên đoàn');?></th><td><?= $theTour['name'] ?></td></tr>
				<tr><th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Số khách');?></th><td><?= $theProduct['pax']?> pax</td></tr>
				<tr><th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Số ngày');?></th><td><?= $theProduct['day_count'] ?> <?= Yii::t('tours_in-cp', 'ngày');?></td></tr>
				<tr>
					<th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Khởi hành');?></th>
					<td>
						<?= date('d-m-Y', strtotime($theProduct['day_from'])) ?>
						(<?= $thus[date('N', strtotime($theProduct['day_from']))] ?>)
					</td>
				</tr>
			</table>
		</div>
		<div class="col-xs-4">
			<table class="table table-condensed table-bordered">
				<tr>
					<th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Kinh doanh');?></th>
					<td class="text-nowrap">
<?
$csList = [];
foreach ($theProduct['bookings'] as $booking) {
	$csList[] = $booking['createdBy']['fname'].' '.$booking['createdBy']['lname'];
}
echo implode('<br>', $csList);
?>
					</td>
				</tr>
				<tr>
					<th><?= Yii::t('tours_in-cp', 'Điều hành');?></th>
					<td>
<?
$csList = [];
foreach ($thePeople as $user) {
	if ($user['role'] == 'operator') {
		$csList[] = $user['name'];
	}
}
echo implode('<br>', $csList);
?>
					</td>
				</tr>
				<? if ($theTour['g1'] != '') { ?><tr><th><?= Yii::t('tours_in-cp', 'Guide m Bắc');?></th><td><?=$theTour['g1']?></td></tr><? } ?>
				<? if ($theTour['g2'] != '') { ?><tr><th><?= Yii::t('tours_in-cp', 'Guide m Bắc');?></th><td><?=$theTour['g2']?></td></tr><? } ?>
				<? if ($theTour['g3'] != '') { ?><tr><th><?= Yii::t('tours_in-cp', 'Guide m Bắc');?></th><td><?=$theTour['g3']?></td></tr><? } ?>
				<? if ($theTour['x1'] != '') { ?><tr><th><?= Yii::t('tours_in-cp', 'Xe m Bắc');?></th><td><?=$theTour['x1']?></td></tr><? } ?>
				<? if ($theTour['x2'] != '') { ?><tr><th><?= Yii::t('tours_in-cp', 'Xe m Bắc');?></th><td><?=$theTour['x2']?></td></tr><? } ?>
				<? if ($theTour['x3'] != '') { ?><tr><th><?= Yii::t('tours_in-cp', 'Xe m Bắc');?></th><td><?=$theTour['x3']?></td></tr><? } ?>
				<tr>
					<th><?= Yii::t('tours_in-cp', 'CSKH');?></th>
					<td>
<?
$csList = [];
foreach ($thePeople as $user) {
	if ($user['role'] == 'cservice') {
		$csList[] = $user['name'];
	}
}
echo implode('<br>', $csList);
?>
					</td>
				</tr>
			</table>
		</div>
		<div class="col-xs-4">
			<table class="table table-condensed table-bordered">
				<tr>
					<th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Giá tour');?></th>
					<td class="text-right"><strong></strong>
					</td>
				</tr>
				<tr>
					<th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Chi phí');?></th>
					<td class="text-right">
					</td>
				</tr>
				<tr>
					<th class="text-nowrap"><?= Yii::t('tours_in-cp', 'Tỉ giá');?></th>
					<td class="text-right">
					<?=number_format($xRates['USD'], 0)?> VND
					</td>
				</tr>
				<tr><th width="45%"><?= Yii::t('tours_in-cp', 'Ngày tính tỉ giá');?></th><td width="55%" class="ta-r">
					<? if (!$theXRate) { ?>
					<?= Yii::t('tours_in-cp', 'tỉ giá mặc định');?>
					<? } else { ?>
					<?= date('d-m-Y', strtotime($theXRate['rate_dt'])) ?>
					<? } ?>
				</td></tr>
				<?
				$total = 0;
				$thisTotal = 0;
				foreach ($cpx as $cp) {
					$sub = $cp['qty']*$cp['price']*$xRates[$cp['unitc']]*(1+$cp['vat']/100);
					if ($cp['plusminus'] == 'plus') {
						$thisTotal += $sub;
					} else {
						$thisTotal -= $sub;
					}
					if (1==0) {
				?>
				<tr><th width="45%"><?=$cp['payer']?></th><td width="55%" class="ta-r"><?=number_format(round($cp['total'] / 100)*100, 0)?> VND</td></tr>
				<?
					}
				}	
				?>
			</table>
		</div>
	</div>

	<hr>

	<div class="row clearfix text-center">
		<div class="col-sm-2 col-xs-4" style="height:200px;"><strong><?= Yii::t('tours_in-cp', 'Trưởng phòng ĐH');?></strong></div>
		<div class="col-sm-2 col-xs-4" style="height:200px;"><strong><?= Yii::t('tours_in-cp', 'Điều hành');?></strong></div>
		<div class="col-sm-2 col-xs-4" style="height:200px;"><strong><?= Yii::t('tours_in-cp', 'Bán hàng');?></strong></div>
		<div class="col-sm-2 col-xs-4" style="height:200px;"><strong><?= Yii::t('tours_in-cp', 'Kiểm soát');?></strong></div>
		<div class="col-sm-2 col-xs-4" style="height:200px;"><strong><?= Yii::t('tours_in-cp', 'Kế toán');?></strong></div>
		<div class="col-sm-2 col-xs-4" style="height:200px;"><strong><?= Yii::t('tours_in-cp', 'Ngày in');?></strong><br><?= date('d-m-Y') ?></div>
	</div>

	<div class="pbr clear" style="page-break-after:always;"></div>
	<h2>[<?= $theTour['code'] ?> - <?= $theTour['name'] ?>] <?= Yii::t('tours_in-cp', 'Chương trình tour');?></h2>
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th><?= Yii::t('tours_in-cp', 'TT');?></th>
				<th><?= Yii::t('tours_in-cp', 'Ngày');?></th>
				<th><?= Yii::t('tours_in-cp', 'Nội dung hoạt động (Bữa ăn)');?></th>
			</tr>
		</thead>
		<tbody>
<?
$cnt = 0;
foreach ($dayIdList as $di) {
	foreach ($theProduct['days'] as $ng) {
		if ($ng['id'] == $di) {
			$cnt ++;
			$ngay = date('Y-m-d', strtotime($theProduct['day_from'].' + '.($cnt - 1).'days'));
?>
			<tr>
				<td class="text-center"><?= $cnt ?></td>
				<td class="text-nowrap"><strong><?= date('d-m-Y', strtotime($ngay)) ?><br><?=$thus[date('N', strtotime($ngay))]?></strong></td>
				<td>
					<h4 style="margin-top:0;"><?= $ng['name'] ?> (<?= $ng['meals'] ?>)</h4>
					<div class="bottom"><?= Markdown::process($ng['body']) ?></div>
				</td>
			</tr>
<?
		}
	}
}
?>
		</tbody>
	</table>

<div class="clear pbr" style="page-break-after:always;"></div>
<h2>[<?= $theTour['code'] ?> - <?= $theTour['name'] ?>] <?= Yii::t('tours_in-cp', 'Chi phí tour chi tiết theo ngày');?></h2>
<table class="table table-condensed table-bordered">
	<thead>
		<tr>
			<th width="20%"><?= Yii::t('tours_in-cp', 'Nội dung');?></th>
			<th width="15%"><?= Yii::t('tours_in-cp', 'Nhà c/cấp');?></th>
			<th width="5%"><?= Yii::t('tours_in-cp', 'Số');?></th>
			<th width="5%"><?= Yii::t('tours_in-cp', 'Đvị');?></th>
			<th width="10%"><?= Yii::t('tours_in-cp', 'Giá');?></th>
			<th width="5%"><?= Yii::t('tours_in-cp', 'Tiền');?></th>
			<th width="10%"><?= Yii::t('tours_in-cp', 'Thành VND');?></th>
			<th width="12%"><?= Yii::t('tours_in-cp', 'Ai đặt');?></th>
			<th width="12%"><?= Yii::t('tours_in-cp', 'Ai trả');?></th>
			<th width="6%"><?= Yii::t('tours_in-cp', 'Notes');?></th>
		</tr>
	</thead>
	<tbody>
<?
$cnt = 0;
foreach ($dayIdList as $di) {
	foreach ($theProduct['days'] as $ng) {
		if ($di == $ng['id']) {
		$cnt ++;
		$ngay = date('Y-m-d', strtotime($theProduct['day_from'].' + '.($cnt - 1).'days'));	
?>
		<tr id="day<?= $ngay ?>" style="background:#ffc;">
			<th><?=$thus[date('N', strtotime($ngay))]?> <?= $ngay ?></th>
			<th colspan="9"><?= $ng['name'].' ('.$ng['meals'].')' ?></th>
		</tr>
<?
			foreach ($theCptx as $s) {
				if ($s['dvtour_day'] == $ngay) {
?>
		<tr class="">
			<td>
<?
					if ($s['start'] != '00:00:00') echo '('.substr($s['start'], 0, 5).') ';
					if ($s['number'] != '') echo '('.$s['number'].') ';
					echo $s['dvtour_name'];
?>
			</td>
    		<td>
<?
					if ($s['venue_id'] != 0) {
						echo $s['venue']['name'];
					} elseif ($s['via_company_id'] != 0) {
						echo $s['company']['name'];
					} elseif ($s['by_company_id'] != 0) {
						echo $s['by_company_name'];
					} else {
						echo $s['oppr'];
					}
?>
			</td>
			<td class="text-right"><?= trim(trim($s['qty'], '0'), '.') ?></td>
			<td class="text-nowrap"><?= $s['unit'] ?></td>
			<td class="text-right">
				<?= $s['plusminus'] == 'minus' ? '-' : '' ?>
				<?= number_format($s['price']) ?>
			</td>
    		<td><?= $s['unitc'] ?></td>
<?
					$sub = $s['qty']*$s['price']*$xRates[$s['unitc']]*(1+$s['vat']/100);
					if ($s['plusminus'] == 'plus') {
						$total += $sub;
					} else {
						$total -= $sub;
					}
?>
			<td class="text-right <? if($s['approved_by'] !=0) {?>approved<? } ?>" title="<?=$s['unitc'] != 'VND' ? Yii::t("tours_in-cp", "Tỉ giá").': '.$xRates[$s['unitc']] : ''?>">
				<b title="<?= Yii::t('tours_in-cp', 'Đã được duyệt ngày');?> <?=$s['approved']?>">
					<?=$s['plusminus'] == 'minus' ? '-' : ''?>
					<?=number_format($sub)?>
				</strong>
			</td>
			<td><?=$s['booker']?></td>
			<td><?=$s['payer']?></td>
			<td></td>
		</tr>
<?
				}
			}
		}
	}
}
?>
		</tbody>
	</table>

	<div class="row clearfix text-center">
		<div class="col-sm-3 col-xs-4" style="font-weight:bold; height:200px;"><strong><?= Yii::t('tours_in-cp', 'Trưởng phòng ĐH');?></strong></div>
		<div class="col-sm-2 col-xs-4" style="font-weight:bold; height:200px;"><strong><?= Yii::t('tours_in-cp', 'Điều hành');?></strong></div>
		<div class="col-sm-2 col-xs-4" style="font-weight:bold; height:200px;"><strong><?= Yii::t('tours_in-cp', 'Bán hàng');?></strong></div>
		<div class="col-sm-2 col-xs-4" style="font-weight:bold; height:200px;"><strong><?= Yii::t('tours_in-cp', 'Kiểm soát');?></strong></div>
		<div class="col-sm-3 col-xs-4" style="font-weight:bold; height:200px;"><strong><?= Yii::t('tours_in-cp', 'Kế toán');?></strong></div>
	</div>
</div>