<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";

require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';

include $sense_common_php_lib_path.'actl_lib.php';

//include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");
$logfile->logfile_writeline("came to click_on_prop_new.php");
$chk_session_exp_stat=$SENSESSION->session_exists();
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
if($chk_session_exp_stat){
    include $sense_common_php_lib_path.'sec.php';
}
$logfile->logfile_writeline("the session exp stat val  ::::::: ".$chk_session_exp_stat); 

//if (is_ajax()) {
if (is_ajax() && $chk_session_exp_stat) {
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $prop_id=""; 
    foreach($json_decoded as $key => $value) {
        if ($key=="prop_id"){
            $prop_id = $value;
        }
    }

    $prop_id_decoded = sec_get_map_val ("prop_id_map", $prop_id);

    $sess_user_id = $SENSESSION->get_val("user_id");
    $logfile->logfile_writeline("the user ID ".$sess_user_id); 
    $arg = array();
    $arg["proj_key"] = $projectID;
    $logfile->logfile_writeline("the project key is (arg) ".$arg["proj_key"]); 
    $arg["prop_id"] = $prop_id_decoded;
    $logfile->logfile_writeline("the property ID is (arg) ".$arg["prop_id"]); 
    $arg["user_id"] = $sess_user_id;
    $arg["dbconn"] = $conn;
    $arg["logfile"] = $logfile;
    $arg["actl_urls"] = $actl_urls;
    $context_arr = get_user_contexts($arg);

    $context_arr_str = var_export($context_arr, true);
    $logfile->logfile_writeline("click_on_prop_new :: context_arr_str :: ".$context_arr_str);

    $context_arr_roles = get_context_roles($arg, $context_arr, false);

    $context_arr_roles_str = var_export($context_arr_roles, true);
    $logfile->logfile_writeline("click_on_prop_new :: context_arr_roles :: ".$context_arr_roles_str);

    $empty_array01 = array();
    
    foreach($context_arr_roles as $key => $value) {
        $unit_name_str = "";
        $unit_id = $value["unit_id"];
        $prop_topo_sql = "SELECT * FROM prop_topo WHERE prop_id = ? AND id= ?";
        $prop_topo_stmt = $conn->prepare($prop_topo_sql);
        $prop_topo_stmt->bind_param("ii",$prop_id_decoded, $unit_id);
        $prop_topo_stmt->execute();
        $prop_topo_result = $prop_topo_stmt->get_result();
        $prop_topo_assoc = $prop_topo_result->fetch_assoc();
        $unit_name = $prop_topo_assoc["node_name"];
        $logfile->logfile_writeline("click_on_prop_new :: unit_name :: ".$unit_name);
        $unit_name_str = $unit_name;
        $context_roles = $value["role"];
        foreach($context_roles as $v) {
            $role_str = $unit_name_str;
            $type = $v["type"];
            $logfile->logfile_writeline("click_on_prop_new :: the role type is :: ".$type);
            if($type == "role"){
                $role_sig = $v["sig"];
                $secedRoleID = sec_push_val_single_entry ("context_role_map", $role_sig);
                $role_name = $v["name"];
                $logfile->logfile_writeline("click_on_prop_new :: the role sig is :: ".$role_sig);
                $logfile->logfile_writeline("click_on_prop_new :: the role name is :: ".$role_name);
                $role_str = $role_str."-".$role_name;
                $empty_array01[$secedRoleID] = $role_str;
            }
        }

    }
    $empty_array01_str = var_export($empty_array01, true);
    $logfile->logfile_writeline("click_on_prop_new :: empty_array01 :: ".$empty_array01_str);

    $con_role_arr["ret_code"] = 0;
    $con_role_arr["roles"] = $empty_array01;
    $raw_json_encoce = json_encode($con_role_arr);
    $logfile->logfile_writeline("---ajax return".$raw_json_encoce);
    echo $raw_json_encoce;

}else{
   
    $con_role_arr["ret_code"] = 4;
    $con_role_arr["roles"] = array();
    $raw_json_encoce = json_encode($con_role_arr);
    echo $raw_json_encoce;
}
$logfile->logfile_close();
?>