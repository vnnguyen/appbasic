<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\CpTour */
/* @var $form yii\widgets\ActiveForm */
$baseUrl = Yii::$app->request->baseUrl;
// $this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.1.1/jquery-confirm.min.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCss('
    .panel {
        padding: 20px 15px;
    }
    .select2-selection--single .select2-selection__arrow::after {right: 5px;}
    .select2-container .select2-selection--single{height:36px}
    .select2{ width: 100% };
    .cp-comment {display: inline-block !important; cursor: pointer !important;}
    .datepicker>div{display:block;}
    .datepicker--button {display:inline-block; width:50%; margin:auto; text-align:center; line-height:32px;}
    .gd1 {background-color:#fffff0;}
    .dp-note {background:blue; width: 4px; height: 4px; border-radius: 50%; left: 50%; bottom: 1px; -webkit-transform: translateX(-50%); transform: translateX(-50%); position:absolute;}
    .-selected- .dp-note {bottom: 2px; background: #fff; opacity: .5;}
    #wrap-options { padding:15px; display:inline-block; width: 100%; display: none}
    .col-md-6 .auto_price::after {content: " / ";}
    .col-md-6 .auto_price:last-child::after {content: "";}
    #add_options {display:inline-block; float: left; margin-right: 10px;}
    #wrap-auto-price {padding-left: 10px}
    .wrap-cpt-group .form-group { margin-bottom: 0}
    .wrap-cpt-group { display: none;}
    .wrap-actions span {display:inline-block; cursor: pointer; padding-left:5px}
    .wrap-actions .span-add_cpt{ color: #16A3E4}
    .wrap-actions .span-edit_cpt{ color: #16A3E4}
    .wrap-actions .span-remove_cpt{ color: #000}
    #cancel_btn {display: none;}
    .entry_info {border:1px solid #ccc; padding: 10px 0; border-radius: 3px}
    .save_btn{ padding: 4px 10px;}
    #cpt_detail_option_modal .modal-header { padding-top: 7px}
    #cpt_detail_option_modal .modal-header .close {right: 15px; top: 10%;}
    .cpt-group-detail { cursor: pointer; color: #cdcdcd}
    /* Custom styled notice CSS */
    .ui-pnotify-container {
        background-color: #fff !important;
        background-image: none !important;
        border: 1px solid #ccc !important;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        border-radius: 10px;
    }
    .tooltip_span::before{content: "* "}
    .help-block { display:block}
    ');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/cptour.js', ['depends'=>'yii\web\JqueryAsset']);
$status_pre_booking = ['not pre booking' => 'not pre booking', 'pre booking' => 'pre booking'];
$options_data = [];
?>
<div class="" id="cp_tour">
    <?php
    if (Yii::$app->session->getAllFlashes()) {
        $errors = [];
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                // var_dump($message);die();
            if ($key == 'success') {

                $success = '<div class="alert alert-success no-border">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">' . $message . '!</span></div>';
            } else {
                $errors[] = '<div class="alert alert-danger no-border">
                <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
                <span class="sr-only">Close</span>
                </button>
                <span class="text-semibold">' . $message . '</span>
                </div>';
            }
        }
        if (count($errors) > 0) {
            foreach ($errors as $v) {
                echo $v;
            }
        } else {
            echo $success;
        }

    }
    ?>
    <!-- list cpt -->
    <div class="" id="cp_table">
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                <li class="active"><a href="#justified-badges-tab1" data-toggle="tab"><i class="fa fa-home"></i> Hotel</a></li>
                <!-- <li><a href="#justified-badges-tab2" data-toggle="tab">Inactive <span class="badge bg-slate position-right">23</span></a></li> -->
                <!-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-info">34</span> <span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#justified-badges-tab3" data-toggle="tab">Dropdown tab</a></li>
                        <li><a href="#justified-badges-tab4" data-toggle="tab">Another tab</a></li>
                    </ul> -->
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="justified-badges-tab1">
                    <div class="col-md-12 row">
                        <div class="data-result table-responsive">
                            <table class="table table-framed table-xxs table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th width="100">Date</th>
                                        <th>Service</th>
                                        <th>Quality</th>
                                        <th>No days</th>
                                        <th>Price</th>
                                        <th>Group</th>
                                        <th>Actions</th>
                                        <!-- <th>Currency</th>
                                        <th>Book off</th>
                                        <th>Pay off</th>
                                        <th>Pay date</th>
                                        <th>Pay person</th> -->
                                    </tr>
                                </thead>
                                <tbody id="body-list-cpt">
                                    <?php foreach ($cpts as $cpt): ?>
                                        <tr class="tr-services" data-cpt_id="<?= $cpt->id?>">
                                            <td><?= date('Y/m/d')?> <!-- <span class="cp-comment"><i class="fa fa-comment-o"></i></span> --></td>
                                            <td><div class="cpt-name-wrap">
                                                <a class="venue_update"><span class="cpt-name"><?= $cpt->dv->name?></span> - <?= $cpt->venue->name?></a>
                                            </div></td>
                                            <td><?= $cpt->qty?></td>
                                            <td><?= $cpt->num_day?></td>
                                            <td><?= $cpt->price?> <span class="text-muted"><?= $cpt->currency?></span></td>
                                            <td class="group_id" data-group_id="<?= $cpt->group_id?>">
                                                <span class="_title cpt-group-detail">
                                                    <?php if ($cpt->group_id != 0): ?>
                                                        <i class="fa fa-asterisk" aria-hidden="true"></i>
                                                    <?php endif ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="wrap-actions">
                                                    <span class="span-add_cpt"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                    <span class="span-edit_cpt" data-cpt-id="<?= $cpt->id?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                                    <span class="span-remove_cpt" data-cpt-id="<?= $cpt->id?>"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="justified-badges-tab2">
                    Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid laeggin.
                </div>

                <div class="tab-pane" id="justified-badges-tab3">
                    DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
                </div>

                <div class="tab-pane" id="justified-badges-tab4">
                    Aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthet.
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 cp_form" id="wrap-cptForm">
        <div class="row entry_info">
            <div class="cp-tour-form col-md-12">

                <?php $form = ActiveForm::begin([
                    'id' => 'cptourForm'
                ]); ?>
                <input id="cptour-id" class="form-control" name="CpTour[id]" type="hidden" value="">
                <div class="form-group col-md-6">
                    <label>Day Use</label>
                    <input type='text' class="form-control datepicker-here" id="selme" data-language="en" data-position="bottom left"  placeholder="Date select"readonly/>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'who_pay')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="clearfix"></div>
                <div class="col-md-6 wrap-ncc">
                    <?= $form->field($model, 'venue_id')->dropDownList([]) ?>
                </div>
                <div class="col-md-6 " id="wrap-dv">
                    <? /*$form->field($model, 'dv_id')->widget(Select2::classname(), [
                    // 'language' => 'de',
                        'options' => ['placeholder' => 'Select', 'required' => 'required']
                    ]);*/ ?>
                    <?= $form->field($model, 'dv_id')->dropDownList([]) ?>
                </div>
                <div id="option_service">
                    <div class="col-md-12"><a title="" id="add_options" >+ Options</a>
                        <div id="group-option">
                            <label class="pull-left">
                                <div class="checker"><span class=""><input class="styled" type="checkbox"></span></div>
                                Group
                            </label>
                            <div class="clearfix"></div>
                            <div class="wrap-cpt-group">
                                <div class="form-group">
                                    <select name="cpt-group" class="form-control" id="cpt-group">
                                        <option value=""></option>
                                        <?php foreach ($cpts_group as $cpt): ?>
                                            <option value="<?= $cpt->id?>"><?= $cpt->dv->name. ' of ' .$cpt->venue->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div id="wrap-auto-price"></div> -->
                    </div>
                    <div id="wrap-options"></div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-2">
                    <?= $form->field($model, 'qty')->textInput() ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'num_day')->textInput() ?>
                </div>

                <?php if (isset($days)): ?>
                    <div class="col-md-3">
                        <?= $form->field($model, 'use_day')->dropDownList($days) ?>
                    </div>
                <?php endif ?>

                <div class="col-md-3">
                    <?= $form->field($model, 'price')->textInput(['maxlength' => true, 'class' => 'text-right form-control numberOnly']) ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'currency')->dropDownList(['VND' => 'VND', 'USD' => 'USD']) ?>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-3">
                    <?= $form->field($model, 'payment_dt')->textInput([
                        'class' => 'form-control datepicker-here',
                        'data-language' => 'en',
                        'data-position' => 'bottom left',
                        'placeholder' => 'Date select',
                        'readonly' =>true
                    ]) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'book_of')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'pay_of')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'status_book')->dropDownList($status_pre_booking)  ?>
                </div>

                <div class="clearfix"></div>
                <div class="col-md-12" >
                    <div class="form-group">
                        <label>Note</label>
                        <?= Html::textarea( 'note_dv', '', ['class'=>'form-control']);?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary save_btn']) ?>

                        <span class="btn btn-default" id="cancel_btn">Cancel</span>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="cpt_comment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel">New message</h4>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="message-text" class="form-control-label">Message:</label>
              <textarea class="form-control" id="message-text"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save change</button>
        </div>
      </div>
    </div>
</div> -->
<div class="modal fade" id="cpt_detail_option_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Detail Option</h4>
    </div>
    <form id="optionForm">
        <input type="hidden" name="option-dv_id" value="">
        <input type="hidden" name="option-cpt_op_id" value="">
        <div class="modal-body">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="message-text" class="form-control-label">Quantity:</label>
                    <input type="text" name="option-qty" class="form-control" value="" placeholder="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="message-text" class="form-control-label">Price:</label>
                    <input type="text" name="option-price" class="form-control numberOnly"  value="" placeholder="">
                    <span id="wrap-op-auto-price"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="message-text" class="form-control-label">Currency:</label>
                    <select id="option-unit" class="form-control" name="option-currency" aria-required="true" aria-invalid="false">
                        <option value="VND">VND</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="message-text" class="form-control-label">Note:</label>
                    <textarea name="option-note" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="option-save">Save change</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </form>
</div>
</div>
</div>
<?php

?>