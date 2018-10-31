<?php
// $webRoot = '/var/www/www.amica-travel.com';
$webRoot = 'D:/wamp/www/appbasic';

require_once $webRoot.'/helpers/phpdocx/classes/CreateDocx.inc';
// require_once($webRoot.'/helpers/phpdocx/classes/CreateDocx.inc');
require_once $webRoot.'/helpers/phpdocx/classes/DocxUtilities.inc';

$data = array(
	'file_name' => $_POST['file_name'],
    // 'template' => $_POST['template'],
    'template' => '01-devis-demo', 
    'img_banner_2' => $_POST['img_banner_2'],
    'devis_name' => $_POST['devis_name'],
    'devis_number' => $_POST['devis_number'],
    'devis_guest' => '<span style="font-family:Calibri;color:black;font-size: 11pt;font-weight:bold;">'.$_POST['devis_guest'].'</span>',
    'devis_date' => '<span style="font-family:Calibri;color:black;font-size: 11pt;font-weight:bold;">'.$_POST['devis_date'].'</span>',
	'devis_prix' => $_POST['devis_prix'],
    'devis_description' => $_POST['devis_description'],
    'sale_detail' => $_POST['sale_detail'],

    'devis_table_programe' => $_POST['devis_table_programe'],
    'devis_detail' => $_POST['devis_detail'],
	'tableau_devis' => $_POST['tableau_devis'],
	'img_banner_tableau' => $_POST['img_banner_tableau'],
    'devis_table_tarif' => $_POST['devis_table_tarif'],
    'devis_promotion' => $_POST['devis_promotion'],
    'devis_condition' => $_POST['devis_condition']
);
$color_text = 'white';
if($_POST['template']=='B2B-FR') {
	$data['devis_guest'] = '<p style="font-family:Candara;font-size: 11pt; color: black;"><b>Type du voyage:</b> en individuel</p><p style="font-family:Candara;font-size: 11pt; color: black;"><b>Devis personnalisé pour:</b> '.$_POST['devis_guest'].'</p>';
	$data['devis_date'] = '<p style="font-family:Candara;font-size: 11pt; color: black;"><b>Durée & Date du voyage:</b> '.$_POST['devis_date'].'</p>';
}
if (!file_exists($webRoot.'/upload/ideas/tours/output/devis-ims')) {
    mkdir($webRoot.'/upload/ideas/tours/output/devis-ims', 0777, true);
}
// if (!file_exists($webRoot.'/upload/ideas/tours/output/devis-ims')) {
//    mkdir($webRoot.'/upload/ideas/tours/output/devis-ims', 0777, true);
// }
$color = '#8A006C';
$color_devis_name= '#FFFFFF';
if(strpos('devis_base_02vietnam_immersion|devis_base_04laos_classique|devis_base_05laos_aventure|devis_base_06cambodge_classique|devis_base_07cambodge_aventure|devis_base_08cambodge_balneaire|devis_base_010_multipays_classique|devis_base_011_multipays_aventure|devis_base_13_thailande_classique|devis_base_05vietnam_mekong|vac_devis_base_01au-dela-de-temples_angkor|vac_devis_base_01cambodge_autrement|vac_devis_base_01cambodge_aventure|vac_devis_base_01cambodge_lindochine|vac_devis_base_01temples_angkor|vac_devis_base_02au-dela-de-temples_angkor|vac_devis_base_02cambodge_aventure|vac_devis_base_02temples_angkor|devis_base_01birmanie_classique|devis_base_02birmanie_trekking',$data['template'])!==false){
	$color= '#FFFFFF';
}
if(strpos($data['template'], 'val')!==false){
	$color= '#FFFFFF';
	// $data['devis_table_programe'] = str_replace(['#a00d80','#8A006C','#A00D80','rgb(172,0,132)'],'#cb6402', $data['devis_table_programe']);
	// $data['devis_detail'] = str_replace(['#a00d80','#8A006C','#A00D80','rgb(172,0,132)'],'#cb6402', $data['devis_detail']);
	// $data['devis_table_tarif'] = str_replace(['#a00d80','#8A006C','#A00D80','rgb(172,0,132)','#AC0086'],'#cb6402', $data['devis_table_tarif']);
	// $data['devis_condition'] = str_replace(['#a00d80','#8A006C','#A00D80','rgb(172,0,132)'],'#cb6402', $data['devis_condition']);
	// $data['devis_prix'] = str_replace(['#a00d80','#8A006C','#A00D80','rgb(172,0,132)'],'#cb6402', $data['devis_prix']);
	// $data['sale_detail'] = str_replace(['#a00d80','#8A006C','#A00D80','rgb(172,0,132)'],'#cb6402', $data['sale_detail']);
}
$docx = new CreateDocx();
$setting = array(
    'zoom' => 100
);
$docx->enableCompatibilityMode();
$docx->addTemplate($webRoot.'/helpers/template/devis/' . $data['template'] . '.docx');
//$docx->addTemplate($webRoot.'/helpers/template/devis/' . $data['template'] . '.docx');
$docx->replaceTemplateVariableByHTML('devis_name', 'block', "<span style='color: #fff;font-size:20pt;margin:0; padding: 0;'>" .  $data['devis_name'] . "</span>");

