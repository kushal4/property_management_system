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

$logfile->logfile_writeline("getting inside get_cities PHP");

sec_clear_map ("city_sig_map");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $state_id="";

    foreach ($json_decoded as $key => $value) {
        if ($key=="state_id"){
            $state_id = $value;
        }  
    }
    $decoded_state_id = sec_get_map_val ("state_sig_map", $state_id);
    $logfile->logfile_writeline("The encoded state ID is:: ".$state_id);
    $logfile->logfile_writeline("The decoded state ID is:: ".$decoded_state_id);

    $check_master_city = "SELECT * FROM master_city WHERE state_id = ?";
    $check_master_city_temp = $conn->prepare($check_master_city);
    $check_master_city_temp->bind_param("i",$decoded_state_id);
    $check_master_city_temp->execute();
    $check_master_city_temp_result = $check_master_city_temp->get_result();
    $check_master_city_row = $check_master_city_temp_result->fetch_all();
    $numofrows = mysqli_num_rows($check_master_city_temp_result);
    $city_array = array();
    foreach ($check_master_city_row as $value){  
        foreach ($value as $k => $v){
            $city_id = $value["0"];
            $state_id = $value["1"];
            $city_name = $value["2"];
        }
        //echo "the city name is:: ".$city_name."\n";
       // echo "the city ID is:: ".$city_id."\n";
        
        $seced_cityID = sec_push_val_single_entry ("city_sig_map", $city_id);
        $city_sub_array["city_name"] = $city_name;
        $city_sub_array["city_id"] = $seced_cityID; 
        
        
        array_push($city_array,$city_sub_array);
        
        //$raw_json["ret_code"] = 0;
        //$raw_json["country_array"] = $country_array;
        
        
    }
    $get_city_arr["ret_code"] = 0;
    $get_city_arr ["city"] = $city_array;
    $raw_json_encoce = json_encode($get_city_arr);
    echo $raw_json_encoce;
}
$logfile->logfile_close();
?>