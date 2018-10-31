<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//////////////////////////////

$baseUrl = Yii::$app->request->baseUrl;
$this->registerCssFile($baseUrl."/css/select2.min.css");
$this->registerCss('
    li{ list-style: none; position: relative;}
    .list-day-tour-wrap { display:block; border: 1px solid #ccc; padding: 0; margin-top: 15px}
    .list-day-tour { border-top: 1px solid #ccc; padding: 0; width: 100%;}
    p.list-title {font-size: 20px; font-weight: 500; padding:20px;}
    .day-tour { padding: 0; margin: 0; display: inline-block !important; width: 100%;  clear: both; 
        background-color: #fcfcfc;
        border: 1px solid #ddd;
        border-radius: 2px;
        color: #777;
        font-size: 12px;
        padding: 6px 12px;
    }
    .title-tour { padding: 10px 15px; border-bottom:1px solid #ccc; cursor: pointer}
    span.badge {background-color: #777; }
    .title-tour span { margin-right:20px}
    .title-tour span.date { font-size: 16px}
    .title-tour span.title-text { font-size: 16px; font-weight: 500;}
    .content-tour { padding-left: 58px; }
    .content-tour .content-text {margin:20px 0 40px; font-size: 14.3px}
    .bottom-link { display:inline-block; float: right; margin-right:5px; padding:10px 0; clear: both; display: none; position: absolute; bottom: 5px; right: 10px;}
    .bottom-link ul { position: relative}
    .bottom-link ul li{float:left; margin-left: 10px; }
    .bottom-link ul li a { padding: 2px 4px}
    .bottom-link ul li a i{font-size: 15px}
    .wrap-ok{ margin-left: 42%}
    .wrap-cancel { }

    .btn-save-priority{ margin: 15px}
    .select2-container .select2-selection--single { height: 36px;}
    .select2-selection--single .select2-selection__arrow::after { content: "";}
    .btn-disable {padding: 2px 12px; display:none; }
    .priority-form {padding-left: 20px}
    .btn-cancel { padding: 3px 12px}
    ');
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/core/libraries/jquery_ui/interactions.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/priority.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/select2.full.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<div class="wrap-tour">
    <div class="col-md-6 detail-tour">
        <div class="col-md-12 list-day-tour-wrap">
            <div class="list-content">
                <!-- <?//= Html::beginForm(['priority/viewpriority', 'id' => $id], 'post', ['enctype' => 'multipart/form-data']) ?> -->
                <?php $form = ActiveForm::begin([
                    'id' => 'priority_frm'
                ]); ?>
                    <ul id="sortable-list-second" class=" selectable-demo-list selectable-demo-connected ui-sortable list-day-tour" data-id="<?php echo $id;?>">
                    <?php
                        if ($listData != null) {
                            $i = 1;
                            foreach ($listData as $v) {?>
                                <li class="ui-sortable-handle day-tour">
                                    <span class="badge i-count"><?= $i?></span>
                                    <div class="col-md-12 row priority-form ">
                                        <div class="priority">
                                            <div class="col-md-3 form-group">
                                                <label><?php echo Yii::t('app', 'Location'); ?></label>
                                                <select class="form-control location" name="location">
                                                    <option value="<?php echo $v['location']; ?>"><?php echo $v["location"]; ?></option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label><?php echo Yii::t('app', 'Other company'); ?></label>
                                                <select class="form-control other_company" name="other_company">
                                                    <option value="<?php echo $v['other_company']; ?>"><?php echo $v['other_company']; ?></option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label><?php echo Yii::t('app', 'Our company'); ?></label>
                                                <select class="form-control our_company" name="our_company">
                                                    <option value="<?php echo $v['our_company']; ?>"><?php echo $v['our_company']; ?></option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label><?php echo Yii::t('app', 'Customer request'); ?></label>
                                                <select class="form-control customer_request" name="customer_request" >
                                                    <option value="<?php echo $v['customer_request']; ?>"><?php echo $v["customer_request"]; ?></option>
                                                </select>
                                            </div>
                                            <div class="col-md-1  wrap-ok">
                                                <button type="button" class="btn btn-success  btn-disable" data-popup="tooltip" title="<?php echo Yii::t('app', 'Add new'); ?>"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bottom-link text-right">
                                        <ul class="wrap-links">
                                            <li><a class="btn btn-default my-bottom-link move-up" data-popup="tooltip" title="<?php echo Yii::t('app', 'Move up');?> "> <i class="fa fa-arrow-up"></i> </a></li>
                                            <li><a class="btn btn-default my-bottom-link move-down" data-popup="tooltip" title="<?php echo Yii::t('app', 'Move down'); ?>"> <i class="fa fa-arrow-down"></i> </a></li>
                                            <li><a class="btn btn-default my-bottom-link edit" data-popup="tooltip" title="<?php echo Yii::t('app', 'Edit'); ?>"> <i class="fa fa-edit"></i></a></li>
                                            <li><a class="btn btn-default my-bottom-link delete-item" data-popup="tooltip" title="<?php echo Yii::t('app', 'Delete'); ?>"> <i class="fa fa-remove"></i></a></li>
                                        </ul>
                                    </div>
                                </li> <!-- end day-tour -->
                                <?php $i++;
                            }
                        }
                    ?>
                                <li class="ui-sortable-handle day-tour">
                                    <span class="badge i-count"></span>
                                    <div class="col-md-12 row priority-form ">
                                        <div class="priority">
                                            <div class="col-md-3 form-group">
                                                <label><?php echo Yii::t('app', 'Location'); ?></label>
                                                <select class="form-control location" name="location"></select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label><?php echo Yii::t('app', 'Other company'); ?></label>
                                                <select class="form-control other_company" name="other_company"></select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label><?php echo Yii::t('app', 'Our company'); ?></label>
                                                <select class="form-control our_company" name="our_company"></select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label><?php echo Yii::t('app', 'Customer request'); ?></label>
                                                <select class="form-control customer_request" name="customer_request" ></select>
                                            </div>
                                            <div class="col-md-1  wrap-ok">
                                                <button type="button" class="btn btn-success  btn-disable"  data-popup="tooltip" title="<?php echo Yii::t('app', 'Add new'); ?>"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bottom-link text-right">
                                        <ul class="wrap-links">
                                            <li><a class="btn btn-default my-bottom-link move-up" data-popup="tooltip" title="<?php echo Yii::t('app', 'Move up'); ?>"> <i class="fa fa-arrow-up"></i> </a></li>
                                            <li><a class="btn btn-default my-bottom-link move-down" data-popup="tooltip" title="<?php echo Yii::t('app', 'Move down'); ?>"> <i class="fa fa-arrow-down"></i> </a></li>
                                            <li><a class="btn btn-default my-bottom-link edit" data-popup="tooltip" title="<?php echo Yii::t('app', 'Edit'); ?>"> <i class="fa fa-edit"></i></a></li>
                                            <li><a class="btn btn-default my-bottom-link delete-item" data-popup="tooltip" title="<?php echo Yii::t('app', 'Delete'); ?>"> <i class="fa fa-remove"></i></a></li>
                                        </ul>
                                    </div>
                                </li> <!-- end day-tour -->
                            </ul>
                                <div class="form-group">
                                    <input type="button" class="btn btn-primary pull-right btn-save-priority" value="save">
                                </div>
                <?php ActiveForm::end(); ?> <!-- end form -->
            </div>
        </div>
    </div>
</div> <!-- end wrap-tour -->
