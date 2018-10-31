<?php
require_once '/var/www/__apps/www.amica-travel.com/helpers/phpdocx/classes/CreateDocx.inc';
$data = array(
	'1.1' => str_replace('<img',"<img style='display: none;'",$_POST['tab1_1']),
	'1.2' => str_replace('<img',"<img style='float: right; padding: 50px 0px 10px 10px; width: 282px; height: 600px;'",$_POST['tab1_2']),
	'1.3' =>  str_replace('<h3 class="block-h3">Programme détaillé</h3>','',$_POST['tab1_3']),
	'2.1' => "<div class='tab-pane tab-pane-2-1 block' style='font-size: 11pt;'>".$_POST['tab2_1']."</div>",
	'2.2' => "<div class='tab-pane tab-pane-2-2 block' style='font-size: 11pt;'>".$_POST['tab2_2']."</div>",
	'2.3' => "<div class='tab-pane tab-pane-2-3 block' style='font-size: 11pt;'>".$_POST['tab2_3']."</div>",
	'2.4' => "<div class='tab-pane tab-pane-2-4 block' style='font-size: 11pt;'>".$_POST['tab2_4']."</div>",
);
$name = $_POST['name'];
$tour_day  = $_POST['tour_day'];
if (!file_exists('/var/www/www.amica-travel.com/upload/ideas/tours/output/'.$_POST['tem'])) {
    mkdir('/var/www/www.amica-travel.com/upload/ideas/tours/output/'.$_POST['tem'], 0777, true);
}
$color = '#8A006C';
if(strpos('cambodge|indochine-en-famille|laos|multi-pays|vietnam-aventure|vietnam-mini-circuit|vietnam-thematique',$_POST['tem'])!==false){
	$color = '#ffffff';
}

	$docx = new CreateDocx();
	$setting = array(
    'zoom' => 100
    );
    $docx->enableCompatibilityMode();
	$docx->addTemplate('/var/www/__apps/www.amica-travel.com/helpers/template/'.$_POST['tem'].'.docx');
	$docx->replaceTemplateVariableByHTML('h1', 'block', "<span style='color: $color;font-weight: bold;font-size:22pt;margin:0; padding: 0;'>".$_POST['h1']."</span>");
	$docx->replaceTemplateVariableByHTML('days', 'block', "<div class='tour-days' style='font-size: 11pt; color: white;font-weight: bold;'> ".$tour_day."</div>");
	$docx->replaceTemplateVariableByHTML('tab-pane-1-1', 'block', "<div class='tab-pane tab-pane-1-1 block' style='font-size: 11pt;'>".$data['1.1']."</div>");
	$docx->replaceTemplateVariableByHTML('tab-pane-1-2', 'block', "<div class='tab-pane tab-pane-1-2 block' style='font-size: 11pt;'>".$data['1.2']."</div>");
	$docx->replaceTemplateVariableByHTML('tab-pane-1-3', 'block', "<div class='tab-pane tab-pane-1-3 block' style='font-size: 11pt;'>".$data['1.3']."</div>");
	$docx->replaceTemplateVariableByHTML('tab-pane-2-1', 'block', $data['2.1']);
	$docx->replaceTemplateVariableByHTML('tab-pane-2-2', 'block', $data['2.2']);
	$docx->replaceTemplateVariableByHTML('tab-pane-2-3', 'block', $data['2.3']);
	$docx->replaceTemplateVariableByHTML('tab-pane-2-4', 'block', $data['2.4']);
	$docx->createDocx('/var/www/www.amica-travel.com/upload/ideas/tours/output/'.$_POST['tem'].'/'.$name);
	$docx->transformDocx('/var/www/www.amica-travel.com/upload/ideas/tours/output/'.$_POST['tem'].'/'.$name.'.docx','/var/www/www.amica-travel.com/upload/ideas/tours/output/'.$_POST['tem'].'/'.$name.'.pdf',null, array('debug'=>true));
	 
?>