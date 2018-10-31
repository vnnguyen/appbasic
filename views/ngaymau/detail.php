<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use dosamigos\ckeditor\CKEditor;
use yii\jui\DatePicker;


$baseUrl = Yii::$app->request->baseUrl;
$this->registerCssFile($baseUrl."/css/select2.min.css");
$this->registerCss('
	li{ list-style: none; position: relative;}
	.list-day-tour-wrap { display:block; border: 1px solid #ccc; padding: 0; margin-top: 15px}
	.list-day-tour { border-top: 1px solid #ccc; padding: 0; width: 100%;}
	p.list-title {font-size: 20px; font-weight: 500; padding:20px;}
	.day-tour, .translate-day-tour { padding: 0; margin: 0; display: inline-block !important; width: 100%;  clear: both;
		background-color: #fcfcfc;
		border: 1px solid #ddd;
		border-radius: 2px;
		color: #777;
		font-size: 12px;
		padding: 6px 12px;
	}
	.title-tour { padding: 10px 15px; border-bottom:1px solid #ccc; cursor: pointer}
	.title-tour span.badge {background-color: #777; }
	.title-tour span { margin-right:20px}
	.title-tour span.date { font-size: 16px}
	.title-tour span.title-text { font-size: 16px; font-weight: 500;}
	.content-tour { padding-left: 58px; }
	.content-tour .content-text , .translate-content{margin:20px 0 40px; font-size: 14.3px}
	.bottom-link { display:inline-block; float: right; margin-right:5px; padding:10px 0; clear: both; display: none; position: absolute; bottom: 5px; right: 10px;}
	.bottom-link ul { position: relative}
	.bottom-link ul li{float:left; margin-left: 10px; }
	.bottom-link ul li a { padding: 2px 4px}
	.bottom-link ul li a i{font-size: 15px}
	.content-main { margin-top: 20px;}
	.detail-tour #edit-modal .modal-dialog {max-width: 900px !important}

	.content-alert { text-align: center;}
	.under { padding-left: 20px; text-decoration: underline;}
	#select-modal .modal-dialog {max-width: 1024px !important; margin:5px auto !important;}
	.btnadd:hover{background:#337AB7}
	.search { display: inline-block; margin-top: 20px; width: 100%;}
	.td-id {display: none;}
	#th-content,.td-content {display: none;}
	.tooltip { max-width: 200px !important;}
	.ul.pagination {
		display: inline-block;
		padding: 0;
		margin: 0;
	}

	ul.pagination li {display: inline;}

	ul.pagination li a {
		color: black;
		float: left;
		padding: 8px 16px;
		text-decoration: none;
		transition: background-color .3s;
	}

	ul.pagination li a.active {
		background-color: #4CAF50;
		color: white;
	}

	ul.pagination li a:hover:not(.active) {background-color: #ddd;}
	#pagingControls ul {padding: 20px 25%}
	.edit-title { display: none; position: absolute; right: 10px}
	.list-title:hover .edit-title {display: inline-block}
	span.tag_name {padding: 4px 7px; border:1px solid #ddd; margin-right: 2px; cursor: pointer}
	.select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #666;}
	.select2.select2-container.select2-container--default{width: 100% !important;}
	.translate-form{display: none;}
	.translate-day-tour{ margin-bottom: 5px;}
	.translate-day-tour:hover {
		border: 1px solid rgb(204,204,204);
		box-shadow: 0 1px 6px 1px rgb(204, 204, 204);

	}
	.translate-day-tour .translate-title span.date { padding-right: 20px}
	.col-md-6.translation-wrap > ul {padding-left: 0; margin-top: 133px;}
	.translate-title { padding: 10px 15px; border-bottom:1px solid #ccc;}
	.remove_translate:hover { cursor: pointer}
	.translate-cancel{ margin-right: 5px}
	.note-content:hover { cursor: pointer }
	.remove-note {display: none; margin-left: 20px}
	.remove-note:hover{ cursor: pointer;}
	.note:hover{ background-color: #ccc;}
	.note-content { padding-left: 10px}
	.add-day-ex { margin-right: 15px}
	');
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
// $this->registerJsFile('http://rubaxa.github.io/Sortable/Sortable.js');
$this->registerJsFile($baseUrl.'/js/core/libraries/jquery_ui/interactions.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);


$this->registerCssFile(Yii::$app->request->baseUrl.'/css/formValidation.min.css', [
  'depends' => ['app\assets\AppAsset'],
  'media' => 'print',
  ], 'css-print-theme');
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/formValidation.min.js', ['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile($baseUrl.'/js/jquery.simplePagination.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/tour.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/plugins/editors/summernote/summernote.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/pages/editor_summernote.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/select2.full.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("
	$('#summernote').summernote({
		toolbar: [
	    // [groupName, [list of button]]
		['style', ['bold', 'italic', 'underline', 'clear']],
		['font', ['strikethrough', 'superscript', 'subscript']],
		['fontsize', ['fontsize']],
		['color', ['color']],
		['para', ['ul', 'ol', 'paragraph']],
		['height', ['height']]
		]
	});
	");

	?>
<div class="col-md-12 wrap-tour">
	<div class="col-md-6 detail-tour">
		<div class="button-top text-right">
			<button type="button" class="btn btn-info" id="btn-add-days-tour"><?= Yii::t('app', 'Add source day')?></button>
			<button type="button" class="btn btn-primary" id="btn-add-tour"><?= Yii::t('app', 'Add day')?></button>
		</div>
		<!--list-day-tour-wrap -->
		<div class="col-md-12 list-day-tour-wrap">
			<p class="list-title"><span class='name-tour'><?= Yii::t('app', $tour_model->title); ?></span><input type="hidden" id="tour_id" value="<?= $tour_model->id; ?>"><input type="hidden" id="start_date" value="<?= Yii::$app->formatter->asDate($tour_model->start_date, 'php:d/m/Y');?>"><span class="btn btn-default edit-title" data-popup="tooltip" title="edit name of tour"> <i class="fa fa-edit"></i></span></p>
			<div class="list-content">
				<ul id="sortable-list-second" class=" selectable-demo-list selectable-demo-connected ui-sortable list-day-tour">
					<?php $i = 1; foreach ($dataProvider as $daystour) {?>
					<li class="ui-sortable-handle day-tour">
						<div class="ui-sortable-handle title-tour">
							<span class="badge i-count"><?= $i?></span>
							<span class="date">20-05-2016</span>
							<span class="title-text"><?= $daystour->ngaymau_title; ?></span>
							<input type="hidden" id="index" value="<?= $daystour->id ?>">
						</div>
						<div class="content-tour collapse" >
							<div class="tour_note">
								<?php if (isset($tour_notes) && $tour_notes != null): ?>
									<?php foreach ($tour_notes as $tour_note): ?>
										<?php if ($tour_note['day_id'] == $daystour->id): ?>
											<p class="note" data-icon="<?= $tour_note['icon']?>" data-id="<?= $tour_note['id']?>" data-color="<?= $tour_note['color']?>" style="color:<?= $tour_note['color']?>">
											<?php if ($tour_note['icon'] == 'guide'): ?>
												<i class="fa fa-user"></i>
											<?php endif ?>
											<?php if ($tour_note['icon'] == 'car'): ?>
												<i class="fa fa-car"></i>
											<?php endif ?>
											<?php if ($tour_note['icon'] == 'plane'): ?>
												<i class="fa fa-plane"></i>
											<?php endif ?>
											<span class="note-content"><?= $tour_note['content']?></span>
											<span class="remove-note" style="color: #000 !important"><i class="fa fa-remove"></i></span>
											</p>
										<?php endif ?>
									<?php endforeach ?>
								<?php endif ?>
							</div>
							<p class="content-text"><?= $daystour->ngaymau_body; ?></p>
						</div>

						<div class="bottom-link text-right">
							<ul class="wrap-links">
								<li><a class="btn btn-default my-bottom-link add-day" data-popup="tooltip" title="Day after" > <i class="fa fa-plus"></i> </a></li>
								<li><a class="btn btn-default my-bottom-link add-blank-day" data-popup="tooltip" title="Day blank after"> <i class="fa fa-plus"></i> </a></li>
								<li><a class="btn btn-default my-bottom-link copy-day" data-popup="tooltip" title="Copy"> <i class="fa fa-copy"></i> </a></li>
								<li><a class="btn btn-default my-bottom-link move-up" data-popup="tooltip" title="Move up"> <i class="fa fa-arrow-up"></i> </a></li>
								<li><a class="btn btn-default my-bottom-link move-down" data-popup="tooltip" title="Move down"> <i class="fa fa-arrow-down"></i> </a></li>
								<li><a class="btn btn-default my-bottom-link edit" data-popup="tooltip" title="edit"> <i class="fa fa-edit"></i></a></li>
								<li><a class="btn btn-default my-bottom-link delete-item" data-popup="tooltip" title="delete"> <i class="fa fa-remove"></i> </a></li>
								<li><a class="btn btn-default my-bottom-link translate-item" data-popup="tooltip" title="Translate">T</a></li>
								<li><a class="btn btn-default my-bottom-link note-item" data-popup="tooltip" title="Note"><i class="fa fa-comment-o"></i></a></li>
							</ul>
						</div>
					</li> <!-- end day-tour -->
					<?php $i++;} ?>
				</ul>
			</div>
		</div>
		<!-- end list-day-tour-wrap -->
		<!-- edit Modal -->
		<div class="modal fade" id="edit-modal" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Update day tour</h4>
					</div>
					<?php $form = ActiveForm::begin([
						'id' => 'tour-form',
						]) ?>
					<div class="modal-body">
						<div class="col-md-12 content-main">
							<div class="row">
								<div class="form-group col-md-10">
									<label>Name of tour</label>
									<input type="text" name="title-tour" id="title-tour" class="form-control" placeholder="Enter name" required="required">
								</div>
							</div>
							<div class="form-group wrap-content">
								<label>Content</label>
								<textarea name="content-tour" class="form-control " id="summernote" placeholder="Enter content" required></textarea>
							</div>
						</div>
					</div>
					<?php ActiveForm::end() ?>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-modal">Close</button>
						<input type="submit" class="btn btn-primary" value="Save" id="btnSave">
					</div>
				</div>
			</div>
		</div>
		<!--end edit Modal -->
	</div>
	<!--translate-->
	<div class="col-md-6 translation-wrap">
		<ul id="list_translate">
			<?php if (isset($translate_data) && $translate_data != null): ?>
				<?php foreach ($translate_data as $trans): ?>
					<li class="translate-day-tour" data-id="<?=$trans->day_id?>">
						<div class="translate-title">
							<span class="date"></span>
							<span class="title-text"><?= $trans->title_t ?></span>
							<span class="pull-right remove_translate" data-id="<?= $trans->id ?>"><i class="fa fa-remove"></i></span>
						</div>
						<div class="translate-content collapse">
							<p class="content-text"><?= $trans->content_t ?></p>
						</div>
					</li>
				<?php endforeach ?>
			<?php endif ?>
		</ul>
	</div>
	<!--End translate-->
</div>
<!--translate-form-->
<div class="translate-form">
	<form id="t_form">
		<div class="col-md-12">
			<div class="form-group">
				<input type="text" class="form-control" name="title" value="" placeholder="">
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<textarea  class="form-control" name="content" style="height:120px"></textarea>
			</div>
		</div>
		<div class="form-group">
			<button class="btn btn-primary pull-right translate-save" name="submitbtn">Save</button>
			<button class="btn btn-default pull-right translate-cancel">Cancel</button>
		</div>
	</form>
</div>
<!--end translate-form-->

<!--select Modal -->
<div class="modal fade" id="select-modal" role="dialog">
	<div class="modal-dialog modal-lg modal-full">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Select day tour</h4>
			</div>
			<?php $form = ActiveForm::begin([
				'id' => 'listData',
				]) ?>
			<div class="modal-body">
				<div class="col-md-12 select-wrap" >
					<div class="search">
						<div class="col-md-6 form-group">
							<input type="text" name="title" class="form-control s_title" placeholder="Search Title" id="txt-search">
						</div>
						<div class="col-md-6 tag-wrap">
							<div class="col-md-12 form-group">
								<select class="form-control" id="tag_select" name="tags"></select>
							</div>
						</div>
					</div>
					<div id="source-day" class="table-responsive table-lg">
						<!-- place gridview -->
						<table class="table table-lg">
							<thead>
								<tr>
									<th>Title</th>
									<th id="th-content" width="100px">Content</th>
								</tr>
							</thead>
							<tbody id="grid_source">
							</tbody>
						</table>
						<div id="pagingControls"></div>
					</div> <!-- end-source-day -->
				</div>
			</div>
			<?php ActiveForm::end() ?>
			<div class="modal-footer">
				<button type="button" class="btn btn-default add-day-ex" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- end-select_modal -->
<div class="modal fade" id="comment_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel">Comment</h4>
        </div>
        <form id="note-form">
        <div class="modal-body">
			<input type="hidden" name="action" value="">
			<div class="col-md-6">
				<div class="form-group">
					<label for="icon-note" class="form-control-label">Icon:</label>
					<select name="icon" class="form-control" id="icon-note" placeholder="select option">
						<option value="">select option</option>
						<option value="car">Car</option>
						<option value="plane">Plane</option>
						<option value="guide">Guide</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
			    <div class="form-group">
					<label for="color-note" class="form-control-label">color:</label>
					<select name="color" class="form-control" id="color-note">
						<option value="">select option</option>
						<option value="red">Red</option>
						<option value="blue">Blue</option>
						<option value="green">Green</option>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
			      <label for="message-text" class="form-control-label">Message:</label>
			      <textarea class="form-control" name="content" id="message-text"></textarea>
			    </div>
			</div>
        </div>
        <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary" id="note-save">Save change</button>
        </div>
        </form>
      </div>
    </div>
</div>



