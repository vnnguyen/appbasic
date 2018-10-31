<?

use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

if (!function_exists('trim00')) {
    function trim00($str)
    {
        return rtrim(rtrim($str, '0'), '.');
    }
}

?>
                <tr>
                    <td class="text-muted text-center"><?= Html::a('Xem', '@web/cpt/r/'.$cpt['dvtour_id'], ['class'=>'text-muted']) ?></td>
                    <? if (!$theTour) { ?>
                    <td><?= Html::a($cpt['tour']['code'], '@web/tours/r/'.$cpt['tour']['id']) ?></td>
                    <td class="text-nowrap"><?= date('d-m-Y D', strtotime($cpt['dvtour_day'])) ?></td>
                    <? } ?>
                    <?php if ($cpt['dv_id'] != 0): ?>
                            <td colspan="2">
                                <span class="links">
                                    <i class="fa fa-link"></i>
                                </span> 
                                <span title="ĐH đánh dấu đã đặt xong" class="label label-<?= $cpt['status'] == 'k' ? 'success' : 'default'?>">ĐH</span>
                        <?php
                            $prefix = '';
                            if ($cpt['dv']['stype'] == 'a') {
                                $prefix = 'Phòng';
                            }
                            if ($cpt['dv']['stype'] == 'f') {
                                $prefix = 'Chuyến bay';
                            }
                            if ($cpt['dv']['stype'] == 'v') {
                                $prefix = 'Tham quan';
                            }
                            if ($cpt['dv']['stype'] == 'p') {
                                $prefix = 'Gói';
                            }
                            if ($cpt['dv']['stype'] == 't') {
                                $prefix = 'Ngủ tàu phòng';
                            }
                        ?>
                        <?= $prefix.' '. Html::a($cpt['dv']['name'], '') . ' của '. Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id']) ?>
                            </td>
                        <td class="text-center"><?= (real)$cpt['qty'] ?></td>
                    <?php endif ?>
                    <?php if ($cpt['dv_id'] == 0): ?>
                    <td>
                        <span title="ĐH đánh dấu đã đặt xong" class="label label-<?= $cpt['status'] == 'k' ? 'success' : 'default'?>">ĐH</span>
                        <? if ($cpt['comments']) { ?>
                        <span class="badge badge-default popovers pull-right"
                            data-trigger="hover"
                            data-placement="right"
                            data-html="true"
                            data-content="
                        <? foreach ($cpt['comments'] as $li2) { ?>
                        <div style='margin-bottom:5px'><strong><?= $li2['updatedBy']['name'] ?></strong> <em><?= DateTimeHelper::format($li2['updated_at'], 'j/n/Y H:i') ?></em></div>
                        <p><?= nl2br(Html::encode($li2['body'])) ?></p>
                        <? } ?>
                        "><?= count($cpt['comments']) ?></span>
                        <? } ?>

                        <? /* if ($cpt['cp']) { ?>
                        <?= Html::a($cpt['cp']['name'], '@web/cp/r/'.$cpt['cp']['id'])?>
                        <? } else { */ ?>
                        <span title="<?= $cpt['updatedBy']['name'] ?> @ <?= date('j/n/Y H:i', strtotime($cpt['updated_at'])) ?>"><?= $cpt['dvtour_name'] ?></span>
                        <? //} ?>

                        @<?= Html::a($cpt['venue']['name'], '@web/venues/r/'.$cpt['venue']['id']) ?>
                        <? if ($cpt['company']) { ?>
                        $<?= Html::a($cpt['company']['name'], '@web/companies/r/'.$cpt['company']['id']) ?>
                        <? } else { ?>
                            <? if ($cpt['oppr'] != '') { ?>
                        $<?= $cpt['oppr'] ?>
                            <? } ?>
                        <? } ?>

                        <?// if ($cpt['updated_by'] == USER_ID || 1 == USER_ID) { ?>
                        <?= Html::a('<i class="fa fa-edit"></i>', '@web/tours/services/'.$cpt['tour_id'].'#dvtour-'.$cpt['dvtour_id'], ['class'=>'text-muted', 'rel'=>'external']) ?>
                        <?// } ?>
                    </td>

                    <td class="text-center text-muted"><?= $cpt['unit'] ?></td>
                    <td class="text-center"><?= (real)$cpt['qty'] ?></td>
                    <?php endif ?>
                    
                    <td class="text-right text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
                    <?
                    $lineTotal = $cpt['price'] * $cpt['qty'];
                    ?>
                    <td class="text-right text-pink text-nowrap"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($lineTotal, intval($lineTotal) == $lineTotal ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></td>
                    <td><?= $cpt['payer'] ?></td>
                    <td><?= $cpt['dv']['so_tk'] ?></td>
                    <td><?= $cpt['due'] == '0000-00-00' ? '' : $cpt['due'] ?></td>
                    <td>
                        <!--
                        <i title="Check 1" class="check check1 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 2" class="check check2 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 3" class="check check3 text-muted fa fa-circle-o cursor-pointer"></i>
                        <i title="Check 4" class="check check4 text-muted fa fa-circle-o cursor-pointer"></i>
                        -->
                        <?
                        $cpt['approved_by'] = trim($cpt['approved_by'], '[');
                        $cpt['approved_by'] = trim($cpt['approved_by'], ':]');
                        $ids = explode(':][', $cpt['approved_by']);
                        $apprCnt = 0;
                        $apprName = [];
                        foreach ($ids as $id2) {
                            foreach ($approvedBy as $user) {
                                if ($user['id'] == (int)$id2) {
                                    $apprCnt ++;
                                    $apprName[] = $user['name'];
                                }
                            }
                        }
                        if ($apprCnt > 0) {
                        ?><span class="badge badge-info" title="Xác nhận: <?= implode(', ', $apprName) ?>"><?= $apprCnt ?></span><?
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        $cptPaidInFull = false;
                        $cptInBasket = false;
                        
                        ?>
                        <? if (!$cptPaidInFull) { ?>
                        <a title="Đánh dấu đã TT 100%" href="#" class="label label-default mark-paid" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">TT</a>
                        <a title="Sửa TT riêng mục này" href="/cpt/r/<?= $cpt['dvtour_id'] ?>?action=new-mtt" class="label label-default" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">TT+</a>
                            <? if ($cptInBasket) { ?>
                        <a title="Bỏ Đề nghị thanh toán" href="#" class="label label-info add-to-b" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">+TT</a>
                            <? } else { ?>
                        <a title="Đề nghị thanh toán" href="#" class="label label-default add-to-b" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">+TT</a>
                            <? } ?>
                        <? } ?>
                    </td>
                    <td>
                        <a title="Đánh dấu đã lấy hoá đơn" href="#" class="label label-<?= $cpt['vat_ok'] == 'ok' ? 'success' : 'default' ?> vat-ok" data-tour_id="<?= $cpt['tour_id'] ?>" data-dvtour_id="<?= $cpt['dvtour_id'] ?>">HĐ</a>
                    </td>


                    <? if (0): ?>
                    <td class="text-nowrap hidden">
                        <small title="Check 1<?= $title['c1'] ?>" data-action="c1" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c1 <?= $cpt['c1'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c1'], 'on') !== false ? 'on' : 'off' ?>">C1</small>
                        <small title="Check 2<?= $title['c2'] ?>" data-action="c2" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c2 <?= $cpt['c2'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c2'], 'on') !== false ? 'on' : 'off' ?>">C2</small>
                        <small title="Th/toan<?= $title['c3'] ?>" data-action="c3" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c3 <?= $cpt['c3'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c3'], 'on') !== false ? 'on' : 'off' ?>">TT</small>
                        <small title="Duyet!!<?= $title['c4'] ?>" data-action="c4" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c4 <?= $cpt['c4'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c4'], 'on') !== false ? 'on' : 'off' ?>">DZ</small>
                    </td>
                    <td class="text-nowrap hidden">
                        <? if (USER_ID == 1) { ?>
                        <small title="Đã đặt cọc<?= $title['c5'] ?>" data-action="c5" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c5 <?= $cpt['c5'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c5'], 'on') !== false ? 'on' : 'off' ?>">DC</small>
                        <small title="KTT xác nhận đặt cọc<?= $title['c6'] ?>" data-action="c6" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c6 <?= $cpt['c6'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c6'], 'on') !== false ? 'on' : 'off' ?>">DC!</small>
                        <small title="Đã thanh toán<?= $title['c7'] ?>" data-action="c7" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c7 <?= $cpt['c7'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c7'], 'on') !== false ? 'on' : 'off' ?>">TT</small>
                        <small title="KTT xác nhận thanh toán<?= $title['c8'] ?>" data-action="c8" data-dvtour_id="<?= $cpt['dvtour_id'] ?>" data-tour_id="<?= $cpt['tour_id'] ?>" class="label cpt c8 <?= $cpt['c8'] == '' ? '' : 'dirty' ?> <?= strpos($cpt['c8'], 'on') !== false ? 'on' : 'off' ?>">TT!</small>
                        <? } ?>
                    </td>
                    <? endif; ?>
                </tr>
