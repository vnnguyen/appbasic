<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tasks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Task'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'co',
            'cb',
            'uo',
            'ub',
            // 'status',
            // 'description',
            // 'mins',
            // 'due_dt',
            // 'is_priority',
            // 'fuzzy',
            // 'is_all',
            // 'assignee_count',
            // 'rtype',
            // 'rid',
            // 'n_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
