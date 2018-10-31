<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
// var_dump($dataProvider); die();
$baseUrl = Yii::$app->request->baseUrl;

$this->registerCss('
    .fa-remove, .fa-edit, .fa-copy { font-size: 16px;}
    .icons-list > li { padding-right: 10px;}
    .btn-copy {cursor: pointer}
    .modal-dialog{ max-width: 900px !important}
    .content-main { padding-top: 20px}
');
// $this->registerJsFile($baseUrl.'/js/maintour.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs('

');



$baseUrl = Yii::$app->request->baseUrl;
$this->title = Yii::t('app', 'Tours');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-flat col-md-12">
    <div class="tour-index">

        <p>
            <?= Html::a(Yii::t('app', 'Create Tour'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <div class="table-responsive data_table">
            <table class="table table-lg">
                <thead>
                    <tr>
                        <th><?= Yii::t('app', 'Name'); ?></th>
                        <th><?= Yii::t('app', 'PDF file'); ?></th>
                        <th><?= Yii::t('app', 'PDF file new'); ?></th>
                        <th><?= Yii::t('app', 'Priority'); ?></th>
                        <th><?= Yii::t('app', 'Start date'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataProvider as $key => $tour) { ?>
                    <tr>
                        <td><?= (Html::a($tour->title, Yii::$app->urlManager->createUrl(['ngaymau/detail/'.$tour->id])).' - '.$tour->excerpt)  ?></td>
                        <td class="file_pdf"> <?= html::a('<i class="fa fa-file-pdf-o"></i>', $baseUrl.'/tour/report/'.$tour->id,[]); ?> </td>
                        <td class="file_pdf"> <?= html::a('<i class="fa fa-file-pdf-o"></i>', $baseUrl.'/tour/report1/'.$tour->id,[]); ?> </td>
                        <td class="priority"> <?= html::a('view', $baseUrl.'/priority/viewpriority/'.$tour->id, []); ?> </td>
                        <td class="start_date"> <?= date('d/m/Y', strtotime($tour->start_date));?> </td>
                        <td class="actions">
                            <ul class="icons-list">
                                <li><?= html::a('<i class="fa fa-edit"></i>',$baseUrl.'/tour/update/'.$tour->id,[
                                    'data-popup' => 'tooltip',
                                    'title' => Yii::t('app', 'edit'),
                                    ]);?></li>
                                <li><?= html::a('<i class="fa fa-copy"></i>',$baseUrl.'/tour/duplicate/'.$tour->id,[
                                    'data-popup' => 'tooltip',
                                    'title' => Yii::t('app', 'copy'),
                                    ]);?></li>
                                <li><?= html::a('<i class="fa fa-remove"></i>',$baseUrl.'/tour/delete/'.$tour->id,[
                                    'data-popup' => 'tooltip',
                                    'title' => Yii::t('app', 'delete'),
                                    'data' => [
                                    'confirm' => Yii::t('app','Are you sure you want to delete this item ?'),
                                    'method' => 'post',
                                    ]
                                    ]);?></li>
                            </ul>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="pages">
                <?= yii\widgets\LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                ?>
            </div>
        </div>
    </div>
</div><!-- end panel -->

