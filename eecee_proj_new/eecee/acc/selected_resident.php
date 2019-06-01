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

    /*
    $resident_1 = 1;
    $sql_context_role = "SELECT * FROM context_role WHERE resident = ?";
    $context_role_stmt = $conn->prepare($sql_context_role);
    $context_role_stmt->bind_param("i", $resident_1);
    $context_role_stmt->execute();
    $context_role_result = $context_role_stmt->get_result();
    $context_role_fetch_all = $context_role_result->fetch_all(MYSQLI_ASSOC);

    $context_role_fetch_all_str = var_export($context_role_fetch_all, true);
    $logfile->logfile_writeline("the context_role_fetch_all_str is:: ".$context_role_fetch_all_str);
    $empty_array = array();
    */


    $res = "SELECT * FROM `context_role` INNER JOIN contexts on context_role.ctx_id=contexts.id where context_role.resident = 1 and contexts.prop_id = ?";
    $res_stmt = $conn->prepare($res);
    $res_stmt->bind_param("i", $prop_id);
    $res_stmt->execute();
    $res_result = $res_stmt->get_result();
    $res_result_all = $res_result->fetch_all(MYSQLI_ASSOC);

    $res_result_all_str = var_export($res_result_all, true);
    $logfile->logfile_writeline("the res_result_all_str is:: ".$res_result_all_str);
    $empty_array = array();
    foreach ($res_result_all as $value){ 
        $res = new stdClass();
        
        $userID = $value["user_id"];
        $unit_id = $value["unit_id"];
        $user_type = $value["user_type"];
        $ctx_id = $value["ctx_id"];
        $secedctxID = sec_push_val_single_entry ("ctx_id_map", $ctx_id);
        
        $logfile->logfile_writeline("the unit ID is :: ".$unit_id);
        $logfile->logfile_writeline("the user_type is :: ".$user_type);
        $logfile->logfile_writeline("the userID is :: ".$userID);
        $logfile->logfile_writeline("the secedctxID is :: ".$secedctxID);

        if($unit_id != NULL){
            $search_str="user_id=".$userID;
            $op_ret_fname_obj = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_FN", $userID, "user_id=".$userID);
            $op_ret_lname_obj = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_LN", $userID, "user_id=".$userID);
            $userName = $op_ret_fname_obj["val"]." ".$op_ret_lname_obj["val"];
        }
        $res->user_name= $userName;
        $res->ctx_id= $secedctxID;

        $logfile->logfile_writeline("the user name is :: ".$userName);

        $sql_prop_topo = "SELECT * FROM prop_topo WHERE id = ? and prop_id = ?";
        $prop_topo_stmt = $conn->prepare($sql_prop_topo);
        $prop_topo_stmt->bind_param("ii",$unit_id, $prop_id);
        $prop_topo_stmt->execute();
        $prop_topo_result = $prop_topo_stmt->get_result();
        $user_prop_result_assoc = $prop_topo_result->fetch_assoc();
        $unit_name = $user_prop_result_assoc['node_name'];
        $logfile->logfile_writeline("the user name is :: ".$unit_name);

        $res->unit_name= $unit_name;

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

    $empty_array_str = var_export($empty_array, true);
    $logfile->logfile_writeline("the empty_array is:: ".$empty_array_str);
    $sel_res_arr["ret_code"] = 0;
    $sel_res_arr["residents"] = $empty_array;
    $raw_json_encoce = json_encode($sel_res_arr);
    $logfile->logfile_writeline("---ajax return".$raw_json_encoce);
    echo $raw_json_encoce;
}
$logfile->logfile_close();
?>