<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post1 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Post1s'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post1-view">

    <p class="text-right">
        <?php if ($model->status == 'adp') {
            echo Html::a(Yii::t('app', 'Payment Now'), ['update', 'id' => $model->id, 'status' => 1], ['class' => 'btn bg-info-800']);
        }?>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-info']) ?>
        <?= ($status2 == 'success')?  Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'status' => 1], ['class' => 'btn btn-info']) : Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['remove', 'id' => $model->id], [
            'class' => 'btn btn-default',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Print'), ['print', 'id' => $model->id], ['class' => 'btn btn-warning', 'target' => '_blank']) ?>
    </p>
    <?php if ($model->status == 'adp') {?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'offer_by',
            'department',
            'content:ntext',
            'amount',
            'status',
            'payment',
            'accom_doc',
            'note',
            [
                'attribute' => 'deadline',
                'format' => ['date', 'php:d-m-Y']
            ],
        ],
    ])
    ?>
    <?php
    }
    else {
        if ($model->status == 'pay') {
    ?>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // 'id',
                    'offer_by_pay',
                    'department_pay',
                    'content_pay:ntext',
                    'cost',
                    'status',
                    'payment_pay',
                    'accom_doc_pay',
                    'note_pay',
                ],
            ])
            ?>
    <?php
        }
    }?>
</div>
