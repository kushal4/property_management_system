<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include 'lib/php-lib/common_functions.php';

include '../lib/php-lib/sec.php';
//include 'lib/php-lib/eecee_sec_map.php';
//include 'curl_url_include.php';
//$log_path = "Logs/eecee.log";
include '../lib/php-lib/session_exp.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $role_sig = $_SESSION["role_sig"];
    $prop_id = $_SESSION["prop_id"];
    //echo "the role sig is:: ".$role_sig."\n";
    //echo "the property ID is:: ".$prop_id."\n";

    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "role_sig"=>$role_sig));

    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $actlRetObj = CurlSendPostJson($actlGetRoleTreeCurlURL, $data_str); //what we get from the ACTL

    //print_r($actlRetObj);

    $decodedJson = json_decode($actlRetObj, true);
    $jsonDataSTR = $decodedJson['d']."\n";
    $data_json_decode = json_decode($jsonDataSTR, true);
    $perm_array = $data_json_decode["p"];
    $_SESSION["perm_obj"] = $perm_array;

    //print_r($perm_array);
    $role_array = array();
    foreach ($perm_array as $value){  
        foreach ($value as $v){
            $sig = $v["sig"];
            $role_name = $v["name"];
            $parent = $v["parent"];
            $type = $v["type"];

            $role_sub_array["text"] = $role_name;
            $role_sub_array["type"] = $type;
            $role_sub_array["parent"] = $parent;
            $role_sub_array["sig"] = $sig;

            
            array_push($role_array, $role_sub_array);
        }
    }

    $obj = array(
        'ret_code'=>0,
        'role'=>$role_array
        
    );
    
    $obj_json = json_encode($obj);
    echo $obj_json;
}
$logfile->logfile_close();
?>