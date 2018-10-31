<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Priority */
/* @var $form yii\widgets\ActiveForm */
$this->registerCss('
    .button{ display: inline-block; margin-left: 20px}
');
?>
<?php $arr = [
                    1 => 'other companies',
                    2 => 'our company',
                    3 => 'customer request'
        ]?>
<div class="panel panel-flat col-md-12 priority-form ">

    <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group">
                    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <?= $form->field($model, 'category')->dropDownList($arr,['prompt'=> Yii::t('app', 'Select category')]); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
        

        <div class="form-group button">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
