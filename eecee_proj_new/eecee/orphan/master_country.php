<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_sec_map.php';
//include '../lib/php-lib/session_exp.php';

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function CurlCountry($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    //echo $data;

    return $data;
}


$key = "541917f273e76bdbf631ac724c21aad8";
$url = "https://battuta.medunes.net/api/country/all/?key=".$key."&callback=";

//$response = CurlCountry($url);
//echo $response."</br>";

$decoded_response =json_decode($response, true);

foreach($decoded_response as $value){
    //print_r($value);
    $name = $value["name"];
    $code = $value["code"];
    //echo "the country name is:: ".$name."</br>";
    //echo "the country code is:: ".$code."</br>";

    /*
    $usr_invite = "INSERT INTO master_country(name, code) VALUES (?, ?)";
    $usr_invite_temp = $conn->prepare($usr_invite);
    if($usr_invite_temp){
        echo "prepare successful"."\n";
        $bind = $usr_invite_temp->bind_param("ss",$name, $code);
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
    */
    
}



?>