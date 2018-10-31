<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Post1 */
/* @var $form yii\widgets\ActiveForm */

$baseUrl = Yii::$app->request->baseUrl;
// $this->registerJsfile($baseUrl.'/js/pages/form_inputs.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJs("
    // CKEDITOR.config.htmlEncodeOutput = false;
    // CKEDITOR.config.entities = false;
    // CKEDITOR.config.entities_greek = false;
    // CKEDITOR.config.entities_latin = false;
");

$this->registerCss('
    .panel{
        padding:20px 15px
    }
    .form-group label { font-weight:bold}
    .ui-datepicker table td.ui-datepicker-today .ui-state-highlight{ color: #000;}
    .ui-datepicker table td.ui-datepicker-current-day .ui-state-active{ color: blue}
    .status{display: none;}
');
$this->registerJs('
     $("#post1-deadline").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
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
if (isset($status) && $status == 1 || $model->status == 'pay') { //var_dump($status); die();
    $this->registerCss('
        .adp_form{display: none;}
        .pay_form{display: block;}
    ');
}
else 
{
     $this->registerCss('
        .adp_form{display: block;}
        .pay_form{display: none;}
    ');
}

// if (var_dump($this->scenarios())) {
//     # code...
// }
?>
<div class="container post1-form">

    <?php $form = ActiveForm::begin([
        // 'action' => ['user/edit'],
        // 'options' => ['class' => 'edit_form'],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
    ]); ?>
        <div class="panel panel-flat col-md-12">
            <?=  $form->errorSummary($model); ?>
            <div class="col-md-12 status">
                <?php
                    $a= ['adp' => Yii::t('app', 'Tạm ứng'), 'pay' => Yii::t('app', 'Thanh toán tạm ứng') ];
                    echo $form->field($model, 'status')->dropDownList($a,['prompt'=>'Select Option']);
                ?>
            </div>
            <div class="clearfix"></div>
            <div class="adp_form">
                <div class="col-md-6">
                    <?= $form->field($model, 'offer_by')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'department')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'content')->textArea(['class' => 'form-control']) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'amount')->widget(\yii\widgets\MaskedInput::className(), [
                            'clientOptions' => [
                            'alias' =>  'decimal',
                            'groupSeparator' => '.',
                            'autoGroup' => true
                        ],
                    ])  ?>
                </div>

                <div class="col-md-6">
                    <?php
                        $a= ['1' => Yii::t('app', 'ready cash'), '0' => Yii::t('app', 'transfer') ];
                        echo $form->field($model, 'payment')->dropDownList($a,['prompt'=> Yii::t('app', 'Select Option')]);
                    ?>
                </div>

                

                <div class="col-md-12">
                    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'deadline')->widget(DatePicker::className(),['dateFormat' => 'dd-MM-yyyy' ,'options'=>['style'=>'width:100%;', 'class'=>'form-control']])?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'accom_doc')->textInput(['maxlength' => true]) ?>
                </div>

            </div>

            <!-- fields of pay status -->
            <div class="pay_form">
                <div class="col-md-6">
                    <?= $form->field($model, 'offer_by_pay')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'department_pay')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'content_pay')->textArea(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'cost')->widget(\yii\widgets\MaskedInput::className(), [
                            'clientOptions' => [
                            'alias' =>  'decimal',
                            'groupSeparator' => '.',
                            'autoGroup' => true
                        ],
                    ])  ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'accom_doc_pay')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?php
                        $a= ['1' => Yii::t('app', 'ready cash'), '0' => Yii::t('app', 'transfer') ];
                        echo $form->field($model, 'payment_pay')->dropDownList($a,['prompt'=>'Select Option']);
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'note_pay')->textInput() ?>
                </div>
            </div>

            <div class="form-group col-md-12">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
