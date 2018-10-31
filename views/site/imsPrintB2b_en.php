

<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\FileHelper;

//require_once('/var/www/www.amica-travel.com/helpers/hxTextile.php');
 require_once('D:/wamp/www/demo.amica-travel.com/helpers/hxTextile.php');
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
    <body style="font-family:Bell MT;  font-size:11pt;">
        <!-- Banner Image header devis -->
                <div>
                    
                        <a href="javascript:void(0)" class="topopup add-banner-header">CHỌN ẢNH BANNER</a>
                        <a style="display: none;" href="javascript:void(0)" class="topopup add-image-tour">Thêm ảnh vào ngày tour</a>
                        <div id="toPopup">
                        <div class="close"></div>
                            <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>

                            <div id="popup_content"> <!--your content start-->
                                <div class="test"><!--Start Image header-->
                                    <?
                                  //  $dir = '/var/www/www.amica-travel.com/upload/banner_2_devis/b2b/banner_new';
                                    $dir = 'D:/wamp/www/demo.amica-travel.com/upload/banner_2_devis/b2b/banner_new';
                                    if (file_exists($dir)) {
                                        $photos = FileHelper::findFiles($dir, ['recursive' => true, 'only' => ['*.jpg']]);

                                        foreach ($photos as $pt) :
                                            $pt = trim(strrchr($pt, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR); ?> 
                                            <a data-val='<?=$pt?>' data-img='<?= DIR . 'upload/banner_2_devis/b2b/banner_new/'.$pt; ?>' href="#">
                                                <img width="200" height="200" src="<?= DIR ?>timthumb.php?src=<?= DIR . 'upload/banner_2_devis/b2b/banner_new/' .$pt; ?>&w=200&h=200"/>
                                            </a>
                                            <?
                                        endforeach;
                                    }
                                    ?>
                                </div><!--End image header-->
                                <div class="image-jour"><!--Start Image jour-->
                                     <span>Chọn ngày muốn thêm ảnh : </span>
                                        <select id="jour-test" name="" style="width: 500px;">

                                                <? $cnt=0;
                                                foreach ($dayIdList as $di) {
                                                 foreach ($theProduct['days'] as $ng) {
                                                    if ($ng['id'] == $di) {
                                                        $cnt ++;
                                                        $ngay = date('D d|m|Y', strtotime($theProduct['day_from'] . ' + ' . ($cnt - 1) . 'days'));
                                                        $ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                                                        $ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
                                                        $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                                                        $ngay = str_replace('|', '<span style="font-weight:normal">|</span>', $ngay);
                                                ?>
                                            <option value="jour-<?=$cnt?>">Jour <?= $cnt ?> - <?= $ngay ?><span style="font-weight:normal"> | <?= $ng['name'] ?> (<?= $ng['meals'] ?>)</option>                         
                                                <?    }         
                                }
                            } ?>
                                          
                               </select>
                                        <div id="myList" style="color:rgb(172,0,132); font-family: Candara" >
                                            
                                        </div>
                                    <ul>
                                    <?
                                   // $dir = '/var/www/www.amica-travel.com/upload/image_devis_secretindochina/';
                                    $dir = 'D:/wamp/www/demo.amica-travel.com/upload/image_devis_secretindochina/';
                                    if (file_exists($dir)) {
                                        $photos = FileHelper::findFiles($dir, ['recursive' => true, 'only' => ['*.jpg']]);

                                        foreach ($photos as $pt) :
                                            $pt = trim(strrchr($pt, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR); 
                                            echo "<li>
                                                <p>".str_replace(['-', '.png', '.jpg'], [' ', '', ''], $pt)."</p>
                                                <img class='img-devis' style='width: 150px' src='".DIR."timthumb.php?src=".DIR."upload/image_devis_secretindochina/$pt&w=185&h=90'>
                                                <input type='checkbox' value='".str_replace(['.png', '.jpg'], [''], $pt)."'/>
                                                </li>";
                                               
                                        endforeach;
                                    }
                                    ?>
                                        </ul>
                                </div><!--End Image jour-->
                            </div> <!--your content end-->
                        </div> <!--toPopup end-->

                        <div class="loader"></div>
                        <div id="backgroundPopup"></div>
                   </div>    
        <!-- End Banner image-->
         
        <div style="width:800px; margin:auto;">
            <h1 style="color:036; font-size:40px; margin:0 0 20px;">Xuất file devis tự động B2B Tiếng Anh:</h1>
            
            <div class="download-devis"><a id="downloadword" url='<?= DIR ?>ajaxphpdocx/ims_to_word_ajax_b2b.php' href="javascript:void(0)">DOWNLOAD DEVIS</a></div>
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
                        <h2>Devis Template</h2>
                        <li>
                            <p>Secret Indochina</p>
                            <img class="img-devis" style="width: 145px" src='<?= DIR ?>assets/img/devis-ims/secretindochina/Devis_B2b_En_New.png'>
                            <input type="checkbox" value="Devis_B2b_EN_New"/>
                        </li>
                        
                    </ul>
            </div>       
           
            <div style="display: inline-block; width: 268pt;" id="sale-detail-style2">
                <table style="margin: 0;padding: 0;border: none;border-collapse: collapse;width: 268pt;">
                    <tr>
                        <td style="text-align: right;padding-top: 5pt;">
                            <p style="margin: 0; padding: 0; font-size: 12pt; color: #A00D80;font-family:Bell MT;">Votre conseillère Amica Travel</p>
                            <h2 style="margin: 0; padding: 0;font-family:Bell MT; font-size: 14pt;color:black;" id="devis-sale-name">Mlle. <?= $theProduct['createdBy']['fname'] . ' ' . $theProduct['createdBy']['lname'] ?></h2>
                            <p style="margin: 0; padding: 0;font-family:Bell MT; font-size: 12pt;" id="devis-sale-email"><a href="maito:<?= $theProduct['createdBy']['email'] ?>"><?= $theProduct['createdBy']['email'] ?></a></p>
                            <p style="margin: 0; padding: 0;font-family:Bell MT; font-size: 12pt;" id="devis-sale-phone">+84 4 62 73 44 55</p>
                        </td>
                        <td style="text-align: left; padding: 0;margin: 0;width: 130px">
                            <img width="130" height="130" src='<?= $theProduct['createdBy']['image'] ?>'/>
                        </td>
                    </tr>
                </table>
            </div>
            <h2 style="border-bottom:1px solid rgb(229,55,145); color:rgb(229,55,145); font-size: 30px;">Front page | Trang bìa</h2>
            
            
                <p id="image-header"></p>
                <span id='devis-guest' style='font-family:Bell MT;  font-size: 14pt;  font-weight: bold;  color: rgb(229,55,145); line-height: 25pt;'><?= $theProduct['about'] ?></span>
                <h3 id="devis-name" style="font-family:Bell MT;  font-size: 11pt; text-transform: uppercase;  margin: 0;  padding: 0;  font-weight: bold; "><?=preg_replace("/[^a-zA-Z]*devis\d[^a-zA-Z]*/","",$theProduct['title']) ?></h3>
               <br>
                <strong style='font-size: 12pt; font-family:Bell MT'>SPIRIT: </strong><br>
                <span id='devis-intro' style='font-family:Bell MT; font-size: 11pt;'><?= $theProduct['intro']?></span>

          

            <h3 style="font-family:Bell MT;  font-size:11pt;font-weight: bold;">HIGHLIGHTS:</h3>
            <div id='devis-description' style='font-family:Bell MT;  font-size: 9pt; '>
<?
$points = $txt->textileThis($theProduct['points']);
$points = str_replace(['<p style="font-family:Bell MT;">', '<li>'], ['<p style="font-family:Bell MT; font-size:11pt; line-height: 20pt;">','<li style="font-family:Bell MT; font-size:11pt; line-height: 20pt;">'], $points);
$points = str_replace('+', '&#8226;', $points);
echo $points;
?>
            </div>


            <h2 style="font-family: Bell MT;border-bottom:1px solid rgb(229,55,145); color:rgb(229,55,145); font-size: 30px;">PROGRAM IN BRIEF</h2>
            <table border="1" id='devis-table-programe' style='margin: 0; padding: 0; width: 100%;border-collapse: collapse;border-color: rgb(191,191,191);'>
                <thead>
                    <tr style="margin: 2mm 0;">
                <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid; border-color: rgb(191,191,191);width: 10%; padding: 2mm 1mm 2mm 0;"><h3 style="font-family:Bell MT;  font-size:11pt; color:rgb(229,55,145); margin:0;">Day</h3></th>
                <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191); width:  18%;"><h3 style="font-family:Bell MT;  font-size:11pt; color:rgb(229,55,145); margin:0; padding-right: 1mm;">Date</h3></th>
                <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);width:  39.1%;"><h3 style="font-family:Bell MT;  font-size:11pt; color:rgb(229,55,145); margin:0;padding-right: 1mm;">Intinerary</h3></th>
                <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);width: 22.6%;"><h3 style="font-family:Bell MT;  font-size:11pt; color:rgb(229,55,145); margin:0;padding-right: 1mm;">Accompaniment</h3></th>
                <th style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);width: 17%;"><h3 style="font-family:Bell MT;  font-size:11pt; color:rgb(229,55,145); margin:0;">Meals</h3></th>
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
                           // $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                            ?>
                            <tr>
                                <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT;  font-size:11pt;padding: 5pt 2pt;">Day <?= $cnt ?></td>
                                <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT;  font-size:11pt;padding: 5pt 2pt;"><?= $ngay ?></td>
                                <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT;  font-size:11pt;padding: 5pt 2pt;"><?= $ng['name'] ?></td>
                                <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT;  font-size:11pt;padding: 5pt 2pt;"><?= $ng['guides'] ?></td>
                                <td style="text-align: center;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);font-family:Bell MT;  font-size:11pt;padding: 5pt 2pt;">
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
            <h2 style="border-bottom:1px solid rgb(229,55,145); color:rgb(229,55,145); font-size: 30px;">PROGRAM IN DETAIL</h2>
            <div id='devis-detail' style="font-size: 12pt;">


                        <?
                        $cnt = 0;
                        foreach ($dayIdList as $di) {
                            foreach ($theProduct['days'] as $ng) {
                                if ($ng['id'] == $di) {
                                    $cnt ++;
                                    $ngay = date('D d|m|Y', strtotime($theProduct['day_from'] . ' + ' . ($cnt - 1) . 'days'));
                                    $ngay_en = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                                    $ngay_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
                                  //  $ngay = str_replace($ngay_en, $ngay_fr, $ngay);
                                    $ngay = str_replace('|', '<span style="">|</span>', $ngay);
                                    ?>
                            <h4 style="font-size:11pt;font-family:Bell MT;font-variant: small-caps;">Day <?= $cnt ?> - <?= $ngay ?><span style=""> | <?= $ng['name'] ?> (<?= $ng['meals'] ?>)</span></h4>
                            <div style="font-family:Bell MT;  font-size:11pt;">
            <?= $ng['transport'] == '' ? '' : '<p style="font-family:Bell MT;  font-size:11pt;">' . $ng['transport'] . '</p>' ?>
                            <?
                            $txxt = $txt->textileThis($ng['body']);
                            $txxt = str_replace('<p>', '<p style="font-family:Bell MT;  font-size:11pt;line-height: 20pt; text-align: justify;">', $txxt);
                            $txxt = str_replace('<li>', '<li style="font-family:Bell MT;  font-size:11pt;">', $txxt);
                            $txxt = str_replace('<strong>', '<strong style="font-family:Bell MT;  font-size:11pt;">', $txxt);
                            $txxt = str_replace('<em>', '<em style="font-family:Bell MT;  font-size:11pt;">', $txxt);
                            $txxt = str_replace(['<span class="caps">', '</span>'], ['', ''], $txxt);
                            echo $txxt;
                            ?>
                            </div>
                            
                            <div class="jour jour-<?=$cnt?>"><img alt="" style="width: 100%;" WIDTH="1080" height="340" src="" ALIGN="left"/> <div class="remove"></div></div>
                            
                            <?
                        }
                    }
                }
                ?>
            </div>
            <h2 style="border-bottom:1px solid rgb(229,55,145); color:rgb(229,55,145); font-size: 30px;">ACCOMMODATIONS SELECTION </h2>
            <div id='devis-table-tarif'>
                <table border="1" class="simple" style="width: 100%; margin: 0; padding: 0;border-collapse: collapse; border-color: rgb(191,191,191);">
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
                            echo '<p style="font-family:Bell MT; font-size:11pt;color:rgb(229,55,145);"><strong>' . trim(substr($theProductp, 7)) . '</strong></p>';
                            echo '<tr style="height: 10mm;">
                <th style="text-align:center;padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid rgb(191,191,191); border-right: 1px solid rgb(191,191,191);width: 17.8%;"><h3 style="background: none;font-family:Bell MT; color:rgb(229,55,145); font-size:11pt;">Destinations</h3></th>
                <th style="text-align:center;padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid rgb(191,191,191); border-right: 1px solid rgb(191,191,191); width: 20.5%;"><h3 style="background: none;font-family:Bell MT; color:rgb(229,55,145); font-size:11pt;">Hotel/Resort & Website</h3></th>
                <th style="text-align:center;padding-left: 2mm;padding-right: 1mm;border-bottom: 1px solid rgb(191,191,191); border-right: 1px solid rgb(191,191,191); width: 22%;"><h3 style="background: none;font-family:Bell MT; color:rgb(229,55,145); font-size:11pt;">Nights Spent</h3></th >
                <th style="text-align:center;padding-left: 2mm;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); width: 39.6%;font-size:11pt;"><h3 style="background: none;font-family:Bell MT; color:rgb(229,55,145); font-size:11pt;">Room Category</h3></th>
                </tr>';
                        }
                        if (substr($theProductp, 0, 2) == '+ ') {
                            $line = trim(substr($theProductp, 2));
                            $line = explode(':', $line);
                            for ($i = 0; $i < 5; $i ++) if (!isset($line[$i])) $line[$i] = '';
                            echo '<tr>
<td style="text-align:center;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); font-family:Bell MT;  font-size:11pt;padding: 5pt 2mm;"><span style="background-color: none;">'.$line[0].'</span></td>
<td style="text-align:center;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); font-family:Bell MT;  font-size:11pt; padding: 5pt 2mm;"><span style="background-color: none;">'.(trim($line[3]) != '' ? Html::a( trim($line[1]), 'http://'.trim($line[3]), ['class' => 'profile-link','style'=>'color: black; text-decoration: none;']) : trim($line[1])) . '</span></td>
<td style="text-align:center;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); font-family:Bell MT;  font-size:11pt;  padding: 5pt 2mm;"><span style="background-color: none;">' . $line[4] . '</span></td>
<td style="text-align:center;border-bottom: 1px solid rgb(191,191,191);border-right: 1px solid rgb(191,191,191); font-family:Bell MT;  font-size:11pt; padding: 5pt 2mm;" class="a-href"><span style="background-color: none;">' . trim($line[2]) . '</span></td></tr>';
                        }

                        if (substr($theProductp, 0, 2) == '- ') {
                            $line = trim(substr($theProductp, 2));
                            $line = explode(':', $line);
                            for ($i = 0; $i < 3; $i ++) if (!isset($line[$i])) $line[$i] = '';
                            $line[1] = trim($line[1]);
                            if (($count == count($theProductpx)) || in_array($count, $last)) {
                                echo '<tr><td style="font-family:Bell MT;  font-size:11pt;  padding: 2mm 1mm 2mm 2mm;border-bottom: 1px solid rgb(191,191,191);border-right:1px solid rgb(191,191,191);" colspan="3" class="ta-r"><span style="background-color: none;">' . $line[0] . '</span></td><td style="text-align: center;border-bottom: 1px solid rgb(191,191,191);border-right:1px solid rgb(191,191,191);"><h3 style="font-size:11pt;font-family:Bell MT; background-color: none;">' . number_format($line[1]) . ' ' . str_replace('EUR', '&euro;', $theProduct['price_unit']) . '</h3></td></tr>';
                            } else {
                                echo '<tr><td style="border-bottom: 1px solid rgb(191,191,191);border-right:1px solid rgb(191,191,191);font-family:Bell MT;  font-size:11pt;  padding: 2mm 1mm 2mm 2mm;" colspan="3" class="ta-r"><span style="background-color: none;">' . $line[0] . '</span></td><td style="text-align: center;border-bottom: 1px solid rgb(191,191,191);border-right:1px solid rgb(191,191,191);"><h3 style="font-size:11pt;font-family:Bell MT; background-color: none;">' . number_format($line[1]) . ' ' . str_replace('EUR', '&euro;', $theProduct['price_unit']) . '</h3></td></tr>';
                            }
                        }
                    }
                    ?>
                </table>

            </div>

            <h2 style="border-bottom:1px solid rgb(229,55,145); color:rgb(229,55,145); font-size: 30px;">TOUR RATES </h2>
            <div id='devis-condition'>
                <p style="font-family:Bell MT; font-size:11pt;">
                    Valid until the 31th December 2016. Rates are <b>nett</b>, in <b>USD/pax</b>, exclude bank transfer fee. Double or Twin shared basis.
                </p>
                <table border="1" cellspacing="0" cellpadding="0" width="" class="table-price" style="width: 100%; margin: 0;border-collapse: collapse; margin: 0; padding: 0; border-color: rgb(191,191,191);">
                    <tr>
                        <td style="background-color: rgb(200,200,200); color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">Group size</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">2<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">3<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">4<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">5<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">6<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">7<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">8<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">9<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">10<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">11<br>pax</span></strong></td>
                        <td style="background-color: rgb(200,200,200);color: rgb(229,55,145); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><strong><span style="background-color: none;">12<br>pax</span></strong></td>
                    </tr>
                    <tr>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);">Price/pax</td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                        <td style="font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"></td>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(240,240,240); font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><span style="background-color: none;">SGL surcharge</span></td>
                        <td style="background-color: rgb(240,240,240);font-family: Bell MT, candara; font-size: 11pt; text-align: center; line-height: 25pt; vertical-align: middle;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);" colspan="11"></td>
                    </tr>
                </table>
                <br>
                <table border="1" cellspacing="0" cellpadding="0" width="" style="width: 100%; margin: 0;border-collapse: collapse; margin: 0; padding: 0;border-color: rgb(191,191,191);">
                    <tr>
                       
