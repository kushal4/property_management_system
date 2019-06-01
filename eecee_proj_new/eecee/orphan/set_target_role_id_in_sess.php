<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include 'lib/php-lib/common_functions.php';

include '../lib/php-lib/sec.php';
//include 'lib/php-lib/eecee_sec_map.php';
include '../lib/php-lib/session_exp.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $target_role_sig=""; 
    foreach ($json_decoded as $key => $value) {
        if ($key=="target_role_sig"){
            $target_role_sig = $value;
        }  
    }

    $target_role_sig_decoded = sec_get_map_val ("role_sig_by_perm_map", $target_role_sig);
    $logfile->logfile_writeline("the encoded targeted role sig is-----------------------------------------------".$target_role_sig);
    $logfile->logfile_writeline("the decoded targeted role sig is-----------------------------------------------".$target_role_sig_decoded);

    $_SESSION["targetRoleSig"] = $target_role_sig_decoded;


    $raw_json["ret_code"] = 0;
    $raw_json["target_role_sig"] = $target_role_sig_decoded;
    $raw_json_encode=json_encode($raw_json);
    echo $raw_json_encode;
}   
?>