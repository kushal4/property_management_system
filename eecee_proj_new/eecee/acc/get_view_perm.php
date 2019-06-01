<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'sec.php';

$logfile = new \Sense\Log($log_path, __FILE__);
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

    //$logfile->logfile_writeline("getting inside get_view_perm.php");

    $logged_in_role_sig = $SENSESSION->get_val("role_sig");
    $target_role_sig = $SENSESSION->get_val("target_role_sig");
    $prop_id = $SENSESSION->get_val("prop_id");

    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "logged_in_role_sig"=>$logged_in_role_sig, "target_role_sig"=>$target_role_sig));

    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);

    $logfile->logfile_writeline("To Curl: ".$data_str); 
    $actlRetObj = CurlSendPostJson($actlGetPermCurlURL, $data_str); //what we get from the ACTL
    $logfile->logfile_writeline("From Curl: ".$actlRetObj); 

    $session_val= is_session_valid();

    if($session_val==0){
        $SENSESSION->token("target_role_sig", $target_sig_decoded);
        $raw_json["ret_code"] = 0;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }
    else{     
        $raw_json["ret_code"] = 4; //if session expires 
    }
}
$logfile->logfile_close();
?>