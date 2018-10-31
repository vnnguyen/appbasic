<?php

include('_nm_inc.php');

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
$baseUrl = Yii::$app->request->baseUrl;
$this->registerCss('
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
    .select2-search__field { width: 100% !important; }
    .actions a{margin-left: 10px;}
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body {background: #f3f3f3;}

    #cpt_nm_modal .modal-dialog{ height: 650px; width: 700px ; overflow-y: auto;}
    .modal-body{
        height: 650px;
        overflow-y: auto;
    }
    .row.top-action {
        background: #ccc none repeat scroll 0 0;
        display: block;
        position: fixed;
        top: 50px;
        z-index: 9999;
        padding-right: 10px
    }
    .wrap_cpt_nm {
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 30px;
    }
    #copy, #delete, #paste { display: none;}
    #wrap-select-all { margin-right:15px}
');
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery.autocomplete.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
if (!1444
    //USER_ID == 1444
    ) {

$dataProvider = new ActiveDataProvider([
    'query' => \app\models\Nm::find()->where(['owner'=>'at']),
    'pagination' => [
        'pageSize' => 20,
    ],
]);

Pjax::begin([
    // PJax options
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        // [	'class' => 'yii\grid\SerialColumn'],
        // Simple columns defined by the data contained in $dataProvider.
        // Data from the model's column will be used.
        'title',
        'tags',
        // More complex one.
        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value' => function ($data) {
                return $data->created_dt; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
        ],
    ],
]);
Pjax::end();
} else {


$sessAction = Yii::$app->session->get('action', '');
$sessTo = Yii::$app->session->get('to', 0);
$sessAt = Yii::$app->session->get('at', 0);

Yii::$app->params['page_title'] = 'Sample days ('.$pagination->totalCount.')';

?>

<div class="col-md-12">
    <? if (1444
    //$sessAction == 'prepare-add-day' && $sessTo != 0
    ) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Click the <i class="fa fa-plus text-pink"></i> icon to add a day to <a href="/products/r/<?= $sessTo ?>">your tour program</a>. (<?= Html::a('Cancel', '/nm?action=cancel-add-day')?>)
    </div>
    <? } ?>

    <? if (1444
    // $sessAction == 'prepare-add-day-sample' && $sessTo != 0
    ) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Click the <i class="fa fa-plus text-pink"></i> icon to add a day to <a href="/tm/r/<?= $sessTo ?>">your sample program</a>. (<?= Html::a('Cancel', '/nm?action=cancel-add-day-sample')?>)
    </div>
    <? } ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('language', $language, $languageList, ['class'=>'form-control']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
                <?= Html::textInput('tags', $tags, ['class'=>'form-control', 'placeholder'=>'Search tags']) ?>
                <?= Html::dropdownList('show', $show, ['all'=>'B2C / all tags', '2015'=>'B2C / "2015" only', 'b2b'=>'B2B only'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('updatedby', $updatedby, ArrayHelper::map($updatedByList, 'id', 'name'), ['prompt'=>'Updated by', 'class'=>'form-control']) ?>
                <?= Html::dropdownList('orderby', $orderby, ['name'=>'Order by name', 'updated'=>'Order by update'], ['class'=>'form-control']) ?>
                <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), DIR.URI) ?>
            </form>
        </div>
        <?
            if (Yii::$app->session->getAllFlashes()) {
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    if ($key == 'success') {
                        echo '<div class="alert alert-success no-border">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">' . $message . '!</span></div>';
                    } else {
                        if ($key == 'error') {
                            echo '<div class="alert alert-danger no-border">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">' . $message . '</span></div>';
                        }
                    }
                }
            }
        ?>
        <div class="table-responsive">
            <table id="tbl-days" class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Name & Content</th>
                        <th>Meals</th>
                        <th>Tags</th>
                        <? if (1444
                        // ($show == 'b2b' && in_array(USER_ID, [1, 3, 26052, 29013])) // Jonathan, Alain
                        // || ($show != 'b2b' && in_array(USER_ID, $this->context->allowList)) // Hieu, Nguyen
                        ) { ?>
                        <th>Edit</th>
                        <? } ?>
                    </tr>
                </thead>
                <tbody>
                <? foreach ($theDays as $day) { ?>
                <div class="modal fade modal-primary" id="nm<?= $day['id'] ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h6 class="modal-title text-pink text-semibold"><?= $day['title'] ?> (<?= $day['meals'] ?>)</h6>
                            </div> 
                            <div class="modal-body">
                                <p><button class="btn btn-default clipboard" data-clipboard-target="#nmbody<?= $day['id'] ?>"><i class="fa fa-copy"></i> Copy to clipboard</button>
                                or <?= Html::a('View detail', '/appbasic/web/nm/r/'.$day['id']) ?>
                                </p>
                                <div id="nmbody<?= $day['id'] ?>">
                                    <?= $day['body'] ?>
                                </div>
                            </div>
                            <div class="modal-footer text-muted">
                                <i class="fa fa-clock-o"></i> <?= $day['updatedBy']['nickname'] ?> <?= Yii::$app->formatter->asRelativeTime($day['updated_dt']) ?>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <tr data-day="<?= $day['id'] ?>">
                    <td>
                        <? if ($sessAction == 'prepare-add-day-sample') { ?>
                        <a title="Add day to program" href="/tm/r/<?= $sessTo ?>?action=add-day-sample&at=<?= $sessAt ?>&add=<?= $day['id'] ?>" class="text-pink"><i class="fa fa-plus text-pink"></i></a>
                        <? } elseif ($sessAction == 'prepare-add-day') { ?>
                        <a title="Add day to program" href="/ct/rr/<?= $sessTo ?>?action=day-add-nm-after&id=<?= $sessAt ?>&nm=<?= $day['id'] ?>" class="text-pink"><i class="fa fa-plus text-pink"></i></a>
                        <? } else { ?>
                            <? if (1444
                            //n_array(USER_ID, [1, 28722]) && $show != 'b2b'
                            ) { ?>
                        <i data-day="<?= $day['id'] ?>" class="nouse cursor-pointer fa fa-ban text-danger" title="NoUse"></i>
                            <? } ?>
                        <? } ?>
                        <a data-href="/nm/r/<?= $day['id'] ?>"
                            class="popovers"
                            data-placement="right"
                            data-trigger="hover"
                            data-html="true"
                            data-title="<?= Html::encode($day['title']) ?> (<?= $day['meals'] ?>)"
                            data-content="<?= Html::encode($day['body']) ?>"
                            data-toggle="modal" data-target="#nm<?= $day['id'] ?>"
                            ><?= $day['title'] ?></a>
                    </td>
                    <td><?=$day['meals'] ?></td>
                    <td><?
                    $tags = explode(',', $day['tags']);
                    $tagList = [];
                    foreach ($tags as $tag) {
                        $tagList[] = trim($tag);
                    }
                    sort($tagList);
                    $tagStrList = [];
                    foreach ($tagList as $tag) {
                        if ($tag == '2015' || $tag == '2016') {
                            $class = 'text-warning';
                        } else {
                            $class = '';
                        }
                        $tagStrList[] = Html::a(trim($tag), DIR.URI.'?tags='.urlencode(trim($tag)), ['class'=>$class]);
                    }
                    echo implode(', ', $tagStrList);
                    ?></td>
                    <? if (1444
                        // ($day['owner'] == 'si' && in_array(USER_ID, [1, 3, 26052, 29013])) // Jonathan, Alain
                        // || ($day['owner'] == 'at' && in_array(USER_ID, $this->context->allowList)) // Hieu, Nguyen
                        ) { ?>
                    <td class="text-nowrap">
                        <?= Html::a('<i class="fa fa-edit"></i>', '/appbasic/web/nm/u/'.$day['id']) ?>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', '/nm/d/'.$day['id'], ['class'=>'text-danger']) ?>
                        <?= Html::a('CPT', '', ['class'=>'text-primary add_cpt',
                                                'data-id' => $day['id'],
                                                'data-title' => $day['title'],
                                                'data-cps' =>$day['cpt_nm_ids']]) ?>
                    </td>
                    <? } ?>
                </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
        <? if ($pagination->totalCount > $pagination->pageSize) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget(array(
            'pagination' => $pagination,
            'prevPageLabel'=>'<',
            'nextPageLabel'=>'>',
            'firstPageLabel'=>'<<',
            'lastPageLabel'=>'>>',
        ));?>
        </div>
        <? } ?>
    </div>
    <div class="modal fade" id="cpt_nm_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog dialog-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="exampleModalLabel">CPT</h4>
            </div>
            <div class="modal-body">
                <form id="cptForm" action="/appbasic/web/nm/cpt_nm" method="post" accept-charset="utf-8">
                    <?= Html::input('hidden', 'day_id', '',['class' => 'day_id']) ?>
                    <div class="row top-action">
                        <span class="btn btn-default" id="wrap-select-all"><?= Html::checkbox('chkAll', false, ['id' => 'select-all', ])?></span>
                        <span id="copy" class="btn btn-default"><i class="fa fa-files-o" aria-hidden="true"></i></span>
                        <span id="delete" class="btn btn-default"><i class="fa fa-trash" aria-hidden="true"></i></span>
                        <span id="paste" class="btn btn-default">Paste</span>
                    </div>
                    <div class="row wrap_cpt_nm">
                        <div class="col-md-12">
                            <?= Html::checkbox('chk', false, ['data-id' => '', 'class' => 'chk'])?>
                        </div>
                        <?= Html::input('hidden', 'cpt_id[]', '',['class' => 'cpt_id']) ?>
                        <div class="col-md-4 wrap-ncc">
                            <?= Html::input('text', 'ncc[]', '',['class' => 'form-control ncc', 'placeholder' => 'Ncc']) ?>
                        </div>
                        <div class="col-md-8 form-group">
                            <?= Html::input('text', 'dv[]', '',['class' => 'form-control dv_id', 'placeholder' => 'Service']) ?>
                            <?= Html::input('hidden', 'dv_id[]', '',['class' => 'dv_ids']) ?>
                        </div>
                        <div class="col-md-12 wrap-ncc">
                            <?= Html::textarea('note[]', '', ['class' => 'form-control note', 'placeholder' => 'note']) ?>
                        </div>
                        <p class="actions">
                            <a class="add_cpt_new"> + Add</a>
                            <a class="delete_cpt"> - Delete</a>
                        </p>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary">Save change</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
          </div>
        </div>
    </div>
