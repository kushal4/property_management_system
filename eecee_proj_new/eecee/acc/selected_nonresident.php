<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $logfile->logfile_writeline("getting inside selected_resident.php");

    $user_id = $SENSESSION->get_val("user_id");
    $prop_id = $SENSESSION->get_val("prop_id");
    $logfile->logfile_writeline("the prop_id is:: ".$prop_id);
    /*
    $resident_0 = 0;
    $sql_context_role = "SELECT * FROM context_role WHERE resident = ?";
    $context_role_stmt = $conn->prepare($sql_context_role);
    $context_role_stmt->bind_param("i", $resident_0);
    $context_role_stmt->execute();
    $context_role_result = $context_role_stmt->get_result();
    $context_role_fetch_all = $context_role_result->fetch_all(MYSQLI_ASSOC);

    $context_role_fetch_all_str = var_export($context_role_fetch_all, true);
    $logfile->logfile_writeline("the context_role_fetch_all_str is:: ".$context_role_fetch_all_str);
    */

    $non_res = "SELECT * from contexts where prop_id = ? and user_id NOT in (SELECT user_id FROM `context_role` INNER JOIN contexts on context_role.ctx_id=contexts.id where context_role.resident= 1)";
    $nonres_stmt = $conn->prepare($non_res);
    $nonres_stmt->bind_param("i", $prop_id);
    $nonres_stmt->execute();
    $nonres_result = $nonres_stmt->get_result();
    $user_prop_result_all = $nonres_result->fetch_all(MYSQLI_ASSOC);

    $user_prop_result_all_str = var_export($user_prop_result_all, true);
    $logfile->logfile_writeline("the user_prop_result_all_str is:: ".$user_prop_result_all_str);

    $empty_array = array();
    foreach ($user_prop_result_all as $value){ 
        $res = new stdClass();
        $userID = $value["user_id"];
        $unit_id = $value["unit_id"];
        $user_type = $value["user_type"];
        $ID = $value["id"];
        //$ctx_id = $value["ctx_id"];
        $secedctxID = sec_push_val_single_entry ("ctx_id_map", $ID);
        
        $logfile->logfile_writeline("the unit ID is :: ".$unit_id);
        $logfile->logfile_writeline("the user_type is :: ".$user_type);
        $logfile->logfile_writeline("the userID is :: ".$userID);
        $logfile->logfile_writeline("the secedctxID is :: ".$ID);
        //$logfile->logfile_writeline("the userID is :: ".$userID);

        if($unit_id != NULL){
            $search_str="user_id=".$userID;
            $op_ret_fname_obj = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_FN", $userID, "user_id=".$userID);
            $op_ret_lname_obj = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_LN", $userID, "user_id=".$userID);
            $userName = $op_ret_fname_obj["val"]." ".$op_ret_lname_obj["val"];
        }
        $res->user_name= $userName;

        $sql_prop_topo = "SELECT * FROM prop_topo WHERE id = ? and prop_id = ?";
        $prop_topo_stmt = $conn->prepare($sql_prop_topo);
        $prop_topo_stmt->bind_param("ii",$unit_id, $prop_id);
        $prop_topo_stmt->execute();
        $prop_topo_result = $prop_topo_stmt->get_result();
        $user_prop_result_assoc = $prop_topo_result->fetch_assoc();
        $unit_name = $user_prop_result_assoc['node_name'];

        $res->unit_name= $unit_name;
        $res->ctx_id= $secedctxID;
        
        if($user_type != NULL && $user_type == 1 ){
            $res->capacity = "Owner";
        }else if($user_type != NULL && $user_type == 2){
            $res->capacity = "Co-owner";
        }else if($user_type != NULL && $user_type == 3){
            $res->capacity = "Family Member";
        }else if($user_type != NULL && $user_type ==42){
            $res->capacity = "Tenant";
        }
        array_push($empty_array, $res);
        
    }
    $sel_res_arr["ret_code"] = 0;
    $sel_res_arr["residents"] = $empty_array;
    $raw_json_encoce = json_encode($sel_res_arr);
    $logfile->logfile_writeline("---ajax return".$raw_json_encoce);
    echo $raw_json_encoce;
}
$logfile->logfile_close();
?>