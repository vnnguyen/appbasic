<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('reg', 'Tour registration').' : '.Yii::t('reg', 'Finish & Submit');

$msg = <<<'TXT'
<h4>You are about to send your group's data to Amica Travel</h4>
<p>Once again, please make sure all required information has been provided. <span class="text-danger">After submitting this page, you cannot edit the data again</span>.</p>
TXT;

if (Yii::$app->language == 'fr') {
	$msg = <<<'TXT'
<h4>Vous êtes sur le point d'envoyer les données de votre groupe pour Amica Voyage </h4>
<p>Une fois de plus, merci d'assurez-vous que toutes les informations requises ont été fournies. <span class="text-danger">Après avoir soumis cette page, vous ne pouvez pas modifier les données à nouveau</span>.</p>
TXT;
} elseif (Yii::$app->language == 'vi') {
	$msg = <<<'TXT'
<h4>Quý khách chuẩn bị gửi các thông tin đến Amica Travel.</h4>
<p>Một lần nữa, xin hãy chắc chắn là mọi thông tin cần thiết đều đã được điền đúng và đủ. <span class="text-danger">Sau khi gửi, quý khách sẽ không sửa được các thông tin này nữa</span>.</p>
TXT;
}
?>
<div class="col-md-9">
	<? include('_reg_tabs.php') ?>
	<!-- NEU CHUA GUI -->
	<?= $msg ?>
	
	<!-- THONG TIN TONG HOP -->

	<? $form = ActiveForm::begin(); ?>
	<?= $form->field($theForm, 'message')->textArea(['rows'=>10]); ?>
	<?= $form->field($theForm, 'agree')->checkBox(); ?>
	<p class="text-right"><?= Html::submitButton(Yii::t('reg', 'Submit to Amica Travel'), ['class'=>'btn btn-primary']) ?></p>
	<? ActiveForm::end(); ?>

	<!-- NEU DA GUI -->
	<!-- THONG TIN TONG HOP -->
	<!-- CHI DAN LIEN HE -->

</div>
<?
include('_reg_sb.php');