$docx->replaceTemplateVariableByHTML('devis_number', 'inline', "<span style='font-family:Candara; font-size: 14pt;font-weight:bold;color: #8A006C;'>".$data['devis_number']."</span>");
$docx->replaceTemplateVariableByHTML('devis_guest', 'block', $data['devis_guest']);
$docx->replaceTemplateVariableByHTML('devis_date', 'block', $data['devis_date']);
$docx->replaceTemplateVariableByHTML('devis_prix', 'inline', $data['devis_prix']);
$docx->replaceTemplateVariableByHTML('devis_description', 'block', $data['devis_description']);
$docx->replaceTemplateVariableByHTML('sale_detail', 'block', $data['sale_detail']);

if($data['img_banner_2']!='no'){
//$docx->addTemplateImage('image-2',$webRoot.'/helpers/banner_2_devis/'.$data['img_banner_2'].'.jpg');
$docx->addTemplateImage('image-2',$webRoot.'/upload/banner_2_devis/'.$data['img_banner_2'].'.jpg');
}
if($data['img_banner_tableau']!='no'){
$docx->addTemplateImage('image_devis_table_pointfort',$webRoot.'/upload/banner_2_devis/image_tableau/'.$data['img_banner_tableau']);
}
$docx->replaceTemplateVariableByHTML('devis_table_tarif', 'block', $data['devis_table_tarif']);
$docx->replaceTemplateVariableByHTML('devis_promotion', 'block', $data['devis_promotion']);
$docx->replaceTemplateVariableByHTML('devis_condition', 'block', $data['devis_condition']); 
$docx->replaceTemplateVariableByHTML('devis_table_programe', 'block', $data['devis_table_programe']);
$docx->replaceTemplateVariableByHTML('devis_detail', 'block', $data['devis_detail']);
if (strpos($data['template'], '_en') == false) {
	$docx->replaceTemplateVariableByHTML('devis_table_pointfort', 'block', $data['tableau_devis']);
}

$docx->createDocx($webRoot.'/upload/ideas/tours/output/devis-ims/' . $data['file_name']);
//$docx->createDocx($webRoot.'/upload/ideas/tours/output/devis-ims/' . $data['file_name']);
if(strpos($data['template'], '_en') === false && $data['template']!='B2B-FR'){
    $docx2 = new CreateDocx();
    $docx2->addTemplate($webRoot.'/helpers/template/devis/footer/footer.docx');
    // $docx2->replaceHeaderImage('image-3',$webRoot.'/helpers/template/devis/footer/banner3/'. $data['template'].'_banner03.jpg');
    if(strpos($data['template'], 'vac_') !== false){
        $docx->replaceTemplateVariableByHTML('link-footer', 'block', '<a style="font-family:Candara; font-size:9pt; color: #17BBFD; text-align: right;" href="https://voyager-au-cambodge.com/agence-voyage-sur-mesure-cambodge/voyage-solidaire">https://voyager-au-cambodge.com/agence-voyage-sur-mesure-cambodge/voyage-solidaire</a>');
        $nameFt = 'footer_vac';
    } else{
        $docx->replaceTemplateVariableByHTML('link-footer', 'block', '<a style="font-family:Candara; font-size:9pt; color: #17BBFD; text-align: right;" href="https://www.amica-travel.com/voyage-solidaire">https://www.amica-travel.com/voyage-solidaire</a>');
        $nameFt = 'footer_fr';
    }
    $docx2->createDocx($webRoot.'/helpers/template/devis/footer/'.$nameFt);
    $merge = new DocxUtilities;
    $merge->mergeDocx($webRoot.'/upload/ideas/tours/output/devis-ims/' . $data['file_name'].'.docx', $webRoot.'/helpers/template/devis/footer/'.$nameFt.'.docx', $webRoot.'/upload/ideas/tours/output/devis-ims/' . $data['file_name'].'.docx', ['mergeType' => 0]);
}
echo json_encode('success');
?>