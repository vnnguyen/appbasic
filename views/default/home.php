<?php
use yii\helpers\Html;
$this->title = Yii::t('reg', 'Welcome to Amica Travel');

?>
<div class="col-md-12">
	<? if (Yii::$app->language == 'en') { ?>
	<h4>What is this page?</h4>
	<p>This is the place for Amica Travel's customers to access their travel information.</p>
	<p>If you are a customer of Amica Travel, please follow the special link that your travel consultant gave you to access your page.</p>

	<h4>About Amica Travel</h4>
	<p>We are a Vietnamese travel organizer based in Hanoi, Vietnam. We specialize in travel around Vietnam, Laos, Cambodia and other South East Asia destinations. We speak English, French and Vietnamese.</p>

	<? } elseif (Yii::$app->language == 'fr') { ?>
	<h4>Quelle est cette page ?</h4>
	<p>Ce est l'endroit pour les clients Amica Travel accéder à leurs informations de Voyage.</p>
	<p>Si vous êtes un client de Amica Voyage, se il vous plaît suivre le lien spécial que votre conseiller de voyage vous a donné d'accéder à votre page.</p>
	<h4>À propos de Amica Travel</h4>
	<p>Nous sommes un organisateur de Voyage vietnamien basé à Hanoi, Vietnam. Nous nous spécialisons dans Voyage autour Vietnam, Laos, Cambodge et d'autres destinations en Asie du Sud-Est. Nous parlons anglais, français et vietnamien.</p>

	<? } else { ?>
	<h4>Trang web này là gì?</h4>
	<p>Đây là website dành riêng cho khách hàng của Amica Travel.</p>
	<p>Nếu quý vị là khách hàng của Amica Travel, vui lòng dùng đường link riêng mà người tư vấn tour của Amica Travel đã gửi cho quý vị.</p>

	<h4>Thông tin về Amica Travel</h4>
	<p>Chúng tôi là một công ty Việt Nam, đặt trụ sở tại Hà Nội, có uy tín trong lĩnh vực tổ chức tour du lịch. Các điểm tour của chúng tôi gồm có Việt Nam, Lào, Cam-pu-chia và các nước Đông Nam Á khác. Chúng tôi dùng tiếng Anh, tiếng Pháp và tiếng Việt.</p>
	<? } ?>
</div>
