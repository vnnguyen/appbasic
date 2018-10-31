
<?

use kartik\mpdf\Pdf;
use yii\helpers\Markdown;

require_once('/var/www/__apps/www.amica-travel.com/helpers/hxTextile.php');
// require_once('D:/wamp/www/demo.amica-travel.com/helpers/hxTextile.php');
$txt = new hxTextile;

$dayIdList = explode(',', $theProduct['day_ids']);

$ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');

$this->title = $theProduct['title'];

$theProduct['gender'] = 'f';
$theProduct['avatar'] = 'avatar';
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title><?= $theProduct['title'] ?> - <?= count($dayIdList) ?> jours - devis - <?= $theProduct['created_at'] ?></title>
    </head>
    <body style="font-family:Candara; font-size:9pt;">
        <div style="width:800px; margin:auto;">
            <h1 style="color:036; font-size:40px; margin:0 0 20px;">Xuất file devis tự động</h1>
            <p>Designer: Elodie | Date: 22/11/2013</p>
            <p style="font-family:Candara; font-size:11pt;">ĐÂY LÀ TEXT DÙNG CHO FILE MẪU DEVIS MỚI NHẤT CỦA AMICA (14 TEMPLATE WORD KHÁC NHAU)      
            </p>
            <div>
                <p style=" color: #09f;font-size: 20px">Để xuất ra file word hãy chọn 1 mẫu devis và <a id="download-word" url='<?= DIR ?>ajaxphpdocx/ims_to_word_ajax.php' href="javascript:void(0)">CLICK VÀO ĐÂY</a></p>
                <p id='loading' style='display:none;'>Please Wait <img src='<?= DIR ?>assets/img/devis-ims/loading.gif'/></p>
                <div class="option-devis">
                    <p>
                        <select id='prix-option' style='width: 110px;'>
                            <option>prix à partir de</option>
                            <option>prix</option>
                        </select>
                        <span type='text' id='devis-prix'><?= $theProduct['price'] ?></span>
                        <span id='prix-money'><?= $theProduct['price_unit'] ?></span>
                    </p>
                    <ul>
                        <h2>Việt Nam</h2>
                        <li>
                            <p>Vietnam Classique</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/vietnam/devis_base_01vietnam_classique.jpg'>
                            <input type="checkbox" value="devis_base_01vietnam_classique"/>
                        </li>
                        <li>
                            <p>Vietnam Immersion</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/vietnam/devis_base_02vietnam_IMMERSION.jpg'>
                            <input type="checkbox" value="devis_base_02vietnam_immersion"/>
                        </li>
                        <li>
                            <p>Vietnam Ethnies</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/vietnam/devis_base_03vietnam_ETHNIES.jpg'>
                            <input type="checkbox" value="devis_base_03vietnam_ethnies"/>
                        </li>
                        <li>
                            <p>Vietnam Ethnies</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/vietnam/devis_base_04vietnam_balneaire.jpg'>
                            <input type="checkbox" value="devis_base_04vietnam_balneaire"/>
                        </li>
                        <li>
                            <p>Vietnam Mekong</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/vietnam/devis_base_05vietnam_mekong.jpg'>
                            <input type="checkbox" value="devis_base_05vietnam_mekong"/>
                        </li>
                    </ul>
                    <ul>
                        <h2>Laos</h2>
                        <li>
                            <p>Laos Classique</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/laos/devis_base_04LAOS_classique.jpg'>
                            <input type="checkbox" value="devis_base_04laos_classique"/>
                        </li>
                        <li>
                            <p>Laos Aventure</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/laos/devis_base_05LAOS_AVENTURE.jpg'>
                            <input type="checkbox" value="devis_base_05laos_aventure"/>
                        </li>
                    </ul>
                    <ul>
                        <h2>Cambodge</h2>
                        <li>
                            <p>Cambodge Classique</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/cambodge/devis_base_06CAMBODGE_classique.jpg'>
                            <input type="checkbox" value="devis_base_06cambodge_classique"/>
                        </li>
                        <li>
                            <p>Cambodge Aventure</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/cambodge/devis_base_07CAMBODGE_aventure.jpg'>
                            <input type="checkbox" value="devis_base_07cambodge_aventure"/>
                        </li>
                        <li>
                            <p>Cambodge Balneaire</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/cambodge/devis_base_08CAMBODGE_BALNEAIRE.jpg'>
                            <input type="checkbox" value="devis_base_08cambodge_balneaire"/>
                        </li>
                    </ul>
                    <ul>
                        <h2>Mutipays</h2>
                        <li>
                            <p>Mutipays Classique</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/mutipays/devis_base_010_MULTIPAYS_classique.jpg'>
                            <input type="checkbox" value="devis_base_010_multipays_classique"/>
                        </li>
                        <li>
                            <p>Mutipays Aventure</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/mutipays/devis_base_011_MULTIPAYS_aventure.jpg'>
                            <input type="checkbox" value="devis_base_011_multipays_aventure"/>
                        </li>
                        <li>
                            <p>Mutipays Luxury</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/mutipays/devis_base_012_multipays_luxury.jpg'>
                            <input type="checkbox" value="devis_base_012_multipays_luxury"/>
                        </li>
                    </ul>
                    <ul>
                        <h2>Thailand</h2>
                        <li>
                            <p>Thailand Classique</p>
                            <img class="img-devis" style="width: 70px" src='<?= DIR ?>assets/img/devis-ims/thailand/devis_base_13_thailande_classique.jpg'>
                            <input type="checkbox" value="devis_base_13_thailande_classique"/>
                        </li>
                    </ul>
                </div>
                
                 <h2>Nếu muốn thay đổi ảnh banner số 2 thì chọn ở đây! <input type="checkbox" id="option-img-banner-2-fr"/></h2>
                <div class="option-img-banner-2">
                    <ul>
                        <li>
                            <p>Hạ long</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_1_halong.jpg'>
                            <input type="checkbox" value="banner2_1_halong"/>
                        </li>
                   
                        <li>
                            <p>Hội an</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_2_hoian.jpg'>
                            <input type="checkbox" value="banner2_2_hoian"/>
                        </li>
                    
                        <li>
                            <p>Marche ethnique</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_3_marche_ethnique.jpg'>
                            <input type="checkbox" value="banner2_3_marche_ethnique"/>
                        </li>
                   
                        <li>
                            <p>Delta mekong</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_4_delta_mekong.jpg'>
                            <input type="checkbox" value="banner2_4_delta_mekong"/>
                        </li>
                   
                        <li>
                            <p>Trek</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_5_trek.jpg'>
                            <input type="checkbox" value="banner2_5_trek"/>
                        </li>
                   
                        <li>
                            <p>Ethnie du nord</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_6_ethnie_du_nord.jpg'>
                            <input type="checkbox" value="banner2_6_ethnie_du_nord"/>
                        </li>
                   
                        <li>
                            <p>Rizieres terrace</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_7_rizieres_terrace.jpg'>
                            <input type="checkbox" value="banner2_7_rizieres_terrace"/>
                        </li>
                    
                        <li>
                            <p>Phú quốc</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_8_phu_quoc.jpg'>
                            <input type="checkbox" value="banner2_8_phu_quoc"/>
                        </li>
                    
                        <li>
                            <p>Tam cốc</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_9_tam_coc.jpg'>
                            <input type="checkbox" value="banner2_9_tam_coc"/>
                        </li>
                    
                        <li>
                            <p>Personne vietnamienne</p>
                            <img class="img-devis" style="width: 150px" src='<?= DIR ?>upload/banner_2_devis/thumb/banner2_10_personne_vietnamienne.jpg'>
                            <input type="checkbox" value="banner2_10_personne_vietnamienne"/>
                        </li>
                    </ul>
                </div>
                
            </div>
            <h3 style="color: #09f; border-bottom: 1px solid #09f;font-size: 30px;">Seller's description | Thông tin seller</h3>
            <div id='sale-detail'>
                <p style='margin: 0; padding: 0;font-size: 9pt;'><img width='130' height='130' src='<?= $theProduct['createdBy']['image'] ?>'/></p>
                <p style='margin: 0; padding: 0; font-size: 9pt; color: #A00D80;font-family:Candara;'>Travel consultant Amica Travel</p>
                <h2 id='devis-sale-name' style='margin: 0; padding: 0;font-family:Candara; font-size: 14pt;color:black;'>Ms. <?= $theProduct['createdBy']['fname'] . ' ' . $theProduct['createdBy']['lname'] ?></h2>
                <p id='devis-sale-email' style='margin: 0; padding: 0;font-family:Candara; font-size: 9pt;'><a href="maito:<?= $theProduct['createdBy']['email'] ?>"><?= $theProduct['createdBy']['email'] ?></a></p>
                <p id='devis-sale-phone' style='margin: 0; padding: 0;font-family:Candara; font-size: 9pt;'>+84 4 62 73 44 55</p>
            </div>

            <div style="display: inline-block; width: 268pt;" id="sale-detail-style2">
                <table style="margin: 0;padding: 0;border: none;border-collapse: collapse;width: 268pt;">
                    <tr>
                        <td style="text-align: right;padding-top: 5pt;">
                            <p style="margin: 0; padding: 0; font-size: 9pt; color: #A00D80;font-family:Candara;">Travel consultant Amica Travel</p>
                            <h2 style="margin: 0; padding: 0;font-family:Candara; font-size: 14pt;color:black;" id="devis-sale-name">Ms. <?= $theProduct['createdBy']['fname'] . ' ' . $theProduct['createdBy']['lname'] ?></h2>
                            <p style="margin: 0; padding: 0;font-family:Candara; font-size: 9pt;" id="devis-sale-email"><a href="maito:<?= $theProduct['createdBy']['email'] ?>"><?= $theProduct['createdBy']['email'] ?></a></p>
                            <p style="margin: 0; padding: 0;font-family:Candara; font-size: 9pt;" id="devis-sale-phone">+84 4 62 73 44 55</p>
                        </td>
                        <td style="text-align: left; padding: 0;margin: 0;width: 130px">
                            <img width="130" height="130" src='<?= $theProduct['createdBy']['image'] ?>'/>
                        </td>
                    </tr>
                </table>
            </div>
            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Front page | Trang bìa</h2>
            <h3 id="devis-name" style="font-family:Candara; font-size: 24pt;text-transform: uppercase; margin: 0; padding: 0;"><?= preg_replace("/[^a-zA-Z]*devis\d[^a-zA-Z]*/", "", $theProduct['title']) ?></h3>
            <p style="font-family:Candara; font-size:9pt;">
                <strong>Program No.<span id='devis-number' style='font-family:Candara; font-size: 14pt;font-weight:bold;'><?= preg_replace("/[^0-9]/", "", $theProduct['title']) ?></span></strong>

                <strong>Type of travel:</strong> Private tour
                <br /><strong>Designed for :</strong> <span id='devis-guest' style='font-family:Candara; font-size: 10pt;'><?= $theProduct['about'] ?></span> 
                <br /><strong>Travel duration & date:</strong><span id='devis-date' style='font-family:Candara; font-size: 10pt;'> <?= count($dayIdList) ?> day<?= count($dayIdList) > 1 ? 's' : '' ?> from <?= str_replace('/', '|', date('d/m/Y', strtotime($theProduct['day_from']))) ?> until <?= str_replace('/', '|', date('d/m/Y', strtotime('+ ' . (count($dayIdList) - 1) . ' day', strtotime($theProduct['day_from'])))) ?></span>
            </p>

            <h3 style="font-family:Candara; font-size:11pt;font-weight: bold;">Highlights: </h3>
            <div id='devis-description' style='font-family:Candara; font-size: 10pt;'>
                <?
                $points = $txt->textileThis($theProduct['points']);
                $points = str_replace('<p style="font-family:Candara;">', '<p style="font-family:Candara; font-size:11pt;">', $points);
                $points = str_replace('+', '&#8226;', $points);
                echo $points;
                ?>
            </div>


            <h2 style="font-family: Candara;border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Overview</h2>
            <p style="font-family:Candara; font-size:11pt;">BEGIN COPY</p>
            <table id='devis-table-programe' style='margin: 0; padding: 0; width: 166mm;border-collapse: collapse;'>
                <thead>
                    <tr style="margin: 2mm 0;">
                        <th style="text-align:left; width: 8.6%; padding: 2mm 1mm 2mm 0;"><h3 style="font-family:Candara; font-size:9pt; color:#a00d80; margin:0;">Day</h3></th>
                <th style="text-align:left;width:  15%;"><h3 style="font-family:Candara; font-size:9pt; color:#a00d80; margin:0; padding-right: 1mm;">Date</h3></th>
                <th style="text-align:left;width:  39.1%;"><h3 style="font-family:Candara; font-size:9pt; color:#a00d80; margin:0;padding-right: 1mm;">Itinerary</h3></th>
                <th style="text-align:left;width: 22.6%;"><h3 style="font-family:Candara; font-size:9pt; color:#a00d80; margin:0;padding-right: 1mm;">Accompaniment</h3></th>
                <th style="text-align:left;width: 14.5%;"><h3 style="font-family:Candara; font-size:9pt; color:#a00d80; margin:0;">Meals</h3></th>
                </tr>
                </thead>
                <?
                $cnt = 0;
                foreach ($dayIdList as $di) {
                    foreach ($theProduct['days'] as $ng) {
                        if ($ng['id'] == $di) {
                            $cnt ++;
                            $ngay = date('D d|m|Y', strtotime($theProduct['day_from'] . ' + ' . ($cnt - 1) . 'days'));
                            $ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
//$ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
                            $ngay_fr = array('Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di');
//                            $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                            ?>
                            <tr>
                                <td style="font-family:Candara; font-size:9pt;font-weight: bold;  padding: 5pt 0;"><strong>Day <?= $cnt ?></strong></td>
                                <td style="font-family:Candara; font-size:9pt;padding: 5pt 0;"><?= $ngay ?></td>
                                <td style="font-family:Candara; font-size:9pt;font-weight: bold;padding: 5pt 0;" ><strong><?= $ng['name'] ?></strong></td>
                                <td style="font-family:Candara; font-size:9pt;padding: 5pt 0;" ><?= $ng['guides'] ?></td>
                                <td style="font-family:Candara; font-size:9pt;padding: 5pt 0;">
                                    <?
                                    if (strpos($ng['meals'], 'B') !== false) {
                                        echo 'B ';
                                    } else {
                                        echo '&mdash; ';
                                    }
                                    if (strpos($ng['meals'], 'L') !== false) {
                                        echo 'L ';
                                    } else {
                                        echo '&mdash; ';
                                    }
                                    if (strpos($ng['meals'], 'D') !== false) {
                                        echo 'D ';
                                    } else {
                                        echo '&mdash; ';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?
                        }
                    }
                }
                ?>
            </table>
            <p style="font-family:Candara; font-size:11pt;">END COPY</p>
            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Detailed Program</h2>
            <p style="font-family:Candara; font-size:11pt;">BEGIN COPY</p>
            <div id='devis-detail' style="font-size: 9pt; text-align: justify;">


                <?
                $cnt = 0;
                foreach ($dayIdList as $di) {
                    foreach ($theProduct['days'] as $ng) {
                        if ($ng['id'] == $di) {
                            $cnt ++;
                            $ngay = date('D d|m|Y', strtotime($theProduct['day_from'] . ' + ' . ($cnt - 1) . 'days'));
                            $ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                            $ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
//                            $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                            $ngay = str_replace('|', '<span style="font-weight:normal">|</span>', $ngay);
                            ?>
                            <h4 style="font-size:11pt; color:rgb(172,0,132);font-family:Candara;">Day <?= $cnt ?> - <?= $ngay ?><span style="font-weight:normal"> | <?= $ng['name'] ?> (<?= $ng['meals'] ?>)</span></h4>
                            <div style="font-family:Candara; font-size:9pt;">
                                <?= $ng['transport'] == '' ? '' : '<p style="font-family:Candara; font-size:9pt;">' . $ng['transport'] . '</p>' ?>
                                <?
                                $txxt = $txt->textileThis($ng['body']);
                                $txxt = str_replace('<p>', '<p style="font-family:Candara; font-size:9pt;">', $txxt);
                                $txxt = str_replace('<li>', '<li style="font-family:Candara; font-size:9pt;">', $txxt);
                                $txxt = str_replace('<strong>', '<strong style="font-family:Candara; font-size:9pt;">', $txxt);
                                $txxt = str_replace('<em>', '<em style="font-family:Candara; font-size:9pt;">', $txxt);
                                $txxt = str_replace(['<span class="caps">', '</span>'], ['', ''], $txxt);
                                echo $txxt;
                                ?>
                            </div>
                            <?
                        }
                    }
                }
                ?>
            </div>
            <p style="font-family:Candara; font-size:11pt;">END COPY</p>
            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Tour Price</h2>
            <p style="font-family:Candara; font-size:11pt;">BEGIN COPY</p>
            <div id='devis-table-tarif'>

                <p style="font-family:Candara; font-size:9pt;">Fare conditions apply from <?= date('d-m-Y', strtotime($theProduct['created_at'])) ?></p>
                <table class="simple" style="width: 170.3mm; margin: 0; padding: 0;border-collapse: collapse;">
                    <?
                    // Gia va cac options
                    $theProductpx = $theProduct['prices'];
                    $theProductpx = explode(chr(10), $theProductpx);
                    $last = array();
                    for ($i = 1; $i < count($theProductpx); $i++) {
                        if (substr($theProductpx[$i], 0, 7) == 'OPTION:') {
                            $last[] = $i;
                        }
                    }
                    $optcnt = 0;
                    $count = 0;
                    foreach ($theProductpx as $theProductp) {
                        $count++;

                        if (substr($theProductp, 0, 7) == 'OPTION:') {
                            $optcnt ++;
                            if ($optcnt != 1) echo '</table>' . chr(10) . '<table class="simple" style="width: 100%; margin: 0;border-collapse: collapse;border-spacing: 1mm;">';
                            echo '<p style="font-family:Candara; font-size:11pt;color:#a00d80;"><strong>' . trim(substr($theProductp, 7)) . '</strong></p>';
                            echo '<tr style="height: 10mm;">
                <th style="padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid black;width: 17.8%; text-align: left;"><h3 style="font-family:Candara; color:#a00d80; font-size:9pt;">Destination</h3></th>
                <th style="padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid black; width: 20.5%;text-align: left;"><h3 style="font-family:Candara; color:#a00d80; font-size:9pt;">Hotel</h3></th>
                <th style="padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid black; width: 22%;text-align: left;"><h3 style="font-family:Candara; color:#a00d80; font-size:9pt;">Room Category</h3></th >
                <th style="padding-left: 2mm;border-bottom: 1px solid black;text-align: left;width: 39.6%;font-size:9pt;color:#a00d80;">Website</h3></th>
                </tr>';
                        }
                        if (substr($theProductp, 0, 2) == '+ ') {
                            $line = trim(substr($theProductp, 2));
                            $line = explode(':', $line);
                            for ($i = 0; $i < 4; $i ++) if (!isset($line[$i])) $line[$i] = '';
                            echo '<tr>
<td style="border-bottom: 1px solid black;font-family:Candara; font-size:9pt;padding: 5pt 2mm;"><strong>' . $line[0] . '</strong></td>
<td style="border-bottom: 1px solid black;font-family:Candara; font-size:9pt; padding: 5pt 2mm;">' . $line[1] . '</td>
<td style="border-bottom: 1px solid black;font-family:Candara; font-size:9pt;  padding: 5pt 2mm;">' . $line[2] . '</td>
<td style="border-bottom: 1px solid black;font-family:Candara; font-size:9pt; padding: 5pt 2mm;" class="a-href">' . trim($line[3]) . '</td></tr>';
                        }

                        if (substr($theProductp, 0, 2) == '- ') {
                            $line = trim(substr($theProductp, 2));
                            $line = explode(':', $line);
                            for ($i = 0; $i < 3; $i ++) if (!isset($line[$i])) $line[$i] = '';
                            $line[1] = trim($line[1]);
                            if (($count == count($theProductpx)) || in_array($count, $last)) {
                                echo '<tr><td style="font-family:Candara; font-size:9pt;  padding: 2mm 1mm 2mm 2mm;color:#AC0086;" colspan="3" class="ta-r"><strong>' . $line[0] . '</strong></td><td style="text-align: center;"><h3 style="font-size:12pt;font-family:Candara; color:#a00d80;">' . number_format($line[1]) . ' ' . str_replace('EUR', '&euro;', $theProduct['price_unit']) . '</h3></td></tr>';
                            } else {
                                echo '<tr><td style="border-bottom: 1px solid black;font-family:Candara; font-size:9pt;  padding: 2mm 1mm 2mm 2mm;color:#AC0086;" colspan="3" class="ta-r"><strong>' . $line[0] . '</strong></td><td style="text-align: center;border-bottom: 1px solid black;"><h3 style="font-size:12pt;font-family:Candara; color:#a00d80;">' . number_format($line[1]) . ' ' . str_replace('EUR', '&euro;', $theProduct['price_unit']) . '</h3></td></tr>';
                            }
                        }
                    }
                    ?>
                </table>

            </div>
            <p style="font-family:Candara; font-size:11pt;">END COPY</p>

            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Promotion</h2>
            <p style="font-family:Candara; font-size:11pt;">BEGIN COPY</p>
            <div id='devis-promotion' style='text-align: center;'>
                <? if ($txt->textileThis($theProduct['promo'])): ?>
                    <span style='font-weight:bold; color: #A00D80; font-size: 14pt; font-family:Candara;'>Promotion: </span><span style='font-weight:bold; color: #A00D80; font-size: 11pt; font-family:Candara;'><?= $txt->textileThis($theProduct['promo']) ?></span>
                    <?
                else: echo "";
                endif;
                ?>
            </div>
            <p style="font-family:Candara; font-size:9pt;">END COPY</p>
            <h2 style="border-bottom:1px solid #09f; color:#09f; font-size: 30px;">Terms and conditions</h2>
            <p style="font-family:Candara; font-size:9pt;">BEGIN COPY</p>
            <div id='devis-condition'>
                <?
// $theProduct['conditions'] = str_replace('', '', $theProduct['conditions']);
                ?>
                <?
                $condText = $txt->textileThis($theProduct['conditions']);
                $condText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend :', 'Ce prix ne comprend pas :'], ['<h3 style="font-size:12pt; color:rgb(172,0,132);font-family:Candara;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Candara; font-size:9pt; margin:0;">', '<li style="font-family:Candara; font-size:9pt;">', 'Ce prix comprend', 'Ce prix ne comprend pas'], $condText);
                echo $condText;
                $otherText = $txt->textileThis($theProduct['others']);
                $otherText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend :', 'Ce prix ne comprend pas :'], ['<h3 style="font-size:12pt; color:rgb(172,0,132);font-family:Candara;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Candara; font-size:9pt; margin:0;">', '<li style="font-family:Candara; font-size:9pt;">', 'Ce prix comprend', 'Ce prix ne comprend pas'], $otherText);
                echo $otherText;
                ?>
            </div>
            <p style="font-family:Candara; font-size:11pt;">END COPY</p>
        </div>
    </body>
</html>
<style>
    .option-devis ul{
        margin: 0;
        padding: 0;
    }
    .option-devis ul h2{
        margin: 0;
    }
    .option-devis ul li{
        display: inline-block;
        list-style: none outside none;
        text-align: center;
        width: 150px;
        cursor: pointer;
    }
    .option-devis ul li:not(:last-of-type){
        border-right: 1px dotted #000000;
    }
    .option-devis ul li input[type="checkbox"] {
        display: block;
        margin: 0 auto;
    }
    .option-devis ul li img{
        cursor: pointer;
    }

    .option-devis ul li:hover{
        background: #E3F2E1;
    }
    #sale-detail-style2{
        display: none !important;
    }
    
     .option-img-banner-2 ul{
        margin: 0;
        padding: 0;
    }
    .option-img-banner-2 ul h2{
        margin: 0;
    }
    .option-img-banner-2 ul li{
        display: inline-block;
        list-style: none outside none;
        text-align: center;
        width: 150px;
        cursor: pointer;
    }
    .option-img-banner-2 ul li:not(:last-of-type){
       // border-right: 1px dotted #000000;
        margin-right: 5px;
    }
    .option-img-banner-2 ul li input[type="checkbox"] {
        display: block;
        margin: 0 auto;
    }
    .option-img-banner-2 ul li img{
        cursor: pointer;
    }

    .option-img-banner-2 ul li:hover{
        background: #E3F2E1;
    }
     .option-img-banner-2{ display: none;}
