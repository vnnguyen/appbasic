<?php
use yii\helpers\Url;
$days = explode(',', $model->days_id);
?>
<htmlpageheader name="myHTMLHeader1">
            <img src="<?= Url::to('@web/images/img/pdf1-header.png', true)?>"/>
</htmlpageheader>
<htmlpagefooter name="MyCustomFooter">
<div>
	<img style="padding: 0; margin: 0; width:100%" src="<?= Url::to('@web/images/img/pdf1-footer.png', true)?>"/>
</div>
	
</htmlpagefooter>
<htmlpagefooter name="MyCustomFooter1">
</htmlpagefooter>

<!-- <sethtmlpageheader name="myHTMLHeader1" value="on" show-this-page="1" /> -->
<!-- <div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">

<img src="<?= Url::to('@web/images/img/img-first-bg.png', true)?>" style="width: 210mm; height: 297mm; margin: 0;" />

</div> -->
<div class="container main-content">
	<div class="col-md-10">
		<h1 style="font-family: Candara">INDOSIAM <br />G1612002 - Dos. 135 –DESNOYERS Lucie</h1>
		<div class="pdf-list">
			<div class= "program">
				<h3>PROGRAMME EN BREF </h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="t-head" style="border-bottom: none">Jour</th>
							<th class="t-head" style="border-bottom: none">Date</th>
							<th class="t-head" style="border-bottom: none">Itinéraire</th>
							<th class="t-head" style="border-bottom: none">Accompagnement</th>
							<th class="t-head" style="border-bottom: none">Repas inclus</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="">Jour 1</td>
							<td class="">Je 29|12|2016</td>
							<td class="">Hanoi arrivée</td>
							<td class="">Guide et chauffeur</td>
							<td class="" style="">— — —</td>
						</tr>
						<tr>
							<td class="">Jour 2</td>
							<td class="">Ve 30|12|2016</td>
							<td class="">Hanoi</td>
							<td class="">Guide et chauffeur</td>
							<td class="" style="">B L —</td>
						</tr>
						<tr>
							<td class="">Jour 3</td>
							<td class="">Je 1|1|2017</td>
							<td class="">Hanoi arrivée</td>
							<td class="">Guide et chauffeur</td>
							<td class="" style="">— — —</td>
						</tr>
						<tr>
							<td class="">Jour 4</td>
							<td class="">Je 2|1|2017</td>
							<td class="">Hanoi arrivée</td>
							<td class="">Guide et chauffeur</td>
							<td class="" style="">— — —</td>
						</tr>
						<tr>
							<td class="">Jour 5</td>
							<td class="">Je 3|1|2017</td>
							<td class="">Hanoi arrivée</td>
							<td class="">Guide et chauffeur</td>
							<td class="" style="">— — —</td>
						</tr>
						<tr>
							<td class="">Jour 6</td>
							<td class="">Je 4|1|2017</td>
							<td class="">Hanoi arrivée</td>
							<td class="">Guide et chauffeur</td>
							<td class="" style="">— — —</td>
						</tr>
					</tbody>
				</table>
			</div> <!-- end program -->
			<div class= "program participant">
				<h3>LISTE DES PARTICIPANTS </h3>
				<table class="table table-bordered" border="1">
					<thead>
						<tr>
							<th class="t-head" style="border-bottom: none">No</th>
							<th class="t-head" style="border-bottom: none">Titre</th>
							<th class="t-head" style="border-bottom: none">Noms et prénoms</th>
							<th class="t-head" style="border-bottom: none">Rooming</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="">1</td>
							<td class="">Mme</td>
							<td class="">DESNOYERS Lucie</td>
							<td class="" rowspan="2" valign="middle" style="padding-top: 20px">1DBL</td>
						</tr>
						<tr>
							<td class="">2</td>
							<td class="">Mr</td>
							<td class="">DESNOYERS Marc</td>
							
						</tr>
						<tr>
							<td class="">3</td>
							<td class="">Mlle</td>
							<td class="">DESNOYERSIsabele</td>
							<td class="" rowspan="2" valign="bottom" style="padding-top: 20px">2TWN</td>
						</tr>
						<tr>
							<td class="">4</td>
							<td class="">Mr</td>
							<td class="">DESNOYERS Luc</td>
						</tr>
					</tbody>
				</table>
			</div> <!-- end program -->
			<div class= "program heber">
				<h3>LISTE DES HEBERGEMENTS</h3>
				<table class="table table-bordered" repeat_header="">
					<thead>
						<tr>
							<th class="t-head" style="border-bottom: none">Destinations</th>
							<th class="t-head" style="border-bottom: none">Hôtel/Resort&Website</th>
							<th class="t-head" style="border-bottom: none">Nuitée(s)</th>
							<th class="t-head" style="border-bottom: none">Type de chambre</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="">Hanoi</td>
							<td class="">EleganceDiamond ***</td>
							<td class="">1</td>
							<td class="">Chambre deluxe</td>
						</tr>
						<tr class="active">
							<td class="">Tam Coc</td>
							<td class="">Tam Coc Garden ****</td>
							<td class="">2</td>
							<td class="">Chambre deluxe</td>
						</tr>
						<tr>
							<td class="">Baie d'Along</td>
							<td class="">RedDragon's partagé *** 5 cabines</td>
							<td class="">1</td>
							<td class="">Cabine privée</td>
						</tr>
						<tr class="active">
							<td class="">Hue</td>
							<td class="">PilgrimageResort& Spa *****</td>
							<td class="">2</td>
							<td class="">Deluxe</td>
						</tr>
						<tr>
							<td class="">Hoi An</td>
							<td class="">Anicent House Village ***+</td>
							<td class="">2</td>
							<td class="">Garden viewDeluxe</td>
						</tr>
						<tr class="active">
							<td class="">Saigon</td>
							<td class="">Lavender Boutique ***</td>
							<td class="">1</td>
							<td class="">Executive</td>
						</tr>
						<tr>
							<td class="">Hoi An</td>
							<td class="">Anicent House Village ***+</td>
							<td class="">2</td>
							<td class="">Garden viewDeluxe</td>
						</tr>
						<tr class="active">
							<td class="">Saigon</td>
							<td class="">Lavender Boutique ***</td>
							<td class="">1</td>
							<td class="">Executive</td>
						</tr>
						<tr>
							<td class="">Hoi An</td>
							<td class="">Anicent House Village ***+</td>
							<td class="">2</td>
							<td class="">Garden viewDeluxe</td>
						</tr>
						<tr class="active">
							<td class="">Saigon</td>
							<td class="">Lavender Boutique ***</td>
							<td class="">1</td>
							<td class="">Executive</td>
						</tr>
					</tbody>
				</table>
			</div> <!-- end program -->
			<div class= "program participant">
				<h3>VOLS</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="t-head" style="border-bottom: none">Vols</th>
							<th class="t-head" style="border-bottom: none">Numéro</th>
							<th class="t-head" style="border-bottom: none">Heure de départ</th>
							<th class="t-head" style="border-bottom: none">Heure d’arrivée</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="">Saigon - Danang</td>
							<td class="">VN 126</td>
							<td class="">17h35</td>
							<td class="">18h55</td>
						</tr>
						<tr class="active">
							<td class="">Hanoi – Dien Bien</td>
							<td class="">VN1704</td>
							<td class="">15h10</td>
							<td class="">16h20</td>
						</tr>
					</tbody>
				</table>
			</div> <!-- end program -->
			<div class= "program participant">
				<h3>TRAINS</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="t-head" style="border-bottom: none">Trains</th>
							<th class="t-head" style="border-bottom: none">Numéro</th>
							<th class="t-head" style="border-bottom: none">Heure de départ</th>
							<th class="t-head" style="border-bottom: none">Heure d’arrivée</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="">Hanoi – Lao Cai</td>
							<td class="">SP1</td>
							<td class="">21h40</td>
							<td class="">05h30</td>
						</tr>
						<tr class="active">
							<td class="">Lao Cai - Hanoi</td>
							<td class="">SP2</td>
							<td class="">20h15</td>
							<td class="">04h20</td>
						</tr>
					</tbody>
				</table>
			</div> <!-- end program -->
			<div class= "program participant">
				<h3>CONTACTS DES GUIDES</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="t-head" style="border-bottom: none">Régions</th>
							<th class="t-head" style="border-bottom: none">Guides</th>
							<th class="t-head" style="border-bottom: none">Contact</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="">Nord</td>
							<td class="">Mr. Giang</td>
							<td class="">0084 …………</td>
						</tr>
						<tr class="active">
							<td class="">Hanoi – Dien Bien</td>
							<td class="">VN1704</td>
							<td class="">15h10</td>
						</tr>
					</tbody>
				</table>
			</div> <!-- end program -->
			<div class= "program participant">
				<h3>CONTACTS URGENTS</h3>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="t-head" style="border-bottom: none">Personnel en charge</th>
							<th class="t-head" style="border-bottom: none">Contact</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="">Mlle. NHUNG</td>
							<td class="">0084 914 76 03 90</td>
						</tr>
						<tr class="active">
							<td class="">Mr. DUC ANH</td>
							<td class="">0084 904542880</td>
						</tr>
					</tbody>
				</table>
			</div> <!-- end program -->
		</div>
	</div>
</div>


