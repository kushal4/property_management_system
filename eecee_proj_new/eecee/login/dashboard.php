<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";
require_once $sense_common_php_lib_path.'Log.php';
require_once $sense_common_php_lib_path.'net.php';


include $eecee_php_lib_path.'eecee_lib.php';
include $eecee_php_lib_path.'eecee_sec_map.php';

include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';



$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

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

//JS Files :: START
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-3.2.1.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-ui.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jstree.js");
addScriptPath($doc_head, $sense_common_js_lib_path."resize.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."sense-lib.js?".time());

addScriptPath($doc_head, "dashboard.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); // rr
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());
// JS Files :: END
$logfile->logfile_writeline("start the curling from ip_addr".$_SERVER['REMOTE_ADDR']); 

$role_sig = $SENSESSION->get_val("role_sig");
$prop_id = $SENSESSION->get_val("prop_id");
$actlGetPermCurlURL = $actl_urls->actlGetPermCurlURL;

$data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "logged_in_role_sig"=>$role_sig, "target_role_sig"=>$role_sig));

$data_str = json_encode($data,JSON_UNESCAPED_SLASHES);

$logfile->logfile_writeline("To Curl: ".$data_str); 
$actlRetObj = CurlSendPostJson($actlGetPermCurlURL, $data_str, $logfile); //what we get from the ACTL
$logfile->logfile_writeline("From Curl: ".$actlRetObj); 
//echo $actlRetObj;

$decodedJson = json_decode($actlRetObj, true);
$jsonDataSTR = $decodedJson['d']."\n";
$data_json_decode = json_decode($jsonDataSTR, true);
$perm_array = $data_json_decode["p"];

$SENSESSION->token("perm_obj", $perm_array["perm"]);
$fea_tree = $perm_array["features"];
$SENSESSION->token("fea_obj", $fea_tree);
$logfile->logfile_writeline("\n\n--------------------writing Feature tree--------------\n\n");
$logfile->logfile_writeline(print_r($fea_tree,true)); 
//var_dump($fea_tree);
//exit;

$dashboard_parent_container = insertElement($layout_main_container,"div",'{"class":"dashboard_parent_container"}'," ");

$feat_cat_array = array();
//echo "<br><br>Parsing Feature Tree<br><br>";
//foreach ($fea_tree as $value){  
//    echo "<br><br>";
//    var_dump($value);
    foreach ($fea_tree as $v){
        $sig = $v["sig"];
        $fet_cat_name = $v["text"];
        $type = $v["type"];
        $parent = $v["parent"];
        if($type == "cat" && $parent!= "root"){
            $secedFeatCatSig = sec_push_val_single_entry ("feat_cat_sig_map", $sig);
            $feat_cat_sub_array["role_name"] = $fet_cat_name;
            $feat_cat_sub_array["role_sig"] = $secedFeatCatSig;
            array_push($feat_cat_array,$feat_cat_sub_array);
            $feature_cat = insertElement($dashboard_parent_container,"div",'{"class":"feature_cat_style fc"}',$fet_cat_name);
            $lower_sig = strtolower($sig);
            addDataToElement($feature_cat, '{"j":"'.$lower_sig.'"}');
            addDataToElement($feature_cat, '{"s":"'.$secedFeatCatSig.'"}');
        }
    }
//}

///////////////////////////////////////////////////////////////
////////START OF DIALOGUES
///////////////////////////////////////////////////////////////
$dialog_parent = $doc_body;

/*password ends*/
///////////////////////////////////////////////////////////////
////////END OF DIALOGUES
///////////////////////////////////////////////////////////////

echo $doc->saveHTML();

$logfile->logfile_close();
?>


<?php

?>




