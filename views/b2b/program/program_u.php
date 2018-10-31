<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
if (file_exists('_program_inc.php')) {
    include('_program_inc.php');
}


$productTypeList = [
    'private'=>'Normal tour',
    'b2b-prod'=>'Product tour',
];

Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Products', 'b2b/programs'],
];

if ($theProgram->isNewRecord) {
    Yii::$app->params['page_title'] = 'New tour program';
    Yii::$app->params['page_breadcrumbs'][] = ['New', 'b2b/programs/c'];
} else {
    Yii::$app->params['page_title'] = 'Edit tour program: '.$theProgram['title'];
    Yii::$app->params['page_breadcrumbs'][] = ['View', 'b2b/programs/r/'.$theProgram['id']];
    Yii::$app->params['page_breadcrumbs'][] = ['Edit', 'b2b/programs/u/'.$theProgram['id']];
}

// cac file anh banner
$files = [];
if (file_exists(Yii::getAlias('@webroot').'/upload/devis-banners/')) {
    $files = scandir(Yii::getAlias('@webroot').'/upload/devis-banners/', 1) ;
    asort($files);
}

$fileNameList = [];
foreach ($files as $k=>$v) {
    if ($v != '.' && $v != '..') {
        $fileNameList[] = ['name'=>$v];
    }
}

$conds = <<<TXT
h3. Ce prix comprend :

* Hébergement pour tout le parcours dans les hôtels listés au programme ou, en cas d’indisponibilité de ceux-ci, dans des hôtels équivalents. 
* Tous les déplacements selon le programme en véhicule privatif.
* Les repas comme mentionnés dans le programme (B = Petit Déjeuner ; L = Déjeuner ; D = Dîner).
* Guides accompagnateurs francophones pour tout le circuit.
* Droits d'entrée des sites à visiter.
* Les billets de volss domestiques : Hué - Hanoi, Buon Me Thuoc – Da Nang par Vietnam Airlines (la plus grande compagnie aérienne du Vietnam)
* Un bateau collectif avec une cabine privée à deux dans la baie d’Halong, 
* Les frais de dossier
* Les taxes
* Tous les services logistiques nécessaires pour l'organisation du programme.

h3. Ce prix ne comprend pas :

* Vols et taxes d'aéroport internationaux depuis/ vers votre pays.
* Pourboire, boissons, téléphone et tout ce qui n’est pas clairement mentionné dans la rubrique « Le prix comprend ». (Pour le pourboire pour guide et chauffeur, à prévoir environ de 3 à 4 Euros par jour par personne, si vous êtes contents de leurs services).
TXT;

$more = <<<TXT
h3. Les plus d’Amica Travel 

* Un petit guide culturel (de 80 pages) du Vietnam mis à la disposition de chaque voyageur dès l’arrivée
* Cadeau de bienvenue
* Boissons fraiches durant les transferts routiers
* Suivi 24h/24 du voyage, depuis le bureau de Hanoi, par un agent clientèle dédié
* Présence téléphonique en France

h3. Conditions de paiement

* Si vous souhaitez payez en Euros, la somme à payer sera reconvertie en Euros selon le taux de change de référence  publié par la Banque Centrale Européenne à la date la plus proche de celle du paiement. Ce taux sera consulté sur le Site Internet de cette Banque, en cliquant sur le lien : "http://www.ecb.int/stats/exchange/eurofxref/html/index.en.html":http://www.ecb.int/stats/exchange/eurofxref/html/index.en.html
* Un acompte de 25% du prix total est à verser par virement bancaire ou par carte bancaire via Internet dès la réservation
* Le solde de 75% est à payer au commencement du voyage, en liquide ou par carte bancaire
* Les frais bancaires liés au paiement sont à la charge du client

h3. Conditions d’annulation

En cas d’annulation du voyage, le client doit payer des pénalités qui correspondent:

* à 3% du prix total du voyage, si son annulation parvient à Amica Travel dans un délai égal ou supérieur à 45 jours avant le commencement du voyage ;
* à 5% du prix total du voyage, si son annulation parvient à Amica Travel de 31 à 45 jours avant le commencement du voyage ;
* à 10% du prix total du voyage, si son annulation parvient à Amica Travel de 15 à 30 jours avant le commencement du voyage ;
* à 15% du prix total du voyage, si son annulation parvient à Amica Travel de 7 à 14 jours avant le commencement du voyage ;
* à 20% du prix total du voyage, si son annulation parvient à Amica Travel de 72 heures à 6 jours avant le commencement du voyage ;
* à 25 % du prix total du voyage, si son annulation parvient à Amica Travel moins de 72 heures avant le commencement du voyage.
TXT;

