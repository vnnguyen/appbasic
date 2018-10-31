<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\post1search */
/* @var $dataProvider yii\data\ActiveDataProvider */
$baseUrl = Yii::$app->request->baseUrl;
$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<?php

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
        table thead tr th{ font-size: 13px; font-weight: 500; }
        .wrap-page{display: block; padding-top:15px;}
        .action_button{ display:block; overflow:hidden}
        .btn-default:hover{background: #ccc;}
        .btn-success:hover{background: #}
        .ui-datepicker table td.ui-datepicker-today .ui-state-highlight{ color: #000;}
        .ui-datepicker table td.ui-datepicker-current-day .ui-state-active{ color: blue}
    ');
$this->registerJs('

    $(\'#clear\').click(function() {
        $("form :text").val("");
        window.location.href="'.$baseUrl.'/post1/index";
    });
');

?>

<div class="post-index">
    <div class="action_button">
        <p class="pull-right">
            <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <div class="search" style="padding:10px 3px; border:1px solid #ddd; margin:20px 0; border-radius: 10px; overflow: hidden; ">
       <?php
            $form = ActiveForm::begin([
            'id' => 'search-form',
            
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
        ]) ?>
        <?=$form->errorSummary($model);?>
            <div class="col-md-4">
                <?= $form->field($model, 'content')->textInput(['placeholder'=>Yii::t('app', 'Content'),'style'=>'width:100%'])->label('') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'offer_by')->textInput(['placeholder'=>Yii::t('app', 'Offer by'),'style'=>'width:100%'])->label('') ?>
            </div>

            <div class=" text-center">
                <div class="">
                    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary ']) ?>
                    <input type="reset" id="clear" value="<?= Yii::t('app', 'Reset')?>" class="btn btn-default">
                </div>
            </div>

        <?php ActiveForm::end() ?>
    </div>
    <div class="table-responsive data_table">
        <table class="table table-lg">
            <thead>
                <tr>
                    <th><?= Yii::t('app', 'Content'); ?></th>
                    <th><?= Yii::t('app', 'Offer by'); ?></th>
                    <th><?= Yii::t('app', 'Amount'); ?></th>
                    <th><?= Yii::t('app', 'Payment'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider as $key => $post) { ?>
                <tr>
                    <td><?= ($post->status == 'pay')? Html::a($post->content_pay, $baseUrl.'/post1/view/'.$post->id.'/1') : Html::a($post->content, $baseUrl.'/post1/view/'.$post->id); ?></td>
                    <td><?= ($post->status == 'pay')? $post->offer_by : $post->offer_by; ?></td>
                    <?php $a = new \NumberFormatter("it-IT", \NumberFormatter::DECIMAL); ?>
                    <td><?= ($post->status == 'pay')? $a->format($post->cost) : $a->format($post->amount); ?></td>
                    <td><?php
                            if ($post->status == 'pay'){
                                if ($post->payment_pay == 1)
                                    echo Yii::t('app', 'ready cash');
                                else
                                {
                                    echo Yii::t('app', 'transfer');
                                }
                            }
                            else
                            {
                                if ($post->payment == 1)
                                    echo Yii::t('app', 'ready cash');
                                else
                                {
                                    echo Yii::t('app', 'transfer');
                                }
                            }
                        ?></td>
                    <?php if ($post->status == 'adp') {?>
                    <td class="payment"> <i class="icon-paypal2"></i>  <?= Html::a(Yii::t('app', 'payment now'), $baseUrl.'/post1/update/'.$post->id.'/1',
                        [
                            'data-popup' => 'tooltip',
                            'title' => Yii::t('app', 'Payment now'),
                        ]
                    ); ?></td>
                    <?php }?>
                    <td class="actions">
                        <ul class="icons-list">
                            <li><?= ($post->status == 'pay')?  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $baseUrl.'/post1/update/'.$post->id.'/1', ['data-popup' => 'tooltip', 'title' => Yii::t('app', 'Edit')]) : Html::a('<span class="glyphicon glyphicon-pencil"></span>', $baseUrl.'/post1/update/'.$post->id, ['data-popup' => 'tooltip', 'title' => Yii::t('app', 'Edit')]); ?></li>
                            <li><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', $baseUrl.'/post1/delete/'.$post->id, [
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