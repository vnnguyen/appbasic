<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

//include('_products_inc.php');

$dayIdList = explode(',', $theProduct['day_ids']);

$title = explode(' -', $theProduct['title']);

$this->title = 'Registration: '.$thePax['name'];
$this->params['small'] = '<br>Confirmed';
$this->params['small'] .= ' | Code: '.$theProduct['op_code'];
$this->params['small'] .= ' | Date: '.date('j/n/Y', strtotime($theProduct['day_from']));

$userGenderList = [];
$allCountries = [];

?>
<div class="col-md-9">
	<p><i class="fa fa-info-circle"></i> <?= Html::a('Click here to go back to the list of travellers', DIR.URI) ?>. NOTE: You will lose any unsaved information.</p>

	<? include('_pax_form.php') ?>
</div>
<? include('_sb.php');