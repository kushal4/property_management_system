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

$logfile->logfile_writeline("getting inside unnit details PHP");

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
    $logfile->logfile_writeline("the encoded node ID is".$unit_id);
    $logfile->logfile_writeline("the decoded node ID is".$unit_id_mapped);

    $session_val= is_session_valid();

    
    if($session_val==0){
        
        $logfile->logfile_writeline(__FILE__."user details start");

        $sql_check_user_prop = "SELECT * FROM prop_topo WHERE id = ?";
        $sql_check_user_prop_temp = $conn->prepare($sql_check_user_prop);
        if($sql_check_user_prop_temp){
            $logfile->logfile_writeline("prepare success");
            $bind = $sql_check_user_prop_temp->bind_param("i",$unit_id_mapped);
            if($bind){
                $logfile->logfile_writeline("bind success");
                $exe = $sql_check_user_prop_temp->execute();
                if($exe){
                    $logfile->logfile_writeline("execute success");
                    $sql_check_user_prop_result = $sql_check_user_prop_temp->get_result();
                    $num_rows = mysqli_num_rows($sql_check_user_prop_result);
                    $logfile->logfile_writeline("the number of row is ::".$num_rows);
                    $sql_check_user_prop_row = $sql_check_user_prop_result->fetch_assoc();
                }else{
                    $logfile->logfile_writeline("execute failed");
                }
                
            }else{
                $logfile->logfile_writeline("bind failed");
            }
            
        }else{
            $logfile->logfile_writeline("prepare failed");
        }
        

        $sql_check_user_prop_row_str = var_export($sql_check_user_prop_row, true);
        $logfile->logfile_writeline("The sql_check_user_prop_row is :: ".$sql_check_user_prop_row_str);
        //$user_prop_num_of_rows = mysqli_num_rows($sql_check_user_prop_result);
        $unit_name = $sql_check_user_prop_row["node_name"];
        $logfile->logfile_writeline("the node name is :: ".$unit_name);
        /*
        if($user_prop_num_of_rows == 0){
            $raw_json["ret_code"] = 1;
            $raw_json["ret_msg"] = "No user is associated with this Unit";
            $raw_json["ret_msg2"] = "Do you want to add new owner with this unit?";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }*/
        //else{
            $raw_json["ret_code"] = 0;
            $raw_json["unit_name"] = $unit_name;
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        //}

        $logfile->logfile_writeline(__FILE__."user details end");

    }

    else{      
    }
    $conn->close();
    
}
$logfile->logfile_close();
?>