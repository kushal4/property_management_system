<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

//include 'prop_topo.php';
include 'show_flats_func.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


$logfile->logfile_writeline("getting inside open excel PHP");

if (is_ajax()) {
    
    $myArray = [];
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $session_val= is_session_valid();

    if($session_val==0){
        $prop_id = $SENSESSION->get_val("prop_id");
        $parentid = 0;
       
        $sql_check_prop = "SELECT * FROM prop_topo WHERE prop_id = ? AND parent_id = ?";
        $sql_check_prop_temp = $conn->prepare($sql_check_prop);
        $sql_check_prop_temp->bind_param("ii",$prop_id, $parentid);
        $sql_check_prop_temp->execute();
        $sql_check_prop_result = $sql_check_prop_temp->get_result();
        $sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
        $prop_topo_root_id = $sql_check_prop_row["id"];

        $ref_array =  array();

        show_flats($ref_array, $prop_topo_root_id, $prop_id, $conn, $logfile);
        $raw_json_encoce=json_encode($ref_array);
        echo $raw_json_encoce;  
    }


    else{      
    }
    $conn->close();
}
$logfile->logfile_close();
?>