</style>

<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
<script>
    $(function() {
        $('.option-devis ul li').click(function() {
            $('.option-devis ul li input[type=checkbox]').prop('checked', false);
            $(this).find('input[type=checkbox]').prop('checked', true);
        });
         $('.option-img-banner-2 ul li').click(function() {
            $('.option-img-banner-2 ul li input[type=checkbox]').prop('checked', false);
            $(this).find('input[type=checkbox]').prop('checked', true);
        });
       $("#option-img-banner-2-fr").click(function(){
         if($("#option-img-banner-2-fr").is(':checked')){
            $('.option-img-banner-2').show();
        }
         if(!$("#option-img-banner-2-fr").is(':checked')){
            $('.option-img-banner-2').hide();
        }
        });
        

    })
    $('#download-word').click(function() {

        var template = $('.option-devis ul li input[type=checkbox]:checked').val();
        var img_banner_2 = $('.option-img-banner-2 ul li input[type=checkbox]:checked').val();
        if (!template && !$("#b2b-fr-options").is(':checked')) {
            alert('Hãy chọn mẫu devis');
            return false;
        }
        var prix_option = $('#prix-option').val();
        template = template + '_en';
        prix_option = 'Tour price';

        if ($("#b2b-fr-options").is(':checked')) {
            template = 'B2B-FR';
        }
        
         if(!$("#option-img-banner-2-fr").is(':checked')){
            img_banner_2 = 'no';
        }
        if($("#option-img-banner-2-fr").is(':checked')){
            if(!img_banner_2){
                alert('Hãy chọn ảnh banner số 2!');
                return false;
            }
        }
        
        
        var file_name = '<?= SEG2 . '-' . strtotime("now") ?>';
        var devis_prix = $('#devis-prix').text();

        devis_prix = '<span style="font-family: Candara; font-size: 10pt; color:#8A006C;">' + prix_option + '</span> <b style="font-size: 14pt; font-family: Candara; color: #8A006C;">' + devis_prix + ' ' + $('#prix-money').text() + '</b> <span style="font-family: Candara; font-size: 14pt; color: #8A006C;">/</span> <span style="font-family: Candara; font-size: 12pt; color: #8A006C;">person</span>';
        var url = $(this).attr('url');
        var devis_name = $('#devis-name').clone().wrap('<p>').parent().html();
        var devis_number = $('#devis-number').html();
        var devis_guest = $('#devis-guest').html();
        var devis_date = $('#devis-date').html();

        var devis_description = $('#devis-description').clone().wrap('<p>').parent().html();
        var sale_detail = '<div style="text-align: right;">' + $('#sale-detail').html() + '</div>';
        if (template.indexOf("multipays") > -1) {
            sale_detail = $('#sale-detail-style2').html();
        }
        var devis_table_programe = $('#devis-table-programe').clone().wrap('<p>').parent().html();
        var devis_detail = $('#devis-detail').clone().wrap('<p>').parent().html();
        var devis_table_tarif = $('#devis-table-tarif').clone().wrap('<p>').parent().html();
        var devis_promotion = $('#devis-promotion').clone().wrap('<p>').parent().html();
        var devis_condition = $('#devis-condition').clone().wrap('<p>').parent().html();
        $('#loading').show();
        $.ajax({
            url: url,
            type: 'post',
            data: {
                file_name: file_name,
                template: template,
                 img_banner_2: img_banner_2,
                devis_name: devis_name,
                devis_number: devis_number,
                devis_guest: devis_guest,
                devis_date: devis_date,
                devis_prix: devis_prix,
                devis_description: devis_description,
                sale_detail: sale_detail,
                devis_table_programe: devis_table_programe,
                devis_detail: devis_detail,
                devis_table_tarif: devis_table_tarif,
                devis_promotion: devis_promotion,
                devis_condition: devis_condition
            },
            dataType: 'json',
            success: function(data) {
                $('#loading').hide();
                var win = window.open('<?php echo DIR . 'upload/ideas/tours/output/devis-ims/' ?>' + file_name + '.docx', '_blank');
                win.focus();
            },
            async: false

        });
        return false;
    })
</script>