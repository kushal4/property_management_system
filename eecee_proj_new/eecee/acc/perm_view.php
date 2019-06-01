<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'sec.php';
//include '../lib/php-lib/eecee_include.php';
//include '../lib/php-lib/common_functions.php';
include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';

$log_path = $eecee_log_path."eecee.log";
require_once $sense_common_php_lib_path.'Log.php';

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

$op = $_POST["op"];
$logfile->logfile_writeline("perm_view :: the OP is ::".$op); 
//$view_perm_parent_container = insertElement($main_container,"div",'{"class":"steffi_class"}',"steffi");
if($op == "upd"){


    $logged_in_role_sig = $SENSESSION->get_val("role_sig");
    $target_role_sig = $SENSESSION->get_val("target_role_sig");

    $logfile->logfile_writeline("************the target role sig is::**********".$target_role_sig); 
    $logfile->logfile_writeline("************the logged role sig is::**********".$logged_in_role_sig); 

    $prop_id = $SENSESSION->get_val("prop_id");
    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "logged_in_role_sig"=>$logged_in_role_sig, "target_role_sig"=>$target_role_sig));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $logfile->logfile_writeline("To Curl: ".$data_str); 
    $actlRetObj = CurlSendPostJson($actl_urls->actlGetPermCurlURL, $data_str,$logfile); //what we get from the ACTL
    $logfile->logfile_writeline("From Curl: ".$actlRetObj); 

    $decodedJson = json_decode($actlRetObj, true);
    $jsonDataSTR = $decodedJson['d']."\n";
    $data_json_decode = json_decode($jsonDataSTR, true);
    $perm_array = $data_json_decode["p"];

    $fea_array = $perm_array["features"];
    $perm_array = $perm_array["perm"];


    $view_perm_parent_container = insertElement($main_container,"div",'{"class":"sel_prop_div"}'," ");
    $view_perm_pg_sec = insertPageSection($view_perm_parent_container, '', '{"class":"sel-prop-page-section"}');

    $upd_perm_head = insertElement($view_perm_pg_sec,"div",'{"class":"upd_perm_head_style"}',"Update Permission"); // button parent container

    $view_perm_table_container = insertPanel($view_perm_pg_sec,'',"");
    $view_perm_table_container->setAttribute('id',"sel_prop_tbl_container");
    addAttribToElement($view_perm_table_container, '{"class":"sel_prop_tbl_container_styl"}');

    $buttons_parent_cont = insertElement($view_perm_pg_sec,"div",'{"class":"buttons_parent_cont_style"}'," "); // button parent container

    $update_button_cont = insertElement($buttons_parent_cont,"div",'{"class":"update_button_cont_style"}'," ");
    $update_button = insertElement($update_button_cont,"div",'{"class":"update_button_style"}',"Update");
    addAttribToElement($update_button, '{"id":"update_button"}');

    //$back_button_cont = insertElement($buttons_parent_cont,"div",'{"class":"back_button_cont_style"}'," ");
    //$back_button = insertElement($back_button_cont,"div",'{"class":"back_button_style"}',"Back");

    $view_perm_table = new sense_table([
        'widgetStyle'=>'wdg_sty',
        'headingStyle'=>'heading_sty',
        'headingText' => 'Test Table Heading Text',
        'headingTextStyle' => 'heading-text-sty',
        'contentTableStyle' => 'generic_table',
        //'contentStyle' => 'sel_prop_table_wrapper'
        'contentStyle' => 'perm_view_table_wrapper'
        
    ]);
    $view_perm_table->setParent($view_perm_table_container);

    $row_obj = $view_perm_table->addRow();
    $view_perm_table->setRowID($row_obj, "row_0");
    addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');

    $cell_obj = $view_perm_table->addCell("permission", TRUE);
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

        $enable_code = $permArrVal["enable"];
        $eligible_code = $permArrVal["eligible"];
        $logfile->logfile_writeline("************** ".$eligible_code); 
        
        $row_obj = $view_perm_table->addRow();
        $view_perm_table->setRowID($row_obj, $secedFeatCatSig);
        addAttribToElement($row_obj, '{"class":"feature_row"}');
        addAttribToElement($row_obj, '{"data-prop_id": '.$sig.' }');

        $cell_obj = $view_perm_table->addCell($feature_name);
        $view_perm_table->setCurrCellID("cell".$secedFeatCatSig."_1");
        
        $cell_obj = $view_perm_table->addCell("");
        $view_perm_table->setCurrCellID("cell".$secedFeatCatSig."_2");
        $enable_checkbox = insertElement($cell_obj,"input",'{"class":"sel_prop_div"}'," ");
        addAttribToElement($enable_checkbox, '{"type":"checkbox"}');
            if($eligible_code == 1){
            
            }else{
                addAttribToElement($enable_checkbox, '{"disabled":"disabled"}'); // read only
            }
            if($enable_code == 1){
                addAttribToElement($enable_checkbox, '{"checked":"checked"}');
            }else{
                addAttribToElement($enable_checkbox, '{"checked":""}');
            }
        }
    }
}

else if($op == "view"){
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
$view_perm_parent_container = insertElement($main_container,"div",'{"class":"sel_prop_div"}'," ");
$view_perm_pg_sec = insertPageSection($view_perm_parent_container, '', '{"class":"sel-prop-page-section"}');
//$role_tree_container = insertElement($features_pg_sec,"div",'{"class":"user_prop_container"}'," ");  
//addAttribToElement($role_tree_container, '{"id":"roll_tree"}');
$upd_perm_head = insertElement($view_perm_pg_sec,"div",'{"class":"upd_perm_head_style"}',"View Permission");

$view_perm_table_container = insertPanel($view_perm_pg_sec,'',"");
$view_perm_table_container->setAttribute('id',"sel_prop_tbl_container");
addAttribToElement($view_perm_table_container, '{"class":"sel_prop_tbl_container_styl"}');

/*
$buttons_parent_cont = insertElement($view_perm_pg_sec,"div",'{"class":"buttons_parent_cont_style"}'," "); // button parent container
$back_button_cont = insertElement($buttons_parent_cont,"div",'{"class":"back_button_cont_style"}'," ");
$back_button = insertElement($back_button_cont,"div",'{"class":"back_button_style"}',"Back");
*/

$view_perm_table = new sense_table([
    //'id'=>'test_tbl',
    'widgetStyle'=>'wdg_sty',
    'headingStyle'=>'heading_sty',
    'headingText' => 'Test Table Heading Text',
    'headingTextStyle' => 'heading-text-sty',
    'contentTableStyle' => 'generic_table',
    'contentStyle' => 'perm_view_table_wrapper'
    
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
}

echo $doc->saveHTML();

$logfile->logfile_close();


?>