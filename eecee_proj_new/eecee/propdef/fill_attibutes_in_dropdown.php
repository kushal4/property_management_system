<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL); 
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';
                             
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'reg_func.php';
include $eecee_php_lib_path.'eecee_lib.php';
//include $eecee_php_lib_path.'eecee_sec_map.php';
include $sense_common_php_lib_path.'actl_lib.php';
//include 'lib/php-lib/eecee_sec_map.php';

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
/*
    $attributes_sql = "SELECT * FROM attributes";
    $attributes_temp = $conn->prepare($attributes_sql);
    //$attributes_temp->bind_param("i",$aa);
    $attributes_temp->execute();
    $attributes_result = $attributes_temp->get_result();
    $attributes_fetchall = $attributes_result->fetch_all(MYSQLI_ASSOC);

    $attributes_fetchall_str = var_export($attributes_fetchall, true);
    $logfile->logfile_writeline("attributes_fetchall_str=".$attributes_fetchall_str); 
    $attrib_array = array();
    sec_clear_map ("attrib_id_map");
    foreach ($attributes_fetchall as $value){
        $attrib_id = $value["id"];
        $attrib_name = $value["name"];

        $seced_attrib_id = sec_push_val_single_entry ("attrib_id_map", $attrib_id);

        $attrib_sub_array["attrib_name"] = $attrib_name;
        $attrib_sub_array["attrib_id"] = $seced_attrib_id; 

        array_push($attrib_array,$attrib_sub_array);
    }
*/

    $get_attrib_arr ["ret_code"] = 0;
    $get_attrib_arr ["attributes"] = $attrib_array;
    $raw_json_encoce = json_encode($get_attrib_arr);
    echo $raw_json_encoce;
}



?>