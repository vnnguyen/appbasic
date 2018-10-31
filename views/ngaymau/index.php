<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'At Ngaymaus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="at-ngaymau-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create At Ngaymau'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uo',
            'ub',
            'ngaymau_title',
            'ngaymau_body:ntext',
            // 'ngaymau_tags:ntext',
            // 'ngaymau_image',
            // 'ngaymau_meals',
            // 'ngaymau_transport',
            // 'ngaymau_hotels',
            // 'ngaymau_guides',
            // 'ngaymau_services:ntext',
            // 'language',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
