<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
$this->title = Yii::t('app','View detail ').' '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('
    $("th:first").css(
        "width", "8%"
    );
');
?>
<div class="post-view">

    <p class="text-right">
        <?= Html::a(Yii::t('app', 'List Post'), ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('app', 'Create Post'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['remove', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app','Are you sure you want to delete this item ?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'post_title',
            // 'post_status',
            // 'post_excerpt',
            'post_content:html',
            // 'attach_file',
            [
                'attribute' => 'date_issued',
                'format' => ['date', 'php:d-m-Y']
            ],
            [
                'attribute' => 'start_day',
                'format' => ['date', 'php:d-m-Y']
            ],
            [
                'attribute' => 'expiry_day',
                'format' => ['date', 'php:d-m-Y']
            ],
            'people_signing',
            'create_by',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:H:i:s d-m-Y']
            ],
            'update_by',
            [
                'attribute' => 'update_at',
                'format' => ['date', 'php:H:i:s d-m-Y']
            ],
        ],
    ]) ?>

</div>
