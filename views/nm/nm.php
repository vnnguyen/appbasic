<?php

include('_nm_inc.php');

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use kartik\select2\Select2;
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

    #list_cpt_nm_modal .modal-dialog{ max-height: 650px; width: 900px ; overflow-y: auto;}
    .modal-body{
        max-height: 650px;
        overflow-y: auto;
    }
    .top-action {
        background: #ccc none repeat scroll 0 0;
        display: flex;
        top: 50px;
        z-index: 9999;
        padding-right: 10px;
        margin-bottom: 10px;
    }
    #copy, #delete, #paste { display: none;}
    #wrap-select-all { margin-right:15px}
    .select2-container{ width: 100% !important; }
    #search { margin-bottom: 10px}
    #save_paste { display: none; }
    #cpt_nm_modal .modal-dialog{ margin-top: 8.5%}
');
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery.autocomplete.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/nm.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
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
    <!--modal-->
    <div class="modal fade" id="list_cpt_nm_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog dialog-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="exampleModalLabel">CPT</h4>
            </div>
            <div class="modal-body">
                <div class="row" id="search">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control search_txt" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-warning" id="search_btn" type="button">Go!</button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-default" id="reset_btn" type="button">reset</button>
                            </span>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 -->
                </div>
                <div class="top-action">
                        <span id="copy" class="btn btn-default"><i class="fa fa-files-o" aria-hidden="true"></i></span>
                        <span id="delete" class="btn btn-default"><i class="fa fa-trash" aria-hidden="true"></i></span>
                        <span id="paste" class="btn btn-default">Paste</span>
                        <span id="add_new_cpt" class="btn btn-primary"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Add</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <!-- <caption>table title and/or explanatory text</caption> -->
                        <thead>
                            <tr>
                                <th width="30px"><?= Html::checkbox('chkAll', false, ['id' => 'select-all', ])?></th>
                                <th><?= Yii::t('app', 'Name') ?></th>
                                <th><?= Yii::t('app', 'Ncc') ?></th>
                                <th><?= Yii::t('app', 'Note') ?></th>
                                <th width="30px"><?= Yii::t('app', 'Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="body-cpts">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_paste">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>
    <!--end modal-->
    <!--modal-->
    <div class="modal fade" id="cpt_nm_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog dialog-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="exampleModalLabel">CPT Form</h4>
            </div>
            <div class="modal-body">
                <form id="cptForm" method="post" accept-charset="utf-8">
                    <?= Html::input('hidden', 'day_id', '',['class' => 'day_id']) ?>
                    <div class="row wrap_cpt_nm">
                        <?= Html::input('hidden', 'cpt_id', '',['class' => 'cpt_id']) ?>
                        <div class="col-md-4 wrap-ncc">
                            <?= Html::input('text', 'ncc', '',['class' => 'form-control ncc', 'placeholder' => 'Ncc']) ?>
                        </div>
                        <div class="col-md-8 form-group">
                            <?= Html::dropdownList('dv_id', '', [],['class' => 'form-control dv_id', 'placeholder' => 'Service']) ?>
                        </div>
                        <div class="col-md-12 wrap-ncc">
                            <?= Html::textarea('note', '', ['class' => 'form-control note', 'placeholder' => 'note']) ?>
                        </div>
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
    <!--end modal-->
</div>
<style>
.popover {max-width:500px;}
</style>
<?

$js = <<<TXT
//new Clipboard('.clipboard-copy');

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

TXT;

$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.15/clipboard.min.js', ['depends'=>'yii\web\JqueryAsset']);
}// not Huan