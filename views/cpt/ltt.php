<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->registerCss("
	.delete_ldntt {color: red}
	.pdf_ldntt {color: #F7250B}
    .ltt_actions span {margin-left: 10px; cursor: pointer;}
    #list-detail-ltt-modal .modal-dialog {width: 900px;}
    .color-blue {color:blue;}
    .color-red {color:red;}
    .color-green {color:green;}
    .span-status {cursor: pointer; background: #6f7f8f; border-radius: 5px; padding: 7px; color: #fff; }
	.span-status-check1 {background: #4642b3; }
	.span-status-check2 {background: #ff66a3; }
	.span-status-check3 {background: #52d68f; }
	.info {width: 100%; over-flow: hidden}
	.info p {float: left; margin-right: 10%}
");

$baseUrl = Yii::$app->request->baseUrl;
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl.'/js/ltt.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

Yii::$app->params['title'] = 'Chi phí tour';
Yii::$app->params['breadcrumbs'][] = ['Tour costs', '@web/cpt'];
$this->title = Yii::t('app', 'Các lượt thanh toán');
$this->params['breadcrumbs'][] = $this->title;
?>
<form class="form-inline panel-search" id="lttForm">
    <input class="form-control" name="ltt_id" value="" placeholder="Code" type="text">        <input class="form-control" name="dvtour" value="" placeholder="Ngày" type="text">        <input class="form-control" name="search" value="" placeholder="Tên người xác nhận" type="text">
	<button type="submit" class="btn btn-primary">Go</button>        <a href="/appbasic/web/cpt">Reset</a>
</form>
<br>
<div class="panel panel-default">
	<div class="table-responsive">
		<table class="table table-xxs table-bordered table-condensed table-striped">
		    <thead>
		        <tr>
		        	<th width="50px">#</th>
		            <th width="150px"><?= Yii::t('app', 'Status') ?></th>
		            <th><?= Yii::t('app', 'Amount') ?></th>
		            <th><?= Yii::t('app', 'Update') ?></th>
		            <th width="150px"><?= Yii::t('app', 'Date') ?></th>
		            <th><?= Yii::t('app', 'Note') ?></th>
		            <th width="100px"></th>
		        </tr>
		    </thead>
		    <tbody id="body-ltt-cpts">
		    	<?php foreach ($ltts as $ltt): ?>
		    		<tr data-ltt-id="<?= $ltt['id']?>">
		    			<td class="ltt-id"><a class="ltt-code" ><?= $ltt['id']?></a></td>
		    			<?
		    				switch ($ltt['status']) {
		    					case 'check1':
		    						$cl = 'span-status-check1';
		    						break;
		    					case 'check2':
		    						$cl = 'span-status-check2';
		    						break;
		    					case 'check3':
		    						$cl = 'span-status-check3';
		    						break;
		    					default:
		    						$cl = '';
		    						break;
		    				}
		    			?>
		    			<td class="ltt-status"><span class="span-status <?= $cl?>"><?= $ltt['status']?></span></td>
		    			<?
		    				$c_m = explode(';',$ltt['t_amount']);
		    				$txt_m = '';
		    				foreach ($c_m as $v) {
		    					$arr_m = explode('-', $v);
		    					if (count($arr_m) != 2) {
		    						continue;
		    					}
		    					$arr_m[0] = number_format($arr_m[0], 2);
		    					$txt_m .= '<b>'.$arr_m[0].'</b> <span class="text-muted">'. $arr_m[1].'</span><br />';
		    				}
		    			?>
		    			<td class="text-right ltt-currency"><?= $txt_m?></td>
		    			<td class="ltt-update"><?
		    			$updated_dt = '';
		    			$actions = explode(';', $ltt['actions']);
		    			foreach ( $actions as $k => $action) {
		    				if ($k == count($actions)-1) {
		    					$ar_action = explode(',', $action);
		    					if (count($ar_action) == 3) {
		    						echo "<b>".$ar_action[0]."</b> confirm <b>".$ar_action[1]."</b>";
		    						$updated_dt = DateTimeHelper::convert($ar_action[2], 'j/n/Y H:i', 'UTC', 'Asia/Ho_Chi_Minh');
		    						// $time = DateTimeHelper::convert($parts[2], 'j/n/Y H:i', 'UTC', 'Asia/Ho_Chi_Minh');
		    					}
		    				}
		    			}
		    			?></td>
		    			<td class="ltt-update-dt"><?= $updated_dt?></td>
		    			<td class="ltt-note"><?= $ltt['note']?></td>
		    			<?
		    				$color = '';
		    				if ($ltt['status'] == 'check1') {
		    					$color = 'color-blue';
		    				}
		    				if ($ltt['status'] == 'check2') {
		    					$color = 'color-red';
		    				}
		    				if ($ltt['status'] == 'check3') {
		    					$color = 'color-green';
		    				}
		    			?>
		    			<td class="ltt_actions"> <span class="checked_ldntt <?= $color?>" title="confirm"><i class="fa fa-check-square fa-2x" aria-hidden="true"></i></span> <span class="cancel_ldntt" title="cancel"><i class="fa fa-ban fa-2x" aria-hidden="true"></i></span> <a href="<?= $baseUrl.'/cpt/pdf/'.$ltt['id']?>" title="view pdf"><span class="pdf_ldntt"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span></a></td>
		    		</tr>
		    	<?php endforeach ?>
		    </tbody>
		</table>
	</div>
</div>

<? if ($pagination->totalCount > $pagination->pageSize) { ?>
    <div class="text-center">
        <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
        ]) ?>
    </div>
    <? } ?>
<!-- DNTT detail Modal -->
    <div class="modal fade" id="list-detail-ltt-modal" role="dialog">
        <div class="modal-dialog dialog-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Payment order detail</h4>
                </div>
                <div class="modal-body">
	                <div class="info">
	                	<p>Họ và tên người đề nghị: <strong>Đặng Minh Ngọc</strong></p>
	                	<p>Bộ phận(hoặc địa chỉ): <strong>Kế toán</strong></p>
	                	<p>Hình thức thanh toán: <strong>VP Bank</strong></p>
	                </div>
                	<div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-xxs table-bordered table-condensed table-striped">
                            <!-- <caption>table title and/or explanatory text</caption> -->
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'Name cost') ?></th>
                                    <th><?= Yii::t('app', 'Service Provider') ?></th>
                                    <th><?= Yii::t('app', 'Remain amount') ?></th>
                                    <th><?= Yii::t('app', 'Amount paid') ?></th>
                                    <th><?= Yii::t('app', 'Note') ?></th>
                                    <th width="30px"></th>
                                </tr>
                            </thead>
                            <tbody id="body-detail-ltt-cpts">
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="modal-footer">
                	<a id="view_pdf" href="<?= $baseUrl.'/cpt/pdf/'?>" class="btn" title="view pdf"><span class="pdf_ldntt">View PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i> </span></a>
                    <button type="submit" class="btn btn-default" data-dismiss="modal" id="btn-close-modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<!--end DNTT detail Modal -->