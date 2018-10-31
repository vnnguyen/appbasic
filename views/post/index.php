<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel app\models\postsearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$baseUrl=Yii::$app->request->baseUrl;
$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile($baseUrl.'/js/plugins/notifications/sweet_alert.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
// $this->registerJsFile($baseUrl.'/js/plugins/notifications/bootbox.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
// $this->registerJsFile($baseUrl.'/js/plugins/notifications/sweet_alert.min.js', ['depends' => \yii\web\JqueryAsset::className()]);

$this->registerCss('
        .post-index{
            background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 15px; border-radius: 5px; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        }
        .colf-md-3{width:20%;}
        .text-center{padding:20px 0;}
        .data_table{width:100%;}
        #link-download{ display: none}
        .download-link{display: none; width:9%; text-align: center}
        .actions{width:5%;}
        .title-row{}
        .title-row a{ text-transform:capitalize; padding:20px 0; text-decoration:none; font-size: 20px;font-weight: 300; font-family: "Roboto",​ "Helvetica Neue",​ Helvetica,​ Arial,​ sans-serif}
        .title-row td{ padding-bottom:7px; }
        .excerpt-row td{padding-top:15px; font-family: Roboto,Helvetica,sans-serif; font-weight: 300; font-size: 17px}
        .date-row span{margin-right:5px;}
        table thead tr th{ font-size: 20px; font-weight: 300; }
        .wrap-page{display: block; padding-top:15px;}
        .action_button{ display:block; overflow:hidden}
        .btn-default:hover{background: #ccc;}
        .btn-success:hover{background: #}
        .ui-datepicker table td.ui-datepicker-today .ui-state-highlight{ color: #000;}
        .ui-datepicker table td.ui-datepicker-current-day .ui-state-active{ color: blue}
    ');
if($title == 'List Post') {
    $this->registerJs('
        $(".download-link, #link-download").show();
    ');
}
$this->registerJs('

    $(\'#clear\').click(function() {
        $("form :text").val("");
        $("form select").val("");
        window.location.href="'.$baseUrl.'/post/'.Yii::$app->controller->action->id.'";
    });
');
if(Yii::$app->controller->action->id == 'off') {
    $this->registerJs('
        $(".btn_off").hide();
    ');
}
if(Yii::$app->controller->action->id == 'draft') {
    $this->registerJs('
        $(".btn_draft").hide();
    ');
}
$this->registerJs('
     $("#post-expiry_day, #post-date_issued").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true, 
        altField: "#actualDate",
        closeText: "Clear",
        yearRange: "c-30:c+30",
        onClose: function () {
                var event = arguments.callee.caller.caller.arguments[0];
                // If "Clear" gets clicked, then really clear it
                if ($(event.delegateTarget).hasClass("ui-datepicker-close")) {
                    $(this).val("");
                }
            }

    });



');
?>
<!-- <script type="text/javascript" src="assets/js/plugins/notifications/bootbox.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script> -->
<div class="post-index">
    <div class="action_button">
        <p class="pull-right">
            <?= Html::a(Yii::t('app', 'Create Post'), ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('app', 'Draft Post'), ['draft'], ['class' => 'btn btn-default btn_draft']) ?>
            <?= Html::a(Yii::t('app', 'Off Post'), ['off'], ['class' => 'btn btn-default btn_off']) ?>
            <?= Html::a(Yii::t('app', 'Recycle bin'), ['recycle'], ['class' => 'btn btn-default']) ?>
        </p>
    </div>
    
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="search" style="padding:10px 3px; border:1px solid #ddd; margin:20px 0; border-radius: 10px; overflow: hidden; ">
       <?php
            $form = ActiveForm::begin([
            'id' => 'search-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
        ]) ?>
        <?=$form->errorSummary($model);?>
            <div class="col-md-4">
                <?= $form->field($model, 'post_title')->textInput(['placeholder' => Yii::t('app', 'Post title'),'style' => 'width:100%'])->label('') ?>
            </div>

            <div class="col-md-3">
                <?php $data=ArrayHelper::map($listData,'id','people_signing');  ?>
                <?= $form->field($model, 'people_signing')->dropDownList($data, ['prompt' => Yii::t('app', 'Select..'),'style'=>'width:100%'])->label('') ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($model, 'date_issued')->widget(DatePicker::className(),['dateFormat'=>'dd-MM-yyyy','options'=>['style'=>'width:100%','placeholder'=> Yii::t('app', 'From Date issued'),'class'=>'form-control']])->label('') ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($model, 'expiry_day')->widget(DatePicker::className(),
                    [
                        'dateFormat'=>'dd-MM-yyyy',
                        'options'=>[
                            'style'=>'width:100%',
                            'placeholder'=>Yii::t('app', 'To date issued'),
                            'class'=>'form-control',
                        ],
                    ])->label('') ?>
            </div>
            <div class=" text-center">
                <div class="">
                    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary ']) ?>
                    <input type="button" id="clear" value="<?= Yii::t('app', 'Reset')?>" class="btn btn-default">
                </div>
            </div>

        <?php ActiveForm::end() ?>
    </div>
    
    <div class="table-responsive data_table">
        <table class="table table-lg">
            <thead>
                <tr>
                    <th><?= Yii::t('app', 'Title') ?></th>
                    <th id="link-download"><?= Yii::t('app', 'Link download') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider as $key => $post) { ?>
                <tr>
                    <td>
                        <table class=" ">
                            <tbody>
                                <tr class="title-row">
                                    <td><?= Html::a($post->post_title, $baseUrl.'/post/view/'.$post->id); ?></td>
                                </tr>
                                <tr class="date-row">
                                     <td><span class="glyphicon  glyphicon-calendar"></span><?= date('d-m-Y', strtotime($post->date_issued)) ?></td>
                                </tr>
                                <tr class="excerpt-row">
                                    <td><?= $post->post_excerpt ?></td>
                                </tr>
                                <tr class="start-day-row">
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="download-link"><?= ($post->attach_file!='')? Html::a(Yii::t('app', 'Download'), $baseUrl.'/post/download/'.$post->id,
                        [
                            'data-popup' => 'tooltip',
                            'title' => Yii::t('app', 'Download file'),
                        ]
                    ) : ''; ?></td>
                    <td class="actions">
                        <ul class="icons-list">
                            <li><?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', $baseUrl.'/post/update/'.$post->id, ['data-popup' => 'tooltip', 'title' => Yii::t('app', 'Edit')]); ?></li>
                            <li><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', $baseUrl.'/post/remove/'.$post->id, [
                                'data-popup' => 'tooltip', 'title' => Yii::t('app', 'Remove'), 'id' => 'confirm',
                                'data' => [
                                    'confirm' => Yii::t('app','Are you sure you want to delete this item ?'),
                                    'method' => 'post',
                                    ]
                            ]); ?></li>
                        </ul>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="wrap-page">
        <?= \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
            ]); ?>
    </div>
    
</div>