<?
// $theProduct['conditions'] = str_replace('', '', $theProduct['conditions']);
?>
<?
$condText = $txt->textileThis($theProduct['conditions']);
//var_dump($condText);exit;
$condText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend', 'Ce prix ne comprend pas', '</ul>'], ['<td style="vertical-align: top; width: 50%; padding-bottom: 20px;border-bottom: 1px solid;border-right: 1px solid;border-color: rgb(191,191,191);"><h3 style="font-size:11pt; color:black;font-family:Bell MT; text-align:center;">', '<ul style="margin:0 0 0 0;">', '<p style="font-family:Bell MT;  font-size:11pt; margin:0; line-height: 20pt;">', '<li style="font-family:Bell MT;  font-size:11pt; line-height: 20pt;">', 'INCLUSIONS', 'EXCLUSIONS','</ul></td>'], $condText);
//var_dump($condText);exit;
echo $condText;

?>
                         
                    </tr>
                </table>    
                
            </div>
            <h2 style="border-bottom:1px solid rgb(229,55,145); color:rgb(229,55,145); font-size: 30px;">NOTES</h2>
            <div id='devis-others'>
                
                <?
                    $otherText = $txt->textileThis($theProduct['others']);
                    //var_dump($otherText);exit;
                    $otherText = str_replace(['<h3>', '<ul>', '<p>', '<li>', 'Ce prix comprend', 'Ce prix ne comprend pas'], ['<h3 style="font-size:11pt; color:rgb(229,55,145);font-family:Bell MT;">', '<ul style="margin:0; padding:0;">', '<p style="font-family:Bell MT;  font-size:11pt; margin:0; line-height: 20pt;">', '<li style="font-family:Bell MT;  font-size:11pt; line-height: 20pt;">', 'INCLUSIONS', 'EXCLUSIONS'], $otherText);
                    echo $otherText;
                ?>
            </div>
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
    //.option-img-banner-2.active{display: block;}
    
    //css-banner-anh-ngay-tour-devis
    
     #toPopup #popup_content .test ul{
        margin: 0;
        padding: 0;
    }
    .test ul h2{
        margin: 0;
    }
    .test ul li{
        display: inline-block;
        list-style: none outside none;
        text-align: center;
        width: 185px;
        cursor: pointer;
    }
    .test ul li img{
        width: 185px !important;
        height: 90px;
    }
    .test ul li:not(:last-of-type){
       // border-right: 1px dotted #000000;
        margin-right: 5px;
    }
    .test ul li input[type="checkbox"] {
        display: block;
        margin: 0 auto;
    }
    .test ul li img{
        cursor: pointer;
    }

    .test ul li:hover{
        background: #E3F2E1;
    }
    .jour{display: none; position: relative;}
    .jour .remove{
        color: red;
        cursor: pointer;
        font-size: 50px;
        height: 40px;
        position: absolute;
        right: 10px;
        text-align: center;
        top: 10px;
        width: 40px;
        display: none;
        background: url('<?='https://www.amica-travel.com/'?>assets/img/xx.png') center center no-repeat;
        background-size: 100% auto;
        border-radius: 50%; 
    }
    .jour:hover .remove{
        display: block;
    }
   // .test{ display: none;}
    .download-devis{
        position: fixed;
        top: 50%;
        right: 20px;
        width: 120px;
        height: 50px;
    }
    .download-devis #downloadword{
        text-align: center;
        display: block;
        font-size: 20px;
        font-weight: bold;
    }
    //.option-img-banner-2.active{display: block;}
    #backgroundPopup {
    z-index:1;
    position: fixed;
    display:none;
    height:100%;
    width:100%;
    background:#000000;
    top:0px;
    left:0px;
}
.topopup {
    display: block;
    font-size: 20px;
    position: fixed;
    right: 20px;
    text-align: center;
    top: 40%;
    width: 120px;
}
.add-image-tour{
    top: 30%;
}
#toPopup {
    font-family: arial,sans-serif;
    background: none repeat scroll 0 0 #FFFFFF;
    border: 10px solid #ccc;
    border-radius: 3px 3px 3px 3px;
    color: #333333;
    display: none;
    font-size: 14px;
    left: 30%;
    //margin-left: -410px;
    position: fixed;
    top: 10%;
    width: 820px;
    z-index: 2;
}
div.loader {
    background: url("<?='https://www.amica-travel.com/'?>assets/img/loading.gif") no-repeat scroll 0 0 transparent;
    height: 32px;
    width: 32px;
    display: none;
    z-index: 9999;
    top: 50%;
    left: 50%;
    position: fixed;
    margin-left: -10px;
}
div.close {
    background: url("<?='https://www.amica-travel.com/'?>assets/img/closebox.png") no-repeat scroll -4px -3px transparent;
    cursor: pointer;
    height: 30px;
    position: absolute;
    right: -27px;
    top: -24px;
    width: 30px;
}
span.ecs_tooltip {
    background: none repeat scroll 0 0 #000000;
    border-radius: 2px 2px 2px 2px;
    color: #FFFFFF;
    display: none;
    font-size: 11px;
    height: 16px;
    opacity: 0.7;
    padding: 4px 3px 2px 5px;
    position: absolute;
    right: -62px;
    text-align: center;
    top: -51px;
    width: 93px;
}
span.arrow {
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 7px solid #000000;
    display: block;
    height: 1px;
    left: 40px;
    position: relative;
    top: 3px;
    width: 1px;
}
div#popup_content {
    margin: 4px 7px;
    /* remove this comment if you want scroll bar */
    overflow-y:auto;
    height:600px
    
}
/*.simple tr th{
    background: rgb(200, 200, 200);
}
.simple tr:nth-of-type(2n+1) td{
    background: rgb(240, 240, 240);
}*/
    
