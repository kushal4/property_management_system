<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."prop_def.log";
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


if (is_ajax()) {
    
    $logfile->logfile_writeline("getting inside edit_unit_type"); 
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $unit_id=""; 
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="unit_id"){
            $unit_id = $value;
        }
        
    }

    $unit_id_mapped = sec_get_map_val ("prop_topo_map", $unit_id);
    $logfile->logfile_writeline("the encoded node ID is".$unit_id);
    $logfile->logfile_writeline("the decoded node ID is".$unit_id_mapped);

    $mas_opt_sql = "SELECT * FROM prop_topo WHERE id = ?";
    $mas_opt_temp = $conn->prepare($mas_opt_sql);
    $mas_opt_temp->bind_param("i",$unit_id_mapped);
    $mas_opt_temp->execute();
    $mas_opt_result = $mas_opt_temp->get_result();
    $mas_opt_fetch_assoc = $mas_opt_result->fetch_assoc();
    $unit_name = $mas_opt_fetch_assoc["node_name"];
    $unit_type_id = $mas_opt_fetch_assoc["unit_type_id"];

    $logfile->logfile_writeline("the unit_name is:: get_unit_name_and_type_name *** ".$unit_name);
    $logfile->logfile_writeline("the unit_type_id is:: get_unit_name_and_type_name *** ".$unit_type_id);

    $sql = "SELECT * FROM unit_types WHERE id = ?";
    $sql_temp = $conn->prepare($sql);
    $sql_temp->bind_param("i",$unit_type_id);
    $sql_temp->execute();
    $result = $sql_temp->get_result();
    $res_fetch_assoc = $result->fetch_assoc();
    $unit_type_name = $res_fetch_assoc["name"];
    $logfile->logfile_writeline("the unit_type_name is:: *** ".$unit_type_name);

    $raw_json["ret_code"] = 0;
    $raw_json["unit_name"] = $unit_name;
    $raw_json["unit_type_name"] = $unit_type_name;
    $raw_json_encoce=json_encode($raw_json);
    echo $raw_json_encoce;
    
}   
$logfile->logfile_close();
?>