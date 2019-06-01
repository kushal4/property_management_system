<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function get_role_by_sig_local($role_sig, $role_arr, $logfile){
    foreach ($role_arr as $value){  
        if($value->sig == $role_sig){
            return $value;
        }
    }
}

if (is_ajax()) {
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $prop_id = $SENSESSION->get_val("prop_id");

    $logfile->logfile_writeline("getting inside delete_scope_role.php");
    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $scope_role_sig=""; 
    $target_role_sig=""; 
    foreach ($json_decoded as $key => $value) {
        if ($key=="scope_role_sig"){
            $scope_role_sig = $value;
        }

        if ($key=="target_role_sig"){
            $target_role_sig = $value;
        }
    }
   
    $scope_role_sig_decoded = sec_get_map_val ("api_role_sig", $scope_role_sig);
    $logfile->logfile_writeline("the encoded scope role sig is-----------------------------------------------".$scope_role_sig);
    $logfile->logfile_writeline("the decoded scope role sig is-----------------------------------------------".$scope_role_sig_decoded); 

    $target_role_sig_decoded = sec_get_map_val ("role_sig_by_perm_map", $target_role_sig);
    $logfile->logfile_writeline("the encoded targeted role sig is-----------------------------------------------".$target_role_sig);
    $logfile->logfile_writeline("the decoded targeted role sig is-----------------------------------------------".$target_role_sig_decoded); 

    $check_role_scp = "SELECT scope_role_sig FROM acc_man_role_scope WHERE prop_id = ? AND target_role_sig = ? AND scope_role_sig = ?";
    $role_scp_result_temp = $conn->prepare($check_role_scp);
    $role_scp_result_temp->bind_param("iss",$prop_id, $target_role_sig_decoded, $scope_role_sig_decoded);
    $role_scp_result_temp->execute();
    $check_role_scp_result = $role_scp_result_temp->get_result();
    $num_rows = mysqli_num_rows($check_role_scp_result);
    $logfile->logfile_writeline("**the number of rows**".$num_rows);
    if($num_rows == 0){
        
    }else{
        $sql_delete = $conn->prepare("DELETE from acc_man_role_scope WHERE prop_id = ? AND target_role_sig = ? AND scope_role_sig = ?");
        $sql_delete->bind_param("iss",$prop_id, $target_role_sig_decoded, $scope_role_sig_decoded);
        $sql_delete->execute();
        $sql_delete_res = $sql_delete->get_result();

        $raw_json["ret_code"] = 0;
        $raw_json_encode=json_encode($raw_json);
        $logfile->logfile_writeline("AJAX return :: ".$raw_json_encode);
        echo $raw_json_encode;
    }
}
?>