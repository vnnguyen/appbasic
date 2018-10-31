<?php 
use yii\helpers\Url;
$days = explode(',', $model->days_id);
?>
<htmlpageheader name="myHTMLHeader1">
            <img src="<?= Url::to('@web/images/img/img-header.png', true)?>"/>
</htmlpageheader>
<htmlpagefooter name="MyCustomFooter">
	<table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;" width="100%">
		<tbody>
			<tr>
				<!-- <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td> -->
				<td style="text-align: right;" width="90%">Logo</td>
				<td style="font-weight: bold; color: #BC489A; " align="left" width="5%">{PAGENO}</td> <!-- {PAGENO}/{nbpg} -->
			</tr>
		</tbody>
	</table>
</htmlpagefooter>
<htmlpagefooter name="MyCustomFooter1">
</htmlpagefooter>

<!-- <sethtmlpageheader name="myHTMLHeader1" value="on" show-this-page="1" /> -->
<!-- <div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">

<img src="<?= Url::to('@web/images/img/img-first-bg.png', true)?>" style="width: 210mm; height: 297mm; margin: 0;" />

</div> -->
<div class="container main-content">
	<div class="col-md-10">
		<h1 style="font-family: Candara"><?= $model->title;?></h1>
		<div class="pdf-list-days">
			<table>
				<thead>
					<tr>
						<th>Day</th>
						<th>Date</th>
						<th>tile</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$date = $model->start_date;
					$i_list = 1;
					$start_date_list = date_create($date);
					foreach ($days as $id) {
						foreach ($days_tour as $day_list) {
							if ($day_list->id == $id) {
				    			//echo this day
								?>
								<tr>
									<td class="td-format" style="font-weight: bold;"><?= "Day ".$i_list?></td>
									<td class="td-format"><?=date_format($start_date_list,"D").' '.date_format($start_date_list,"d|m|Y");?></td>
									<td class="td-format" style="font-weight: bold;">
											<?php echo $day_list->ngaymau_title;?>
									</td>
								</tr>
								<?php
								$i_list++;
								date_add($start_date_list,date_interval_create_from_date_string("1 days"));
							}
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<pagebreak page-break-type="clonebycss">
		<div class="pdf-list-days-detail">
			<table>
				<?php
				$i = 1;
				$date2 = $model->start_date;
				$start_date = date_create($date2);
				foreach ($days as $id) {
					foreach ($days_tour as $day) {
						if ($day->id == $id) {
			    			//echo this day
							?>
							<tr>
								<td>
								<div class="wrap-day-pdf">
									<div class="wrap-title-pdf">
										<h4 class="pdf-title" style="text-align: right;">
											<span class="pdf-date-tour"><?php echo 'jour '.$i.'-'.date_format($start_date,"d|m|Y");?></span>
											<?php echo ' | '.$day->ngaymau_title;?>
										</h4>
									</div>
									<br/>
									<div style="display: inline-block; margin-top: 20px">
										<p class="pdf-content-tour" style=""><?= $day->ngaymau_body;?></p>
									</div>
									<p style="margin-top: 20px">
										<br />
										<span class="note-pdf" style="font-size: 10px; font-weight: bold;">note:</span>
									</p>
								</div>
								</td>
							</tr>
							<?php
							$i++;
							date_add($start_date,date_interval_create_from_date_string("1 days"));
						}
					}
				}
				?>
			</table>
		</div>
	</div>
</div>