</style>
<style>
.image-jour ul {
    margin: 0;
    padding: 0;
}
.image-jour ul li:not(:last-of-type) {
    margin-right: 5px;
}
.image-jour ul li {
    cursor: pointer;
    display: inline-block;
    list-style: outside none none;
    text-align: center;
    width: 185px;
}
.image-jour ul li img {
    cursor: pointer;
}
.image-jour ul li img {
    height: 90px;
    width: 185px !important;
}
.image-jour ul li input[type="checkbox"] {
    display: block;
    margin: 0 auto;
}
.image-jour ul li:hover {
    background: #e3f2e1 none repeat scroll 0 0;
}
</style>
<script src="//www.amica-travel.com/assets/js/jquery-1.11.2.min.js"></script>
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
        $('.image-jour ul li').click(function() {
            $('.image-jour ul li input[type=checkbox]').prop('checked', false);
            $(this).find('input[type=checkbox]').prop('checked', true);
            var jourdate = $('#jour-test').val();
            var img = $('.image-jour ul li input[type=checkbox]:checked').val();
            $('.'+jourdate).show();
            $('.' + jourdate + ' img').attr('src','<?= DIR ?>upload/image_devis_secretindochina/'+img+'.jpg');
            alert('Thêm ảnh banner thành công');
           // var titlejour = $('.'+jourdate).parent().children('.title-'+jourdate).text();
           var titlejour = $('#jour-test option:selected').text();
            var node = document.createElement("P");
            var textnode = document.createTextNode(titlejour + ' // image:'+ img );
            node.appendChild(textnode);
            $(node).addClass(jourdate);
            
            document.getElementById("myList").appendChild(node);
            $(node).append('<span class="del" style="color: red; float: right; margin-right: 20px; cursor: pointer;">Delete</span>');
        });

            $(document).on('click', '#myList .del', function() {
             $(this).parent().remove();
            $('.image-jour ul li input[type=checkbox]').prop('checked', false);
             var jour = $(this).parent().attr('class');
            $('.'+jour+' img').attr('src','').parent().hide();
            });
            
         $('.remove').click(function() {
             $('.image-jour ul li input[type=checkbox]').prop('checked', false);
             var jour = $(this).parent().attr('class').split(" ")[1];
             $(this).parent().hide();
             $(this).parent().children().attr('src','');
             $('#myList .'+jour).remove();
         });
         $('#jour-test').change(function() {
              $('.image-jour ul li input[type=checkbox]').prop('checked', false);
         });
        

