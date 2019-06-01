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

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$logfile->logfile_writeline("getting inside get_master_country PHP");

sec_clear_map ("country_sig_map");

if (is_ajax()) {
    $check_mast_country = "SELECT * FROM master_country";
    $check_mast_country_temp = $conn->prepare($check_mast_country);
    //$check_mast_country_temp->bind_param("i",$aa);
    $check_mast_country_temp->execute();
    $check_mast_country_result = $check_mast_country_temp->get_result();
    $mast_country_result_row = $check_mast_country_result->fetch_all();
    $numofrows = mysqli_num_rows($check_mast_country_result);

    //echo "the number of rows are:: ".$numofrows;
    $country_array = array();
    foreach ($mast_country_result_row as $value){  
        foreach ($value as $k => $v){
            $country_id = $value["0"];
            $country_name = $value["1"];
            $country_code = $value["2"];
        }
        $seced_permSigPriID = sec_push_val_single_entry ("country_sig_map", $country_id);

        $country_sub_array["country_name"] = $country_name." (".$country_code.")";
        $country_sub_array["country_id"] = $seced_permSigPriID; 

        
        array_push($country_array,$country_sub_array);
        //$raw_json["ret_code"] = 0;
        //$raw_json["country_array"] = $country_array;
        
        
    }
    $get_country_arr ["ret_code"] = 0;
    $get_country_arr ["country"] = $country_array;
    $raw_json_encoce = json_encode($get_country_arr);
    echo $raw_json_encoce;
}

$curr_map=sec_get_map("country_sig_map");
/*
$logfile->logfile_writeline(__FILE__."---Dumping country_sig_map MAP: Begin");
        foreach($curr_map as $key => $value)
            {
                $logfile->logfile_writeline($key." : ".$value);
            }
$logfile->logfile_writeline(__FILE__."---Dumping country_sig_map MAP: End");
*/

$logfile->logfile_close();
?>