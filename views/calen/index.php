<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Calens');
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Yii::$app->request->baseUrl;
$this->registerCss('
    .modal-body{ height: auto; padding: 20px}
   .ui-datepicker{ z-index:1151 !important; }
   .detail_time { display: none;}
   .no_padding{padding: 0}
   .bootstrap-select.btn-group .dropdown-menu > li > a .check-mark{ color: #333; opacity: 1}
   .bootstrap-select.btn-group .dropdown-menu > li > a .check-mark{top: 20%}
   .glyphicon{ font-size: 14px;}
   .text-pink{ margin-right: 5px}
');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css');
$this->registerCssFile($baseUrl.'/css/jquery.timepicker.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJsFile($baseUrl.'/js/calend.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJsFile('//oss.maxcdn.com/bootbox/4.2.0/bootbox.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJsFile($baseUrl.'/js/jquery.timepicker.min.js', ['depends' => \yii\web\JqueryAsset::className()]);


?>
<div class="calen-index">
    <div class="panel panel-default">

        <div class="table-responsive">
            <table class="table table-striped table-narrow">
                <thead>
                    <tr>
                        <th><?= Yii::t('app','Day reception') ?></th>
                        <th><?= Yii::t('app','Time') ?></th>
                        <th><?= Yii::t('app','Meeting place') ?></th>
                        <th><?= Yii::t('app','customer care staff') ?></th>
                        <th><?= Yii::t('app','Purpose') ?></th>
                        <th><?= Yii::t('app','Note') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($theCalens as $theCalen) {
                        // var_dump($task);die();
                        if ($theCalen->description != '') {
                            $arr_des = explode('#', $theCalen->description);
                            $md = array_diff($arr_des, [$arr_des[0],$arr_des[1], $arr_des[count($arr_des)-1]]);
                            $meet_place = $arr_des[1];
                            $note = $arr_des[count($arr_des)-1];
                ?>
                    <tr>
                        <td class="text-bold text-right text-nowrap text-muted">
                            <span class="text-muted"><?= date('m-d-Y', strtotime($theCalen->due_dt)); ?></span>
                        </td>
                        <td class="text-nowrap">
                            <a class="task_time editable editable-click"  data-type="text" data-pk="<?= date('H:i:s', strtotime($theCalen->due_dt)); ?>" data-id = "<?= $theCalen->id;?>" data-title="Sửa thời gian" title="" data-original-title="Sửa thời gian" data-toggle="modal" data-target="#edit_modal" data-url="<?= Url::to(['cal_info','id' => $theCalen->id]);?>">TBA</a>
                        </td>
                        <td>
                            <a class="text-success" href="/tasks/u/136356" title="AC"><i class="fa fa-check-circle"></i></a>
                            <a href="/tours/r/14089" rel="external"><?= '#'.$meet_place; ?></a>
                        </td>
                        <td class="text-nowrap"><a href="/users/r/12952"><?= $theCalen->cb; ?></a></td>
                        <?php
                        $icons ='';
                         foreach ($md as $v) {
                             if ($v == 'a') {
                                 $icons .= ' <i class="glyphicon fa fa-dollar text-pink"></i> ';
                             }
                             if ($v == 'b') {
                                 $icons .= ' <i class="glyphicon fa fa-refresh text-pink"></i> ';
                             }
                             if ($v == 'c') {
                                 $icons .= ' <i class="glyphicon fa fa-gift text-pink"></i> ';
                             }
                             if ($v == 'd') {
                                 $icons .= ' <i class="glyphicon fa fa-birthday-cake text-pink"></i> ';
                             }
                         }

                         ?>
                        <td class="text-nowrap"><?= $icons?></td>
                        <td class="text-nowrap"><?= $note; ?></td>
                        
                    </tr>
                <?php
                        }
                    }
                ?>
                </tbody>
            </table>
        </div><!--end table-->
    </div><!--end panel-->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title"><?= Yii::t('app','Edit information'); ?></h5>
                </div>

                <div class="modal-body">
                    <!-- The form is placed inside the body of modal -->
                    <form id="editForm" method="post" class="">
                        <input type="hidden" class="form-control" name="id" />
                        <input type="hidden" class="form-control" name="meet_place" id="meet_place" />
                        <div class="col-md-6 fuzi no_padding">
                            <div class="form-group">
                                <label class=" control-label selectpicker" ><?= Yii::t('app', 'Time from') ?></label>
                                <div class="">
                                    <select id="s_time" name="s_time" class="form-control" placeholder="select option">
                                      <option value="" disabled><?= Yii::t('app', 'select time')?></option>
                                      <option value="none"><?= Yii::t('app', 'Unknown');?></option>
                                      <option value="11:59:59"><?= Yii::t('app', 'Forenoon');?></option>
                                      <option value="17:29:59"><?= Yii::t('app', 'Afternoon');?></option>
                                      <option value="detail"><?= Yii::t('app', 'Time Details');?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 detail_time no_padding">
                            <div class="form-group col-md-6">
                                <label class=" control-label"><?= Yii::t('app', 'Time from') ?></label>
                                <input type="text" class="form-control" id="datetime1" name="time" />
                            </div>

                            <div class="form-group col-md-6">
                                <label class=" control-label"><?= Yii::t('app', 'Minutes') ?></label>
                                <div class="">
                                    <input type="text" class="form-control" name="mins" />
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label class=" control-label"><?= Yii::t('app', 'Purpose') ?></label>
                            <select id="md_purpose" class="form-control selectpicker show-tick" name="purpose" multiple  data-none-selected-text="- Select -">
                                <option data-icon="fa fa-dollar text-pink" value="a"><?= Yii::t('app', 'Charges') ?></option>
                                <option data-icon="fa fa-refresh text-pink" value="b"><?= Yii::t('app', 'Give money back') ?></option>
                                <option data-icon="fa fa-gift text-pink" value="c"><?= Yii::t('app', 'Give a gift')  ?></option>
                                <option data-icon="fa fa-birthday-cake text-pink" value="d"><?= Yii::t('app', 'Happy Birthday') ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('app', 'Note') ?></label>
                            <div class="">
                                <textarea name="note" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-offset-9">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