//         $('.remove').click(function() {
//             $('.test ul li input[type=checkbox]').prop('checked', false);
//             $(this).parent().hide();
//             $(this).parent().children().attr('src','');
//         });
//      $('#jour-test').change(function() {
//              $('.test ul li input[type=checkbox]').prop('checked', false);
//         });
         
          $("#option-image-jour").click(function(){
                if($("#option-image-jour").is(':checked')){
                   $('.test').show();
               }
                if(!$("#option-image-jour").is(':checked')){
                   $('.test').hide();
               }
          });
        
        
        $('.add-image-tour').click(function() {
            $('#popup_content .test').hide();
            $('#popup_content .image-jour').show();
        });
         $('.add-banner-header').click(function() {
            $('#popup_content .image-jour').hide();
            $('#popup_content .test').show();
        });
    });
    //popup-option-add-image-date-tour
    jQuery(function($) {

          $('.test a').click(function() {
            $('#image-header').html('<img data-val="'+$(this).data('val')+'" width="850" src="'+$(this).data('img')+'"/>');
            disablePopup();
        });
     
    $("a.topopup").click(function() {
            loading(); // loading
            setTimeout(function(){ // then show popup, deley in .5 second
                loadPopup(); // function show popup
            }, 500); // .5 second
    return false;
    });
     
    /* event for close the popup */
    $("div.close").hover(
                    function() {
                        $('span.ecs_tooltip').show();
                    },
                    function () {
                        $('span.ecs_tooltip').hide();
                      }
                );
     
    $("div.close").click(function() {
        disablePopup();  // function close pop up
    });
     
    $(this).keyup(function(event) {
        if (event.which == 27) { // 27 is 'Ecs' in the keyboard
            disablePopup();  // function close pop up
        }      
    });
     
    $("div#backgroundPopup").click(function() {
        disablePopup();  // function close pop up
    });
     
    $('a.livebox').click(function() {
        alert('Hello World!');
    return false;
    });
     
 
     /************** start: functions. **************/
    function loading() {
        $("div.loader").show();  
    }
    function closeloading() {
        $("div.loader").fadeOut('normal');  
    }
     
    var popupStatus = 0; // set value
     
    function loadPopup() {
        if(popupStatus == 0) { // if value is 0, show popup
            closeloading(); // fadeout loading
            $("#toPopup").fadeIn(0500); // fadein popup div
            $("#backgroundPopup").css("opacity", "0.7"); // css opacity, supports IE7, IE8
            $("#backgroundPopup").fadeIn(0001);
            popupStatus = 1; // and set value to 1
        }    
    }
         
     
    function disablePopup() {
        if(popupStatus == 1) { // if value is 1, close popup
            $("#toPopup").fadeOut("normal");  
            $("#backgroundPopup").fadeOut("normal");  
            popupStatus = 0;  // and set value to 0
        }
    }
    /************** end: functions. **************/
}); // jQuery End
    
    //end
    $('#downloadword').click(function() {
        var file_name = '<?= SEG2.'-'.strtotime("now") ?>';
        var template = $('.option-devis ul li input[type=checkbox]:checked').val();
        var url = $(this).attr('url');
        var image_header = $('#image-header img').data('val');
        if (!template && !$('.option-devis ul li input[type=checkbox]').is(':checked')) {
            alert('Hãy chọn mẫu devis');
            return false;
        }
        if(!image_header){
            alert('Hãy chọn ảnh banner trước!');
            return false;
        }
        
        var devis_name = $('#devis-name').clone().wrap('<p>').parent().html();
        var devis_guest = $('#devis-guest').clone().wrap('<p>').parent().html();
        var devis_intro = $('#devis-intro').clone().wrap('<p>').parent().html();

        var devis_description = $('#devis-description').clone().wrap('<p>').parent().html();
        
        var devis_table_programe = $('#devis-table-programe').clone().wrap('<p>').parent().html();
        var devis_detail = $('#devis-detail').clone().wrap('<p>').parent().html();
        var devis_table_tarif = $('#devis-table-tarif').clone().wrap('<p>').parent().html();
        var devis_condition = $('#devis-condition').clone().wrap('<p>').parent().html();
        var devis_others = $('#devis-others').clone().wrap('<p>').parent().html();
        $('#loading').show();
        $.ajax({
            url: url,
            type: 'post',
            data: {
                file_name: file_name,
                template: template,
                image_header: image_header,
                devis_name: devis_name,
                devis_guest: devis_guest,
                devis_intro: devis_intro,
                devis_description: devis_description,
                devis_table_programe: devis_table_programe,
                devis_detail: devis_detail,
                devis_table_tarif: devis_table_tarif,
                devis_condition: devis_condition,
                devis_others: devis_others,
            },
            async:true,
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
<script>
    //$("#devis-condition table ul:nth-of-type(1)").after("</td> <td>");
  //  $("<td>").insertAfter("#devis-condition table ul:nth-of-type(1)");
  $('.simple tr th').css("background-color","rgb(200,200,200)");
  $('.simple tr th h3').css("background-color","none");
  $('.simple tr th h3').css("margin","0");
  $('.simple tr th h3').css("padding","5pt 0");
  $('.simple tr:nth-of-type(2n+1) td').css("background-color","rgb(240,240,240)");
  //$('.simple tr:nth-of-type(n) td span').css("background-color","none");
</script>    