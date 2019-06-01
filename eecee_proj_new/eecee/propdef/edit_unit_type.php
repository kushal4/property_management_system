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
    $unit_type_name=""; 
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="unit_type_name"){
            $unit_type_name = $value;
        }
        
    }

    $unit_type_id = $SENSESSION->get_val("unit_type_id");
    if($unit_type_id != null)
    {   
        

    
    }else{
        $prop_id = $SENSESSION->get_val("prop_id");
        $logfile->logfile_writeline("the prop id is:: ".$prop_id); 
        $logfile->logfile_writeline("the unit_type_name is:: ".$unit_type_name);
        $unit_types_sql = "INSERT INTO unit_types(prop_id, name) VALUES (?, ?)";
        $unit_types_temp = $conn->prepare($unit_types_sql);
        $unit_types_temp->bind_param("is",$prop_id, $unit_type_name);
        $unit_types_temp->execute();

        $last_id = $conn->insert_id;

        $logfile->logfile_writeline("the last inserted id is ::".$last_id); 
        $SENSESSION->token("unit_type_id", $last_id);

        $raw_json["ret_code"] = 0;
        $raw_json["unit_type_name"] = $unit_type_name;
        $raw_json_encoce=json_encode($raw_json);
        echo $raw_json_encoce;
    }
}   
$logfile->logfile_close();
?>