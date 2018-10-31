<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Service */
/* @var $form yii\widgets\ActiveForm */

$baseUrl = Yii::$app->request->baseUrl;

$this->registerCss('
	.input-group-addon, .input-group-btn{vertical-align: bottom;}
	.button_save{ margin-top: 20px}
	#condition-modal .modal-dialog, #add-condition-modal .modal-dialog{ max-width: 950px;}
	#add-new-conditions{ margin-bottom: 20px}
	#th-check, #th-code{ width: 10%}
	#th-category{ width: 15%;}
	.td-checkCondition, .td-category, .td-code{ text-align: center; }
	#ul-condition{ padding: 0}
	#ul-condition li{ display:inline-block; list-style: none;}
	.bottom-link{ display: none; float: right; position: absolute; right: 2px; top: 2px;}
	.btn-remove-condition{ cursor: pointer;}
	.wrap-from, .wrap-to, .wrap-equal {display: none}
	.simple-pagination { padding-top: 10px}
	.wrap-setting { display:inline-block; width: 100%; border: 1px solid #e3e3e3; padding-top: 15px; border-radius: 5px; margin-bottom: 20px}
	.select2 {width: 100% !important;}
	.btn-create-con{ float: right; margin-bottom: 15px}
	.con_value3{ display: none;}
	');
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/core/libraries/jquery_ui/interactions.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/jquery.simplePagination.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/select2.full.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/condition_modal.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<div class="panel panel-flat col-md-6 service-form ">
	<div class="service-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'name_service')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'dram')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

		<div class="input-group field-service-conditions">
			<label for="service-conditions" class="control-label">Conditions</label>
			<?= Html::activeInput('text', $model, 'conditions', ['class' => 'form-control']) ?>
			<span class="input-group-btn" id="button-add-conditions">
		        <button class="btn btn-secondary" type="button">Add conditions!</button>
		    </span>
			<div class="help-block"></div>
		</div>
	    <div class="form-group button_save">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="condition-modal" role="dialog">
<div class="modal-dialog modal-lg modal-full">

	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Select condition</h4>
		</div>
		<?php $form1 = ActiveForm::begin([
			'id' => 'listData',
			]) ?>
			<div class="modal-body">
				<div class="col-md-12 select-wrap" >
					<div class="search">
						<div class = "col-md-12 wrap-setting">
							<div class="col-md-2 tag-wrap">
								<div class="col-md-12 form-group">
									<label>Code</label>
									<input type="text" name="code" class="form-control txt-code"  id="txt-input-code">
								</div>
							</div>
							<div class="col-md-2">
								<div class="col-md-12 form-group">
									<label>Category</label>
									<select class="form-control" id="category_select" name="tags">
										<option value="conditions">Conditions</option>
										<option value="multip_conditions">multiple conditions</option>
									</select>
								</div>
							</div>
							<div class="col-md-2 tag-wrap">
								<div class="col-md-12 form-group">
									<label>Operator</label>
									<select class="form-control" id="operator_select" name="">
										<option value="or">OR</option>
										<option value="and">AND</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 con_value1 or_condition">
								<div class="col-md-12 form-group">
									<label>Conditions Value 1</label>
									<select class="form-control code_select" id="con_value1" name="tags"></select>
								</div>
							</div>
							<div class="col-md-3 con_value2 or_condition">
								<div class="col-md-12 form-group">
									<label>Conditions Value 2</label>
									<select class="form-control code_select" id="con_value2" name="tags"></select>
								</div>
							</div>
							<div class="col-md-6 tag-wrap con_value3">
								<div class="col-md-12 form-group">
									<label>Conditions Value multiple condition</label>
									<select class="form-control code_select" id="con_value3" name="tags"></select>
								</div>
							</div>
							
							<div class="col-md-12 form-group">
								<label>Description</label>
								<textarea class="form-control set_description" ></textarea>
							</div>
							<div class="col-md-1 btn-create-con">
									<button type="button" class="btn btn-info" id="btn-create-conditions">Create</button>
							</div>
						</div>
						<div class="col-md-4 form-group">
							<input type="text" name="code" class="form-control s_code" placeholder="Search code" id="txt-search-code">
						</div>
						<div class="col-md-4 form-group">
							<input type="text" name="category" class="form-control s_category" placeholder="Search category" id="txt-search-category">
						</div>
						<div class="col-md-4 form-group">
							<input type="text" name="description" class="form-control s_description" placeholder="Search description" id="txt-search-description">
						</div>
						<!-- <div class="col-md-4 tag-wrap">
							<div class="col-md-12 form-group">
								<select class="form-control" id="tag_select" name="tags"></select>
							</div>
						</div> -->
					</div>
					<div class="col-md-12">
						<span class="pull-right" id="add-new-conditions">
					        <button class="btn btn-success" type="button">Add new!</button>
					    </span>
					</div>
					<div id="list-condition" class="">
						<!-- place gridview -->
						<table class="table table-bordered table-hover table-lg">
							<thead>
								<tr>
									<th id="th-check">Check</th>
									<th id="th-code">Code</th>
									<th id="th-category">Category</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<div id="pagingControls"></div>
					</div> 
				</div>

			</div>
			<?php ActiveForm::end() ?>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="btn-add-conditions">Add</button>
			</div>
		</div>

	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="add-condition-modal" role="dialog">
<div class="modal-dialog modal-lg modal-full">

	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Select condition</h4>
		</div>
		<?php $form2 = ActiveForm::begin() ?>
			<div class="modal-body">
				<ul	id="ul-condition">
					<li class="panel panel-flat col-md-12 li-wrap-condition">
						<div class="col-md-12 select-wrap" >
							<div class="col-md-2 form-group wrap-code">
								<label>Code</label>
								<input type="text" class="form-control input-code-field" value="">
							</div>
							<div class="col-md-2 form-group">
								<label>Category</label>
								<select name="category[]" class="form-control select-category" placeholder="select option">
									<option value="">select</option>
									<option value="date_book">Ngày đặt</option>
									<option value="date_use">Ngày sử dụng</option>
									<option value="age">Tuổi</option>
									<option value="num_mem">Số lượng thành viên</option>
								</select>
							</div>
							<div class="col-md-1 form-group">
								<label>Operator</label>
								<select name="operator[]" class="form-control select-operator" placeholder="select option">
									<option value="">select</option>
									<option value="before">Before</option>
									<option value="after">After</option>
									<option value="from">From</option>
									<option value="to">To</option>
									<option value="equal">Equal</option>
									<option value="beetween">Beetween</option>
									<option value="or">Or</option>
									<option value="and">And</option>
									<option value="not">Not</option>
								</select>
							</div>
							<div class="col-md-3 form-group wrap-from">
								<label>From</label>
								<input type="text" class="form-control from-field" value="">
							</div>
							<div class="col-md-3 form-group wrap-to">
								<label>To</label>
								<input type="text" class="form-control to-field" value="">
							</div>
							<div class="col-md-3 form-group wrap-equal">
								<label>Equal</label>
								<input type="text" class="form-control equal-field" value="">
							</div>
							<div class="col-md-5 form-group">
								<label>Description</label>
								<textarea class="form-control description" ></textarea>
							</div>
							<div class="bottom-link">
								<span class="btn-remove-condition"><i class="glyphicon glyphicon-remove"></i></span>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<?php ActiveForm::end() ?>
			<div class="modal-footer">
				<div class="pull-left">
					<button type="button" class="btn btn-info" id="btn-add-more">+</button>
				</div>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="btn-save-conditions">Save</button>
			</div>
		</div>

	</div>
</div>

