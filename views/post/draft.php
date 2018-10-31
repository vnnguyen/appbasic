<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\postsearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Draft Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List Post', ['index'], ['class' => 'btn btn-info']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'post_title',
            // 'post_status',
            'post_excerpt',
            // 'post_content:ntext',
            // 'attach_file',
            // 'date_issued',
            // 'start_day',
            // 'expiry_day',
            // 'people_signing',
            'create_by',
            'create_at',
            // 'update_by',
            // 'update_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
