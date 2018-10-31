<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
?>
	<?= $form->field($theForm, 'room_type')->dropdownList($roomTypeList, ['prompt'=>Yii::t('reg', '- Select -')]) ?>
	<?= $form->field($theForm, 'pax_ids', ['enableClientValidation'=>false, 'inputOptions'=>['class'=>'form-control', 'data-none-selected-text'=>Yii::t('reg', '- Select -')]])->dropdownList(ArrayHelper::map(isset($paxWithoutRoomEdit) ? $paxWithoutRoomEdit : $paxWithoutRoom, 'id', 'name'), ['multiple'=>'multiple']) ?>
	<?= $form->field($theForm, 'note')->textArea(['rows'=>3]) ?>
	<p><?= Html::submitButton(Yii::t('reg', 'Submit'), ['class'=>'btn btn-primary']) ?></p>
<?php
ActiveForm::end();
