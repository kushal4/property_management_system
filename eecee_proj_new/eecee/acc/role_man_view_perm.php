<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'sec.php';
include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $eecee_php_lib_path.'eecee_sec_map.php';
require_once $sense_common_php_lib_path.'net.php';
$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

include $sense_common_php_lib_path.'master_layout.php';
addIcon($doc_head, "/favicon.ico");

// CSS Files :: START
addStyleSheet($doc_head, $eecee_styles_path."home.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."vert_tab.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."style_test03.css?".time());

addStyleSheet($doc_head, $eecee_ext_styles_path."jquery-ui.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."themes/default/style.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."/themes/default-dark/style.css");
// CSS Files :: END

// JS Files :: START
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-3.2.1.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-ui.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jstree.js");
addScriptPath($doc_head, $sense_common_js_lib_path."resize.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."sense-lib.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."table_creator.js?".time());

addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); 
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, "role_man_view_perm.js?".time());
// JS Files :: END



$logged_in_role_sig = $SENSESSION->get_val("role_sig");
$target_role_sig = $SENSESSION->get_val("target_role_sig");
$prop_id = $SENSESSION->get_val("prop_id");
$logfile->logfile_writeline("the target role sig is:: ".$target_role_sig); 
//$logfile->logfile_writeline("inside role_man_view_perm"); 


$data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "logged_in_role_sig"=>$logged_in_role_sig, "target_role_sig"=>$target_role_sig));

$data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
$logfile->logfile_writeline("inside role_man_view_perm"); 
//$logfile->logfile_writeline("To Curl: ".$data_str); 
$actlRetObj = CurlSendPostJson($actl_urls->actlGetPermCurlURL, $data_str,$logfile); //what we get from the ACTL
//$logfile->logfile_writeline("From Curl: ".$actlRetObj); 

$decodedJson = json_decode($actlRetObj, true);
$jsonDataSTR = $decodedJson['d']."\n";
$data_json_decode = json_decode($jsonDataSTR, true);
$perm_array = $data_json_decode["p"];

$fea_array = $perm_array["features"];
$perm_array = $perm_array["perm"];

$the_role = get_role_by_sig($target_role_sig, $fea_array, $logfile);
$the_role_str = var_export($the_role, true);
$logfile->logfile_writeline("role_man_view_perm:: the_role_str ".$the_role_str);

//print_r($fea_array);
/*
echo "\n";
print_r($perm_array);
*/
$view_perm_parent_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");
$view_perm_pg_sec = insertPageSection($view_perm_parent_container, '', '{"class":"sel-prop-page-section"}');
//$role_tree_container = insertElement($features_pg_sec,"div",'{"class":"user_prop_container"}'," ");  
//addAttribToElement($role_tree_container, '{"id":"roll_tree"}');

$view_perm_table_container = insertPanel($view_perm_pg_sec,'',"");
$view_perm_table_container->setAttribute('id',"sel_prop_tbl_container");
addAttribToElement($view_perm_table_container, '{"class":"sel_prop_tbl_container_styl"}');

$buttons_parent_cont = insertElement($view_perm_pg_sec,"div",'{"class":"buttons_parent_cont_style"}'," "); // button parent container

$back_button_cont = insertElement($buttons_parent_cont,"div",'{"class":"back_button_cont_style"}'," ");
$back_button = insertElement($back_button_cont,"div",'{"class":"back_button_style"}',"Back");

$view_perm_table = new sense_table([
    //'id'=>'test_tbl',
    'widgetStyle'=>'wdg_sty',
    'headingStyle'=>'heading_sty',
    'headingText' => 'Test Table Heading Text',
    'headingTextStyle' => 'heading-text-sty',
    'contentTableStyle' => 'generic_table',
    'contentStyle' => 'sel_prop_table_wrapper'
    
]);
$view_perm_table->setParent($view_perm_table_container);

$row_obj = $view_perm_table->addRow();
$view_perm_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');

$cell_obj = $view_perm_table->addCell("Features", TRUE);
$view_perm_table->setCurrCellID("cell_1");

$cell_obj = $view_perm_table->addCell("", TRUE);
$view_perm_table->setCurrCellID("cell_2");

foreach ($fea_array as $v){
    $sig = $v["sig"];
    $feature_name = $v["text"];
    $type = $v["type"];
    $parent = $v["parent"];

    if($type == "feature"){
    $secedFeatCatSig = sec_push_val_single_entry ("feat_sig_map", $sig);

    $permArrVal = $perm_array[$sig];

    //print_r($abc);
    //echo "the feature name is:: ".$feature_name."<br>";
    $enable_code = $permArrVal["enable"];
    //echo $enable_code."<br>";

    
    $row_obj = $view_perm_table->addRow();
    $view_perm_table->setRowID($row_obj, "row_".$sig);
    addAttribToElement($row_obj, '{"class":""}');
    addAttribToElement($row_obj, '{"data-prop_id": '.$sig.' }');

    $cell_obj = $view_perm_table->addCell($feature_name);
    $view_perm_table->setCurrCellID("cell".$secedFeatCatSig."_1");
    //insertElement($cell_obj,"input",'{"class":"sel_prop_div"}'," ");

    $cell_obj = $view_perm_table->addCell("");
    $view_perm_table->setCurrCellID("cell".$secedFeatCatSig."_2");
    $enable_checkbox = insertElement($cell_obj,"input",'{"class":"sel_prop_div"}'," ");
    addAttribToElement($enable_checkbox, '{"type":"checkbox"}');
    addAttribToElement($enable_checkbox, '{"disabled":"disabled"}');
        if($enable_code == 1){
            addAttribToElement($enable_checkbox, '{"checked":"checked"}');
            
        }
    }
}



///////////////////////////////////////////////////////////////
////////START OF DIALOGUES
///////////////////////////////////////////////////////////////
$dialog_parent = $doc_body;

//$cant_del_role_cat_dialog = insertPanel($dialog_parent, '{"id":"cant_del_role_cat_dialog", "class":"cant_del_role_cat_dialog_style", "title":" "}',"");
//insertElement($cant_del_role_cat_dialog,"span",'{"class":"heading_styles cant_del_role_cat_dialog_span_style"}', "Can't delete Role Categories which contains roles under it");

///////////////////////////////////////////////////////////////
////////END OF DIALOGUES
///////////////////////////////////////////////////////////////

echo $doc->saveHTML();

$logfile->logfile_close();
?>


<?php

?>




