<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Post1 */

$this->title = Yii::t('app', 'Create Post1');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Post1s'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
	.field-post1-cost{ display: none;}
	.pay_form { display: none;}
');
?>
<div class="post1-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
