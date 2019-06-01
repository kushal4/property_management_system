<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
//include 'curl_url_include.php';
//$log_path = "Logs/eecee.log";
include '../lib/php-lib/session_exp.php';
//include 'sec.php';
//require_once 'Log.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside save_setup_prop.php";

if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    
    $session_val= is_session_valid();

    if($session_val==0){
        $prop_id = $SENSESSION->get_val("prop_id");
        
        $sql_check_prop = "SELECT * FROM properties WHERE id = ?";
        $sql_check_prop_temp = $conn->prepare($sql_check_prop);
        $sql_check_prop_temp->bind_param("i",$prop_id);
        $sql_check_prop_temp->execute();
        $sql_check_prop_result = $sql_check_prop_temp->get_result();
        $sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
       
        //print_r ($sql_check_prop_row);
        foreach ($sql_check_prop_row as $value){  
            $prop_name = $sql_check_prop_row["setup_name"];
        }
        //echo "the property name is".$prop_name;
        
        $raw_json["ret_code"] = 0;
        $raw_json["prop_name"] = $prop_name;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }

    else{      
    }
}
$logfile->logfile_close();
?>