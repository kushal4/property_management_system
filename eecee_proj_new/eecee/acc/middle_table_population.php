<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $eecee_php_lib_path.'eecee_lib.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    //$logfile->logfile_writeline("the conn is :: ".$conn); 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $logfile->logfile_writeline("getting inside this");
    $raw_json_str = $_POST["k"];
    $logfile->logfile_writeline("AJAX Parameter:BEGIN"); 
    $logfile->logfile_writeline($raw_json_str); 
    $logfile->logfile_writeline("AJAX Parameter:END"); 

    $json_decoded = json_decode($raw_json_str, true);
    $ctx_id=""; 
    
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="ctx_id"){
            $ctx_id = $value;
        }
        
    }

    $decodded_ctx_id = sec_get_map_val ("ctx_id_map", $ctx_id);
    $logfile->logfile_writeline("middle_table_population :: the encoded user ID is".$ctx_id);
    $logfile->logfile_writeline("middle_table_population :: the decoded context ID is".$decodded_ctx_id);

    $sql_user_prop = "SELECT * FROM contexts WHERE id = ?";
    $user_prop_stmt = $conn->prepare($sql_user_prop);
    $user_prop_stmt->bind_param("i",$decodded_ctx_id);
    $user_prop_stmt->execute();
    $user_prop_result = $user_prop_stmt->get_result();
    $user_prop_row = $user_prop_result->fetch_assoc();
    $user_id = $user_prop_row["user_id"];
    $unit_id = $user_prop_row["unit_id"];
    $logfile->logfile_writeline("the user id is :: ".$user_id);

    $search_str="user_id=".$user_id;
    $fname = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_FN", $user_id, "user_id=".$user_id);
    $lname = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_LN", $user_id, "user_id=".$user_id);
    $userName = $fname["val"]." ".$lname["val"];
    $logfile->logfile_writeline("the user name is :: ".$userName);

    $reg_user_prop = "SELECT * FROM reg_user WHERE id = ?";
    $reg_user_stmt = $conn->prepare($reg_user_prop);
    $reg_user_stmt->bind_param("i",$user_id);
    $reg_user_stmt->execute();
    $reg_user_result = $reg_user_stmt->get_result();
    $reg_user_asc = $reg_user_result->fetch_assoc();
    $email = $reg_user_asc["email"];
    $logfile->logfile_writeline("the email is :: ".$email);

    $prop_topo_sql = "SELECT * FROM prop_topo WHERE id = ?";
    $prop_topo_temp = $conn->prepare($prop_topo_sql);
    $prop_topo_temp->bind_param("i",$unit_id);
    $prop_topo_temp->execute();
    $prop_topo_result = $prop_topo_temp->get_result();
    $prop_topo_asc = $prop_topo_result->fetch_assoc();
    $unit_name = $prop_topo_asc["node_name"];
    $logfile->logfile_writeline("the node name :: ".$unit_name);

    $raw_json["ret_code"] = 0;
    $raw_json["user_name"] = $userName;
    $raw_json["email"] = $email;
    $raw_json["node_name"] = $unit_name;
    $raw_json_encode=json_encode($raw_json);
    $logfile->logfile_writeline("middle table population :: AJAX return :: ".$raw_json_encode);
    echo $raw_json_encode;
}
$logfile->logfile_close();
?>