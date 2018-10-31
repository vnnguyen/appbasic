<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
// var_dump($dataProvider); die();
$baseUrl = Yii::$app->request->baseUrl;

$this->registerCss('
    .fa-remove, .fa-edit, .fa-copy { font-size: 16px;}
    .icons-list > li { padding-right: 10px;}
    .btn-copy {cursor: pointer}
    .modal-dialog{ max-width: 900px !important}
    .content-main { padding-top: 10px}
    .panel-body {padding-bottom: 0px; border-bottom: 1px solid #ccc; margin-bottom: 4px}
');
// $this->registerJsFile($baseUrl.'/js/maintour.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<<'TXT'
    var tr_clicked;
    $('#addNewWay').on('click', function(){
        $('#editForm').find('[name="action"]').val('create');
        $('#editForm').formValidation('resetForm', true);
        $('#editForm').find('[name="note"]').val('');
        $('#edit-modal').modal('show');
        return false;
    });
    $('.deleteWay').on('click', function(){
        var id = $(this).data('id');
        tr_clicked = $(this).closest('tr');
        new PNotify({
                    title: 'Confirm',
                    text: 'Delete this item ?\n <div class="text-right"><a class="btn btn-primary confirm_ok" style="padding: 4px 7px" data-id="' +id+ '">Yes</a> <a class="btn btn-default confirm_cancel" style="padding: 4px 7px">Cancel</a></div>',
                    delay:3500,
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                });
        return false;
    });
    $(document).on('click', '.confirm_ok', function(){
        var id = $(this).data('id');
        tr_clicked.fadeIn(800, function(){
            $(this).remove();
            $.ajax({
              url: "/appbasic/web/way/delete_way",
              method: "GET",
              data: { id : id }
            })
            .done(function( msg ) {
              if (msg == 1) {
                  new PNotify({
                        type: 'success',
                        title: 'Notice',
                        text: 'Delete completed!',
                        delay:2500,
                        buttons: {
                            closer: false,
                            sticker: false
                        },
                    });
              }
            })
            .fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
        });
        PNotify.removeAll();
    });
    $(document).on('click', '.confirm_cancel', function(){
        PNotify.removeAll();
    });
    $('.editWay').on('click', function(){
        var id = $(this).data('id');
        $('#editForm').find('[name="action"]').val(id);
        $.ajax({
          url: "/appbasic/web/way/get_way",
          method: "GET",
          data: { id : id },
          dataType: "json"
        })
        .done(function( msg ) {
          console.log( msg );
          $('#editForm').find('[name="name"]').val(msg.name);
          $('#editForm').find('[name="acro"]').val(msg.acro);
          $('#editForm').find('[name="sl"]').val(msg.sl);
          $('#editForm').find('[name="unit"]').val(msg.unit);
          $('#editForm').find('[name="status"]').val(msg.status);
          $('#editForm').find('[name="note"]').val(msg.note);
          $('#edit-modal').modal('show');
        })
        .fail(function( jqXHR, textStatus ) {
          alert( "Request failed: " + textStatus );
        });
        return false;
    });
    $('#editForm').formValidation({
        framework: 'bootstrap',
        icon: false,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required'
                    }
                }
            },
            'acro': {
                validators: {
                    notEmpty: {
                        message: 'The acronym is required'
                    }
                }
            },
            'sl': {
                validators: {
                    notEmpty: {
                        message: 'The quantity is required'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The quantity must is number'
                    }
                }
            },
            unit: {
                validators: {
                    notEmpty: {
                        message: 'The unit is required'
                    }
                }
            },
            status: {
                validators: {
                    notEmpty: {
                        message: 'The status is required'
                    }
                }
            }
        }
    });

TXT;
$this->registerJs($js);



