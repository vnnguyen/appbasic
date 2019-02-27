<?php
use yii\helpers\Url;
// var_dump($t_amount);die();
?>
<!-- <htmlpageheader name="myHTMLHeader1">
            <img src="<?= '';//Url::to('@web/images/img/img-header.png', true)?>"/>
</htmlpageheader> -->
<htmlpagefooter name="MyCustomFooter">
<div>
	<!-- <img style="padding: 0; margin: 0; width:100%" src="<?= '';//Url::to('@web/images/img/pdf1-footer.png', true)?>"/> -->
</div>

</htmlpagefooter>
<htmlpagefooter name="MyCustomFooter1">
</htmlpagefooter>

<!-- <sethtmlpageheader name="myHTMLHeader1" value="on" show-this-page="1" /> -->
<!-- <div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">

<img src="<?= Url::to('@web/images/img/img-first-bg.png', true)?>" style="width: 210mm; height: 297mm; margin: 0;" />

</div> -->
<div class="container main-content">
	<div class="col-md-12">
		<h1 >GIẤY ĐỀ NGHỊ THANH TOÁN</h1>
		<div class="info">
			<p>Họ và tên người đề nghị: Đặng Minh Ngọc</p>
			<p>Bộ phận (hoặc địa chỉ): Kế toán</p>
			<p>Dề nghị được thanh toán số tiền theo nội dung dưới đây:</p>
			<p>Hình thức thanh toán: VP Bank</p>
		</div>
		<div class="pdf-list">
			<div class= "program">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th width="20px" class="t-head" style="border-bottom: none">STT</th>
							<th class="t-head" style="border-bottom: none">Nội dung</th>
							<th width="150px" class="t-head" style="border-bottom: none; text-align: right;">Số tiền</th>
							<th class="t-head" style="border-bottom: none">Chứng từ</th>
							<th class="t-head" style="border-bottom: none">Ghi chú</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($mtts as $i => $mtt): ?>
							<tr>
								<td class=""><?= $i+1?></td>
								<td class=""><?= $mtt['venue']['name']?></td>
								<td class="" style="text-align: right;"><?= number_format($mtt['amount'],2)?> <span class="text-muted"><?= $mtt['currency']?></span></td>
								<td class="">00000</td>
								<td class="" style=""><?= $mtt['note']?></td>
							</tr>
						<?php endforeach ?>

						<tr>
							<td class=""></td>
							<td class=""><b>Tổng cộng</b></td>
							<td class="" style="text-align: right;">
								<?php
								foreach ($t_amount as $m) {
									$moneys = explode('-', $m);
									echo '<b>'.number_format($moneys[0], 2).'</b> <span class="text-muted">'.$moneys[1].'</span><br />';
								}
								?>
							</td>
							<td class=""></td>
							<td class="" style=""></td>
						</tr>
					</tbody>
				</table>
			</div> <!-- end program -->
		</div>

		<div class="text-money">

			<?php
			echo '<div><strong>Số tiền bằng chữ</strong>: </div>';
			foreach ($t_amount as $m) {
				$moneys = explode('-', $m);
				echo '<p>'.Yii::$app->formatter->asSpellout($moneys[0], 2).' <span class="pdf_currency">'.$moneys[1].'</span></p>';
			}
			?>
		</div>
		<div class="bottom-div">
			<p class="text-right dntt-date">Hà nội ngày <?= date('d', strtotime('now'))?> tháng <?= date('m', strtotime('now'))?> năm <?= date('Y', strtotime('now'))?></p>
			<div class="text-confirm">
				<table class="signing-people" border="0">
				<thead>
					<tr>
						<th>Tổng giám đốc</th>
						<th>Kế toán trưởng</th>
						<th>Kế toán thanh toán</th>
						<th>Trưởng phòng</th>
						<th>Người đề nghị</th>
					</tr>
				</thead>
				<tbody>
					<tr class="who-sign">
						<td>Hà Đức Mạnh</td>
						<td></td>
						<td></td>
						<td></td>
						<td>Đặng Minh Ngọc</td>
					</tr>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


