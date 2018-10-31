<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\postsearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Recycle bin');
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
    .restore-link{ margin-left:10px}
');
?>
<div class="post-index">
    <?php  // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
         <!-- <?//= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?> -->
         <?= Html::a(Yii::t('app','List Post'), ['index'], ['class' => 'btn btn-info']) ?>
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
            [
                'attribute' => 'date_issued',
                'format' => ['date', 'php: d-m-Y'],

            ],
            // 'start_day',
            // 'expiry_day',
            // 'people_signing',
            // 'create_by',
            // 'create_at',
            'update_by',
            [
                'attribute' => 'update_at',
                'format' => ['date', 'php: d-m-Y'],

            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete} {link}',
                'buttons' => [
                    'delete' => function ($url,$model,$key) {
                         $url=Yii::$app->request->baseUrl.'/post/delete2/'.$key;
                        return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                $url,
                                [
                                    'data-popup' => 'tooltip',
                                    'title' => Yii::t('app', 'Remove'),
                                    'data' => [
                                    'confirm' => Yii::t('app','Are you sure you want to delete this item ?'),
                                    'method' => 'post',
                                    ]
                                ]
                            );
                    },
                    'link' => function ($url,$model,$key) {
                            $url=Yii::$app->request->baseUrl.'/post/restore/'.$key;
                            return Html::a('<i class="icon-undo2"></i> ' . Yii::t('app','restore'), $url,
                                [
                                    'data-popup' => 'tooltip',
                                    'title' => Yii::t('app', 'Restore'),
                                    'class' => 'restore-link',
                                ]
                            );
                    },
                ],

            ],
        ],
    ]); ?>
</div>
