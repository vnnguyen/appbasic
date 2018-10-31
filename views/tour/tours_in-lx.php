<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

// require_once('_tours_inc.php');

$this->title = 'In lịch xe cho tour: '.$theTour['op_code'];

$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
    ['In lịch xe'],
];

$dayIdList = explode(',', $theTour['day_ids']);

$dviList = [
    'km'=>'Km',
    'db'=>'Ngày ĐB',
    'tb'=>'Ngày TB',
    'chang'=>'Chặng',
];

$vpList = [
    'hanoi'=>'VP Hà Nội',
    'saigon'=>'VP Sài Gòn',
];
$this->registerCss('
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
');
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery.autocomplete.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/lx.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<style>
.table-tight td, .table-tight th {padding:2px!important; vertical-align:top!important}
.table-tight input, .table-tight select, .table-tight textarea {padding:2px!important; border:1px solid #eee!important;}
.table-tight input:focus, .table-tight select:focus, .table-tight textarea:focus {border:1px solid #f33!important;}
</style>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Thông tin bản in</h6>
        </div>
        <div class="panel-body">
            <? $form = ActiveForm::begin(['id'=>'lxForm']); ?>
            <fieldset>
                <div class="row">
                    <div class="col-md-4"><?= $form->field($theForm, 'vp')->dropdownList($vpList)->label('In cho') ?></div>
                    <div class="col-md-4"><?= $form->field($theForm, 'dieuhanh')->dropdownList(ArrayHelper::map($theTourOld['operators'], 'name', 'name'))->label('Điều hành') ?></div>
                    <div class="col-md-4"><?= $form->field($theForm, 'huongdan')->dropdownList(ArrayHelper::map($theTour['guides'], 'guide_name', 'guide_name'), ['prompt'=>'( Không chọn )'])->label('Hướng dẫn viên') ?></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><?= $form->field($theForm, 'loaixe')->label('Loại xe') ?></div>
                    <div class="col-md-4"><?= $form->field($theForm, 'chuxe')->label('Chủ xe') ?></div>
                    <div class="col-md-4"><?= $form->field($theForm, 'laixe')->label('Lái xe') ?></div>
                </div>
                <p class="text-info">Click <i class="fa fa-plus"></i> để copy dòng xuống dưới (vd trường hợp một ngày có nhiều mức giá), click <i class="fa fa-trash-o text-danger"></i> để in/không in từng dòng.
                    <br>Trường hợp đơn vị tính là "chặng" thì ĐH tự nhập giá của chặng.
                </p>
                <table id="tblLichxe" class="table-tight table table-borderless table-xxs">
                    <thead>
                        <tr>
                            <th>TT</th>
                            <th width="60">Ngày</th>
                            <th>Nội dung</th>
                            <th width="60">SL</th>
                            <th width="100">Đ/vị</th>
                            <th width="100">Giá tiền</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
<?
if ($lx_old != null) {
    $lichxe = unserialize($lx_old['content']);
    $count = 0;
    for ($i=0; $i < count($lichxe['tt']); $i++) {
        $count++;
        // var_dump($lichxe);die();
    ?>
    <tr class="tr<?= $count ?>">
        <?= Html::hiddenInput('tt[]', $count) ?>
        <td class="text-muted text-right">
            <?= $count ?>
        </td>
        <td><?= Html::textInput('ngay[]', $lichxe['ngay'][$i], ['class'=>'form-control text-center']) ?></td>
        <td><?= Html::textInput('noidung[]', $lichxe['noidung'][$i], ['class'=>'form-control autocomplete']) ?></td>
        <td><?= Html::textInput('sl[]', $lichxe['sl'][$i], ['class'=>'form-control text-right']) ?></td>
        <td><?= Html::dropdownList('dvi[]', $lichxe['dvi'][$i], $dviList, ['class'=>'field_dvi form-control']) ?></td>
        <td><?= Html::textInput('gia[]', $lichxe['gia'][$i], ['class'=>'form-control text-right money', 'readonly'=>'readonly']) ?></td>
        <td class="text-nowrap">
            <i data-day="<?= $count ?>" title="Copy dòng" class="add cursor-pointer  fa fa-plus"></i>
            <i data-day="<?= $count ?>" title="In/Không in dòng" class="del cursor-pointer fa fa-trash-o text-danger"></i>
        </td>
    </tr>
    <?}
}else{
    $cnt = 0;
    foreach ($dayIdList as $id) {
        foreach ($theTour['days'] as $day) {
            if ($id == $day['id']) {
                $cnt ++;
                $date = date('j/n', strtotime('+'.($cnt - 1).' days', strtotime($theTour['day_from'])));
    ?>
                            <tr class="tr<?= $cnt ?>">
                                <?= Html::hiddenInput('tt[]', $cnt) ?>
                                <td class="text-muted text-right">
                                    <?= $cnt ?>
                                </td>
                                <td><?= Html::textInput('ngay[]', $date, ['class'=>'form-control text-center']) ?></td>
                                <td><?= Html::textInput('noidung[]', $day['name'], ['class'=>'form-control autocomplete']) ?></td>
                                <td><?= Html::textInput('sl[]', 1, ['class'=>'form-control text-right']) ?></td>
                                <td><?= Html::dropdownList('dvi[]', '', $dviList, ['class'=>'field_dvi form-control']) ?></td>
                                <td><?= Html::textInput('gia[]', '', ['class'=>'form-control text-right money', 'readonly'=>'readonly']) ?></td>
                                <td class="text-nowrap">
                                    <i data-day="<?= $cnt ?>" title="Copy dòng" class="add cursor-pointer  fa fa-plus"></i>
                                    <i data-day="<?= $cnt ?>" title="In/Không in dòng" class="del cursor-pointer fa fa-trash-o text-danger"></i>
                                </td>
                            </tr>
    <?
            }
        }
    }
}?>

                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <legend>Giá xe miền Bắc (VND)</legend>
                <p class="text-info">Giá này sẽ dùng trong trang in ở bước tiếp theo</p>
                <div class="row">
                    <div class="col-md-4"><?= $form->field($theForm, 'giakm')->label('Giá km') ?></div>
                    <div class="col-md-4"><?= $form->field($theForm, 'giadb')->label('Giá ngày Đông Bắc') ?></div>
                    <div class="col-md-4"><?= $form->field($theForm, 'giatb')->label('Giá ngày Tây Bắc') ?></div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Ghi chú</legend>
                <p class="text-info">Thông tin này sẽ được in kèm lịch xe</p>
                <?= $form->field($theForm, 'note')->textArea(['rows'=>10])->label('Note') ?>
            </fieldset>
            <div><?= Html::submitButton('Ghi và in lịch xe', ['class'=>'btn btn-primary']) ?></div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Chương trình tour</h6>
        </div>
        <div class="table-responsive">
            <table id="tblCurrentProg" class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="10" class="text-center"></th>
                        <th class="no-padding-left">
                            Activity
                            (<a href="#" class="toggle-day-contents">Ẩn/hiện mọi ngày</a>)
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?
                $cnt = 0;
                foreach ($dayIdList as $dayId) {
                    foreach ($theTour['days'] as $day) {
                        if ($dayId == $day['id']) {
                            $dayDate = date('Y-m-d', strtotime('+ '.$cnt.' days', strtotime($theTour['day_from'])));
                            $cnt ++;
                ?>
                    <tr class="tr-day" data-id="<?= $day['id'] ?>" id="ngay_<?= $day['id'] ?>">
                        <td class="text-center" width="20">
                            <span class="text-muted"><?= $cnt ?></span>
                        </td>
                        <td class="no-padding-left">
                            <div class="day-actions text-nowrap text-right pull-right position-right">
                            </div>
                            <span class="day-date"><?= Yii::$app->formatter->asDate($dayDate, 'php:j/n/Y D') ?></span>
                            <a class="day-name" href="/days/r/<?= $day['id'] ?>"><?= $day['name'] == '' ? '(no name)' : $day['name'] ?></a>
                            <em class="day-meals text-nowrap"><?= $day['meals'] ?></em>
                            <div class="day-content mt-20" style="display:none;">
                                <p>
                                    <span class="day-guides"><?= $day['guides'] == '' ? '' : '<i class="fa fa-user"></i> '.$day['guides'] ?></span>
                                    <span class="day-transport"><?= $day['transport'] == '' ? '' : '<i class="fa fa-car"></i> '.$day['transport']?></span>
                                </p>
                                <div class="day-body" id="day-body-<?= $day['id'] ?>">
                                <?
                                if (substr($day['body'], 0, 1) == '<') {
                                    echo $day['body'];
                                } else {
                                    echo $parser->parse($day['body']);
                                }
                                ?>
                                </div>
                            </div>    
                        </td>
                    </tr>
                <?
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?
$js = <<<'TXT'
$('i.fa.del').on('click', function(){
    var cnt = $(this).data('day');
    if ($('.tr'+cnt).length > 1) {
        var tr = $(this).parent().parent();
        tr.remove();
    } else {
        $('.tr'+cnt).toggleClass('danger');
    }
});
$('i.fa.add').on('click', function(){
    var tr = $(this).parent().parent();
    var chirld = tr.clone(true, true);
    chirld.find('input[name="ngay[]"]').val('');
    chirld.insertAfter(tr);
});
$('.field_dvi').on('change', function(){
    if ($(this).val() == 'chang') {
        $(this).parent().parent().find('input:last').prop('readonly', false).focus();
    } else {
        $(this).parent().parent().find('input:last').val('').prop('readonly', true);
    }
});
$('#lxForm').submit(function() {
    $('tr.danger').remove();
});

//$('textarea.autogrow').autogrow({vertical: true, horizontal: false});
// $('.money').mask('000,000,000', {reverse: true});
TXT;
$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.ns-autogrow/1.1.6/jquery.ns-autogrow.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.min.js', ['depends'=>'yii\web\JqueryAsset']);
