<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."acc.log";

require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $eecee_php_lib_path.'common_functions.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';
//$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside click_on_prop.php";
$logfile->logfile_writeline("getting inside set_target_role_sig_sess");
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

    $curr_map = sec_get_map("role_sig_map");
    $logfile->logfile_writeline(__FILE__."set_target ********************************* Dumping role_sig_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."set_target ********************************* Dumping role_sig_map MAP: End");

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