$form = ActiveForm::begin();
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <? if ($theProgram->isNewRecord) { ?>
            <div class="alert alert-info">
                <i class="fa fa-fw fa-info-circle text-info"></i>
                You can also create a new itinerary by copy an existing one. Just <a class="alert-link" href="/ct">view all itineraries</a>, select one you want to copy and then select the <kbd>Copy as new</kbd> menu item.
            </div>
            <? } ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theProgram, 'title') ?></div>
                <div class="col-md-6"><?= $form->field($theProgram, 'offer_type')->dropdownList($productTypeList)->label('Program type') ?></div>
            </div>
            <div class="row">
                <div class="col-md-12"><?= $form->field($theProgram, 'about')->label('Description') ?></div>
            </div>
            <?= $form->field($theProgram, 'tags') ?>
            <?= $form->field($theProgram, 'esprit')->textArea(['rows'=>3]) ?>
            <?= $form->field($theProgram, 'points')->textArea(['rows'=>3]) ?>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theProgram, 'language')->dropdownList(isset($languageList) ? $languageList: [] , ['prompt'=>'- Select -'])->label('Language') ?></div>
                <div class="col-md-3"><?= $form->field($theProgram, 'day_count')->label('Days') ?></div>
                <div class="col-md-3"><?= $form->field($theProgram, 'pax') ?></div>
                <div class="col-md-3" class="df"><?= $form->field($theProgram, 'day_from')->label('Start date') ?></div>
            </div>
            <?= $form->field($theProgram, 'intro')->textArea(['rows'=>3]) ?>
            <p><strong>PRICES AND PROMOTIONS</strong></p>
            <?= $form->field($theProgram, 'prices')->textArea(['rows'=>15, 'style'=>'font-family:Courier New, mono; font-size:14px; color:#000;']) ?>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theProgram, 'price') ?></div>
                <div class="col-md-3"><?= $form->field($theProgram, 'price_unit')->dropdownList(['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND']) ?></div>
                <div class="col-md-3"><?= $form->field($theProgram, 'price_for')->dropdownList(['personne'=>'personne', 'groupe'=>'groupe']) ?></div>
                <div class="col-md-3"><?= $form->field($theProgram, 'price_until') ?></div>
            </div>
            <?= $form->field($theProgram, 'promo')->textArea(['rows'=>10]) ?>

            <?= $form->field($theProgram, 'conditions')->textArea(['rows'=>10]) ?>
            <?= $form->field($theProgram, 'others')->textArea(['rows'=>10]) ?>

            <p><strong>NOTE (FOR AMICA ONLY)</strong></p>
            <?= $form->field($theProgram, 'summary')->textArea(['rows'=>3]) ?>
            <div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
            
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $form->field($theProgram, 'image')->dropdownList(ArrayHelper::map($fileNameList, 'name', 'name'), ['id'=>'header-image', 'prompt'=>'- Select -']) ?>
            <div id="image-preview" class="mb-1em">
                <? if ($theProgram['image'] != '') { ?>
                <img class="img-responsive thumbnail" src="<?= DIR ?>upload/devis-banners/small/<?= $theProgram['image'] ?>" />
                <? } ?>
            </div>
            <p><strong>CHỈ DẪN</strong></p>
            <p>Các trường miêu tả text dài có thể đánh dấu chữ đậm nghiêng như sau:</p>
            <p>*đậm* --> <b>đậm</b>
            <br />_nghiêng_ --> <i>nghiêng</i>
            <br />* List item --> &middot; List item
            </p>

            <p>Thông tin giá nhập vào cần tuân theo dạng thức cố định, cách nhau bằng các dấu hai chấm. Mỗi thông tin viết trên một dòng.</p>
            <p><code>
            OPTION: Giải thích về option<br />
            + Ville : Hotel : Chambre : www.abcd.com<br />
            + Ville : Hotel : Chambre : www.abcd.com<br />
            - Prix / personne en chambre double : 1234<br />
            - Prix / personne en chambre individuelle : 2345<br />
            OPTION: Giải thích về option<br />
            + Ville : Hotel : Chambre : www.xyzt.com<br />
            + Ville : Hotel : Chambre : www.xyzt.com<br />
            - Prix / personne en chambre double : 5678<br />
            - Prix / personne en chambre individuelle : 9012<br />
            </code></p>
            <p>Chọn giá đại diện và đơn vị tính giá ở bên cạnh</p>

            <? if (isset($theDays)) { ?>
            <p><strong>ITINERARY</strong></p>
            <ol>
                <? foreach ($theDays as $day) { ?>
                <li><?= $day['name'] ?> (<?= $day['meals'] ?>)</li>
                <? } ?>
            </ol>
            <? } ?>
        </div>
    </div>
</div>
<?
ActiveForm::end();
$js = <<<TXT
$('#header-image').change(function(){
    var image = $(this).val();
    if (image == '') {
        $('#image-preview').html('<img class="img-responsive thumbnail" src="http://placehold.it/300x100" />');
    } else {
        $('#image-preview').html('<img class="img-responsive thumbnail" src="/upload/devis-banners/small/'+image+'" />');
    }
});
$('#product-offer_type').change(function(){
    if ($(this).val() == 'b2b-prod') {
        $('#product-language').closest('.col-md-3').hide();
        $('#product-day_from').closest('.col-md-3').hide();
    }
});
$('#product-day_from, #product-price_until').datepicker({
    format: "yyyy-mm-dd",
    weekStart: 1,
    todayBtn: "linked",
    clearBtn: true
});
TXT;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);
