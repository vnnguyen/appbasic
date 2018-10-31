<p>Hotel: <b><?= $cpt[0]['ncc']['name']?></b></p>
<p>Dear Reservation department,<br />I would like to make a reservation for our clients with following details:</p>

<div class="table-responsive">
	<table class="table table-bordered" style="" border-spacing=0>
		<tbody>
			<tr>
				<td>Booking code:</td>
				<td width="200px"><b><?= $cpt[0]['tour']['code']?></b></td>
				<td>Group name:</td>
				<td width="300px"><b><?= $cpt[0]['tour']['name']?></b></td>
			</tr>
			<tr>
				<td>Nationality:</td>
				<td colspan="3"><b>french</b></td>
			</tr>
			<tr>
				<td>No. of guest(s):</td>
				<td colspan="3"><b><?= $cpt[0]['ct']['pax']?> pax</b></td>
			</tr>
			<tr>
				<td>No. of room(s):</td>
				<td colspan="3"><b><?= intval($cpt[0]['qty'])?></b></td>
			</tr>
			<tr>
				<td>Room category:</td>
				<td colspan="3"><b><?= $cpt[0]['unit']?></b></td>
			</tr>
			<?php foreach ($cpt['check_in'] as $k => $v) {?>
				<tr>
					<td width="100px">Check-in:</td>
					<td><b><?= $v ?></b></td>
					<td width="100px">Check-out:</td>
					<td><b><?= $cpt['check_out'][$k]?></b></td>
				</tr>
			<?php }	?>
			<tr>
			</tr>
			<tr>
				<td>Note:</td>
				<td colspan="3"><b>abc</b></td>
			</tr>
		</tbody>
	</table>
</div>

<p style="">I am looking forward to your prompt confirmation.<br />Thanks & Regards,</p>