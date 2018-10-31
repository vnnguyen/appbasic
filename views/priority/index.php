<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Priorities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="priority-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Priority'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'location',
            [
                'attribute' => 'category',
                'content' => function($data) {
                    switch ($data->category) {
                        case 1:
                            return "Other Companies";
                            break;

                        case 2:
                            return "Our Company";
                            break;break;

                        default:
                            return "Customer Request";
                            break;
                    }
                }
            ],
            'content',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url,$model){
                        return Html::a('<i class="fa fa-edit"></i>',$url);
                    },
                    'delete' => function($url,$model){
                        return Html::a('<i class="fa fa-remove"></i>',$url,
                                        [
                                        'data-popup' => 'tooltip', 'title' => Yii::t('app', 'Remove'),
                                        'id' => 'confirm',
                                        'data' => [
                                            'confirm' => Yii::t('app','Are you sure you want to delete this item ?'),
                                            'method' => 'post'
                                            ]
                                        ]
                                    );
                    },
                ],
            ],
        ],
    ]); ?>
</div>
