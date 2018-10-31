<?php
// var_dump(isset($theBooking['case']['owner']['fname'])); die();
use yii\helpers\Html;

$this->title = Yii::t('reg', 'Tour registration');

$msg = <<<'TXT'
<h4>Instruction</h4>
<p>This is the page where you register the information for all members of your travel group.</p>
<p>Please click each of the tabs "Travellers", "Arrival & Departure", "Hotel rooms" above to input required data.</p>
<p>Make sure your information is entered correctly, otherwise you risk being denied of entry or certain services during the tour.</p>
<p>After you have finished all forms, please click the "Finish" link above to submit all data to Amica Travel.</p>
<p>Should you have any questions or comments, do not hesitate to contact your travel consultant: {{ seller_name }}.</p>
<p>Thank you for your time and cooperation.</p>
TXT;

if (Yii::$app->language == 'fr') {
	$msg = <<<'TXT'
<h4>Instruction</h4>
<p>Voici la page où vous devez enregistrer les informations pour chaque membre de votre groupe de voyage.</p>
<p>Veuillez cliquer sur chaque onglet : « Voyageurs »,  « Arrivée & Départ » et « Chambres d’hôtel » ci-dessus afin de rentrer les données requises.</p> 
<p><em>Ensuite, prenez soin de vérifier que les informations que vous avez rentrées sont correctes. Sinon, l’entrée à certains sites ou certains services risque de vous être refusés durant votre circuit.</em></p>
<p>Après avoir rempli l’intégralité du formulaire, veuillez cliquer ci-dessus sur l’onglet « Terminer & Envoyer » pour soumettre toutes les données à Amica Travel.</p>
<p>Si vous avez des questions ou des suggestions, n’hésitez pas à contacter votre conseiller/conseillère : {{ seller_name }}</p>
<p>Merci de votre coopération et du temps que vous consacrerez à ce formulaire.</p>
TXT;
} elseif (Yii::$app->language == 'vi') {
	$msg = <<<'TXT'
<h4>Hướng dẫn cách đăng ký thông tin</h4>
<p>Trang này là nơi quý khách đăng ký thông tin cho các thành viên trong nhóm tour của mình.</p>
<p>Xin lần lượt click và điền thông tin cho các mục "Danh sách khách", "Ngày đến và đi", "Phòng khách sạn" trên đây.</p>
<p>Vui lòng kiểm tra kỹ, vì nếu thông tin điền không đúng, quý khách sẽ có thể gặp vấn đề với việc xuất nhập cảnh và các dịch vụ trong quá trình đi tour.</p>
<p>Sau khi đã điền xong và quý khách chắc chắn không cần sửa đổi thêm nữa, xin hãy click tab "Kết thúc và Gửi" ở trên để gửi toàn bộ thông tin đến Amica Travel.</p>
<p>Nếu có câu hỏi hay ý kiến, xin liên hệ với nhân viên tư vấn tour của quý khách: {{ seller_name }} .</p>
<p>Xin cảm ơn sự hợp tác của quý khách.</p>
TXT;
}

?>
<div class="col-md-9">
	<?php if (isset($theClientPageLink['reg_confirmed_by']) && $theClientPageLink['reg_confirmed_by'] != 0) { ?>
	<p>REGISTRATION DATA CONFIRMED</p>
	<?php } else { ?>
	<?php include('_reg_tabs.php') ?>
		<?= str_replace(['{{ seller_name }}'], [$theBooking['case']['owner']['fname'].' '.$theBooking['case']['owner']['lname']], $msg) ?>
	<?php } ?>
</div>
<?php
include('_reg_sb.php');
?>