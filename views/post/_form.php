<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\jui\DatePicker;
// use app\assets\CkeditorAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
// CkeditorAsset::register($this);
$baseUrl = Yii::$app->request->baseUrl;
$this->registerJsfile($baseUrl.'/js/plugins/forms/styling/uniform.min.js', ['depends' => \yii\web\JqueryAsset::className()]);

$this->registerJsfile($baseUrl.'/js/pages/form_inputs.js', ['depends' => \yii\web\JqueryAsset::className()]);
// $this->registerJsfile($baseUrl.'/js/ck_config.js', ['depends' => [\yii\web\JqueryAsset::className(), \dosamigos\ckeditor\CKEditor::className()]]);
$this->registerJs("
    CKEDITOR.config.height = 500;
    CKEDITOR.config.htmlEncodeOutput = false;
    CKEDITOR.config.entities = false;
    CKEDITOR.config.entities_greek = false;
    CKEDITOR.config.entities_latin = false;
");
$this->registerCss('
    .panel{
        padding:20px 15px
    }
    .form-group label { font-weight:bold}
    .ui-datepicker table td.ui-datepicker-today .ui-state-highlight{ color: #000;}
    .ui-datepicker table td.ui-datepicker-current-day .ui-state-active{ color: blue}
');
$this->registerJs('
     $("#post-expiry_day, #post-date_issued, #post-start_day").datepicker({
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
?>
<div class="container post-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="panel panel-flat col-md-12">
            <div class="col-md-8">
                <?= $form->field($model, 'post_title')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?php
                    $a= ['draft' => Yii::t('app', 'draft'), 'on' => Yii::t('app', 'on'), 'off' => Yii::t('app', 'off'), 'deleted' => Yii::t('app', 'deleted')];
                    echo $form->field($model, 'post_status')->dropDownList($a,['prompt'=> Yii::t('app', 'Select Option')]);
                ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'post_excerpt')->textArea(['maxlength' => true]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'post_content')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'full'
                ]) ?>
            </div>
            <div class="col-md-12 ">
                <div class="uploader">
                    <?= $form->field($model, 'attach_file')->fileInput(['class' => 'file-styled-primary']) ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'date_issued')->widget(DatePicker::className(),['dateFormat' => 'dd-MM-yyyy' ,'options'=>['style'=>'width:100%;', 'class'=>'form-control']])?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'start_day')->widget(DatePicker::className(),['dateFormat' => 'dd-MM-yyyy' ,'options'=>['style'=>'width:100%;', 'class'=>'form-control']])?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'expiry_day')->widget(DatePicker::className(),['dateFormat' => 'dd-MM-yyyy' ,'options'=>['style'=>'width:100%;', 'class'=>'form-control']])?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'people_signing')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
