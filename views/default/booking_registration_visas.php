<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

$this->title = Yii::t('reg', 'Tour registration').' : '.Yii::t('reg', 'Visas');

$this->params['small'] = '<br>'.Yii::t('reg', 'Tour code').': '.$theProduct['op_code'];
$this->params['small'] .= ' | '.Yii::t('reg', 'Start date').': '.date('j/n/Y', strtotime($theProduct['day_from']));

?>
<div class="col-md-9">
	<? include('_reg_tabs.php') ?>


</div>
<? include('_reg_sb.php');