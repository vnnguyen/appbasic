<?php

require_once '/var/www/__apps/www.amica-travel.com/helpers/phpdocx/classes/CreateDocx.inc';
$data = array(
	'file_name' => $_POST['file_name'],
    'template' => $_POST['template'],
    'devis_name' => $_POST['devis_name'],
    'devis_number' => $_POST['devis_number'],
    'devis_guest' => '<span style="font-family:Candara; color:white; font-size: 11pt;font-weight:bold;">'.$_POST['devis_guest'].'</span>',
    'devis_date' => '<span style="font-family:Candara; color:white; font-size: 10pt;font-weight:bold;">'.$_POST['devis_date'].'</span>',
	'devis_prix' => $_POST['devis_prix'],
    'devis_description' => $_POST['devis_description'],
    'sale_detail' => '<div style="text-align: right;">'.$_POST['sale_detail'].'</div>',

    'devis_table_programe' => $_POST['devis_table_programe'],
    'devis_detail' => $_POST['devis_detail'],
    'devis_table_tarif' => $_POST['devis_table_tarif'],
    'devis_promotion' => $_POST['devis_promotion'],
    'devis_condition' => $_POST['devis_condition']
);
if (!file_exists('/var/www/www.amica-travel.com/upload/ideas/tours/output/devis-ims')) {
    mkdir('/var/www/www.amica-travel.com/upload/ideas/tours/output/devis-ims', 0777, true);
}
$color = '#8A006C';

$docx = new CreateDocx();
$setting = array(
    'zoom' => 100
);
$docx->enableCompatibilityMode();
$docx->addTemplate('/var/www/__apps/www.amica-travel.com/helpers/template/devis/' . $data['template'] . '.docx');
$docx->replaceTemplateVariableByHTML('devis_name', 'block', "<span style='color: $color;font-weight: bold;font-size:22pt;margin:0; padding: 0;'>" .  $data['devis_name'] . "</span>");

$docx->replaceTemplateVariableByHTML('devis_number', 'block', $data['devis_number']);
$docx->replaceTemplateVariableByHTML('devis_guest', 'block', $data['devis_guest']);
$docx->replaceTemplateVariableByHTML('devis_date', 'block', $data['devis_date']);
$docx->replaceTemplateVariableByHTML('devis_prix', 'block', $data['devis_prix']);
$docx->replaceTemplateVariableByHTML('devis_description', 'block', $data['devis_description']);
$docx->replaceTemplateVariableByHTML('sale_detail', 'block', $data['sale_detail']);

$docx->replaceTemplateVariableByHTML('devis_table_tarif', 'block', $data['devis_table_tarif']);
$docx->replaceTemplateVariableByHTML('devis_promotion', 'block', $data['devis_promotion']);
$docx->replaceTemplateVariableByHTML('devis_condition', 'block', $data['devis_condition']); 
$docx->replaceTemplateVariableByHTML('devis_table_programe', 'block', $data['devis_table_programe']);
$docx->replaceTemplateVariableByHTML('devis_detail', 'block', $data['devis_detail']);


$docx->createDocx('/var/www/www.amica-travel.com/upload/ideas/tours/output/devis-ims/' . $data['file_name']);
?>