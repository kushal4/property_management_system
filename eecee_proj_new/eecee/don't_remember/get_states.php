<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL); 
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
//$log_path = "Logs/eecee.log";
include '../lib/php-lib/sec.php';
include 'prop_topo.php';
include '../lib/php-lib/session_exp.php';
include '../lib/php-lib/reg_func.php';

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

$logfile->logfile_writeline("getting inside get_states PHP");

sec_clear_map ("state_sig_map");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $country_id="";

    foreach ($json_decoded as $key => $value) {
        if ($key=="country_id"){
            $country_id = $value;
        }  
    }
    $decoded_country_id = sec_get_map_val ("country_sig_map", $country_id);
    $logfile->logfile_writeline("The encoded country ID is:: ".$country_id);
    $logfile->logfile_writeline("The decoded country ID is:: ".$decoded_country_id);

    $check_master_state = "SELECT * FROM master_state WHERE country_id = ?";
    $check_master_state_temp = $conn->prepare($check_master_state);
    $check_master_state_temp->bind_param("i",$decoded_country_id);
    $check_master_state_temp->execute();
    $check_master_state_result = $check_master_state_temp->get_result();
    $check_master_state_row = $check_master_state_result->fetch_all();
    $numofrows = mysqli_num_rows($check_master_state_result);


    $check_master_ph_code = "SELECT * FROM master_phone_code WHERE country_id = ?";
    $master_ph_code_temp = $conn->prepare($check_master_ph_code);
    $master_ph_code_temp->bind_param("i",$decoded_country_id);
    $master_ph_code_temp->execute();
    $master_ph_code_result = $master_ph_code_temp->get_result();
    $master_ph_code_result_row = $master_ph_code_result->fetch_assoc();
    //print_r($master_ph_code_result_row);
    //$ph_code_numofrows = mysqli_num_rows($master_ph_code_result);
    $phone_code_num = $master_ph_code_result_row["phone_code"];
    //echo "the phone code is:: ".$phone_code_num;


    $state_array = array();
    foreach ($check_master_state_row as $value){  
        foreach ($value as $k => $v){
            $state_id = $value["0"];
            $country_id = $value["1"];
            $state_name = $value["2"];
        }
        //echo "the state name is:: ".$state_name."\n";
        //echo "the state ID is:: ".$state_id."\n";
        
        $seced_stateID = sec_push_val_single_entry ("state_sig_map", $state_id);
        $state_sub_array["state_name"] = $state_name;
        $state_sub_array["state_id"] = $seced_stateID; 
        
        
        array_push($state_array,$state_sub_array);
        
        //$raw_json["ret_code"] = 0;
        //$raw_json["country_array"] = $country_array;
        
        
    }
    $get_state_arr["ret_code"] = 0;
    $get_state_arr ["state"] = $state_array;
    $get_state_arr ["phone_code"] = $phone_code_num;
    $raw_json_encoce = json_encode($get_state_arr);
    echo $raw_json_encoce;
}
$logfile->logfile_close();
?>