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

$logfile->logfile_writeline("getting inside unit details PHP");

if (is_ajax()) {
    
    $myArray = [];
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
    //echo $node_id;
    $unit_id_mapped = sec_get_map_val ("prop_topo_map", $unit_id);
    $logfile->logfile_writeline("the encoded unit ID is".$unit_id);
    $logfile->logfile_writeline("the decoded unit ID is".$unit_id_mapped);


    $session_val= is_session_valid();

    if($session_val==0){
        $SENSESSION->token("unit_id", $unit_id_mapped);
        $raw_json["ret_code"] = 0;
        $raw_json_encoce=json_encode($raw_json);
        echo $raw_json_encoce;
        
    }


    else{      
    }
    $conn->close();
}
$logfile->logfile_close();

?>