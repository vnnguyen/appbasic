<?php
//var_dump($caseStats->pa_destinations); die();
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;
use kartik\select2\Select2;
use yii\jui\DatePicker;

$this->title = Yii::t('app', 'Case');
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
	.panel {
	    padding: 20px 15px;
	}
	.ui-datepicker table td.ui-datepicker-today .ui-state-highlight{ color: #000;}
    .ui-datepicker table td.ui-datepicker-current-day .ui-state-active{ color: blue}
');
$js = <<<'TXT'
$(document).on('click', '#add_pax_age', function() {
        var html = '<div class="col-md-12 wrap_pax_age"> <div class="col-md-8"> <div class="form-group"> <label>Pa Pax Ages</label> <select name="list[]" class="form-control"> <option value="60+">Seniors plus de 60</option> <option value="51-59">51-59</option> <option value="50-51">50-51</option> <option value="36-50">36-50</option> <option value="26-35">26-35</option> <option value="18-25">18-25</option> <option value="11-18">11-18</option> <option value="11-">Moins de 11</option> </select> </div> </div> <div class="col-md-2"> <div class="form-group"> <label>Number pax</label> <input type="number" value="0" name="num_pax[]" class="form-control">						</div> </div> </div>'; 
        var position = $('.wrap_pax_age:last');
        if ($('.wrap_pax_age').length < 8) {
        	$(html).insertAfter(position);
        }
    })
    // Remove button click handler
    .on('click', '#delete_pax_age', function() {
        var row = $('.wrap_pax_age:last');
        if ($('.wrap_pax_age').length > 1) {
            // Remove fields
            row.remove();
        }
    })

     $("#atcasestat-pa_start_date").datepicker({
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
TXT;
$this->registerJs($js);
$dentisnation = [
				'Vietnam' => 'Vietnam',
				'Cambodge' => 'Cambodge', 
				'Laos' => 'Laos', 
				'Birmanie' => 'Birmanie', 
				'Thailande' => 'Thailande', 
				'Indonésie' => 'Indonésie',
				'Chine' => 'Chine',
				'Autres pays ou combinaisons' => 'Autres pays ou combinaisons',
				];
$pax_ages = ['60+' => 'Seniors plus de 60', '51-59' => '51-59', '50-51' => '50-51', '36-50' => '36-50', '26-35' => '26-35', '18-25' => '18-25', '11-18' => '11-18', '11-' => 'Moins de 11'];
$group_type = [
				'Travel alone' => 'Travel alone',
				'Couple' => 'Couple',
				'Friends or couples traveling together' => 'Friends or couples traveling together',
				'Family' => 'Family',
				'Business Travel' => 'Business Travel',
				'Other' => 'Other',
			  ];
?>
	<div class="panel panel-flat col-md-6">
	<div class="">
		
	</div>
		<!-- <p><strong>THE REQUEST</strong></p> -->
		<?php $form = ActiveForm::begin();  ?>
		<?= $form->field($caseStats, 'pa_destinations')->widget(Select2::classname(), [
		    'data' => $dentisnation,
		     'value' => $caseStats->pa_destinations,
		    // 'language' => 'de',
		    'options' => ['multiple' => true,'placeholder' => 'Select'],
		    'pluginOptions' => [
		        'allowClear' => true,
		    ],
		]); ?>
		<div class="row pax_ages">
			<?php
			if ($caseStats->pa_pax_ages != '') {
				// var_dump($caseStats->pa_pax_ages);die();
				foreach ($caseStats->pa_pax_ages as $v) {
					$arr = explode(':', $v);
					?>
					<div class="col-md-12 wrap_pax_age">
						<div class="col-md-8">
							<div class="form-group">
							<label>Pa Pax Ages</label>
							<?= Html::dropDownList('list[]', $arr[0], $pax_ages,['class'=>'form-control']);?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Number pax</label>
								<?= Html::input('number', 'num_pax[]', $arr[1], ['class' => "form-control"]) ?>
							</div>
						</div>
					</div>
					<?php
				}
			} else {?>
			<div class="col-md-12 wrap_pax_age">
						<div class="col-md-8">
							<div class="form-group">
							<label>Pa Pax Ages</label>
							<?= Html::dropDownList('list[]', '', $pax_ages,['class'=>'form-control']);?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Number pax</label>
								<?= Html::input('number', 'num_pax[]', '', ['class' => "form-control"]) ?>
							</div>
						</div>
					</div>
			<?php
			}
			?>
		</div>
		<div>
			<?= Html::a( 'Add', $url = null, $options = ['id' => 'add_pax_age'] );?>
			|
			<?= Html::a( 'Remove', $url = null, $options = ['id' => 'delete_pax_age'] );?>
		</div>
		<div class="row">
			<div class="col-md-3"><?= $form->field($caseStats, 'pa_days');?></div>
			<div class="col-md-3"><?= $form->field($caseStats, 'pa_start_date')->widget(DatePicker::className(),['dateFormat' => 'dd-MM-yyyy' ,'options'=>['style'=>'width:100%;', 'class'=>'form-control']]) ?></div>
		</div>
		<div class="row">
			<div class="col-md-6"><?= $form->field($caseStats, 'pa_tour_type') ?></div>
			<div class="col-md-6"><?= $form->field($caseStats, 'pa_group_type')->dropDownList($group_type) ?></div>
		</div>
		<?= $form->field($caseStats, 'pa_tags') ?>
		<?= $form->field($caseStats, 'tour_name')->textarea();?>
		<?= $form->field($caseStats, 'country')->dropDownList($listCountries, ['options' => ['' => ''], 'prompt' => ' -- Select Country --']) ?>
		<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
		<?php ActiveForm::end(); ?>
	</div>