</div>
<style>
.popover {max-width:500px;}
</style>
<?

$js = <<<TXT
//new Clipboard('.clipboard-copy');
var cpt_mau;
var ids_selected = '';
var ids_copy = '';
var count_check = 0;
$('.popovers').popover();

$('i.nouse').on('click', function(){
    var day = $(this).data('day');
    $.ajax({
        method: "POST",
        url: "/sample-tour-days?xh",
        data: { action: "nouse", day: day }
    })
    .done(function() {
        $('tr[data-day="'+day+'"]').fadeOut(200);
    })
    .fail(function() {
        alert( "Error adding NoUse tag " );
    });
});
new Clipboard('.clipboard');
$('.add_cpt').on('click', function(){
    $('.wrap_cpt_nm').each(function(index, item){
        if ($('.wrap_cpt_nm').length > 1) {
            $(item).remove();
        }
    });
    var day_id = $(this).data('id');
    var day_title = $(this).data('title');
    var cpt_nm_ids = $(this).data('cps');
    $('#cpt_nm_modal').find('[name="day_id"]').val(day_id);
    $('#cpt_nm_modal').find('.modal-title').text(day_title);
    var wrap_cpt = $('#cpt_nm_modal').find('.wrap_cpt_nm:first');
    $('#select-all').prop('checked', false);
    $('#copy, #delete').hide();
    if (cpt_nm_ids != '') {
        $.ajax({
            method: "GET",
            url: "/appbasic/web/nm/list_cpt",
            data: { arr: cpt_nm_ids },
            dataType: 'json'
        })
        .done(function(result) {
            if (result != 0) {
                jQuery.each(result, function(index, item){
                    var cpt_nm = wrap_cpt.clone();
                    $(cpt_nm).find('[name="chk"]').prop('checked', false);
                    $(cpt_nm).find('[name="chk"]').data('id', item.id);
                    $(cpt_nm).find('.cpt_id').val(item.id);
                    $(cpt_nm).find('.ncc').val(item.venue.name);
                    $(cpt_nm).find('.dv_id').val(item.dv.name);
                    $(cpt_nm).find('.dv_ids').val(item.dv_id);
                    $(cpt_nm).find('.note').val(item.note);
                    $(cpt_nm).insertBefore(wrap_cpt);
                });
            } else {
                return;
            }
        })
        .fail(function() {
            alert( "Error adding NoUse tag " );
        });
    }
    if (ids_copy != '') {
        $('#paste').show();
    } else { $('#paste').hide(); }
    $('#cpt_nm_modal').modal('show');
    return false;
});
$(document).on('focus', '.ncc', function(){
    var txt_ncc = $(this);
    $(this).devbridgeAutocomplete({
        serviceUrl: '/appbasic/web/cpt/list_ncc',
        lookupFilter: function (suggestion, query, queryLowerCase) {
            console.log(1);
        },
        onSelect: function (suggestion) {
            console.log(suggestion.data);
            $(txt_ncc).val(suggestion.value);
            $(this).closest('.wrap_cpt_nm').find('.dv_id').data('ncc-id',suggestion.data);
            //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
});
$(document).on('focus', '.dv_id', function(){
    var txt_dv = $(this);
    var venue_id = $(this).data('ncc-id');
    $(this).devbridgeAutocomplete({
        serviceUrl: '/appbasic/web/cpt/list_dv?vid='+venue_id,
        lookupFilter: function (suggestion, query, queryLowerCase) {
            console.log(1);
        },
        onSelect: function (suggestion) {
            console.log(suggestion.data);
            $(txt_dv).val(suggestion.value);

            $(txt_dv).next().val(suggestion.data);
            //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
});


$(document).on('click', '.add_cpt_new', function(){
    var cpt_nm = $(this).closest('.wrap_cpt_nm').clone();
    $(cpt_nm).find('.cpt_id').val('');
    $(cpt_nm).find('.dv_ids').val('');
    $(cpt_nm).find('.dv_id').val('');
    $(cpt_nm).find('.ncc').val('');
    $(cpt_nm).find('.note').val('');
    $(cpt_nm).find('[name="chk"]').data('id','');
    $(cpt_nm).insertAfter($(this).closest('.wrap_cpt_nm'));
});
$(document).on('click', '.delete_cpt', function(){
    if ($('.delete_cpt').length > 1) {
        var cfirm = confirm('ban muon xoa');
        if (cfirm) {
            var cpt_nm = $(this).closest('.wrap_cpt_nm');
            $(cpt_nm).slideUp(500, function(){
                if ($(cpt_nm).remove()) {

                    var ids = $(this).closest('.wrap_cpt_nm').find('.cpt_id').val();
                    if (ids == '') {
                        return;
                    }
                    var day_id = $('#cptForm').find('.day_id').val()
                    $.ajax({
                        method: "GET",
                        url: "/appbasic/web/nm/delete_cpt",
                        data: { ids: ids, day_id: day_id},
                        dataType: 'json'
                    })
                    .done(function(result) {

                        if (result != 1) {
                            alert(result.error);
                        }
                    })
                    .fail(function() {
                        alert( "Error adding NoUse tag " );
                    });
                }
            });
        }
    }
});

$('#select-all').on('click', function(){
    var cnt = 0;
   if ($(this).prop('checked')) {
        $('#copy, #delete').show();
        $('.wrap_cpt_nm').each(function(index, item){
            if ($(item).find('[name="chk"]').data('id') != '') {
                $(item).find('[name="chk"]').prop('checked', true);
                cnt++;
                ids_selected += $(item).find('[name="chk"]').data('id') + ', ';
            }
        });
   } else {
        $('#copy, #delete').hide();
        $('.wrap_cpt_nm').each(function(index, item){
            $(item).find('[name="chk"]').prop('checked', false);
        });
        cnt = 0;
        ids_selected = '';
   }
   count_check = cnt;
});
$(document).on('click', '.chk', function(){
    if ($(this).prop('checked')) {
        $('#copy, #delete').show();
        count_check ++;
   } else {
        count_check --;
        if (count_check == 0) {
            $('#copy, #delete').hide();
        }
   }
   ids_selected = '';
   $('.wrap_cpt_nm').each(function(index, item){
        if ($(item).find('.chk').prop('checked')) {
            ids_selected += $(item).find('[name="chk"]').data('id') + ', ';
       }
    });
});
$('#delete').on('click', function(){
    var cfirm = confirm('ban muon xoa');
    if (cfirm) {
        if (ids_selected == '') {
            return;
        }
        var day_id = $('#cptForm').find('.day_id').val();
        $.ajax({
            method: "GET",
            url: "/appbasic/web/nm/delete_cpt",
            data: { ids: ids_selected, day_id: day_id},
            dataType: 'json'
        })
        .done(function(result) {

            if (result != 1) {
                alert(result.error);
            }
        })
        .fail(function() {
            alert( "Error" );
        });
        $('.wrap_cpt_nm').each(function(index, item){
            if ($(item).find('.chk').prop('checked')) {
                if ( $(item).find('[name="chk"]').data('id') != '' ) {
                    $(item).slideUp(500, function(){
                        $(item).remove();
                    });
                }
            }
        });
        $('#select-all').prop('checked', false);
    }
});
$('#copy').on('click', function(){
    if (ids_selected != '') {
        ids_copy = ids_selected;
        new PNotify({
            title: 'Copy info',
            text: 'Copy ok!',
            delay:2500,
            buttons: {
                closer: false,
                sticker: false
            },
        });
    }
});

$('#paste').on('click', function(){
    var wrap_cpt = $('#cpt_nm_modal').find('.wrap_cpt_nm:first');
    if (ids_copy != '') {
        $.ajax({
            method: "GET",
            url: "/appbasic/web/nm/list_cpt",
            data: { arr: ids_copy },
            dataType: 'json'
        })
        .done(function(result) {
            if (result != 0) {
                jQuery.each(result, function(index, item){
                    var cpt_nm = wrap_cpt.clone();
                    $(cpt_nm).find('[name="chk"]').prop('checked', false);
                    $(cpt_nm).find('[name="chk"]').data('id', '');
                    $(cpt_nm).find('.cpt_id').val('');
                    $(cpt_nm).find('.ncc').val(item.venue.name);
                    $(cpt_nm).find('.dv_id').val(item.dv.name);
                    $(cpt_nm).find('.dv_ids').val(item.dv_id);
                    $(cpt_nm).find('.note').val(item.note);
                    $(cpt_nm).insertBefore(wrap_cpt);
                });
            } else {
                return;
            }
        })
        .fail(function() {
            alert( "Error adding NoUse tag " );
        });
    }
    if (ids_copy != '') {
        $('#paste').show();
    } else { $('#paste').hide(); }
    return false;
});

TXT;
$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.15/clipboard.min.js', ['depends'=>'yii\web\JqueryAsset']);
}// not Huan