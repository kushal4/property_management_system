<?php 
session_start();
//unset($_SESSION['unit_type_id']);
//unset($SENSESSION->get_val("unit_type_id"));
//$SENSESSION->delete("unit_type_id");
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$SENSESSION->delete("unit_type_id");
//echo "opening dev.php";
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';
//include 'lib/php-lib/eecee_constants.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'reg_func.php';
//include 'sec.php';
$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");
//clear map arrays from session

//$logfile = new \Sense\Log("Logs/tarantoo.log", __FILE__);
//$logfile->logfile_open("w");
//$fle_handle=fopen("/home/steffi/Steffi_usr/stef/tarantoo/Logs/tarantoo.log","w");
//$fle_handle=fopen("Logs/tarantoo.log","w");

//echo "before master_layout.php";
include $sense_common_php_lib_path.'master_layout.php';

addIcon($doc_head, "/favicon.ico");

// CSS Files :: START
addStyleSheet($doc_head, $eecee_styles_path."home.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."vert_tab.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."style_test03.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."fea_tbl.css?".time());

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

addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); 
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());

addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());

addScriptPath($doc_head, "prop_def.js?".time());
addScriptPath($doc_head, "prof_def_new.js?".time());
// JS Files :: END


//addScriptPath($doc_head, "../lib/js-lib/vert_tab.js?".time());

//addScriptPath($doc_head, "lib/js-lib/dashboard.js?".time());
//addScriptPath($doc_head, "../lib/js-lib/Chart.js?".time());


///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: START
///////////////////////////////////////////////////////////////
//Insert a tabs control
$unit_type_id = $SENSESSION->get_val("unit_type_id");
if($unit_type_id != null){

    $logfile->logfile_writeline("unit_type_id session key exists"); 
}else{
    $logfile->logfile_writeline("unit_type_id session key does not exist"); 
}

echo $doc->saveHTML();

$logfile->logfile_close();
?>



