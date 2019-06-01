<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'sec.php';
//include 'prop_topo.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$logfile->logfile_writeline("getting inside feat_cat_sess PHP");

if (is_ajax()) {
    
    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $feat_cat_sig="";
    

    foreach ($json_decoded as $key => $value) {
        
        if ($key=="feat_cat_sig"){
            $feat_cat_sig = $value;
        }
    }

    $feat_cat_sig_decoded = sec_get_map_val ("feat_cat_sig_map", $feat_cat_sig);
    $logfile->logfile_writeline("the encoded prop ID is".$feat_cat_sig);
    $logfile->logfile_writeline("the decoded feature category sig is".$feat_cat_sig_decoded);
    
    $SENSESSION->token("FCAT", $feat_cat_sig_decoded);

    $raw_json["ret_code"] = 0;
    $raw_json_encode=json_encode($raw_json);
    echo $raw_json_encode;
}
$logfile->logfile_close();
?>