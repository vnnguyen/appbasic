<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use app\components\DateProcess;

$fromTimeZone = 'UTC';
$toTimeZone = 'Asia/Ho_Chi_Minh';
$this->registerCss('
    .tasks ul li{ list-style: none;}
    .tasks ul li span{padding-left: 10px;}
    .tasks .modal-dialog {max-width: 900px;}
    .tasks .content_tasks{ display: inline-block; padding: 20px 15px}
    .tasks .modal-dialog .modal-body{ height: auto}
    .tasks .task-group{ padding:0; clear: both; margin: 15px 0}
    .modal-body .radio{ border: none;}
    .option-detail-task{display:none}
    .wrap-add-task{ display: inline-block; padding: 20px 0 0 100px;}
    .ui-datepicker{ z-index:1151 !important; }
    #AnyTime--anytime-both{z-index:1151 !important;}
    .select2-container{ width: 100% !important; }
    span.remove-task{ margin-left: 15px; display: none; cursor: pointer;}
    .flash-alert{ display: none;}
    #span-refresh-task{ margin-left: 10px; cursor: pointer;}
    #task-form .form-control-feedback {
    pointer-events: auto;
    }
    #task-form .form-control-feedback:hover {
    cursor: pointer;
    }
    .option-detail-task{ margin-left: 15px}
    .span-check-success{ cursor:pointer}

');
// $this->registerCssFile(Yii::$app->request->baseUrl.'/css/datetimepicker.min.css', [
//   'depends' => ['app\assets\AppAsset'],
//   'media' => 'print',
//   ], 'css-print-theme');
$this->registerCssFile(Yii::$app->request->baseUrl.'/css/formValidation.min.css', [
  'depends' => ['app\assets\AppAsset'],
  'media' => 'print',
  ], 'css-print-theme');
