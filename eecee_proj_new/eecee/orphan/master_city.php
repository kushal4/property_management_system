<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_sec_map.php';

$start_state_id = 3329;
$end_state_id = 3720;
$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function CurlCity($url){
    //echo "getting inside CurlState";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    //echo $data;

    return $data;
}

$key = "41a01786e6d8f4b15397cc5d305ec88a";

$check_reg_user = "SELECT * FROM master_state where id >= ? and id <= ? ";
$check_reg_user_temp = $conn->prepare($check_reg_user);
$check_reg_user_temp->bind_param("ii",$start_state_id, $end_state_id);
$check_reg_user_temp->execute();
$check_reg_user_temp_result = $check_reg_user_temp->get_result();
$reg_user_temp_row = $check_reg_user_temp_result->fetch_all();
$numofrows = mysqli_num_rows($check_reg_user_temp_result);

foreach ($reg_user_temp_row as $value){  
    foreach ($value as $k => $v){
        $region_id = $value["0"];
        $country_id = $value["1"];
        $region_name = $value["2"]; // got the region
    }
    //echo "the country_id is::  ".$country_id."</br>";
    echo "the region_name is::  ".$region_name."</br>";

    $check_master_country = "SELECT * FROM master_country where id = ?";
    $check_master_country_temp = $conn->prepare($check_master_country);
    $check_master_country_temp->bind_param("i",$country_id);
    $check_master_country_temp->execute();
    $check_master_country_temp_result = $check_master_country_temp->get_result();
    $check_master_country_temp_result_row = $check_master_country_temp_result->fetch_assoc();
    $countryCode = $check_master_country_temp_result_row["code"];
    $countryName = $check_master_country_temp_result_row["name"];
    echo "the country name is:: ".$countryName."</br>"; // got the country_code

    $url ="https://battuta.medunes.net/api/city/" .$countryCode ."/search/?region=" .$region_name ."&key=" .$key."&callback=";

    $response = CurlCity($url);
    echo $response."</br>";
    $decoded_response =json_decode($response, true);

    foreach($decoded_response as $value){
        //print_r($value);
        $city = $value["city"];
        echo "the city is:: ".$city."</br>";
        
        
        $usr_invite = "INSERT INTO master_city(state_id, name) VALUES (?, ?)";
        $usr_invite_temp = $conn->prepare($usr_invite);
        if($usr_invite_temp){
            echo "prepare successful"."\n";
            $bind = $usr_invite_temp->bind_param("is", $region_id, $city);
            if($bind){
                echo "bind successful"."\n";
                $execute = $usr_invite_temp->execute();
                echo "exec error= ".$usr_invite_temp->error."\n";
                if($execute){
                    echo "execution successful"."\n";
                }else{
                    echo "execution failed"."\n";
                }
            }else{
                echo "bind failed"."\n";
            }
        }else{
            echo "prepare failed"."\n";
        }
        
        
        
        
    }
}
?>