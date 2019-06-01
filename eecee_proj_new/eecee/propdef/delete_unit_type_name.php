<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside delete role category";
if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $unit_type_id="";
    
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="unit_type_id"){
            $unit_type_id = $value;
        }
    }
    //echo $unit_type_id;

    $curr_map = sec_get_map("unit_type_id_sig_map");
    
    $logfile->logfile_writeline(__FILE__."---Dumping unit_type_id_sig_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."---Dumping unit_type_id_sig_map MAP: End");

    $unit_type_id_decoded = sec_get_map_val ("unit_type_id_sig_map", $unit_type_id);
    $logfile->logfile_writeline("the encoded unit_type_id is".$unit_type_id);
    $logfile->logfile_writeline("the decoded unit_type_id is".$unit_type_id_decoded);

    
    $sql_delete = $conn->prepare("DELETE from unit_types where id = ?");
    $sql_delete->bind_param("i", $unit_type_id_decoded);
    $sql_delete->execute();
    $sql_delete_res = $sql_delete->get_result();
    

    $raw_json["ret_code"] = 0;
    $raw_json_encode=json_encode($raw_json);
    echo $raw_json_encode;
    

   
    
}
$logfile->logfile_close();
?>