$this->registerCssFile(Yii::$app->request->baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/formValidation.min.js', ['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/framework/bootstrap.min.js', ['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/core/libraries/jquery_ui/widgets.min.js', ['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/plugins/pickers/anytime.min.js', ['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/select2.full.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
// $this->registerJsFile(Yii::$app->request->baseUrl.'/js/datetimepicker.min.js', ['depends' => ['app\assets\AppAsset']]);

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/task.js', ['depends' => ['app\assets\AppAsset']]);
// $this->registerJs('
//      $("#datepicker").datepicker({
//         changeMonth: true,
//         changeYear: true,
//         dateFormat: "dd-mm-yy",
//         showButtonPanel: true,
//         altField: "#actualDate",
//         closeText: "Clear",
//         yearRange: "c-30:c+30",
//         onClose: function () {
//                 var event = arguments.callee.caller.caller.arguments[0];
//                 // If "Clear" gets clicked, then really clear it
//                 if ($(event.delegateTarget).hasClass("ui-datepicker-close")) {
//                     $(this).val("");
//                 }
//             }

//     });
// ');
?>

<div class="panel col-md-4 tasks">
  <div class="pull-right text-right" style="width:100px;">
    <a class="text-muted" id = 'add_new_task'>+New</a>
    <span id="span-refresh-task"><i class="fa fa-refresh"></i></span>
  </div>
  <p>
    <strong>RELATED TASKS</strong>
  </p>
  <ul id="ul-list-task">
    <?php $datePro = new DateProcess();

    ?>
    <?php if ($task != null): ?>
      <?php foreach ($task as $t): ?>

        <?php if ($taskUser != null):
        $arr = [];
        foreach ($taskUser as $tu):
          if ($tu->task_id == $t->id): 
            if ($tu->completed_dt !='0000-00-00 00:00:00') {
              $arr[] = '<del>'.$tu->user_id.'</del>';
            } else {
              $arr[] = $tu->user_id;
            }
            endif;
            endforeach
            ?>
            <li class="li-wrap-task">
              <span class="span-check-success"><i class="task-check fa <?php echo ($t->status=='on')?'fa-square-o':'fa-check-square-o';?>"></i></span><!-- <i class="fa fa-check-square-o"></i> -->
              <input type="hidden" class="is_priority" value="<?php echo $t->is_priority?>">
              <input type="hidden" class="due_t" value="<?php echo $datePro->convert($t->due_dt, 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);?>">
              <input type="hidden" class="is_all" value="<?php echo $t->is_all?>">
              <span class="task-date"><?php 
                $date = $datePro->convert($t->due_dt, 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
                $text = $datePro->resultTheDay(date('Y-m-d',strtotime($date)));
                if ($text != '') {
                  echo $text;
                }
                else{
                  echo $datePro->convert($t->due_dt, 'd-m', $fromTimeZone, $toTimeZone);
                }
                ?></span>
                <span>
                  <a class="task_desc" data-id="<?php echo $t->id; ?>"><?php echo $t->description;?></a>
                </span>
                <input type="hidden" class="id_user" value="1">
                <span class="name-user"><?php echo implode(',',$arr); ?></span>
                <span class="remove-task" title="remove" data-popup="tooltip"><i class="fa fa-remove"></i></span>
              </li>
            <?php endif ?>
          <?php endforeach ?>
        <?php endif ?>
      </ul>
      <!-- Modal -->
      <div id="modal_tasks" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
          <?php 
          $form = ActiveForm::begin([
           'options' => [
           'class' => 'form-horizontal',
           'id' => 'task-form',
           ],
            // 'enableClientValidation' => false, // it's not related to client validation
            // 'enableClientScript' => false, // but related to yii.activeForm.js
           ])?>
           <!-- Modal content-->
           <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Task Form</h4>
            </div>
            <div class="modal-body">
              <div class="col-md-12 content_tasks">

               <fieldset>
                <input type="hidden" id="input_action" name="action" value="" >
                <input type="hidden" id="task_id" name="task_id" value="" >
                <div class="form-group">
                 <label for="" class="col-md-2 control-label">Liên quan đến</label>
                 <div class="controls col-md-10"><a href="/tours/r/13732">F1607065 - Mathilde Hoornaert</a></div>
               </div>
               <div class="form-group wrap-priority">
                <label for="description" class="col-md-2 control-label">Việc cần làm và thời hạn (ngày, giờ)</label>
                <div class="col-md-10">
                  <input type="text" value="" name="description[]" class="form-control description" placeholder="Miêu tả việc cần làm, tối đa 255 chữ">
                </div>
              </div>
              <div class="col-md-10 col-md-offset-2">
                <div class="col-lg-2">
                  <div class="form-group">
                    <div class="checkbox checkbox-switchery switchery-xs">
                      <label>
                        <input type="checkbox" name="priority" class="switchery" id="is_priority">
                        Priority
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <?php $today = date('Y-m-d'); ?>
                    <select id="deadline" name="deadlines" class="form-control" placeholder="select option">
                      <option value="today"><?= Yii::t('app', 'Today');?></option>
                      <option value="tomorrow"><?= Yii::t('app', 'Tomorrow');?></option>
                      <option value="after_tomorrow"><?= Yii::t('app', 'The day after tomorrow');?></option>
                      <option value="this_week"><?= Yii::t('app', 'This Week');?></option>
                      <option value="next_week"><?= Yii::t('app', 'Next Week');?></option>
                      <option value="this_month"><?= Yii::t('app', 'This Month');?></option>
                      <option value="next_month"><?= Yii::t('app', 'Next Month');?></option>
                      <option value="any_time"><?= Yii::t('app', 'Anytime');?></option>
                      <option value="detail"><?= Yii::t('app', 'Time Details');?></option>
                    </select>
                  </div>
                </div>

            <!-- <div class="col-md-3 option-detail-task">
              <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                <input type="text" class="form-control datepicker-animation" placeholder="yyyy-mm-dd" value="" id="datepicker" name="due_date">
              </div>
            </div> -->
            <div class="col-md-5 option-detail-task" id = "date-detail">
              <div class="form-group">
                <div class="input-group ">
                  <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                  <input type="text" class="form-control" id="anytime-both" name="due_date" value="" placeholder="yyyy-mm-dd hh:mm">
                </div>
              </div>
            </div>
            
            <!-- <div class="col-md-3 option-detail-task">
              <input type="text" class="form-control" placeholder="hh:mm" maxlength="5" value="" name="due_time" style="" class="ta-c">
            </div> -->
          </div> <!-- end -->
          <div class="wrap-add-task form-group ">
            <div class=" col-md-10 col-md-offset-2">
              <p>
                <a href="#" id="task-group-add">+ Thêm một việc nữa</a> hoặc
                <a href="#" style="color:#c00;" id="task-group-delete">- Xoá việc nằm dưới cùng</a>
              </p>
            </div>
          </div>

          <div class="form-group" style="clear: both">
           <label for="" class="col-md-2 control-label">Giao cho</label>
           <div class="col-md-10">
             <select name="who[]" class="form-control" id="assignee" multiple="true" >
             </select>
           </div>
         </div>
         <div class="form-group">
           <label for="" class="col-md-2 control-label">Nếu giao cho nhiều người thì</label>
           <div class="col-md-10">
            <label class="radio form-control" ><input type="radio" checked="checked" value="yes" name="is_all" id ="set_is_all"> Việc hoàn thành khi mọi người được giao hoàn thành</label>
            <label class="radio form-control"><input type="radio" value="no" name="is_all"  id ="set_is_not_all" > Việc hoàn thành khi có 1 người được giao hoàn thành</label>
          </div>
        </div>
      </fieldset>

    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-default" id="btn-save-task" name="submitButton" >Save</button>
    <?php ActiveForm::end() ?>
  </div>
</div>

</div>
</div>
</div>
