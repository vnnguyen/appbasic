<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
Yii::$app->params['page_title'] = 'Hotels';
Yii::$app->params['page_small_title'] = 'Khách sạn';
Yii::$app->params['page_icon'] = 'building-o';
Yii::$app->params['page_breadcrumbs'] = [
	['Ref', 'ref'],
	['Hotels'],
];
$categories = [
	'str' => Yii::t('app', 'Priority'),
	're' => Yii::t('app', 'Recommended'),
	'not' => Yii::t('app', 'Not Ok'),
];
$this->registerCss('
	.ks-title { font-weight: 500;}
');
?>
<div class="col-md-12">
	<form class="form-inline panel-search">
		<?=Html::dropDownList('category', $category, $categories,
			[
				'class'=>'form-control',
				'prompt'=>'- Category -',
			]) ?>
		<?=Html::dropDownList('destination', $destination, ArrayHelper::map($destinations, 'id', 'name_en', 'country_name'),
			[
				'class'=>'form-control',
				'prompt'=>'- Destination -',
			]) ?>
		<?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Stars, tags, ..']) ?>
		<?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Hotel name']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-default']) ?>
		<?= Html::a('Reset', '/ref/hotels') ?>
		|
		<a href="#" onclick="$('#pagehelp').toggle(); return false;">Help</a>
	</form>

	<div id="pagehelp" style="display:none;" class="mt-10 alert alert-info">
		Hàng trên là các trường tìm kiếm:
		<br>- Địa điểm: chọn địa điểm. Chỉ những địa điểm có khách sạn mới được liệt kê ở đây.
		<br>- Tag: chọn một hoặc nhiều tag liên quan đến: số sao (vd: 1s, 2s, 3s, 4s, 5s), năm hợp đồng (vd: 2016), được recommend (re), các tag khác (vd: fam del cla v.v). Nếu tìm nhiều tag thì cách nhau bằng dấu cách, vd "3s re 2016" có nghĩa là tìm ks 3 sao có hợp đồng 2016 và được khuyên dùng
		<br>- Tên: tìm theo tên khách sạn (chỉ cần đánh một phần tên là được)
		<br>Hàng bên dưới: nơi trình bày kết quả.
		<br>- Số ks trong một trang
		<br>- Số trang hiện có
		<br>Bảng kết quả
		<br>- Click tên cột để sắp xếp các ks theo cột đó. Dùng Shift+Click để sort nhiều cột.
	</div>

	<? if (empty($theVenues)) { ?><p>No items found.</p><? } else { ?>

	<div class="table-responsive">
		<table id="tblHotels" class="table table-bordered table-condensed table-striped dataTable">
			<thead>
				<tr>
					<th width="">Info</th>
					<th>Tags</th>
					<th>Contracts</th>
					<th>TripAdv</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theVenues as $li) {
					$tags = explode(' ', $li['search']);

					// Stars
					$venueStar = '';
					$venueRates = [];
					$venueTags = [];
					$venueContracts = [];
					$venueTripAdv = '';
					$venueLocations = [];

					// Rates
					foreach ($tags as $tag) {
						if (in_array($tag, ['1s', '2s', '3s', '4s', '5s'])) {
							$venueStar = substr($tag, 0, 1);
						} elseif (substr($tag, 0, '2') == 'rf') {
							$venueRates[] = substr($tag, 2);
						} elseif (substr($tag, 0, '2') == 'hd') {
							$venueContracts[] = (int)substr($tag, 2) >= (int)date('Y') ? '<span style="color:blue;">'.substr($tag, 2).'</span>' : substr($tag, 2);
						} elseif (substr($tag, 0, '2') == 'tr' && $tag != 'trekking') {
							$venueTripAdv = substr($tag, 2);
						} else {
							if ($tag == 're1') {
								$tag = '<span style="color:green">recommended++</span>';
							} elseif ($tag == 're2') {
								$tag = '<span style="color:green">recommended+</span>';
							} elseif ($tag == 're') {
								$tag = '<span style="color:green">recommended</span>';
							} elseif ($tag == 'charm') {
								$tag = '<span style="color:blue">charming</span>';
							} elseif ($tag == 'not') {
								$tag = '<s style="color:red">not OK</s>';
							} elseif ($tag == 'see') {
								$tag = 'đợi khảo sát';
							} elseif ($tag == 'far') {
								$tag = 'xa trung tâm';
							}

							if (substr($tag, 0, 1) == '@') $tag = '';
							if ($tag == 're' || $tag == 'ks') $tag = '';
							//if (str_replace('_', '', fURL::makeFriendly($li['name'], '_')) == $tag) $tag = '';
							if (trim($tag) != '')
								$venueTags[] = $tag;
						}
					}

					?>
				<tr>
					<td>
						<div class="ks">
							<p class="ks-name">
								<span class="ks-title ks-name-title">Name: </span>
								<span class="ks-content ks-name-content">
									<?php //&& in_array(Yii::$app->user->id, [1, 7766])?>
										<? if ($li['images_booking'] != '') { ?><i class="text-muted fa fa-picture-o"></i><? } ?>
										<? if ($li['company_id'] != 0 ) { ?><i class="text-muted fa fa-home"></i><? } ?>
										<?=Html::a($li['name'], '@web/venues/r/'.$li['id'])?>
										<?
										foreach ($li['metas'] as $li2) {
											if ($li2['k'] == 'website') {
												echo Html::a('<i class="fa fa-external-link"></i>', 'http://'.str_replace('http://', '', $li2['v']), ['title'=>$li2['v'], 'rel'=>'external', 'class'=>'text-muted']);
												break;
											}
										}
										?>
								</span>
							</p>
							<p class="ks-address">
								<span class="ks-title ks-address-title">Address: </span>
								<span class="ks-contentks-address-content">
									<?
									foreach ($li['metas'] as $li2) {
										if ($li2['k'] == 'address') {
											echo $li2['v'];
											break;
										}
									}
									?>
								</span>
							</p>
							<p class="ks-star">
								<span class="ks-title ks-star-title">Star: </span>
								<span class="ks-content ks-star-content"><?=$venueStar?></span>
							</p>
							<p class="ks-phone">
								<span class="ks-title ks-phone-title">Phone: </span>
								<span class="ks-content ks-phone-content">
									<?
									foreach ($li['metas'] as $li2) {
										if ($li2['k'] == 'tel' || $li2['k'] == 'mobile') {
											echo $li2['v'];
											break;
										}
									}
									?>
								</span>
							</p>
							<p class="ks-rates">
								<span class="ks-title ks-rates-title">Rates: </span>
								<span class="ks-content ks-rates-content"><?=implode(', ', $venueRates)?></span>
							</p>
						</div>
					</td>
					<td><?=implode(', ', $venueTags)?></td>
					<td class="text-center"><?=implode(', ', $venueContracts)?></td>
					<td class="text-center">
						<?=$venueTripAdv?>
						<? if ($li['link_tripadvisor'] != '') { ?>
						<a rel="external" href="<?=$li['link_tripadvisor']?>"><i class="fa fa-external-link"></i></a>
						<? } ?>
					</td>
					<td class="text-muted td-n" width="40">
						<a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?=DIR?>venues/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a class="text-muted" title="<?=Yii::t('mn', 'Delete')?>" href="<?=DIR?>venues/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>
</div>
<style type="text/css">
	.dt_header {margin-bottom:1em;}
	div#tblHotels_filter label, div#tblHotels_length label {width:100%;}
	#tblHotels_filter input {padding:5px 12px; height:34px; line-height:20px; width:100%;}
	#tblHotels_length select {padding:6px 12px; height:34px; line-height:20px; width:100%;}
	#tblHotels_info {height:34px; line-height:34px; }
	#tblHotels_filter {display: none;}
</style>
<?
$js = <<<TXT
	$('#tblHotels').dataTable({
		"iDisplayLength": 100,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"sDom": "<'dt_header row'<'hide col-lg-2'f><'col-md-2'l><'col-md-4'i><'text-right col-md-4'p>r>t>",
		"sPaginationType": "bootstrap",
		"bStateSave": true,
		"aoColumns": [
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			{"bSortable": true}
		],
		"oLanguage": {
			"sLengthMenu": "_MENU_",
			"sSearch": "_INPUT_",
			"oPaginate": {
				"sPrevious": "",
				"sNext": ""
			},
			"sInfo": "Showing _START_ to _END_ of _TOTAL_",
			"sInfoFiltered": " - filtering from _MAX_"
		}
	});
TXT;
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js', ['depends'=>\yii\web\JqueryAsset::className()]);
//$this->registerJsFile(DIR.'assets/js/datatables/paging-b3.js', ['depends'=>'app\assets\BootstrapAsset']);
$this->registerJs($js);
