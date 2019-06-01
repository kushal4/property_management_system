<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
include '../eecee_include.php';
require_once $sense_common_php_lib_path.'Log.php';
//include 'lib/php-lib/eecee_constants.php';
//include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
//include 'lib/php-lib/common_functions.php';
include $eecee_php_lib_path.'common_functions.php';

//include '../lib/php-lib/sec.php';
include $sense_common_php_lib_path.'sec.php';
//include 'lib/php-lib/eecee_sec_map.php';
//include 'curl_url_include.php';
//$log_path = "Logs/eecee.log";
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside click_on_prop.php";

if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $target_sig=""; 
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="target_sig"){
            $target_sig = $value;
        }
        
    }

    $target_sig_decoded = sec_get_map_val ("role_sig_map", $target_sig);
    $logfile->logfile_writeline("the encoded target sig is".$target_sig);
    $logfile->logfile_writeline("-------------------------------------------------------------------------------------the decoded target sig is".$target_sig_decoded); 
    
    //echo "the encoded target sig is:: ".$target_sig."\n";
    //echo "the decoded target sig is:: ".$target_sig_decoded."\n";

    
    $_SESSION["target_role_sig"] = $target_sig_decoded;

    //echo "the decoded target sig is:: ".$_SESSION["target_role_sig"];

    $raw_json["ret_code"] = 0;
    $raw_json_encode=json_encode($raw_json);
    echo $raw_json_encode;
    
    
}
$logfile->logfile_close();
?>