$baseUrl = Yii::$app->request->baseUrl;
$this->title = Yii::t('app', 'Way');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="get" accept-charset="utf-8">
            <div class="col-md-2 form-group">
                <?= Html::input('text', 'name', $name, ['class' => 'form-control', 'placeholder' => "Search name or acro"]) ?>
            </div>
            <div class="col-md-2 form-group">
                <?= Html::dropDownList('status', '', ['on' => 'On', 'off' => 'Off', 'draft' => 'Draft'],[
                    'class' => 'form-control'
                ]) ?>
            </div>
            
            <button class="btn btn-primary" type="submit">Go</button>
            <a href="/appbasic/web/way">Reset</a>
        </form>
    </div>
        <p class="text-right" >
            <a id="addNewWay" class="btn btn-success " style="padding: 0px 10px; margin-right: 15px"><i class="fa fa-plus" aria-hidden="true"></i></a>
        </p>
    <div class="clearfix"></div>
        <?php
            foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                if ($key == 'created') {
                    echo '<div class="alert alert-success">' . $message . '</div>';
                }
                if ($key == 'updated') {
                    echo '<div class="alert alert-primary">' . $message . '</div>';
                }
            }
        ?>
        <div class="table-responsive data_table" style="border-top: 1px solid #ccc">
            <table class="table table-lg">
                <thead>
                    <tr>
                        <th><?= Yii::t('app', 'Name'); ?></th>
                        <th><?= Yii::t('app', 'Acro'); ?></th>
                        <th><?= Yii::t('app', 'Unit'); ?></th>
                        <th><?= Yii::t('app', 'Status'); ?></th>
                        <th><?= Yii::t('app', 'Action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataProvider as $key => $way) { ?>
                    <tr>
                        <td><?= $way->name  ?></td>
                        <td><?= $way->acro  ?></td>
                        <td><?= $way->sl.' '.$way->unit  ?></td>
                        <td><?= $way->status  ?></td>
                        <td><?= Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
'/*, ['user/view', 'id' => $id], ['class' => 'profile-link']*/,'#'.$way->id, ['class' => 'editWay', 'data-id' => $way->id]) ?> <?= Html::a('<i class="fa fa-trash" aria-hidden="true"></i>
'/*, ['user/view', 'id' => $id], ['class' => 'profile-link']*/,'#'.$way->id, ['class' => 'deleteWay', 'data-id' => $way->id]) ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="pages">
                <?= yii\widgets\LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                ?>
            </div>
        </div>
    </div>
    <!-- edit Modal -->
        <div class="modal fade" id="edit-modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Way info</h4>
                    </div>
                    <?php $form = ActiveForm::begin([
                        'id' => 'editForm',
                        ]) ?>
                    <div class="modal-body">
                        <?= Html::input('hidden', 'action', 'create') ?>
                        <div class="col-md-12 content-main">
                            <div class="form-group">
                                <?= Html::input('text', 'name', '', ['class' => 'form-control', 'placeholder' => "Name"]) ?>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                <?= Html::input('text', 'acro', '', ['class' => 'form-control', 'placeholder' => "Acronym"]) ?>
                                </div>
                                <div class="col-md-4 form-group">
                                <?= Html::input('text', 'sl', '', ['class' => 'form-control', 'placeholder' => "Quantity"]) ?>
                                </div>
                                <div class="col-md-4 form-group">
                                <?= Html::input('text', 'unit', '', ['class' => 'form-control', 'placeholder' => "Unit"]) ?>
                                </div>
                                <div class="col-md-4 form-group">
                                <?= Html::dropDownList('status', '', ['' => 'Status', 'on' => 'On', 'off' => 'Off', 'draft' => 'Draft'],[
                                    'class' => 'form-control'
                                ]) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= Html::textarea('note', '', ['class' => 'form-control', 'placeholder' => "Note"]) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Save" id="btnSave" name="save">
                        <button type="submit" class="btn btn-default" data-dismiss="modal" id="btn-close-modal">Close</button>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    <!--end edit Modal -->
</div><!-- end panel -->
