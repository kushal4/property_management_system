<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL); 
include '../eecee_include.php';
include '../prop_topo.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'reg_func.php';
include $eecee_php_lib_path.'eecee_sec_map.php';

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

    $unit_features_sql = "SELECT * FROM unit_features";
    $unit_features_temp = $conn->prepare($unit_features_sql);
    //$unit_features_temp->bind_param("i",$aa);
    $unit_features_temp->execute();
    $unit_features_result = $unit_features_temp->get_result();
    $unit_features_result_fetchall = $unit_features_result->fetch_all(MYSQLI_ASSOC);

    $unit_features_result_fetchall_str = var_export($unit_features_result_fetchall, true);
    $logfile->logfile_writeline("unit_features_result_fetchall_str=".$unit_features_result_fetchall_str); 
    $feature_array = array();
    sec_clear_map ("feature_id_map");
    foreach ($unit_features_result_fetchall as $value){
        $feature_id = $value["id"];
        $feature_name = $value["name"];

        $seced_feature_id = sec_push_val_single_entry ("feature_id_map", $feature_id);

        $feature_sub_array["feature_name"] = $feature_name;
        $feature_sub_array["feature_id"] = $seced_feature_id; 

        array_push($feature_array,$feature_sub_array);
    }

    $curr_map = sec_get_map("feature_id_map");
    
    $logfile->logfile_writeline(__FILE__."fill_features_in_dropdown ---Dumping feature_id_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."fill_features_in_dropdown ---Dumping feature_id_map MAP: End");

    $get_feature_arr ["ret_code"] = 0;
    $get_feature_arr ["features"] = $feature_array;
    $raw_json_encoce = json_encode($get_feature_arr);
    echo $raw_json_encoce;
}



?>