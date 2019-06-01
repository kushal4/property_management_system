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

$logfile->logfile_writeline("getting inside user details PHP");

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

        /*
        $sql_check_prop = "SELECT * FROM prop_topo WHERE id = ? ";
        $sql_check_prop_temp = $conn->prepare($sql_check_prop);
        $sql_check_prop_temp->bind_param("i",$unit_id_mapped);
        $sql_check_prop_temp->execute();
        $sql_check_prop_result = $sql_check_prop_temp->get_result();
        $sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
        $prop_id = $sql_check_prop_row["prop_id"];
        */

        //searching user_prop table
        $sql_check_user_prop = "SELECT * FROM contexts WHERE unit_id = ? ";
        $sql_check_user_prop_temp = $conn->prepare($sql_check_user_prop);
        $sql_check_user_prop_temp->bind_param("i",$unit_id_mapped);
        $sql_check_user_prop_temp->execute();
        $sql_check_user_prop_result = $sql_check_user_prop_temp->get_result();
        $sql_check_user_prop_row = $sql_check_user_prop_result->fetch_assoc();
        $user_prop_num_of_rows = mysqli_num_rows($sql_check_user_prop_result);
        //echo "the number of rows::". $user_prop_num_of_rows;

        /*
        $sql_prop_name = "SELECT * FROM properties WHERE id = ? ";
        $sql_prop_name_temp = $conn->prepare($sql_prop_name);
        $sql_prop_name_temp->bind_param("i",$prop_id);
        $sql_prop_name_temp->execute();
        $sql_prop_name_result = $sql_prop_name_temp->get_result();
        $sql_prop_name_result_row = $sql_prop_name_result->fetch_assoc();
        $num_of_rows = mysqli_num_rows($sql_prop_name_result);
        echo "the number of rows::". $num_of_rows;
        */
        //$logfile->logfile_writeline("the decoded node ID is".$user_prop_num_of_rows);

        if($user_prop_num_of_rows == 0){
            $raw_json["ret_code"] = 1;
            $raw_json["ret_msg"] = "No user is associated with this Unit";
            $raw_json["ret_msg2"] = "Do you want to add new owner with this unit?";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
        else{
            $raw_json["ret_code"] = 2;
            $raw_json["ret_msg"] = "An user is already associated with this Unit";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }

        //$logfile->logfile_writeline(__FILE__."the number of rows are".$num_of_rows);


        $logfile->logfile_writeline(__FILE__."user details end");

    }


    else{      
    }
    $conn->close();
    
}
$logfile->logfile_close();
?>