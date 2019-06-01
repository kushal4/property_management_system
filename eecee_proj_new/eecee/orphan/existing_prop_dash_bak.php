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
    $prop_id=""; 
    foreach ($json_decoded as $key => $value) {
        //echo $key. "=>>>>>" .$value;
        //$log_str.="\n $key =>>>>> $value \n";
        if ($key=="prop_id"){
            $prop_id = $value;
        }
        
        
    }

    //echo "blah".$prop_id;
    
    $session_val= is_session_valid();

    if($session_val==0){
        $_SESSION["prop_id"] = $prop_id;

        $sess_user_id = $_SESSION["user_id"];


        $user_role_sql = "SELECT * FROM context_role WHERE prop_id = ? AND user_id = ?";
        $user_role_stmt = $conn->prepare($user_role_sql);
        $user_role_stmt->bind_param("ii",$prop_id, $sess_user_id);
        $user_role_stmt->execute();
        $user_role_result = $user_role_stmt->get_result();
        $user_role_row = $user_role_result->fetch_all();
        //print_r($user_role_row);
        //$prop_name = $prop_row["setup_name"];

        $role_sig_arr = array();
        foreach ($user_role_row as $value){  
            foreach ($value as $k => $v){
                $role_sig = $value["3"];
            }
            
            //echo "the role sig is:: ".$role_sig."\n";
            //$role_sig_sub_arr["role_sig"] = $role_sig;
            //$role_sig_sub_arr["role_sig"] = $role_sig;
            
            array_push($role_sig_arr,$role_sig);
            
        }

        //print_r($role_sig_arr);
        $role_sig_arr_json_encode=json_encode($role_sig_arr);
        //echo $role_sig_arr_json_encode;

        $actlGetRoleDetsCurlURL;

        $projectKey = $projectID;

        $data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id, "sig_arr"=>$role_sig_arr));

        //print_r($data);
        
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);

        //echo $data_str."\n";


        $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str); //what we get from the ACTL

        //echo $actlRetObj;


        $prop_sql = "SELECT * FROM properties WHERE id = ?";
        $prop_stmt = $conn->prepare($prop_sql);
        $prop_stmt->bind_param("i",$prop_id);
        $prop_stmt->execute();
        $prop_stmt_result = $prop_stmt->get_result();
        $prop_row = $prop_stmt_result->fetch_assoc();
        $prop_name = $prop_row["setup_name"];

        $_SESSION["prop_name"] = $prop_name;

        $raw_json["ret_code"] = 0;
       // $raw_json["prop_name"] = $prop_name;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }
    else{     
        $raw_json["ret_code"] = 4; //if session expires 
    }
}
$logfile->logfile_close();
?>