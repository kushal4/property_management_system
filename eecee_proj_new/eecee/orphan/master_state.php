<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_sec_map.php';

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function CurlState($url){
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

$key = "a941d3c884b9f1f70aaec341b0e94207";

$check_reg_user = "SELECT * FROM master_country";
$check_reg_user_temp = $conn->prepare($check_reg_user);
//$check_reg_user_temp->bind_param("i",$aa);
$check_reg_user_temp->execute();
$check_reg_user_temp_result = $check_reg_user_temp->get_result();
$reg_user_temp_row = $check_reg_user_temp_result->fetch_all();
$numofrows = mysqli_num_rows($check_reg_user_temp_result);
// print_r($reg_user_temp_row);

foreach ($reg_user_temp_row as $value){  
    foreach ($value as $k => $v){
        $country_id = $value["0"];
        $country_name = $value["1"];
        $country_code = $value["2"];
    }
    /*
    echo "the country_id is::  ".$country_id."</br>";
    echo "the country_name is::  ".$country_name."</br>";
    echo "the country_code is::  ".$country_code."</br>";
    */

    /*
    $url = "https://battuta.medunes.net/api/region/".$country_code."/all/?key=".$key."&callback=";
    $response = CurlState($url);
    echo $response;
    $decoded_response =json_decode($response, true);

    foreach($decoded_response as $value){
        //print_r($value);
        $region = $value["region"];
        echo "the country region/ state is:: ".$region."</br>";
        
        
        $usr_invite = "INSERT INTO master_state(country_id, name) VALUES (?, ?)";
        $usr_invite_temp = $conn->prepare($usr_invite);
        if($usr_invite_temp){
            echo "prepare successful"."\n";
            $bind = $usr_invite_temp->bind_param("is", $country_id, $region);
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
    */
}


?>