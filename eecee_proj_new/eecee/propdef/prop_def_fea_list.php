<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'sec.php';

include $eecee_php_lib_path.'eecee_lib.php';
//include $eecee_php_lib_path.'eecee_sec_map.php';
include $sense_common_php_lib_path.'actl_lib.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

$html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
$html.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
$html.="<html>";
$html.="<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">";
$html.="<body>";
$html.="    <div id=\"main_cont\">";
$html.="    </div>";
$html.="</body>";
$html.="</html>";

$doc = createDomDoc($html);
$main_container =$doc->getElementById('main_cont');

$container_head = insertElement($main_container,"div",'{"id":"container_head", "class":"container_head_style"}'," ");

$container_body = insertElement($main_container,"div",'{"class":"container_body_style"}'," ");
//$container_body_pg_sec = insertPageSection($container_body, '', '{"id" : "container_body_pg_sec", class":"container_body_pg_sec_style"}');
$container_body_pg_sec = insertPageSection  ($container_body, '', '{"class":"container_body_pg_sec_style"}');

//$role_tree_container = insertElement($features_pg_sec,"div",'{"class":"user_prop_container"}'," ");  
//addAttribToElement($role_tree_container, '{"id":"roll_tree"}');

$feature_table_container = insertPanel($container_body_pg_sec,'',"");
$feature_table_container->setAttribute('id',"sel_prop_tbl_container");
addAttribToElement($feature_table_container, '{"class":"prop_def_tbl_container_styl"}');

$buttons_parent_cont = insertElement($container_body_pg_sec,"div",'{"class":"buttons_parent_cont_style"}'," "); // button parent container

//$back_button_cont = insertElement($buttons_parent_cont,"div",'{"class":"back_button_cont_style"}'," ");
//$back_button = insertElement($back_button_cont,"div",'{"class":"back_button_style"}',"Back");

$featureTable = new sense_table([
    //'id'=>'test_tbl',
    'widgetStyle'=>'fea_tbl_wdg_sty',
    'headingStyle'=>'fea_tbl_heading_sty',
    'headingText' => 'Test Table Heading Text',
    'headingTextStyle' => 'fea_tbl_heading_text_sty',
    //'contentTableStyle' => 'fea_tbl_cont_tbl_sty',
    //'contentTableStyle' => 'prop_def_fea_table',
    //'contentStyle' => 'prop_def_table_wrapper',
    'contentStyle' => 'fea_tbl_cont_sty'
    
]);
$featureTable->setParent($feature_table_container);

$row_obj = $featureTable->addRow();
$featureTable->setRowID($row_obj, "row_0");
//addAttribToElement($row_obj, '{"class":"generic_feature_name_style"}');

$cell_obj = $featureTable->addCell("", TRUE);
//addAttribToElement($cell_obj, '{"class":"prop_def_feature_name_style"}');

$featureTable->setCurrCellID("cell_1");
$fea_obj = $SENSESSION->get_val("fea_obj");
$fea_obj_str = var_export($fea_obj, true);
$logfile->logfile_writeline("fea_obj_str".$fea_obj_str); 

$feature_cat_sig = $SENSESSION->get_val("FCAT");
//$logfile->logfile_writeline("feature_cat_sig".$feature_cat_sig); 
print_r($feature_cat_sig);
$feature_cat_sig_str = var_export($feature_cat_sig, true);
$logfile->logfile_writeline("The feature_cat_sig is :: ".$feature_cat_sig_str);

foreach ($fea_obj as $v){
        $sig = $v["sig"];
        $fet_cat_name = $v["text"];
        $type = $v["type"];
        $parent = $v["parent"];
        $feature_name = $v["text"];
        
        if($type == "feature" && $parent == $feature_cat_sig){
            $secedFeatCatSig = sec_push_val_single_entry ("feat_sig_map", $sig);
            //echo "the sig is::".$sig."</br>";
            //echo "the parent is::".$parent."</br>";
            //echo "the type is::".$type."</br>";
            //echo "the feature name is::".$feature_name;
            //echo "</br>";

            $row_obj = $featureTable->addRow();
            $featureTable->setRowID($row_obj, "row_".$sig);
            //addAttribToElement($row_obj, '{"class":"generic_feature_name_style"}');
            addAttribToElement($row_obj, '{"data-prop_id": '.$sig.' }');

            $cell_obj = $featureTable->addCell($feature_name);
            $featureTable->setCurrCellID("cell".$secedFeatCatSig."_1");
            //addAttribToElement($cell_obj, '{"class":"generic_feature_name_style"}');
            addAttribToElement($cell_obj, '{"class":"generic_feature_name"}');
            
            //addAttribToElement($cell_obj, '{"data-prop_id": '.$sig.' }');
            $lower_sig = strtolower($sig);
            //echo "the lower sig is".$lower_sig."</br>";
            addDataToElement($cell_obj, '{"j":"'.$lower_sig.'"}');
            addDataToElement($cell_obj, '{"s":"'.$secedFeatCatSig.'"}');
        }
    }

echo $doc->saveHTML();

$logfile->logfile_close();


?>