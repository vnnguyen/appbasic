<?php
	$this->registerCSS('
		.logo{ width:150px}
		.title-0 h2{ text-align: center; margin:0 15px; font-size: 20px; padding-right:90px; margin-right:20px;}
		.samp-number{ text-align:right; }
		.samp-number p{ border: 1px solid #ccc; display: inline-block; padding: 9px 15px; font-size: 15px; }
		.title-1 h1{ font-size: 23px; text-transform: uppercase; text-align: center; font-weight: 500; padding-left:38px; margin:0}
		.info-u{ display: block;     padding: 20px 0;}
		.info-u tr .td-first{ font-size: 16px;     padding: 3px;}
		.info-u tr .td-last p, .info-u tr .td-last-normal p{ font-size: 16px; margin:0; display:block; border-bottom:1px dotted #ccc; padding-left:20px}
		.info-u tr .td-last{ font-size: 22px; font-weight: 600; width:400px}
		.info-u tr .td-last-normal{ font-size: 16px;}

		.info-content{padding:20px 5px;}
		.info-content tr th{font-size:15px }
		.info-content tbody tr td p{ font-size:17px; padding: 15px 0;}
		.date-now{ text-align: right; font-size: 15px; font-weight: 300; font-style: italic; }
		.money-read p{padding: 10px; font-size: 15px; font-style: italic; }
		.ext-money {padding: 10px; font-size: 14px; font-style: italic;}
		.who-sign td {font-size:15px; font-weight:500; border:none !important; padding-bottom:100px !important}
		.text-money{ font-weight: 600;}
		.signing-people tr td{padding-left:0 !important; padding-right:0 !important; text-align:center}
		.signing-people .who-sign td{ min-width: 150px; }
	');
?>
<table style="" class="report">
	<tr>
		<td  class="logo" rowspan="2">Logo</td>
		<td  colspan="2" class="title-0"><h2>CÔNG TY CP ĐT & DU LỊCH THÂN THIỆN VIỆT NAM <br /> AMICA TRAVEL</h2></td>
	</tr>
	<tr>
		<td class="samp-number" colspan="2"> <p>Mẫu : 01-TU/QT01-TCKT</p></td>
	</tr>

	<tr>
		<td class="title-1" colspan="3"><h1>giấy đề nghị tạm ứng</h1></td>
	</tr>
	<tr>
		<td colspan="3">
			<table class="info-u">
				<tr>
					<td class="td-first">Họ và tên người đề nghị:</td>
					<td class="td-last"><p><?= $model->offer_by ?></p></td>
				</tr>
				<tr>
					<td class="td-first">Bộ phận(hoặc địa chỉ):</td>
					<td class="td-last-normal"><p><?= $model->department?></p></td>
				</tr>
				<tr>
					<td class="td-first" colspan="2">Đề nghị được tạm ứng tiền theo nội dung dưới đây</td>
				</tr>
				<tr>
					<td class="td-first">Hình thức tạm ứng: </td>
					<td class="td-last"><p><?= ($model->payment = 0)? "CK": "Tiền mặt"; ?></p></td>
				</tr>
				<tr>
					<td class="td-first">Thời hạn hoàn ứng: </td>
					<td class="td-last-normal"><p><?= date('d-m-Y', strtotime($model->deadline)); ?></p></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table class="info-content table table-bordered">
				<thead>
					<tr>
						<th>STT</th>
						<th>Nội dung</th>
						<th>Số tiền</th>
						<th>Chứng từ</th>
						<th>Ghi chú</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><p>1</p></td>
						<td><p><?= $model->content ?></p></td>
						 <?php $a = new \NumberFormatter("it-IT", \NumberFormatter::DECIMAL); ?>
						<td ><p><?= $a->format($model->amount) ?></p></td>
						<td><p><?= $model->accom_doc ?></p></td>
						<td ><p><?= $model->note ?></p></td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td class="money-read"><p>số tiền bằng chữ: </p></td>
		<td colspan="2" class="money-read text-money"><p><?= $textVnd; ?></p></td>
	</tr>
	<tr>
		<?php $str_date = Yii::$app->formatter->asDate('now', 'php:d-m-Y'); $date = explode('-',$str_date);?>
		<td colspan="3" class="date-now"><p>Hà Nội, ngày <?= $date[0]; ?> tháng <?= $date[1]; ?> năm <?= $date[2]; ?></p></td>
	</tr>
	<tr>
		<td colspan="3">
			<table class="table signing-people">
					<tr class="who-sign">
						<td>Tổng giám đốc</td>
						<td>Kế toán trưởng</td>
						<td width="300px" style="padding-left:0; padding-right:0">Kế toán thanh toán</td>
						<td>Trưởng phòng</td>
						<td>Người đề nghị</td>
					</tr>
					<tr class="who-sign">
						<td>Hà Đức Mạnh</td>
						<td>Nguyễn Hà Tú Phương</td>
						<td></td>
						<td></td>
						<td><?= $model->offer_by?></td>
					</tr>
			</table>
		</td>
	</tr>
</table>